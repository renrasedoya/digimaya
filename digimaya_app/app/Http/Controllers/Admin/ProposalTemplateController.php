<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\LogoWallItem;
use App\Models\PricingTier;
use App\Models\ProposalSnippet;
use App\Models\ProposalTemplate;
use App\Models\PublicService;
use App\Models\Testimonial;
use App\Services\ProposalBlockParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProposalTemplateController extends Controller
{
    public function index(): View
    {
        $templates = ProposalTemplate::orderBy('key')->get();

        return view('admin.proposal-templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('admin.proposal-templates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Normalise key to a lowercase slug before validating uniqueness.
        $request->merge(['key' => Str::slug((string) $request->input('key'))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:proposal_templates,key'],
        ]);

        $template = ProposalTemplate::create([
            'key' => $validated['key'],
            'name' => $validated['name'],
            'content_blocks' => [],
        ]);

        return redirect()
            ->route('admin.proposal-templates.edit', $template)
            ->with('success', 'Template dibuat. Susun block lalu klik Simpan Template.');
    }

    public function destroy(ProposalTemplate $proposalTemplate): RedirectResponse
    {
        // Always keep at least one template so "Buat Proposal" has a source.
        if (ProposalTemplate::count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus: minimal harus ada 1 template untuk "Buat Proposal".');
        }

        $proposalTemplate->delete();

        return redirect()
            ->route('admin.proposal-templates.index')
            ->with('success', 'Template dihapus.');
    }

    public function edit(ProposalTemplate $proposalTemplate): View
    {
        return view('admin.proposal-templates.edit', [
            'proposalTemplate' => $proposalTemplate,
        ] + $this->builderData());
    }

    public function update(Request $request, ProposalTemplate $proposalTemplate, ProposalBlockParser $blockParser): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $proposalTemplate->update([
            'name' => $request->filled('name') ? $validated['name'] : $proposalTemplate->name,
            'content_blocks' => $blockParser->parse($request->input('content_blocks')),
        ]);

        return redirect()
            ->route('admin.proposal-templates.edit', $proposalTemplate)
            ->with('success', 'Template tersimpan.');
    }

    /**
     * Library data the shared builder partial needs.
     * Same shape as ProposalController@edit (snippets, pricingCounts, referenceData).
     */
    private function builderData(): array
    {
        $snippets = ProposalSnippet::active()->ordered()->get(['id', 'title', 'body', 'images']);

        $lower = PricingTier::where('is_active', true)->where('zone', 'lower')->count();
        $upper = PricingTier::where('is_active', true)->where('zone', 'upper')->count();
        $pricingCounts = ['all' => $lower + $upper, 'lower' => $lower, 'upper' => $upper];

        $referenceData = [
            'logo_wall' => LogoWallItem::active()->ordered()->get(['id', 'name'])
                ->map(fn ($i) => ['id' => $i->id, 'label' => $i->name])->values(),
            'testimonials' => Testimonial::active()->ordered()->get(['id', 'name', 'company'])
                ->map(fn ($i) => ['id' => $i->id, 'label' => trim($i->name . ($i->company ? ' — ' . $i->company : ''))])->values(),
            'case_studies' => CaseStudy::active()->ordered()->get(['id', 'client_name', 'title'])
                ->map(fn ($i) => ['id' => $i->id, 'label' => trim(($i->client_name ? $i->client_name . ': ' : '') . $i->title)])->values(),
            'services' => PublicService::active()->ordered()->get(['id', 'title'])
                ->map(fn ($i) => ['id' => $i->id, 'label' => $i->title])->values(),
        ];

        return compact('snippets', 'pricingCounts', 'referenceData');
    }
}
