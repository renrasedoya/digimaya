<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\RecurringExpenseGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        // Lazy-generate recurring drafts for missing periods (no cron).
        app(RecurringExpenseGenerator::class)->run();

        $query = Expense::query()->confirmed()->with('category', 'creator');

        // Filter by month (default: current month)
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        if ($month >= 1 && $month <= 12) {
            $query->forMonth($year, $month);
        }

        // Filter by category
        if ($categoryId = $request->input('category_id')) {
            $query->where('expense_category_id', $categoryId);
        }

        // Filter by recurring type
        if ($recurring = $request->input('recurring')) {
            if (in_array($recurring, ['one_time', 'monthly', 'yearly'], true)) {
                $query->where('recurring_type', $recurring);
            }
        }

        // Search by vendor or description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $expenses = $query
            ->orderBy('expense_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Summary stats for selected month (confirmed only — drafts excluded)
        $monthQuery = Expense::confirmed()->forMonth($year, $month);
        $summary = [
            'total' => (clone $monthQuery)->sum('amount'),
            'recurring' => (clone $monthQuery)->whereIn('recurring_type', ['monthly', 'yearly'])->sum('amount'),
            'one_time' => (clone $monthQuery)->where('recurring_type', 'one_time')->sum('amount'),
            'count' => (clone $monthQuery)->count(),
        ];

        // Breakdown per category for selected month (confirmed only)
        $categoryBreakdown = Expense::confirmed()->forMonth($year, $month)
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->with('category:id,name')
            ->get();

        $categories = ExpenseCategory::active()->ordered()->get(['id', 'name']);

        // Pending recurring drafts (current month only — the reminder zone)
        $pendingDrafts = Expense::draft()
            ->with('category:id,name')
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->orderBy('expense_category_id')
            ->get();

        return view('admin.expenses.index', compact('expenses', 'summary', 'categoryBreakdown', 'categories', 'year', 'month', 'pendingDrafts'));
    }

    public function create(): View
    {
        $categories = ExpenseCategory::active()->ordered()->get(['id', 'name']);

        return view('admin.expenses.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['expense_date'] = $this->resolveExpenseDate($validated);
        unset($validated['expense_month'], $validated['expense_year']);

        Expense::create($validated);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense): View
    {
        $categories = ExpenseCategory::active()->ordered()->get(['id', 'name']);

        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['expense_date'] = $this->resolveExpenseDate($validated);
        unset($validated['expense_month'], $validated['expense_year']);

        // Editing a pending recurring draft counts as reviewing it:
        // saving promotes it straight to confirmed.
        $wasDraft = $expense->status === Expense::STATUS_DRAFT;
        if ($wasDraft) {
            $validated['status'] = Expense::STATUS_CONFIRMED;
        }

        $expense->update($validated);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', $wasDraft ? 'Recurring expense confirmed.' : 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    public function confirmRecurring(Expense $expense): RedirectResponse
    {
        if ($expense->status !== Expense::STATUS_DRAFT) {
            return back()->with('error', 'This expense is not a pending draft.');
        }

        $expense->update(['status' => Expense::STATUS_CONFIRMED]);

        return back()->with('success', 'Recurring expense confirmed.');
    }

    public function skipRecurring(Expense $expense): RedirectResponse
    {
        if ($expense->status !== Expense::STATUS_DRAFT) {
            return back()->with('error', 'This expense is not a pending draft.');
        }

        $expense->update(['status' => Expense::STATUS_SKIPPED]);

        return back()->with('success', 'Recurring expense skipped for this period.');
    }

    private function resolveExpenseDate(array $validated): string
    {
        return \Carbon\Carbon::create(
            (int) $validated['expense_year'],
            (int) $validated['expense_month'],
            1
        )->format('Y-m-d');
    }

    private function validationRules(): array
    {
        return [
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'expense_month' => ['required', 'integer', 'between:1,12'],
            'expense_year' => ['required', 'integer', 'between:2026,2030'],
            'vendor_name' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', Rule::in(array_keys(Expense::PAYMENT_METHODS))],
            'recurring_type' => ['required', Rule::in(array_keys(Expense::RECURRING_TYPES))],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
