<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateRequest;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademyOverviewController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $endOfThisMonth = $now->copy()->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // ---- KPI Cards ----

        // 1. Total Members (active)
        $totalActiveMembers = Member::where('is_active', 1)->count();

        // 2. New Members This Month + delta vs Last Month
        $newMembersThisMonth = Member::whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])->count();
        $newMembersLastMonth = Member::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $newMembersDelta = $newMembersThisMonth - $newMembersLastMonth;

        // 3. Active Certificates (issued and not revoked)
        $activeCertificates = Certificate::where('status', 'active')->count();

        // 4. Pending Certificate Requests
        $pendingRequests = CertificateRequest::where('status', 'pending')->count();

        // ---- Chart: Member Growth Last 12 Months ----

        // Build 12 month labels (oldest to newest), e.g. Jun 2025 → May 2026
        $months = [];
        $monthKeys = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $months[] = $m->format('M Y');           // "Jun 2025"
            $monthKeys[] = $m->format('Y-m');         // "2025-06"
        }

        // Query members grouped by year-month
        $rawCounts = DB::table('members')
            ->whereNull('deleted_at')
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('ym')
            ->pluck('cnt', 'ym')
            ->toArray();

        // Fill 12 months with 0 for empty
        $chartData = [];
        foreach ($monthKeys as $key) {
            $chartData[] = (int) ($rawCounts[$key] ?? 0);
        }

        return view('admin.academy.overview', compact(
            'totalActiveMembers',
            'newMembersThisMonth',
            'newMembersDelta',
            'activeCertificates',
            'pendingRequests',
            'months',
            'chartData'
        ));
    }
}
