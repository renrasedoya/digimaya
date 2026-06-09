<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class IncomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Income::query()->with('client', 'service', 'creator');

        // Filter by month (default: current month)
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        if ($month >= 1 && $month <= 12) {
            $query->forMonth($year, $month);
        }

        // Filter by category
        if ($category = $request->input('category')) {
            if (in_array($category, ['agency', 'academy', 'other'], true)) {
                $query->where('source_category', $category);
            }
        }

        // Search by client name
        if ($search = $request->input('search')) {
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $incomes = $query
            ->orderBy('received_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Summary stats for selected month
        $monthQuery = Income::forMonth($year, $month);
        $summary = [
            'total' => (clone $monthQuery)->sum('amount'),
            'agency' => (clone $monthQuery)->where('source_category', 'agency')->sum('amount'),
            'academy' => (clone $monthQuery)->where('source_category', 'academy')->sum('amount'),
            'other' => (clone $monthQuery)->where('source_category', 'other')->sum('amount'),
            'count' => (clone $monthQuery)->count(),
        ];

        return view('admin.incomes.index', compact('incomes', 'summary', 'year', 'month'));
    }

    public function create(): View
    {
        $services = Service::active()->ordered()->get(['id', 'name', 'category']);

        return view('admin.incomes.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        // Auto-fill source_category from service if not provided
        if (empty($validated['source_category']) && ! empty($validated['service_id'])) {
            $service = Service::find($validated['service_id']);
            if ($service) {
                $validated['source_category'] = $service->category;
            }
        }

        Income::create($validated);

        return redirect()
            ->route('admin.incomes.index')
            ->with('success', 'Income recorded successfully.');
    }

    public function edit(Income $income): View
    {
        $services = Service::active()->ordered()->get(['id', 'name', 'category']);
        $income->load('client:id,business_name');

        return view('admin.incomes.edit', compact('income', 'services'));
    }

    public function update(Request $request, Income $income): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        // Auto-fill source_category from service if not provided
        if (empty($validated['source_category']) && ! empty($validated['service_id'])) {
            $service = Service::find($validated['service_id']);
            if ($service) {
                $validated['source_category'] = $service->category;
            }
        }

        $income->update($validated);

        return redirect()
            ->route('admin.incomes.index')
            ->with('success', 'Income updated successfully.');
    }

    public function destroy(Income $income): RedirectResponse
    {
        $income->delete();

        return redirect()
            ->route('admin.incomes.index')
            ->with('success', 'Income deleted successfully.');
    }

    private function validationRules(): array
    {
        return [
            'client_id' => ['nullable', 'exists:clients,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'source_category' => ['required', Rule::in(['agency', 'academy', 'other'])],
            'amount' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'received_date' => ['required', 'date'],
            'payment_method' => ['required', Rule::in(array_keys(Income::PAYMENT_METHODS))],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
