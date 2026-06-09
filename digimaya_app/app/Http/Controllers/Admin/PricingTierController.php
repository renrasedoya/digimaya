<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingTier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PricingTierController extends Controller
{
    public function index(Request $request): View
    {
        $query = PricingTier::query();

        if ($filter = $request->input('filter')) {
            match ($filter) {
                'lower' => $query->where('zone', PricingTier::ZONE_LOWER),
                'upper' => $query->where('zone', PricingTier::ZONE_UPPER),
                'active' => $query->where('is_active', true),
                'inactive' => $query->where('is_active', false),
                default => null,
            };
        }

        $tiers = $query->ordered()->paginate(20)->withQueryString();

        $counts = [
            'total' => PricingTier::count(),
            'lower' => PricingTier::where('zone', PricingTier::ZONE_LOWER)->count(),
            'upper' => PricingTier::where('zone', PricingTier::ZONE_UPPER)->count(),
            'active' => PricingTier::where('is_active', true)->count(),
            'inactive' => PricingTier::where('is_active', false)->count(),
        ];

        return view('admin.pricing-tiers.index', compact('tiers', 'counts'));
    }

    public function create(): View
    {
        return view('admin.pricing-tiers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['is_active'] = $request->has('is_active');

        PricingTier::create($validated);

        return redirect()
            ->route('admin.pricing-tiers.index')
            ->with('success', 'Pricing tier created successfully.');
    }

    public function edit(PricingTier $pricingTier): View
    {
        return view('admin.pricing-tiers.edit', compact('pricingTier'));
    }

    public function update(Request $request, PricingTier $pricingTier): RedirectResponse
    {
        $validated = $request->validate($this->validationRules($pricingTier));
        $validated['is_active'] = $request->has('is_active');

        $pricingTier->update($validated);

        return redirect()
            ->route('admin.pricing-tiers.index')
            ->with('success', 'Pricing tier updated successfully.');
    }

    public function destroy(PricingTier $pricingTier): RedirectResponse
    {
        $pricingTier->delete();

        return redirect()
            ->route('admin.pricing-tiers.index')
            ->with('success', 'Pricing tier deleted successfully.');
    }

    private function validationRules(?PricingTier $tier = null): array
    {
        return [
            'budget' => [
                'required', 'integer', 'min:0',
                Rule::unique('pricing_tiers', 'budget')->ignore($tier?->id),
            ],
            'agency_fee' => ['required', 'integer', 'min:0'],
            'zone' => ['required', Rule::in([PricingTier::ZONE_LOWER, PricingTier::ZONE_UPPER])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
