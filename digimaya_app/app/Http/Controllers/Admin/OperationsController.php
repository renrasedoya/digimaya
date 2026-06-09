<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperationsController extends Controller
{
    public function overview(Request $request): View
    {
        $user = $request->user();

        // ============ Build scoped queries based on role ============
        // super_admin/admin: all projects, all reports
        // account_manager: projects where client.account_manager_id = $user->id
        // advertiser: projects where advertiser_id = $user->id

        $projectQuery = Project::query();
        $reportQuery = ProjectReport::query();

        if ($user->isAccountManager()) {
            $projectQuery->whereHas('client', function ($q) use ($user) {
                $q->where('account_manager_id', $user->id);
            });
            $reportQuery->whereHas('project.client', function ($q) use ($user) {
                $q->where('account_manager_id', $user->id);
            });
        } elseif ($user->isAdvertiser()) {
            $projectQuery->where('advertiser_id', $user->id);
            $reportQuery->whereHas('project', function ($q) use ($user) {
                $q->where('advertiser_id', $user->id);
            });
        } elseif (!in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            // Marketing or other unsupported role
            abort(403, 'You do not have access to Operations Overview.');
        }

        // ============ KPI Metrics ============
        $activeProjects = (clone $projectQuery)->where('status', 'active')->count();

        $totalProjects = (clone $projectQuery)->count();

        $reportsThisMonth = (clone $reportQuery)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $unreviewedCount = (clone $reportQuery)->whereNull('reviewed_at')->count();

        $criticalActive = (clone $reportQuery)
            ->where('health', ProjectReport::HEALTH_CRITICAL)
            ->where('status', '!=', ProjectReport::STATUS_RESOLVED)
            ->count();

        $pendingAckCount = (clone $reportQuery)
            ->whereNotNull('reviewed_at')
            ->whereNull('acknowledged_at')
            ->count();

        // Projects awaiting first report (advertiser onboarding)
        $awaitingFirstReportCount = 0;
        if ($user->isAdvertiser()) {
            $awaitingFirstReportCount = (clone $projectQuery)
                ->whereDoesntHave('reports')
                ->count();
        }

        // Clients awaiting first project (AM onboarding)
        $awaitingFirstProjectCount = 0;
        if ($user->isAccountManager()) {
            $awaitingFirstProjectCount = \App\Models\Client::where('account_manager_id', $user->id)
                ->whereIn('status', ['active', 'inactive', 'churned'])
                ->whereDoesntHave('projects')
                ->count();
        }

        $kpis = compact(
            'activeProjects',
            'totalProjects',
            'reportsThisMonth',
            'unreviewedCount',
            'criticalActive',
            'pendingAckCount',
            'awaitingFirstReportCount',
            'awaitingFirstProjectCount'
        );

        $canReviewAny = in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)
            || $user->isAccountManager();

        // Stale Projects visible to reviewers (super/admin/AM) AND advertisers (own projects only, scoped via $projectQuery)
        $canSeeStale = $canReviewAny || $user->isAdvertiser();

        // ============ Recent Reports (paginated, with filters) ============
        $recentReportsQuery = (clone $reportQuery)
            ->with(['project:id,name,client_id', 'project.client:id,business_name', 'submitter:id,name', 'reviewer:id,name']);

        if ($request->boolean('critical_active')) {
            $recentReportsQuery->where('health', ProjectReport::HEALTH_CRITICAL)
                               ->whereIn('status', ['open', 'in_progress']);
        }
        // Filter: month + year
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', 0);
        if ($month >= 1 && $month <= 12) {
            $recentReportsQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
        } elseif ($request->filled('year')) {
            $recentReportsQuery->whereYear('created_at', $year);
        }

        // Filter: health
        if ($request->filled('health')) {
            $recentReportsQuery->where('health', $request->health);
        }

        // Filter: report status
        if ($request->filled('report_status')) {
            $recentReportsQuery->where('status', $request->report_status);
        }

        // Filter: advertiser (the submitter, which is always advertiser role)
        if ($request->filled('advertiser_id')) {
            $recentReportsQuery->where('submitted_by', $request->advertiser_id);
        }

        // Filter: AM (super_admin + admin only) — filter via client.account_manager_id
        if ($request->filled('account_manager_id')) {
            $amId = $request->account_manager_id;
            $recentReportsQuery->whereHas('project.client', function ($q) use ($amId) {
                $q->where('account_manager_id', $amId);
            });
        }

        // Filter: review/ack lifecycle status
        // pending_review = AM belum review
        // pending_ack = AM sudah review, advertiser belum acknowledge
        // completed = advertiser sudah acknowledge
        if ($request->filled('review')) {
            if ($request->review === 'pending_review') {
                $recentReportsQuery->whereNull('reviewed_at');
            } elseif ($request->review === 'pending_ack') {
                $recentReportsQuery->whereNotNull('reviewed_at')->whereNull('acknowledged_at');
            } elseif ($request->review === 'completed') {
                $recentReportsQuery->whereNotNull('acknowledged_at');
            }
        }

        // Filter: search (project name or client business name)
        if ($request->filled('search')) {
            $search = $request->search;
            $recentReportsQuery->whereHas('project', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q2) use ($search) {
                        $q2->where('business_name', 'like', "%{$search}%");
                    });
            });
        }

        // Review lifecycle counts (for tabs at top)
        $reviewCountsBase = (clone $reportQuery);
        // Apply other filters (except 'review' itself) to keep tab counts in sync with current filter context
        // Simple approach: just count all reports in scope, ignore other filters for now
        $reviewCounts = [
            'all'             => (clone $reviewCountsBase)->count(),
            'pending_review'  => (clone $reviewCountsBase)->whereNull('reviewed_at')->count(),
            'pending_ack'     => (clone $reviewCountsBase)->whereNotNull('reviewed_at')->whereNull('acknowledged_at')->count(),
            'completed'       => (clone $reviewCountsBase)->whereNotNull('acknowledged_at')->count(),
        ];

        $recentReports = $recentReportsQuery
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // ============ Advertiser list for filter dropdown ============
        // Scoped to advertisers who have submitted reports visible to this user
        $advertisers = User::byRole(User::ROLE_ADVERTISER)
            ->active()
            ->whereIn('id', (clone $reportQuery)->distinct()->pluck('submitted_by'))
            ->orderBy('name')
            ->get(['id', 'name']);

        // ============ Account Manager list for filter dropdown ============
        // Only super_admin + admin can filter by AM (AM and advertiser don't need this)
        $accountManagers = collect();
        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            $accountManagers = User::byRole(User::ROLE_ACCOUNT_MANAGER)
                ->active()
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        // ============ Stale Projects (active projects with no report in 7 days; reviewers + advertiser-own) ============
        $staleProjects = collect();
        if ($canSeeStale) {
            $staleProjects = (clone $projectQuery)
                ->where('status', 'active')
                ->whereDoesntHave('reports', function ($q) {
                    $q->where('created_at', '>=', now()->subDays(7));
                })
                ->with(['client:id,business_name', 'advertiser:id,name'])
                ->withMax('reports as last_report_at', 'created_at')
                ->orderByRaw('last_report_at IS NULL DESC, last_report_at ASC')
                ->limit(20)
                ->get();
        }

        return view('admin.operations.overview', compact('kpis', 'recentReports', 'canReviewAny', 'canSeeStale', 'advertisers', 'accountManagers', 'year', 'month', 'staleProjects', 'reviewCounts'));
    }
}
