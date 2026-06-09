<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $this->ensureAccessible($user);

        $query = Project::query()->with(['client:id,business_name,account_manager_id', 'advertiser:id,name', 'client.accountManager:id,name']);

        // Scope per role
        if ($user->isAccountManager()) {
            $query->forAccountManager($user->id);
        } elseif ($user->isAdvertiser()) {
            $query->forAdvertiser($user->id);
        }
        // super_admin + admin: no scope (sees all)

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('advertiser_id')) {
            $query->where('advertiser_id', $request->advertiser_id);
        }

        // AM filter only meaningful for super_admin/admin
        if ($request->filled('account_manager_id') && in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])) {
            $query->forAccountManager((int) $request->account_manager_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('client', fn ($cq) => $cq->where('business_name', 'like', "%{$search}%"));
            });
        }

        // Projects without any report yet (matches Operations "Awaiting First Report" KPI)
        if ($request->input('filter') === 'no_report') {
            $query->whereDoesntHave('reports');
        }

        $projects = $query->orderBy('status')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Status counts respecting role scope (clone before filters)
        $countQuery = Project::query();
        if ($user->isAccountManager()) {
            $countQuery->forAccountManager($user->id);
        } elseif ($user->isAdvertiser()) {
            $countQuery->forAdvertiser($user->id);
        }
        $statusCounts = ['total' => (clone $countQuery)->count()];
        foreach (array_keys(Project::STATUSES) as $status) {
            $statusCounts[$status] = (clone $countQuery)->where('status', $status)->count();
        }

        // Filter dropdown options scoped by role
        $accountManagers = collect();
        $availableClients = collect();
        $availableAdvertisers = collect();

        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])) {
            $accountManagers = User::byRole(User::ROLE_ACCOUNT_MANAGER)->active()->orderBy('name')->get(['id', 'name']);
            $availableClients = Client::orderBy('business_name')->get(['id', 'business_name']);
            $availableAdvertisers = User::byRole(User::ROLE_ADVERTISER)->active()->orderBy('name')->get(['id', 'name']);
        } elseif ($user->isAccountManager()) {
            $availableClients = Client::where('account_manager_id', $user->id)->orderBy('business_name')->get(['id', 'business_name']);
            $availableAdvertisers = User::where('parent_am_id', $user->id)->where('role', User::ROLE_ADVERTISER)->active()->orderBy('name')->get(['id', 'name']);
        }

        return view('admin.projects.index', compact('projects', 'statusCounts', 'accountManagers', 'availableClients', 'availableAdvertisers'));
    }

    public function show(Request $request, Project $project): View
    {
        $this->ensureCanViewProject($request->user(), $project);

        $project->load(['client.accountManager', 'advertiser']);

        // Build filtered reports query (Phase 14.4 inline mgmt)
        $reportsQuery = \App\Models\ProjectReport::with(['submitter:id,name', 'reviewer:id,name', 'issueCategory:id,name', 'issueSubCategory:id,name'])
            ->forProject($project->id);

        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', 0);
        if ($month >= 1 && $month <= 12) {
            $reportsQuery->whereYear('period_start', $year)->whereMonth('period_start', $month);
        } elseif ($request->filled('year')) {
            $reportsQuery->whereYear('period_start', $year);
        }

        if ($request->filled('health')) {
            $reportsQuery->where('health', $request->health);
        }

        if ($request->filled('report_status')) {
            $reportsQuery->where('status', $request->report_status);
        }

        if ($request->filled('report_id')) {
            $reportsQuery->where('id', $request->report_id);
        }

        $reports = $reportsQuery->orderBy('created_at', 'desc')->paginate(15, ['*'], 'reports_page')->withQueryString();

        // Status counts (un-filtered for context)
        $countQuery = \App\Models\ProjectReport::forProject($project->id);
        $reportStatusCounts = ['total' => (clone $countQuery)->count()];
        foreach (array_keys(\App\Models\ProjectReport::STATUSES) as $status) {
            $reportStatusCounts[$status] = (clone $countQuery)->where('status', $status)->count();
        }

        $issueCategories = \App\Models\IssueCategory::active()->ordered()
            ->with(['activeSubCategories' => function ($q) {
                $q->orderBy('display_order')->orderBy('name');
            }])->get();

        return view('admin.projects.show', compact('project', 'reports', 'reportStatusCounts', 'year', 'month', 'issueCategories'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $this->ensureCanCreate($user);

        [$clients, $advertisers] = $this->scopedClientsAndAdvertisers($user);

        $project = new Project(['status' => Project::STATUS_ACTIVE]);

        return view('admin.projects.create', compact('project', 'clients', 'advertisers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $this->ensureCanCreate($user);

        $validated = $this->validateProject($request, $user);

        $project = Project::create($validated);

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function edit(Request $request, Project $project): View
    {
        $user = $request->user();
        $this->ensureCanEdit($user, $project);

        [$clients, $advertisers] = $this->scopedClientsAndAdvertisers($user, $project);

        return view('admin.projects.edit', compact('project', 'clients', 'advertisers'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $user = $request->user();
        $this->ensureCanEdit($user, $project);

        $validated = $this->validateProject($request, $user, $project);

        $project->update($validated);

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * AJAX search endpoint for autocomplete dropdowns (Invoice form).
     * Role-aware: super/admin see all; AM sees own clients' projects; advertiser sees assigned.
     */
    public function search(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = trim($request->input('q', ''));
        $limit = min((int) $request->input('limit', 20), 50);

        $base = Project::query()
            ->with('client:id,business_name')
            ->whereIn('status', ['active', 'paused']);

        if ($user->isAccountManager()) {
            $base->whereHas('client', function ($q) use ($user) {
                $q->where('account_manager_id', $user->id);
            });
        } elseif ($user->isAdvertiser()) {
            $base->where('advertiser_id', $user->id);
        }

        $projects = $base
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($qq) use ($query) {
                    $qq->where('name', 'like', '%' . $query . '%')
                       ->orWhereHas('client', fn ($c) => $c->where('business_name', 'like', '%' . $query . '%'));
                });
            })
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'client_id', 'started_at', 'ended_at', 'project_value'])
            ->map(function ($p) {
                $clientName = $p->client?->business_name ?? '-';
                return [
                    'value' => (string) $p->id,
                    'text' => $clientName . ' - ' . $p->name,
                    'client_id' => (string) $p->client_id,
                    'client_name' => $clientName,
                    'started_at' => $p->started_at ? $p->started_at->format('Y-m-d') : null,
                    'anchor_day' => $p->started_at ? (int) $p->started_at->day : null,
                    'project_value' => $p->project_value !== null ? (float) $p->project_value : null,
                ];
            });

        return response()->json($projects);
    }

    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $user = $request->user();
        $this->ensureCanDelete($user);

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    // ===== HELPERS =====

    private function ensureAccessible(User $user): void
    {
        $allowed = [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN, User::ROLE_ACCOUNT_MANAGER, User::ROLE_ADVERTISER];
        if (!in_array($user->role, $allowed, true)) {
            throw new AuthorizationException('You do not have access to Projects.');
        }
    }

    private function ensureCanCreate(User $user): void
    {
        $allowed = [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN, User::ROLE_ACCOUNT_MANAGER];
        if (!in_array($user->role, $allowed, true)) {
            throw new AuthorizationException('You do not have permission to create projects.');
        }
    }

    private function ensureCanDelete(User $user): void
    {
        if ($user->role !== User::ROLE_SUPER_ADMIN) {
            throw new AuthorizationException('Only super admin can delete projects.');
        }
    }

    private function ensureCanEdit(User $user, Project $project): void
    {
        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            return;
        }

        if ($user->isAccountManager()) {
            $projectAmId = $project->client->account_manager_id ?? null;
            if ($projectAmId !== $user->id) {
                throw new AuthorizationException('You can only edit projects of clients you manage.');
            }
            return;
        }

        throw new AuthorizationException('You do not have permission to edit this project.');
    }

    private function ensureCanViewProject(User $user, Project $project): void
    {
        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            return;
        }

        if ($user->isAccountManager()) {
            if (($project->client->account_manager_id ?? null) !== $user->id) {
                throw new AuthorizationException('You can only view projects of clients you manage.');
            }
            return;
        }

        if ($user->isAdvertiser()) {
            if ($project->advertiser_id !== $user->id) {
                throw new AuthorizationException('You can only view projects assigned to you.');
            }
            return;
        }

        throw new AuthorizationException('You do not have permission to view this project.');
    }

    private function scopedClientsAndAdvertisers(User $user, ?Project $project = null): array
    {
        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            $clients = Client::orderBy('business_name')->get(['id', 'business_name', 'account_manager_id']);
            $advertisers = User::byRole(User::ROLE_ADVERTISER)->active()->orderBy('name')->get(['id', 'name', 'parent_am_id']);
            return [$clients, $advertisers];
        }

        if ($user->isAccountManager()) {
            $clients = Client::where('account_manager_id', $user->id)->orderBy('business_name')->get(['id', 'business_name', 'account_manager_id']);
            $advertisers = User::where('parent_am_id', $user->id)->where('role', User::ROLE_ADVERTISER)->active()->orderBy('name')->get(['id', 'name', 'parent_am_id']);
            return [$clients, $advertisers];
        }

        return [collect(), collect()];
    }

    private function validateProject(Request $request, User $user, ?Project $project = null): array
    {
        \App\Services\UrlNormalizer::normalizeRequest($request, ['account_url']);

        if ($user->isAccountManager()) {
            $allowedClientIds = Client::where('account_manager_id', $user->id)->pluck('id')->all();
            $allowedAdvertiserIds = User::where('parent_am_id', $user->id)
                ->where('role', User::ROLE_ADVERTISER)
                ->pluck('id')->all();
        } else {
            $allowedClientIds = Client::pluck('id')->all();
            $allowedAdvertiserIds = User::where('role', User::ROLE_ADVERTISER)->pluck('id')->all();
        }

        $validated = $request->validate([
            'client_id' => ['required', 'integer', Rule::in($allowedClientIds)],
            'advertiser_id' => ['required', 'integer', Rule::in($allowedAdvertiserIds)],
            'name' => ['required', 'string', 'max:255'],
            'account_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', Rule::in(array_keys(Project::STATUSES))],
            'project_value' => ['nullable', 'numeric', 'min:0'],
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'notes' => ['nullable', 'string'],
        ], [
            'client_id.in' => 'You can only assign projects to clients you manage.',
            'advertiser_id.in' => 'The selected advertiser is not under your team.',
        ]);

        $client = Client::findOrFail($validated['client_id']);
        $advertiser = User::findOrFail($validated['advertiser_id']);

        // Client status active check applies only on CREATE (not update).
        // Existing projects can be edited even after client becomes inactive (e.g. wrap-up).
        $isUpdate = $project !== null;
        if (!$isUpdate && $client->status !== 'active') {
            throw ValidationException::withMessages([
                'client_id' => 'Project hanya bisa dibuat untuk client dengan status active. Status client saat ini: ' . ucfirst($client->status) . '.',
            ]);
        }

        if ($client->account_manager_id !== null && $advertiser->parent_am_id !== $client->account_manager_id) {
            throw ValidationException::withMessages([
                'advertiser_id' => 'Advertiser must be under the same Account Manager as this client. Please re-assign client AM first or pick a different advertiser.',
            ]);
        }

        return $validated;
    }
}
