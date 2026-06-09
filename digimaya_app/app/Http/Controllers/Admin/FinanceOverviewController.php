<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\View\View;

class FinanceOverviewController extends Controller
{
    public function index(): View
    {
        $now = now();
        $thisMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();

        // This month totals
        $incomeThisMonth = Income::forMonth($now->year, $now->month)->sum('amount');
        $expenseThisMonth = Expense::confirmed()->forMonth($now->year, $now->month)->sum('amount');
        $profitThisMonth = $incomeThisMonth - $expenseThisMonth;

        // Last month totals (for comparison)
        $incomeLastMonth = Income::forMonth($lastMonthStart->year, $lastMonthStart->month)->sum('amount');
        $expenseLastMonth = Expense::confirmed()->forMonth($lastMonthStart->year, $lastMonthStart->month)->sum('amount');
        $profitLastMonth = $incomeLastMonth - $expenseLastMonth;

        // Percentage change calculations
        $incomeChange = $this->percentChange($incomeLastMonth, $incomeThisMonth);
        $expenseChange = $this->percentChange($expenseLastMonth, $expenseThisMonth);
        $profitChange = $this->percentChange($profitLastMonth, $profitThisMonth);

        // 6 months trend data
        $monthsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $income = Income::forMonth($date->year, $date->month)->sum('amount');
            $expense = Expense::confirmed()->forMonth($date->year, $date->month)->sum('amount');
            $monthsData[] = [
                'label' => $date->format('M Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
                'profit' => (float) ($income - $expense),
            ];
        }

        // This month income breakdown by category
        $incomeByCategory = [
            'agency' => (float) Income::forMonth($now->year, $now->month)->where('source_category', 'agency')->sum('amount'),
            'academy' => (float) Income::forMonth($now->year, $now->month)->where('source_category', 'academy')->sum('amount'),
            'other' => (float) Income::forMonth($now->year, $now->month)->where('source_category', 'other')->sum('amount'),
        ];

        // Balance KPI — Latest Snapshot semantic
        // Find the latest period (year+month) that has any balance record
        $latestBalance = Balance::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        $totalBalance = 0.0;
        $balancePeriodLabel = null;
        $balanceChange = null;
        $hasBalanceData = false;

        if ($latestBalance) {
            $hasBalanceData = true;
            $latestYear = (int) $latestBalance->year;
            $latestMonth = (int) $latestBalance->month;
            $totalBalance = (float) Balance::where('year', $latestYear)
                ->where('month', $latestMonth)
                ->sum('balance_amount');
            $balancePeriodLabel = \Carbon\Carbon::create($latestYear, $latestMonth)->format('M Y');

            // MoM change vs previous month
            $prev = \Carbon\Carbon::create($latestYear, $latestMonth, 1)->subMonthNoOverflow();
            $prevTotal = (float) Balance::where('year', $prev->year)
                ->where('month', $prev->month)
                ->sum('balance_amount');
            if ($prevTotal > 0) {
                $balanceChange = $this->percentChange($prevTotal, $totalBalance);
            }
        }

        // 12-month Total Balance trend (rolling, ending at previous month from now)
        // Skip current month (not yet closed); include current month - 1 down to current month - 12
        $balanceTrend = [];
        for ($i = 12; $i >= 1; $i--) {
            $date = $now->copy()->subMonths($i);
            $sum = Balance::where('year', $date->year)
                ->where('month', $date->month)
                ->sum('balance_amount');
            $hasData = Balance::where('year', $date->year)
                ->where('month', $date->month)
                ->exists();
            $balanceTrend[] = [
                'label' => $date->format('M Y'),
                'value' => $hasData ? (float) $sum : null,
            ];
        }

        return view('admin.finance.overview', compact(
            'incomeThisMonth',
            'expenseThisMonth',
            'profitThisMonth',
            'incomeChange',
            'expenseChange',
            'profitChange',
            'monthsData',
            'incomeByCategory',
            'totalBalance',
            'balancePeriodLabel',
            'balanceChange',
            'hasBalanceData',
            'balanceTrend'
        ));
    }

    private function percentChange(float $previous, float $current): ?float
    {
        if ($previous == 0) {
            return $current == 0 ? 0.0 : null;
        }

        return round((($current - $previous) / abs($previous)) * 100, 1);
    }
}
