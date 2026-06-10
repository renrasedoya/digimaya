<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Proposal;
use App\Services\ProposalSnapshotService;
use App\Services\ProposalTemplateService;
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

    public function create(Request $request, ProposalTemplateService $templates): View
    {
        $clients = $this->prospectClients();
        $preselectClientId = (int) $request->input('client_id', 0);
        $templateOptions = $templates->options();

        return view('admin.proposals.create', compact('clients', 'preselectClientId', 'templateOptions'));
    }

    public function store(Request $request, ProposalTemplateService $templates): RedirectResponse
    {
        $validated = $request->validate($this->validationRules() + [
            'template' => ['required', 'string', Rule::in(array_keys(ProposalTemplateService::TEMPLATES))],
        ]);

        $created = Proposal::create([
            'client_id' => $validated['client_id'],
            'title' => $validated['title'],
            'created_by' => $request->user()->id,
            'status' => Proposal::STATUS_DRAFT,
            'content_blocks' => $templates->blocksFor($validated['template']),
        ]);

        return redirect()
            ->route('admin.proposals.edit', $created)
            ->with('success', 'Proposal dibuat dari template. Hapus section yang tidak perlu, lalu publish.');
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

    public function update(Request $request, Proposal $proposal, ProposalSnapshotService $snapshot): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['content_blocks'] = $this->parseBlocks($request->input('content_blocks'));
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

    /**
     * Decode + sanitize the content_blocks JSON coming from the Alpine builder.
     * Unknown keys are dropped; bodies are HTML-sanitized. Returns a clean array.
     */
    private function parseBlocks($raw): array
    {
        if (is_array($raw)) {
            $decoded = $raw;
        } else {
            $decoded = json_decode((string) $raw, true);
        }

        if (!is_array($decoded)) {
            return [];
        }

        $clean = [];
        foreach ($decoded as $block) {
            if (!is_array($block) || empty($block['type'])) {
                continue;
            }

            $type = $block['type'];

            if ($type === 'custom') {
                $imageUrl = trim((string) ($block['image_url'] ?? ''));
                // URL-only for now (upload layer added later). Accept only http(s) URLs.
                if ($imageUrl !== '' && !str_starts_with($imageUrl, 'http')) {
                    $imageUrl = '';
                }
                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'custom',
                    'title' => mb_substr(trim((string) ($block['title'] ?? '')), 0, 255),
                    'body' => clean((string) ($block['body'] ?? ''), 'blog'),
                    'image_url' => mb_substr($imageUrl, 0, 1000),
                    'caption' => mb_substr(trim((string) ($block['caption'] ?? '')), 0, 255),
                ];
            } elseif ($type === 'snippet') {
                // Copy-on-insert: title + body + images are stored on the block itself,
                // fully editable, decoupled from the source snippet record.
                // Images are URL strings (Bagian A: shared URLs, no physical copy yet).
                $images = is_array($block['images'] ?? null) ? $block['images'] : [];
                $images = array_values(array_filter(
                    array_map(fn ($u) => mb_substr(trim((string) $u), 0, 2000), $images),
                    fn ($u) => $u !== '' && str_starts_with($u, 'http')
                ));
                $images = array_slice($images, 0, 8);

                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'snippet',
                    'title' => mb_substr(trim((string) ($block['title'] ?? '')), 0, 255),
                    'body' => clean((string) ($block['body'] ?? ''), 'blog'),
                    'images' => $images,
                ];
            } elseif ($type === 'pricing') {
                // Stores only the display choice; tier rows are resolved live at render.
                $option = $block['option'] ?? 'all';
                if (!in_array($option, ['all', 'lower', 'upper'], true)) {
                    $option = 'all';
                }
                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'pricing',
                    'heading' => mb_substr(trim((string) ($block['heading'] ?? '')), 0, 255),
                    'option' => $option,
                ];
            } elseif ($type === 'reference') {
                // Stores source + ids only; actual records resolved live at render,
                // frozen into the snapshot on publish (Fase 4).
                $source = $block['source'] ?? '';
                if (!in_array($source, ['logo_wall', 'testimonials', 'case_studies', 'services'], true)) {
                    continue; // invalid source, drop the block
                }
                $ids = $block['ids'] ?? [];
                $ids = is_array($ids) ? array_values(array_unique(array_map('intval', $ids))) : [];
                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'reference',
                    'heading' => mb_substr(trim((string) ($block['heading'] ?? '')), 0, 255),
                    'source' => $source,
                    'ids' => $ids,
                ];
            }
        }

        return $clean;
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
        return Client::where('status', 'prospect')
            ->orderBy('business_name')
            ->get(['id', 'business_name']);
    }
}
