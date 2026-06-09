<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingOverviewController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $endOfThisMonth = $now->copy()->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // ---- Row 1: Funnel metrics ----

        // New Leads This Month + delta vs Last Month
        $newLeadsThisMonth = Lead::whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])->count();
        $newLeadsLastMonth = Lead::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $newLeadsDelta = $newLeadsThisMonth - $newLeadsLastMonth;

        // Conversion Rate This Month — promoted / total leads created this month
        $promotedThisMonth = Lead::whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->where('status', 'promoted')
            ->count();
        $promotedLastMonth = Lead::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->where('status', 'promoted')
            ->count();

        $conversionRateThisMonth = $newLeadsThisMonth > 0
            ? round(($promotedThisMonth / $newLeadsThisMonth) * 100, 1)
            : 0;

        $conversionRateLastMonth = $newLeadsLastMonth > 0
            ? round(($promotedLastMonth / $newLeadsLastMonth) * 100, 1)
            : 0;

        $conversionRateDelta = round($conversionRateThisMonth - $conversionRateLastMonth, 1);

        // Active Leads — snapshot saat ini
        $activeLeadsCount = Lead::whereIn('status', ['new', 'contacted', 'screened'])->count();
        $unassignedActiveLeads = Lead::whereIn('status', ['new', 'contacted', 'screened'])
            ->whereNull('assigned_to')
            ->count();

        // ---- Row 2: Status breakdown (snapshot) + Top sources (last 30 days) ----

        $statusBreakdown = [];
        foreach (array_keys(Lead::STATUSES) as $status) {
            $statusBreakdown[$status] = Lead::where('status', $status)->count();
        }
        $statusBreakdownMax = max($statusBreakdown) ?: 1;

        $sourceLast30Days = Lead::select('source', DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->groupBy('source')
            ->orderByDesc('total')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->source => (int) $row->total])
            ->toArray();
        $sourceMax = ! empty($sourceLast30Days) ? max($sourceLast30Days) : 1;

        // ---- Row 3: Trend chart - multi-series (last 12 months) ----

        $trendStart = $now->copy()->subMonths(11)->startOfMonth();

        $rawInflow = Lead::select(
                DB::raw('YEAR(created_at) as y'),
                DB::raw('MONTH(created_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $trendStart)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => $row->y . '-' . str_pad($row->m, 2, '0', STR_PAD_LEFT));

        $rawPromoted = Lead::select(
                DB::raw('YEAR(promoted_at) as y'),
                DB::raw('MONTH(promoted_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('promoted_at')
            ->where('promoted_at', '>=', $trendStart)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => $row->y . '-' . str_pad($row->m, 2, '0', STR_PAD_LEFT));

        $trendLabels = [];
        $trendInflow = [];
        $trendPromoted = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $key = $month->format('Y-m');
            $trendLabels[] = $month->format('M Y');
            $trendInflow[] = isset($rawInflow[$key]) ? (int) $rawInflow[$key]->total : 0;
            $trendPromoted[] = isset($rawPromoted[$key]) ? (int) $rawPromoted[$key]->total : 0;
        }

        return view('admin.marketing.overview', compact(
            'newLeadsThisMonth',
            'newLeadsDelta',
            'conversionRateThisMonth',
            'conversionRateDelta',
            'activeLeadsCount',
            'unassignedActiveLeads',
            'statusBreakdown',
            'statusBreakdownMax',
            'sourceLast30Days',
            'sourceMax',
            'trendLabels',
            'trendInflow',
            'trendPromoted'
        ));
    }
}
