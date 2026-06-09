<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;

class RecurringExpenseGenerator
{
    /**
     * Generate draft occurrences for every active recurring chain,
     * filling missing periods up to the current month.
     *
     * Idempotent. Lazy-triggered (no cron).
     *
     * @return int Number of draft rows created this run.
     */
    public function run(): int
    {
        $created = 0;
        $currentPeriod = Carbon::now()->startOfMonth();

        // Roots = original recurring sources (no parent). Each root owns a chain;
        // its occurrences (children) live under recurring_parent_id = root->id.
        $roots = Expense::query()
            ->whereIn('recurring_type', ['monthly', 'yearly'])
            ->whereNull('recurring_parent_id')
            ->get();

        foreach ($roots as $root) {
            $created += $this->generateForChain($root, $currentPeriod);
        }

        return $created;
    }

    /**
     * Generate missing draft occurrences for a single chain, identified by its root.
     */
    private function generateForChain(Expense $root, Carbon $currentPeriod): int
    {
        $created = 0;
        $stepMonths = $root->recurring_type === 'yearly' ? 12 : 1;

        // The latest occurrence in the chain (root or any child) drives everything:
        // its period is where we resume from, and its amount is the default to carry.
        $latest = $this->latestOccurrence($root);

        // A skipped latest still occupies its period — we resume from the next step.
        $lastPeriod = $latest->expense_date->copy()->startOfMonth();
        $lastAmount = $latest->amount;

        // Hard stop, evaluated at month granularity.
        $until = $root->recurring_until
            ? $root->recurring_until->copy()->startOfMonth()
            : null;

        $period = $lastPeriod->copy()->addMonths($stepMonths);

        while ($period->lte($currentPeriod)) {
            if ($until && $period->gt($until)) {
                break;
            }

            $exists = Expense::query()
                ->where('recurring_parent_id', $root->id)
                ->whereYear('expense_date', $period->year)
                ->whereMonth('expense_date', $period->month)
                ->exists();

            if (! $exists) {
                Expense::create([
                    'expense_category_id' => $root->expense_category_id,
                    'created_by' => $root->created_by,
                    'amount' => $lastAmount,
                    'expense_date' => $period->copy()->startOfMonth(),
                    'vendor_name' => $root->vendor_name,
                    'payment_method' => $root->payment_method,
                    'recurring_type' => $root->recurring_type,
                    'status' => Expense::STATUS_DRAFT,
                    'recurring_parent_id' => $root->id,
                    'reference_number' => $root->reference_number,
                    'description' => $root->description,
                ]);
                $created++;
            }

            $period->addMonths($stepMonths);
        }

        return $created;
    }

    /**
     * Latest occurrence in a chain: the root itself, or its newest child by date.
     */
    private function latestOccurrence(Expense $root): Expense
    {
        $latestChild = Expense::query()
            ->where('recurring_parent_id', $root->id)
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->first();

        if ($latestChild && $latestChild->expense_date->gte($root->expense_date)) {
            return $latestChild;
        }

        return $root;
    }
}
