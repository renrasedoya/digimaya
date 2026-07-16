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

        // ---- Row 1: Lifecycle / state metrics ----

        $activeClients = Client::where('status', 'active')->count();
        $mrr = (float) Client::where('status', 'active')->sum('monthly_retainer');

        // Real client base for retention math — excludes prospects and lost (never-clients).
        $realClients = Client::whereIn('status', ['active', 'inactive', 'churned'])->count();

        // ARPA = average MRR per active client (unit economics for sales/finance).
        $arpa = $activeClients > 0 ? $mrr / $activeClients : 0.0;

        // ---- Row 2: Monthly performance table ----
        // Per-month activations & churn, reused below to build the performance table.

        // History only exists from the moment tracking began (the backfill import).
        // Rendering months before that as zeros reads as "no business happened",
        // which is a lie — there was business, we just have no record of it. So the
        // table starts at the first recorded event and never claims more than it knows.
        $firstHistoryAt = ClientStatusHistory::min('changed_at');
        $trackingStart = $firstHistoryAt
            ? Carbon::parse($firstHistoryAt)->startOfMonth()
            : $now->copy()->startOfMonth();

        $monthsToShow = min(11, $trackingStart->diffInMonths($now->copy()->startOfMonth()));
        $trendStart = $now->copy()->subMonths($monthsToShow)->startOfMonth();

        // Backfill rows are synthetic: one status_to='active' event per pre-existing
        // client, all stamped with the import date. Counting them as activations would
        // report the entire existing client book as new business in a single month —
        // and would blow up the active-base reconstruction below (it goes negative).
        // They are excluded here and from $rawNewMrr for the same reason.
        $rawActivations = ClientStatusHistory::select(
                DB::raw('YEAR(changed_at) as y'),
                DB::raw('MONTH(changed_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status_to', 'active')
            ->excludingBackfill()
            ->where('changed_at', '>=', $trendStart)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => $row->y . '-' . str_pad($row->m, 2, '0', STR_PAD_LEFT));

        $rawLosses = ClientStatusHistory::select(
                DB::raw('YEAR(changed_at) as y'),
                DB::raw('MONTH(changed_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status_from', 'active')
            ->whereIn('status_to', ClientStatusHistory::CHURN_STATUSES)
            ->where('changed_at', '>=', $trendStart)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => $row->y . '-' . str_pad($row->m, 2, '0', STR_PAD_LEFT));

        // Prospect funnel, per month: won (prospect→active) vs lost (prospect→lost).

        $keyByYm = fn ($row) => $row->y . '-' . str_pad($row->m, 2, '0', STR_PAD_LEFT);

        $rawWon = ClientStatusHistory::select(
                DB::raw('YEAR(changed_at) as y'),
                DB::raw('MONTH(changed_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status_from', 'prospect')
            ->where('status_to', 'active')
            ->where('changed_at', '>=', $trendStart)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy($keyByYm);

        $rawProspectLost = ClientStatusHistory::select(
                DB::raw('YEAR(changed_at) as y'),
                DB::raw('MONTH(changed_at) as m'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status_from', 'prospect')
            ->where('status_to', 'lost')
            ->where('changed_at', '>=', $trendStart)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy($keyByYm);

        // MRR movement, per month: revenue gained from activations vs lost to churn.
        // Uses the client's current monthly_retainer (retainers are stored per-client and
        // stable over time) as a close proxy for the value at transition time.
        $mrrSelect = [
            DB::raw('YEAR(client_status_history.changed_at) as y'),
            DB::raw('MONTH(client_status_history.changed_at) as m'),
            DB::raw('SUM(clients.monthly_retainer) as total'),
        ];

        $rawNewMrr = ClientStatusHistory::query()
            ->join('clients', 'clients.id', '=', 'client_status_history.client_id')
            ->where('client_status_history.status_to', 'active')
            ->excludingBackfill()
            ->where('client_status_history.changed_at', '>=', $trendStart)
            ->select($mrrSelect)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy($keyByYm);

        $rawChurnedMrr = ClientStatusHistory::query()
            ->join('clients', 'clients.id', '=', 'client_status_history.client_id')
            ->where('client_status_history.status_from', 'active')
            ->whereIn('client_status_history.status_to', ClientStatusHistory::CHURN_STATUSES)
            ->where('client_status_history.changed_at', '>=', $trendStart)
            ->select($mrrSelect)
            ->groupBy('y', 'm')
            ->get()
            ->keyBy($keyByYm);

        // Reconstruct the active client base at the start of each month by walking
        // backward from the current active count. Every real status_to='active' event is
        // +1 active; every status_from='active' event (→inactive/churned) is −1. So:
        //   active_start(M) = active_end(M) − activations(M) + deactivations(M)
        // and active_end(M) = active_start(M+1).
        //
        // Backfilled clients are excluded from activations, which correctly treats them
        // as already-active at the start of the import month rather than as new business.
        $monthlyPerformance = [];
        $runningActiveEnd = $activeClients; // active "now" = end of the current month so far

        for ($i = 0; $i <= $monthsToShow; $i++) {
            $month = $now->copy()->subMonths($i);
            $key = $month->format('Y-m');

            $newActive  = isset($rawActivations[$key]) ? (int) $rawActivations[$key]->total : 0;
            $churned    = isset($rawLosses[$key]) ? (int) $rawLosses[$key]->total : 0;
            $won        = isset($rawWon[$key]) ? (int) $rawWon[$key]->total : 0;
            $lostProsp  = isset($rawProspectLost[$key]) ? (int) $rawProspectLost[$key]->total : 0;
            $newMrr     = isset($rawNewMrr[$key]) ? (float) $rawNewMrr[$key]->total : 0.0;
            $churnedMrr = isset($rawChurnedMrr[$key]) ? (float) $rawChurnedMrr[$key]->total : 0.0;
            $netMrr     = $newMrr - $churnedMrr;

            $activeBaseStart = $runningActiveEnd - $newActive + $churned;

            $decided = $won + $lostProsp;

            $monthlyPerformance[] = [
                'label'         => $month->format('M Y'),
                'isCurrent'     => $i === 0,
                'newActive'     => $newActive,
                'churned'       => $churned,
                'net'           => $newActive - $churned,
                'won'           => $won,
                'lostProsp'     => $lostProsp,
                'winRate'       => $decided > 0 ? round($won / $decided * 100) : null,
                'activeBase'    => max($activeBaseStart, 0),
                'churnRate'     => $activeBaseStart > 0 ? round($churned / $activeBaseStart * 100, 1) : null,
                'newMrr'        => $newMrr,
                'churnedMrr'    => $churnedMrr,
                'netMrr'        => $netMrr,
                'newMrrFmt'     => $newMrr > 0 ? $this->compactRupiah($newMrr) : null,
                'churnedMrrFmt' => $churnedMrr > 0 ? $this->compactRupiah($churnedMrr) : null,
                'netMrrFmt'     => $netMrr == 0 ? null
                    : ($netMrr > 0 ? '+' : '−') . $this->compactRupiah(abs($netMrr)),
            ];

            // Start of this month becomes the end-of-month figure for the previous month.
            $runningActiveEnd = $activeBaseStart;
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
            ->becameActive()
            ->excludingBackfill()
            ->whereBetween('changed_at', [$monthStart, $monthEnd])
            ->orderBy('changed_at', 'asc')
            ->get();

        $lostClientsList = ClientStatusHistory::with('client:id,business_name')
            ->becameInactive()
            ->excludingBackfill()
            ->whereBetween('changed_at', [$monthStart, $monthEnd])
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
            ->excludingBackfill()
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
            'realClients',
            'activeClients',
            'mrr',
            'arpa',
            'monthlyPerformance',
            'trackingStart',
            'monthsToShow',
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

    /**
     * Compact Rupiah for dense table cells: Rp1,2 jt / Rp250 rb / Rp1,5 M (miliar).
     */
    private function compactRupiah(float $v): string
    {
        $v = abs($v);

        if ($v >= 1_000_000_000) {
            return 'Rp' . rtrim(rtrim(number_format($v / 1_000_000_000, 1, ',', '.'), '0'), ',') . ' M';
        }
        if ($v >= 1_000_000) {
            return 'Rp' . rtrim(rtrim(number_format($v / 1_000_000, 1, ',', '.'), '0'), ',') . ' jt';
        }
        if ($v >= 1_000) {
            return 'Rp' . number_format($v / 1_000, 0, ',', '.') . ' rb';
        }

        return 'Rp' . number_format($v, 0, ',', '.');
    }
}