<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientStatusHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrmOverviewController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $endOfThisMonth = $now->copy()->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // ---- Row 1: Lifecycle / state metrics ----

        $totalClients = Client::count();
        $activeClients = Client::where('status', 'active')->count();
        $mrr = (float) Client::where('status', 'active')->sum('monthly_retainer');

        // ---- Row 2: This-month flow metrics (from history) ----

        $newActiveThisMonth = ClientStatusHistory::becameActive()
            ->whereBetween('changed_at', [$startOfThisMonth, $endOfThisMonth])
            ->count();

        $newActiveLastMonth = ClientStatusHistory::becameActive()
            ->whereBetween('changed_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $newActiveDelta = $newActiveThisMonth - $newActiveLastMonth;

        $lostThisMonth = ClientStatusHistory::becameInactive()
            ->whereBetween('changed_at', [$startOfThisMonth, $endOfThisMonth])
            ->count();

        $lostLastMonth = ClientStatusHistory::becameInactive()
            ->whereBetween('changed_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $lostDelta = $lostThisMonth - $lostLastMonth;

        // ---- Row 3: Trend chart - multi-series (last 12 months) ----

        $trendStart = $now->copy()->subMonths(11)->startOfMonth();

        $rawActivations = ClientStatusHistory::select(
                DB::raw('YEAR(changed_at) as y'),
                DB::raw('MONTH(changed_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status_to', 'active')
            ->where('changed_at', '>=', $trendStart)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => $row->y . '-' . str_pad($row->m, 2, '0', STR_PAD_LEFT));

        $rawLosses = ClientStatusHistory::select(
                DB::raw('YEAR(changed_at) as y'),
                DB::raw('MONTH(changed_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status_to', 'inactive')
            ->where('changed_at', '>=', $trendStart)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => $row->y . '-' . str_pad($row->m, 2, '0', STR_PAD_LEFT));

        $trendLabels = [];
        $trendActivations = [];
        $trendLosses = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $key = $month->format('Y-m');
            $trendLabels[] = $month->format('M Y');
            $trendActivations[] = isset($rawActivations[$key]) ? (int) $rawActivations[$key]->total : 0;
            $trendLosses[] = isset($rawLosses[$key]) ? (int) $rawLosses[$key]->total : 0;
        }

        // ---- Row 4: New & Lost Clients list (filterable by month) ----

        // Default: Last Month (for early-month team review use case)
        $defaultMonth = $now->copy()->subMonth()->format('Y-m');
        $selectedMonth = $request->query('month', $defaultMonth);

        // Validate format YYYY-MM, fallback to default if invalid
        if (! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = $defaultMonth;
        }

        try {
            $monthCarbon = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Exception $e) {
            $monthCarbon = $now->copy()->subMonth()->startOfMonth();
            $selectedMonth = $monthCarbon->format('Y-m');
        }

        $monthStart = $monthCarbon->copy()->startOfMonth();
        $monthEnd = $monthCarbon->copy()->endOfMonth();

        $newClientsList = ClientStatusHistory::with('client:id,business_name')
            ->where('status_to', 'active')
            ->whereBetween('changed_at', [$monthStart, $monthEnd])
            ->where(function ($q) {
                // Skip backfill rows
                $q->whereNull('notes')->orWhere('notes', 'NOT LIKE', 'Backfill%');
            })
            ->orderBy('changed_at', 'asc')
            ->get();

        $lostClientsList = ClientStatusHistory::with('client:id,business_name')
            ->where('status_to', 'inactive')
            ->whereBetween('changed_at', [$monthStart, $monthEnd])
            ->where(function ($q) {
                $q->whereNull('notes')->orWhere('notes', 'NOT LIKE', 'Backfill%');
            })
            ->orderBy('changed_at', 'asc')
            ->get();

        // Build month dropdown options (last 12 months including current)
        $monthOptions = [];
        for ($i = 0; $i < 12; $i++) {
            $m = $now->copy()->subMonths($i);
            $monthOptions[] = [
                'value' => $m->format('Y-m'),
                'label' => $m->format('F Y'),
            ];
        }

        // ---- Row 5: Recent Activity (last 5 transitions, excluding backfill) ----

        $recentActivity = ClientStatusHistory::with('client:id,business_name')
            ->where(function ($q) {
                $q->whereNull('notes')->orWhere('notes', 'NOT LIKE', 'Backfill%');
            })
            ->orderByDesc('changed_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        // ---- Prospects card (Phase 14.8.1, Opsi D: ALL prospects with age breakdown) ----
        $prospectFreshStart = $now->copy()->subDays(30);

        $totalProspects = Client::where('status', 'prospect')->count();

        $freshProspects = Client::where('status', 'prospect')
            ->where('created_at', '>=', $prospectFreshStart)
            ->count();

        $agedProspects = $totalProspects - $freshProspects;

        // Urgency trigger: aged prospects WITHOUT pending followup
        $agedProspectsNeedFu = Client::where('status', 'prospect')
            ->where('created_at', '<', $prospectFreshStart)
            ->whereDoesntHave('followups', function ($q) {
                $q->whereNull('completed_at');
            })
            ->count();

        return view('admin.crm.overview', compact(
            'totalClients',
            'activeClients',
            'mrr',
            'newActiveThisMonth',
            'newActiveDelta',
            'lostThisMonth',
            'lostDelta',
            'trendLabels',
            'trendActivations',
            'trendLosses',
            'newClientsList',
            'lostClientsList',
            'selectedMonth',
            'monthOptions',
            'recentActivity',
            'totalProspects',
            'freshProspects',
            'agedProspects',
            'agedProspectsNeedFu'
        ));
    }
}