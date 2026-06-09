<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBalanceRequest;
use App\Http\Requests\Admin\UpdateBalanceRequest;
use App\Models\Balance;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index(Request $request)
    {
        $prev = now()->subMonthNoOverflow();
        $year = (int) $request->input('year', $prev->year);
        $month = (int) $request->input('month', $prev->month);

        $minYear = (int) now()->year - 10;
        $maxYear = (int) now()->year;
        $hasMonthFilter = $month >= 1 && $month <= 12;
        $hasYearFilter = $year >= $minYear && $year <= $maxYear;
        $isSpecificPeriod = $hasMonthFilter && $hasYearFilter;

        // Table query — apply available filters
        $query = Balance::with(['bankAccount', 'creator']);
        if ($hasMonthFilter) {
            $query->where('month', $month);
        }
        if ($hasYearFilter) {
            $query->where('year', $year);
        }

        $balances = $query
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('bank_account_id')
            ->paginate(15)
            ->withQueryString();

        // Count records in scope (independent of period semantics)
        $recordScopeQuery = Balance::query();
        if ($hasMonthFilter) {
            $recordScopeQuery->where('month', $month);
        }
        if ($hasYearFilter) {
            $recordScopeQuery->where('year', $year);
        }
        $accountsReported = (clone $recordScopeQuery)->count();
        $activeAccounts = BankAccount::where('is_active', true)->count();

        // Total Balance semantics: stock value, NEVER aggregate across months
        // - Specific period: SUM all accounts in that period (cash position snapshot)
        // - Aggregate filter (All Months / All Years / All Time): find latest period in scope, SUM that period
        $totalBalance = 0;
        $latestPeriodYear = null;
        $latestPeriodMonth = null;

        if ($isSpecificPeriod) {
            // Direct sum within the specific year+month
            $totalBalance = Balance::where('year', $year)->where('month', $month)->sum('balance_amount');
            $latestPeriodYear = $year;
            $latestPeriodMonth = $month;
        } else {
            // Find latest period (year+month) within current filter scope
            $latestScopeQuery = Balance::query();
            if ($hasMonthFilter) {
                $latestScopeQuery->where('month', $month);
            }
            if ($hasYearFilter) {
                $latestScopeQuery->where('year', $year);
            }
            $latest = (clone $latestScopeQuery)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->first();

            if ($latest) {
                $latestPeriodYear = (int) $latest->year;
                $latestPeriodMonth = (int) $latest->month;
                $totalBalance = Balance::where('year', $latestPeriodYear)
                    ->where('month', $latestPeriodMonth)
                    ->sum('balance_amount');
            }
        }

        // Month-over-Month (MoM) — compare anchor period vs previous month
        $momPreviousYear = null;
        $momPreviousMonth = null;
        $momPreviousTotal = null;
        $momPreviousCount = null;
        $momAnchorCount = null;
        $momDelta = null;
        $momPercent = null;
        $momHasPrior = false;
        $momCoverageMatch = null;

        if ($latestPeriodYear !== null && $latestPeriodMonth !== null) {
            $anchor = \Carbon\Carbon::create($latestPeriodYear, $latestPeriodMonth, 1);
            $prev = $anchor->copy()->subMonthNoOverflow();
            $momPreviousYear = (int) $prev->year;
            $momPreviousMonth = (int) $prev->month;

            $momPreviousTotal = (float) Balance::where('year', $momPreviousYear)
                ->where('month', $momPreviousMonth)
                ->sum('balance_amount');
            $momPreviousCount = Balance::where('year', $momPreviousYear)
                ->where('month', $momPreviousMonth)
                ->count();
            $momAnchorCount = Balance::where('year', $latestPeriodYear)
                ->where('month', $latestPeriodMonth)
                ->count();

            $momHasPrior = $momPreviousCount > 0;

            if ($momHasPrior) {
                $momDelta = (float) $totalBalance - $momPreviousTotal;
                if ($momPreviousTotal > 0) {
                    $momPercent = ($momDelta / $momPreviousTotal) * 100;
                }
                $momCoverageMatch = ($momAnchorCount === $momPreviousCount);
            }
        }

        return view('admin.balances.index', compact(
            'balances',
            'year',
            'month',
            'totalBalance',
            'accountsReported',
            'activeAccounts',
            'isSpecificPeriod',
            'hasMonthFilter',
            'hasYearFilter',
            'latestPeriodYear',
            'latestPeriodMonth',
            'momPreviousYear',
            'momPreviousMonth',
            'momPreviousTotal',
            'momPreviousCount',
            'momAnchorCount',
            'momDelta',
            'momPercent',
            'momHasPrior',
            'momCoverageMatch'
        ));
    }

    public function create()
    {
        $bankAccounts = BankAccount::where('is_active', true)
            ->orderBy('bank_name')
            ->get();

        $prev = now()->subMonthNoOverflow();
        $defaultYear = (int) $prev->year;
        $defaultMonth = (int) $prev->month;

        return view('admin.balances.create', compact(
            'bankAccounts',
            'defaultYear',
            'defaultMonth'
        ));
    }

    public function store(StoreBalanceRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $balance = Balance::create($data);

        return redirect()
            ->route('admin.balances.index', [
                'year' => $balance->year,
                'month' => $balance->month,
            ])
            ->with('success', 'Laporan balance berhasil dibuat.');
    }

    public function edit(Balance $balance)
    {
        $bankAccounts = BankAccount::where('is_active', true)
            ->orderBy('bank_name')
            ->get();

        $balance->load(['bankAccount', 'creator']);

        return view('admin.balances.edit', compact('balance', 'bankAccounts'));
    }

    public function update(UpdateBalanceRequest $request, Balance $balance)
    {
        $balance->update($request->validated());

        return redirect()
            ->route('admin.balances.index', [
                'year' => $balance->year,
                'month' => $balance->month,
            ])
            ->with('success', 'Laporan balance berhasil diupdate.');
    }

    public function destroy(Balance $balance)
    {
        $year = $balance->year;
        $month = $balance->month;

        $balance->delete();

        return redirect()
            ->route('admin.balances.index', ['year' => $year, 'month' => $month])
            ->with('success', 'Laporan balance berhasil dihapus.');
    }
}
