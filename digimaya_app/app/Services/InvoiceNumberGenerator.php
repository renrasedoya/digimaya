<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceNumberGenerator
{
    /**
     * Generate the next invoice number for the given issue date.
     *
     * Format: {PREFIX}/YYYY/MM/SEQ where SEQ is sequential per month.
     * Example: DGMY/2026/05/001
     *
     * IMPORTANT: This method MUST be called inside a DB::transaction() to ensure
     * the lockForUpdate() actually locks rows. Without a transaction, the lock
     * is a silent no-op and concurrent calls can produce duplicate numbers.
     *
     * @param  Carbon|string|null  $issueDate  Defaults to today (Asia/Jakarta).
     * @param  string|null  $prefix  Override prefix; defaults to Setting invoice_number_prefix.
     * @return string
     */
    public static function next($issueDate = null, ?string $prefix = null): string
    {
        $date = $issueDate ? Carbon::parse($issueDate) : Carbon::now();
        $year = $date->year;
        $month = $date->month;

        $prefix = $prefix ?? Setting::get('invoice_number_prefix', 'DGMY');

        $monthPrefix = sprintf('%s/%d/%02d/', $prefix, $year, $month);

        // Lock matching rows for the duration of the parent transaction.
        // Caller MUST be inside DB::transaction().
        // CRITICAL: withTrashed() — soft-deleted invoices still occupy invoice_number
        // in DB unique constraint. Without this, generator produces conflicts.
        $maxSeq = Invoice::query()
            ->withTrashed()
            ->lockForUpdate()
            ->where('invoice_number', 'like', $monthPrefix . '%')
            ->whereYear('issue_date', $year)
            ->whereMonth('issue_date', $month)
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(invoice_number, '/', -1) AS UNSIGNED)) AS max_seq")
            ->value('max_seq');

        $next = ((int) ($maxSeq ?? 0)) + 1;

        return sprintf('%s%03d', $monthPrefix, $next);
    }

    /**
     * Convenience wrapper that handles the transaction boundary itself.
     * Use this when you only need the number and are not bundling it with other
     * DB operations. If you need to insert the invoice atomically with the number,
     * call ::next() directly inside your own DB::transaction().
     */
    public static function nextInTransaction($issueDate = null, ?string $prefix = null): string
    {
        return DB::transaction(function () use ($issueDate, $prefix) {
            return static::next($issueDate, $prefix);
        });
    }
}
