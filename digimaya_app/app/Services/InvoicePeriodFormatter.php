<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class InvoicePeriodFormatter
{
    /**
     * Format a billing period for display in invoices.
     *
     * Rules:
     * - Same month, same year     → "1 - 31 May 2026"
     * - Different month, same year → "15 May - 14 June 2026"
     * - Different year             → "15 December 2025 - 14 January 2026"
     *
     * @param  CarbonInterface|string|null  $start
     * @param  CarbonInterface|string|null  $end
     * @return string|null  Returns null if either date is missing.
     */
    public static function format($start, $end): ?string
    {
        if (! $start || ! $end) {
            return null;
        }

        $start = $start instanceof CarbonInterface ? $start : Carbon::parse($start);
        $end   = $end instanceof CarbonInterface ? $end   : Carbon::parse($end);

        // Different year → write both years
        if ($start->year !== $end->year) {
            return $start->format('j F Y') . ' - ' . $end->format('j F Y');
        }

        // Same month and year → "1 - 31 May 2026"
        if ($start->month === $end->month) {
            return $start->format('j') . ' - ' . $end->format('j F Y');
        }

        // Different month, same year → "15 May - 14 June 2026"
        return $start->format('j F') . ' - ' . $end->format('j F Y');
    }

    /**
     * Compute the default billing period for a project (Mode 1 invoice).
     *
     * Strategy: "Next period after today" (upfront billing).
     * - anchor day = day-of-month from project.started_at
     * - period covers anchor day → (anchor day - 1) of following month
     * - default to the period that STARTS on or after today
     *
     * Examples (anchor=15):
     * - today=11 May 2026  → period_start=15 May,  period_end=14 June 2026
     * - today=14 May 2026  → period_start=15 May,  period_end=14 June 2026
     * - today=15 May 2026  → period_start=15 June, period_end=14 July 2026
     * - today=20 June 2026 → period_start=15 July, period_end=14 August 2026
     *
     * Special case (anchor=1):
     * - period covers 1 → last day of same month (calendar month)
     * - today=15 May 2026 → period_start=1 June, period_end=30 June 2026
     *
     * @param  CarbonInterface|string  $projectStartedAt
     * @param  CarbonInterface|null    $today  (defaults to now)
     * @return array  ['start' => Carbon, 'end' => Carbon]
     */
    public static function computeDefaultPeriod($projectStartedAt, ?CarbonInterface $today = null): array
    {
        $started = $projectStartedAt instanceof CarbonInterface
            ? $projectStartedAt
            : Carbon::parse($projectStartedAt);
        $today = $today ?? Carbon::today();

        $anchorDay = $started->day;

        if ($anchorDay === 1) {
            // Calendar-month billing: period = whole next month after today
            $start = $today->copy()->addMonthNoOverflow()->startOfMonth();
            $end   = $start->copy()->endOfMonth();
            return ['start' => $start, 'end' => $end];
        }

        // Anchor-day billing: find next period that STARTS on or after today
        // Try this month's anchor first
        $candidateStart = $today->copy()->startOfMonth()->addDays($anchorDay - 1);

        // If anchor for this month is already past (today >= anchor), bump to next month
        if ($today->gte($candidateStart)) {
            $candidateStart = $candidateStart->copy()->addMonthNoOverflow();
        }

        $candidateEnd = $candidateStart->copy()->addMonthNoOverflow()->subDay();

        return ['start' => $candidateStart, 'end' => $candidateEnd];
    }
}
