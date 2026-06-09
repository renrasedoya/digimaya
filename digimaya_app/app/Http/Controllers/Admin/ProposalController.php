<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Proposal;
use App\Services\ProposalSnapshotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $clients = $this->prospectClients();
        $preselectClientId = (int) $request->input('client_id', 0);

        return view('admin.proposals.create', compact('clients', 'preselectClientId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['created_by'] = $request->user()->id;
        $validated['status'] = Proposal::STATUS_DRAFT;
        $validated['content_blocks'] = [];

        $proposal = Proposal::create($validated);

        return redirect()
            ->route('admin.proposals.edit', $proposal)
            ->with('success', 'Proposal created. Start adding blocks.');
    }

    public function edit(Proposal $proposal): View
    {
        $clients = $this->prospectClients();
        $snippets = \App\Models\ProposalSnippet::active()->ordered()->get(['id', 'title', 'body']);

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
                    'body' => clean((string) ($block['body'] ?? '')),
                    'image_url' => mb_substr($imageUrl, 0, 1000),
                    'caption' => mb_substr(trim((string) ($block['caption'] ?? '')), 0, 255),
                ];
            } elseif ($type === 'snippet') {
                // Copy-on-insert: title + body are stored on the block itself,
                // fully editable, decoupled from the source snippet record.
                $clean[] = [
                    'uid' => (string) ($block['uid'] ?? uniqid('b', true)),
                    'type' => 'snippet',
                    'title' => mb_substr(trim((string) ($block['title'] ?? '')), 0, 255),
                    'body' => clean((string) ($block['body'] ?? '')),
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
