<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Proposal;
use App\Models\ProposalTemplate;
use App\Services\ProposalBlockParser;
use App\Services\ProposalSnapshotService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProposalController extends Controller
{
    public function index(Request $request): View
    {
        $query = Proposal::query()->with('client:id,business_name', 'creator:id,name');

        if ($filter = $request->input('filter')) {
            match ($filter) {
                'draft' => $query->where('status', Proposal::STATUS_DRAFT),
                'published' => $query->where('status', Proposal::STATUS_PUBLISHED),
                default => null,
            };
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $proposals = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $counts = [
            'total' => Proposal::count(),
            'draft' => Proposal::where('status', Proposal::STATUS_DRAFT)->count(),
            'published' => Proposal::where('status', Proposal::STATUS_PUBLISHED)->count(),
        ];

        return view('admin.proposals.index', compact('proposals', 'counts'));
    }

    public function create(Request $request): View
    {
        $prospectClients = $this->clientsByStatus('prospect');
        $activeClients = $this->clientsByStatus('active');
        $preselectClientId = (int) $request->input('client_id', 0);
        $templateOptions = ProposalTemplate::orderBy('key')->get(['key', 'name']);

        return view('admin.proposals.create', compact('prospectClients', 'activeClients', 'preselectClientId', 'templateOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Recipient may be a prospect OR an active client; other statuses rejected.
            'client_id' => ['required', Rule::exists('clients', 'id')->whereIn('status', ['prospect', 'active'])],
            'title' => ['required', 'string', 'max:255'],
            'template' => ['required', 'string', 'exists:proposal_templates,key'],
        ]);

        $template = ProposalTemplate::where('key', $validated['template'])->firstOrFail();

        $created = Proposal::create([
            'client_id' => $validated['client_id'],
            'title' => $validated['title'],
            'created_by' => $request->user()->id,
            'status' => Proposal::STATUS_DRAFT,
            'content_blocks' => $this->copyTemplateBlocks($template->content_blocks),
        ]);

        return redirect()
            ->route('admin.proposals.edit', $created)
            ->with('success', 'Proposal dibuat dari template. Hapus section yang tidak perlu, lalu publish.');
    }

    /**
     * Deep-copy a template's content_blocks into a fresh proposal, regenerating
     * each block uid so the proposal's blocks are independent of the template.
     * Reference ids are copied as-is (frozen), per the agreed design.
     */
    private function copyTemplateBlocks(?array $blocks): array
    {
        $blocks = is_array($blocks) ? $blocks : [];

        return array_values(array_map(function ($block) {
            if (is_array($block)) {
                $block['uid'] = uniqid('b', true);
            }

            return $block;
        }, $blocks));
    }

    public function edit(Proposal $proposal): View
    {
        $clients = $this->prospectClients();
        $snippets = \App\Models\ProposalSnippet::active()->ordered()->get(['id', 'title', 'body', 'images']);

        $lower = \App\Models\PricingTier::where('is_active', true)->where('zone', 'lower')->count();
        $upper = \App\Models\PricingTier::where('is_active', true)->where('zone', 'upper')->count();
        $pricingCounts = ['all' => $lower + $upper, 'lower' => $lower, 'upper' => $upper];

        $referenceData = [
            'logo_wall' => \App\Models\LogoWallItem::active()->ordered()->get(['id', 'name'])
                ->map(fn ($i) => ['id' => $i->id, 'label' => $i->name])->values(),
            'testimonials' => \App\Models\Testimonial::active()->ordered()->get(['id', 'name', 'company'])
                ->map(fn ($i) => ['id' => $i->id, 'label' => trim($i->name . ($i->company ? ' — ' . $i->company : ''))])->values(),
            'case_studies' => \App\Models\CaseStudy::active()->ordered()->get(['id', 'client_name', 'title'])
                ->map(fn ($i) => ['id' => $i->id, 'label' => trim(($i->client_name ? $i->client_name . ': ' : '') . $i->title)])->values(),
            'services' => \App\Models\PublicService::active()->ordered()->get(['id', 'title'])
                ->map(fn ($i) => ['id' => $i->id, 'label' => $i->title])->values(),
        ];

        return view('admin.proposals.edit', compact('proposal', 'clients', 'snippets', 'pricingCounts', 'referenceData'));
    }

    public function update(Request $request, Proposal $proposal, ProposalSnapshotService $snapshot, ProposalBlockParser $blockParser): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['content_blocks'] = $blockParser->parse($request->input('content_blocks'));
        $proposal->fill($validated);

        $action = $request->input('action', 'save');

        if ($action === 'publish') {
            $proposal->save(); // persist latest blocks first, then snapshot from DB state
            $resolved = $snapshot->resolve($proposal);

            if (empty($resolved)) {
                return back()->with('error', 'Tidak bisa publish: proposal belum punya block. Tambahkan minimal satu block lalu coba lagi.');
            }

            $proposal->update([
                'status' => Proposal::STATUS_PUBLISHED,
                'published_content' => $resolved,
                'published_at' => now(),
            ]);

            return redirect()->route('admin.proposals.edit', $proposal)
                ->with('success', 'Proposal published. Link publik siap dibagikan.');
        }

        if ($action === 'unpublish') {
            $proposal->status = Proposal::STATUS_DRAFT;
            $proposal->published_at = null;
            $proposal->save();

            return redirect()->route('admin.proposals.edit', $proposal)
                ->with('success', 'Proposal di-unpublish. Link publik tidak lagi aktif.');
        }

        $proposal->save();

        return redirect()->route('admin.proposals.edit', $proposal)
            ->with('success', 'Proposal saved.');
    }

    public function destroy(Proposal $proposal): RedirectResponse
    {
        $proposal->delete();

        return redirect()
            ->route('admin.proposals.index')
            ->with('success', 'Proposal deleted.');
    }

    /**
     * Admin preview of the proposal as the client would see it.
     * Resolves the live draft blocks (no need to publish first).
     */
    public function preview(Proposal $proposal, ProposalSnapshotService $snapshot): View
    {
        $blocks = $snapshot->resolve($proposal);

        return view('public.proposals.show', [
            'proposal' => $proposal,
            'blocks' => $blocks,
            'preview' => true,
        ]);
    }

    /**
     * Download the proposal as a PDF. Uses the frozen snapshot when published,
     * otherwise resolves the current draft blocks live.
     */
    public function downloadPdf(Proposal $proposal, ProposalSnapshotService $snapshot): Response
    {
        $blocks = $this->resolvedBlocks($proposal, $snapshot);

        return $this->buildPdf($proposal, $blocks)->download($this->pdfFilename($proposal));
    }

    /**
     * AJAX endpoint for the builder: stores an uploaded image on the public disk
     * and returns its absolute URL to embed into a custom block.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $path = $request->file('image')->store('proposals', 'public');

        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }

    private function resolvedBlocks(Proposal $proposal, ProposalSnapshotService $snapshot): array
    {
        if ($proposal->isPublished() && is_array($proposal->published_content)) {
            return $proposal->published_content;
        }

        return $snapshot->resolve($proposal);
    }

    private function buildPdf(Proposal $proposal, array $blocks)
    {
        return Pdf::loadView('admin.proposals.pdf', [
            'proposal' => $proposal,
            'blocks' => $blocks,
        ])->setPaper('a4', 'portrait')->setOption('isRemoteEnabled', true);
    }

    private function pdfFilename(Proposal $proposal): string
    {
        $safe = preg_replace('/[^A-Za-z0-9_.-]+/', '-', $proposal->title ?: 'proposal');

        return 'Proposal-' . trim($safe, '-') . '.pdf';
    }

    private function validationRules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:255'],
        ];
    }

    private function prospectClients()
    {
        return $this->clientsByStatus('prospect');
    }

    private function clientsByStatus(string $status)
    {
        return Client::where('status', $status)
            ->orderBy('business_name')
            ->get(['id', 'business_name']);
    }
}
