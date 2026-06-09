<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\User;
use App\Services\LeadPromotionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::query()->with(['assignedUser', 'creator']);

        // Filter by month + year (default: current month)
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        if ($month >= 1 && $month <= 12) {
            $query->forMonth($year, $month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('interest')) {
            $query->where('interested_in', $request->interest);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('contact_name', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Status counts respect month filter (kalau bulan dipilih, count cuma di bulan itu)
        $countQuery = Lead::query();
        if ($month >= 1 && $month <= 12) {
            $countQuery->forMonth($year, $month);
        }
        $statusCounts = ['total' => (clone $countQuery)->count()];
        foreach (array_keys(Lead::STATUSES) as $status) {
            $statusCounts[$status] = (clone $countQuery)->where('status', $status)->count();
        }

        // ============ Followup Card data ============
        $user = $request->user();

        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            // Super admin + Admin: monitor all marketing followups
            $followupBaseQuery = LeadFollowup::whereHas('lead', function ($q) {
                $q->whereNotNull('assigned_to');
            });
            $myFollowupsTitle = 'Team Followups';
        } else {
            // Marketing: personal — lead assigned to self OR FU created by self
            $followupBaseQuery = LeadFollowup::where(function ($q) use ($user) {
                $q->whereHas('lead', fn($q2) => $q2->where('assigned_to', $user->id))
                  ->orWhere('created_by', $user->id);
            });
            $myFollowupsTitle = 'My Followups';
        }

        $followupBaseQuery->whereNull('completed_at');

        $myFollowups = [
            'overdue'  => (clone $followupBaseQuery)->overdue()->with('lead:id,business_name,contact_name')->orderBy('scheduled_at')->get(),
            'today'    => (clone $followupBaseQuery)->today()->whereNull('completed_at')->with('lead:id,business_name,contact_name')->orderBy('scheduled_at')->get(),
            'upcoming' => (clone $followupBaseQuery)->upcoming()->where('scheduled_at', '<=', now()->addDays(3))->with('lead:id,business_name,contact_name')->orderBy('scheduled_at')->get(),
        ];

        $myFollowupsCount = [
            'overdue'  => $myFollowups['overdue']->count(),
            'today'    => $myFollowups['today']->count(),
            'upcoming' => $myFollowups['upcoming']->count(),
        ];

        return view('admin.leads.index', compact('leads', 'statusCounts', 'year', 'month', 'myFollowups', 'myFollowupsCount', 'myFollowupsTitle'));
    }

    public function show(Lead $lead): View
    {
        $lead->load(['assignedUser', 'creator', 'followups.creator', 'promotedClient']);

        return view('admin.leads.show', compact('lead'));
    }

    public function create(): View
    {
        $assignableUsers = User::byRole(User::ROLE_MARKETING)->active()->orderBy('name')->get(['id', 'name']);

        return view('admin.leads.create', compact('assignableUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        \App\Services\UrlNormalizer::normalizeRequest($request, ['website_url']);
        $validated = $request->validate($this->validationRules());

        $validated = $this->normalizeInterestFields($validated);

        $validated['created_by'] = $request->user()->id;

        // Manual entry default — kalau marketing input dari admin tanpa specify source,
        // fallback ke 'manual'. Kalau form contact public submit, source di-set di public controller.
        if (empty($validated['source'])) {
            $validated['source'] = 'manual';
        }

        $lead = Lead::create($validated);

        return redirect()
            ->route('admin.leads.show', $lead)
            ->with('success', 'Lead berhasil ditambahkan.');
    }

    public function edit(Lead $lead): View
    {
        $assignableUsers = User::byRole(User::ROLE_MARKETING)->active()->orderBy('name')->get(['id', 'name']);

        return view('admin.leads.edit', compact('lead', 'assignableUsers'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        \App\Services\UrlNormalizer::normalizeRequest($request, ['website_url']);
        $validated = $request->validate($this->validationRules($lead->id));

        $validated = $this->normalizeInterestFields($validated);

        $lead->update($validated);

        return redirect()
            ->route('admin.leads.show', $lead)
            ->with('success', 'Lead berhasil diperbarui.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()
            ->route('admin.leads.index')
            ->with('success', 'Lead berhasil dihapus.');
    }

    /**
     * Promote a screened Lead to a Client.
     * Manual handover from Marketing team to Sales team.
     */
    public function promote(Request $request, Lead $lead, LeadPromotionService $promotionService): RedirectResponse
    {
        $validated = $request->validate([
            'lead_quality'   => ['required', Rule::in(array_keys(Client::LEAD_QUALITIES))],
            'handover_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $client = $promotionService->promote($lead, $validated);
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->route('admin.leads.show', $lead)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.leads.show', $lead)
            ->with('success', "Lead berhasil di-handover ke tim Sales sebagai Client #{$client->id}.");
    }

    /**
     * AJAX search endpoint untuk autocomplete (e.g., promote-to-client picker, followup assignment).
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->input('q', ''));
        $limit = min((int) $request->input('limit', 20), 50);

        $leads = Lead::query()
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($inner) use ($query) {
                    $inner->where('contact_name', 'like', '%' . $query . '%')
                          ->orWhere('business_name', 'like', '%' . $query . '%')
                          ->orWhere('contact_email', 'like', '%' . $query . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'contact_name', 'business_name', 'contact_email', 'status'])
            ->map(fn ($l) => [
                'value'         => (string) $l->id,
                'text'          => $l->contact_name,
                'business_name' => $l->business_name,
                'email'         => $l->contact_email,
                'status'        => $l->status,
            ]);

        return response()->json($leads);
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

    /**
     * Validation rules untuk store/update.
     *
     * @param  int|null  $leadId  Untuk update — exclude current lead dari unique check kalau ada.
     */
    private function validationRules(?int $leadId = null): array
    {
        return [
            'contact_name'            => ['required', 'string', 'max:255'],
            'contact_email'           => ['nullable', 'email', 'max:255'],
            'contact_phone'           => ['nullable', 'string', 'max:30'],
            'business_name'           => ['nullable', 'string', 'max:255'],
            'website_url'             => ['nullable', 'url', 'max:255'],
            'monthly_ad_budget'       => ['nullable', Rule::in(array_keys(Lead::BUDGETS))],
            'message'                 => ['nullable', 'string'],

            // Source & UTM
            'source'                  => ['nullable', Rule::in(array_keys(Lead::SOURCES))],
            'utm_source'              => ['nullable', 'string', 'max:100'],
            'utm_medium'              => ['nullable', 'string', 'max:100'],
            'utm_campaign'            => ['nullable', 'string', 'max:100'],
            'referrer_url'            => ['nullable', 'url', 'max:500'],

            // Interest (split: enum + conditional freetext)
            'interested_in'           => ['nullable', Rule::in(array_keys(Lead::INTERESTED_IN_OPTIONS))],
            'interested_in_other'     => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => request('interested_in') === 'others'),
            ],

            // Workflow
            'status'                  => ['required', Rule::in(array_keys(Lead::STATUSES))],
            'assigned_to'             => [
                'nullable',
                'integer',
                \Illuminate\Validation\Rule::exists('users', 'id')
                    ->where('role', User::ROLE_MARKETING)
                    ->where('is_active', true),
            ],
            'disqualification_reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}