<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientFollowup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{
    private const STATUSES = ['prospect', 'active', 'inactive', 'churned', 'lost'];

    public function index(Request $request): View
    {
        $query = Client::query();


        // Month filter removed: Client is an ongoing entity, not a per-month record.
        // It must appear regardless of which month created_at falls in.

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('account_manager_id')) {
            $query->where('account_manager_id', $request->account_manager_id);
        }

        
        if ($request->filled('interest')) {
            $query->where('interested_in', $request->interest);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        $clients = $query->with('accountManager:id,name')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Status counts respect month filter
        $countQuery = Client::query();
        // Counts reflect current state across all clients (no month filter).
        $statusCounts = [
            'total'    => (clone $countQuery)->count(),
            'prospect' => (clone $countQuery)->where('status', 'prospect')->count(),
            'active'   => (clone $countQuery)->where('status', 'active')->count(),
            'inactive' => (clone $countQuery)->where('status', 'inactive')->count(),
            'churned'  => (clone $countQuery)->where('status', 'churned')->count(),
            'lost'     => (clone $countQuery)->where('status', 'lost')->count(),
        ];

        $accountManagers = \App\Models\User::byRole(\App\Models\User::ROLE_ACCOUNT_MANAGER)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        // ============ Followup Card data ============
        $followupBaseQuery = ClientFollowup::query()->whereNull('completed_at')->has('client');

        $myFollowups = [
            'overdue'  => (clone $followupBaseQuery)->overdue()->with('client:id,business_name')->orderBy('scheduled_at')->get(),
            'today'    => (clone $followupBaseQuery)->today()->whereNull('completed_at')->with('client:id,business_name')->orderBy('scheduled_at')->get(),
            'upcoming' => (clone $followupBaseQuery)->upcoming()->where('scheduled_at', '<=', now()->addDays(3))->with('client:id,business_name')->orderBy('scheduled_at')->get(),
        ];

        $myFollowupsCount = [
            'overdue'  => $myFollowups['overdue']->count(),
            'today'    => $myFollowups['today']->count(),
            'upcoming' => $myFollowups['upcoming']->count(),
        ];

        // Super admin + admin both see all (oversight) — title same for both
        $myFollowupsTitle = 'Team Followups';

        return view('admin.clients.index', compact('clients', 'statusCounts', 'accountManagers', 'myFollowups', 'myFollowupsCount', 'myFollowupsTitle'));
    }

    /**
     * Show single client detail page (Phase 12.1).
     * Eager-load creator relationship for header/sidebar display.
     */
    public function show(Client $client): View
    {
        $client->load(['creator', 'accountManager']);

        return view('admin.clients.show', compact('client'));
    }

    public function create(): View
    {
        $accountManagers = \App\Models\User::byRole(\App\Models\User::ROLE_ACCOUNT_MANAGER)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.clients.create', compact('accountManagers'));
    }

    public function store(Request $request): RedirectResponse
    {
        \App\Services\UrlNormalizer::normalizeRequest($request, ['website_url']);
        $validated = $request->validate($this->validationRules($request));

        $validated = $this->normalizeInterestFields($validated);

        $validated['created_by'] = $request->user()->id;

        $validated['account_manager_id'] = $this->resolveAccountManager($validated, null);
        Client::create($validated);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function edit(Client $client): View
    {
        $accountManagers = \App\Models\User::byRole(\App\Models\User::ROLE_ACCOUNT_MANAGER)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.clients.edit', compact('client', 'accountManagers'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        \App\Services\UrlNormalizer::normalizeRequest($request, ['website_url']);
        $validated = $request->validate($this->validationRules($request, $client->id));

        $validated = $this->normalizeInterestFields($validated);

        $validated['account_manager_id'] = $this->resolveAccountManager($validated, $client);
        $client->update($validated);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    /**
     * AJAX search endpoint for autocomplete dropdowns.
     */
    public function search(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = trim($request->input('q', ''));
        $limit = min((int) $request->input('limit', 20), 50);

        $base = Client::query();

        // Role-aware scoping (defense in depth):
        // - super_admin/admin: see all clients
        // - account_manager: only clients assigned to them
        // - advertiser: only clients of projects assigned to them
        if ($user->isAccountManager()) {
            $base->where('account_manager_id', $user->id);
        } elseif ($user->isAdvertiser()) {
            $base->whereHas('projects', function ($q) use ($user) {
                $q->where('advertiser_id', $user->id);
            });
        }

        $clients = $base
            ->when($query !== '', function ($q) use ($query) {
                $q->where('business_name', 'like', '%' . $query . '%');
            })
            ->orderBy('business_name')
            ->limit($limit)
            ->get(['id', 'business_name', 'industry'])
            ->map(fn ($c) => ['value' => (string) $c->id, 'text' => $c->business_name, 'industry' => $c->industry]);

        return response()->json($clients);
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    /**
     * Normalize interest fields: clear interested_in_other if interested_in != 'others'.
     * Prevents stale freetext when user switches between options.
     */
    private function normalizeInterestFields(array $validated): array
    {
        $interestedIn = $validated['interested_in'] ?? null;

        if ($interestedIn !== 'others') {
            $validated['interested_in_other'] = null;
        }

        // Also clear interested_in_other if it's empty string (treat as null)
        if (isset($validated['interested_in_other']) && trim((string) $validated['interested_in_other']) === '') {
            $validated['interested_in_other'] = null;
        }

        return $validated;
    }

    private function resolveAccountManager(array $validated, ?\App\Models\Client $client = null): ?int
    {
        $status = $validated['status'] ?? null;
        if ($status === 'active') {
            return $validated['account_manager_id'] ?? null;
        }
        if (in_array($status, ['inactive', 'churned'], true)) {
            return $client?->account_manager_id;
        }
        return null;
    }

    private function validationRules(\Illuminate\Http\Request $request, ?int $clientId = null): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'status' => [
                'required',
                Rule::in(self::STATUSES),
                function ($attribute, $value, $fail) use ($clientId) {
                    $currentStatus = $clientId
                        ? \App\Models\Client::find($clientId)?->status
                        : null;
                    if (!\App\Models\Client::canTransitionTo($currentStatus, $value)) {
                        $labels = ['prospect' => 'Prospect', 'active' => 'Active', 'inactive' => 'Inactive', 'churned' => 'Churned', 'lost' => 'Lost'];
                        $from = $labels[$currentStatus] ?? '(baru)';
                        $to = $labels[$value] ?? $value;
                        $fail("Transisi status dari {$from} ke {$to} tidak diperbolehkan.");
                    }
                },
            ],
            'account_manager_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('role', \App\Models\User::ROLE_ACCOUNT_MANAGER)->where('is_active', true)),
                // AM-vs-status enforced in resolveAccountManager() (single source of truth):
                // active uses input, inactive/churned preserve, prospect/lost force null.
            ],
            'client_since' => ['nullable', 'date'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'monthly_retainer' => ['nullable', 'numeric', 'min:0'],
            'acquisition_cost' => ['nullable', 'numeric', 'min:0'],
            'source' => ['nullable', 'string', 'max:100'],
            'interested_in' => ['nullable', Rule::in(array_keys(Client::INTERESTED_IN_OPTIONS))],
            'interested_in_other' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->input('interested_in') === 'others'),
            ],
            'notes' => ['nullable', 'string'],
        ];
    }
}