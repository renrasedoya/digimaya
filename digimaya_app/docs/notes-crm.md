# Digimaya CRM — CRM Module Notes

> **Pair with**: `notes-general.md`
> **Scope**: Lead, Client, Lead Followup, Client Followup, CRM Overview
> **Audience access**: super_admin, admin (full); marketing (limited — see permissions)

---

## 1. PHILOSOPHY

CRM module manages the **lifecycle** of a business relationship from first contact to ongoing client. Two distinct entities with separate pipelines:

- **Lead**: Pre-sale. Captured from public contact form OR manually created. Qualification pipeline.
- **Client**: Post-sale. Promoted from Lead. Long-term lifecycle tracking.

**Key principle**: Lead pipeline ≠ Client lifecycle. Each has own status field. NO shared "stage" concept.

---

## 2. ROLE STAKEHOLDERS & ACCESS

| Role | Lead | Client | Followups |
|---|---|---|---|
| `super_admin` | Full | Full | Full |
| `admin` | Full | Full | Full |
| `marketing` | Read+create | Read only | Read only |
| `account_manager` | NO | Read own (where `account_manager_id = auth.id`) | Read own scope |
| `advertiser` | NO | NO | NO |

**Note**: AM access to clients is for Operations module (project management context), NOT for CRM management actions.

---

## 3. DATA MODEL

### `leads`
```
id, business_name, contact_name, contact_email, contact_phone,
source (mis. 'contact_form', 'manual', 'referral'),
status enum('new','contacted','screened','promoted','disqualified') default 'new',
interested_in enum('agency','academy','partnership','others') nullable,    -- Phase 14.8
interested_in_other varchar(255) nullable,                                  -- Phase 14.8 (freetext when interested_in='others')
notes (text, nullable),
utm_source, utm_medium, utm_campaign (nullable),
referrer (nullable),
created_by (FK users, nullable),
timestamps, deleted_at (SoftDeletes)
```

**Status flow**: `new → contacted → screened → promoted/disqualified`
**Promote**: Triggered manually via "Promote to Client" button on Lead detail. Status MUST be `screened` AND `interested_in` MUST be filled (enforced via `Lead::canPromote()`).
**Disqualified**: Terminal state, no further action.

### `clients`
```
id, business_name, contact_name, contact_email, contact_phone,
industry, lead_quality (nullable, mis. 'A','B','C'),
status enum('prospect','active','inactive','churned') default 'prospect',
interested_in enum('agency','academy','partnership','others') nullable,    -- Phase 14.8 (inherited from Lead on promote)
interested_in_other varchar(255) nullable,                                  -- Phase 14.8 (freetext when interested_in='others')
account_manager_id (FK users, nullable),
client_since (date, nullable — auto-set on first activation by ClientObserver),
client_until (date, nullable — manual when churned),
monthly_retainer (decimal:2, nullable),
acquisition_cost (decimal:2, nullable),
notes (text, nullable),
promoted_from_lead_id (FK leads, nullable),
created_by (FK users, nullable),
timestamps, deleted_at (SoftDeletes)
```

**Status flow**: `prospect → active → inactive → churned`
**Critical cast**: `'account_manager_id' => 'integer'` (Bug 4 family).

### `lead_followups`
```
id, lead_id (FK leads, cascadeOnDelete),
scheduled_at (datetime), completed_at (datetime, nullable),
outcome enum('positive','negative','no_response') (nullable until completed),
notes (text, nullable),
created_by (FK users),
timestamps
```

### `client_followups`
```
id, client_id (FK clients, cascadeOnDelete),
scheduled_at (datetime), completed_at (datetime, nullable),
outcome enum('positive','negative','no_response') (nullable until completed),
notes (text, nullable),
created_by (FK users),
timestamps
```

### `client_status_history`
```
id, client_id (FK clients, cascadeOnDelete),
status_from (string, nullable for first entry), status_to (string),
changed_at (timestamp), changed_by (FK users, nullable),
note (text, nullable),
timestamps
```

Auto-managed by `ClientObserver`. Records every status transition.

---

## 4. MODELS

### `Lead`
```php
class Lead extends Model {
    use HasFactory, SoftDeletes, LogsActivity;

    // STATUSES const
    // INTERESTED_IN_OPTIONS const (Phase 14.8): ['agency'=>'Agency', 'academy'=>'Academy', 'partnership'=>'Partnership', 'others'=>'Other']

    // Relations
    followups(): HasMany LeadFollowup
    promotedClient(): HasOne Client (via promoted_from_lead_id)
    creator(): BelongsTo User

    // Scopes
    scopeForMonth($year, $month) // filter by created_at
    scopeByStatus($status)

    // Accessors
    getInterestedInLabelAttribute(): string  // Phase 14.8 — returns freetext if 'others'+filled, else label from const

    // Helpers
    canPromote(): bool           // Phase 14.8 — status === 'screened' AND isReadyForPromotion()
    isReadyForPromotion(): bool  // Phase 14.8 — interested_in filled, plus interested_in_other filled if 'others'
    hasInterest(): bool          // Phase 14.8
    isPromoted(), isDisqualified(): bool
}
```

### `Client`
```php
class Client extends Model {
    use HasFactory, SoftDeletes, LogsActivity;

    // STATUSES const: prospect, active, inactive, churned
    // INTERESTED_IN_OPTIONS const (Phase 14.8): same as Lead

    protected $casts = [
        'account_manager_id' => 'integer',  // CRITICAL Bug 4 fix
        'client_since' => 'date',
        'client_until' => 'date',
        'monthly_retainer' => 'decimal:2',
        'acquisition_cost' => 'decimal:2',
    ];

    // Relations
    followups(): HasMany ClientFollowup
    statusHistory(): HasMany ClientStatusHistory
    accountManager(): BelongsTo User
    promotedFromLead(): BelongsTo Lead
    invoices(): HasMany Invoice
    incomes(): HasMany Income
    projects(): HasMany Project (Operations module)

    // Scopes
    scopeForMonth($year, $month)
    scopeByStatus($status)
    scopeActive() // shortcut for status='active'

    // Accessors
    getInterestedInLabelAttribute(): string  // Phase 14.8 — same pattern as Lead

    // Helpers
    hasInterest(): bool  // Phase 14.8

    // Static
    Client::search($query): JSON for autocomplete (used in invoice/income forms, project picker)
}
```

### `LeadFollowup`, `ClientFollowup`
```php
// Both share same structure
class XxxFollowup extends Model {
    // OUTCOMES const: positive, negative, no_response

    // Relations: parent (lead/client), creator (user)
    // Helpers: isCompleted(), isPending()
}
```

---

## 5. CONTROLLERS

### `Admin\LeadController`
- Resource routes (index, create, store, show, edit, update, destroy)
- `promote($lead)` custom action — calls `LeadPromotionService::promote()`
- Filter: month + year (default current month) + status + source + **interest** (Phase 14.8) + search
- Sort: created_at DESC
- `normalizeInterestFields()` private helper (Phase 14.8) — defensive clear `interested_in_other` if `interested_in !== 'others'`

### `Admin\ClientController`
- Resource routes
- `search($request)` — JSON autocomplete endpoint (route name `admin.clients.search`)
- Filter: month + year + status + AM (admin/super only) + **interest** (Phase 14.8) + search
- Sort: created_at DESC (alphabetical for `search()` only)
- `normalizeInterestFields()` private helper (Phase 14.8)

### `Admin\LeadFollowupController`, `Admin\ClientFollowupController`
**Inline pattern (similar to ProjectReport)**:
- 4 actions: store, update, destroy, complete
- NO dedicated index/create/edit pages
- All managed inline at parent show page

### `Admin\CrmOverviewController`
- Dashboard at `/admin/crm`
- KPIs: lifecycle metrics (4 cards including Prospects Action Card — Phase 14.8.1), this-month flow, trend chart, recent activity
- Prospects breakdown (Phase 14.8.1): `$totalProspects`, `$freshProspects`, `$agedProspects`, `$agedProspectsNeedFu`

---

## 6. ROUTES

```php
// Lead routes
GET     /admin/leads                       → leads.index
GET     /admin/leads/create                → leads.create
POST    /admin/leads                       → leads.store
GET     /admin/leads/{lead}                → leads.show
GET     /admin/leads/{lead}/edit           → leads.edit
PUT     /admin/leads/{lead}                → leads.update
DELETE  /admin/leads/{lead}                → leads.destroy
POST    /admin/leads/{lead}/promote        → leads.promote (custom action)

// Lead Followup (inline, 4 routes)
POST    /admin/leads/{lead}/followups                  → leads.followups.store
PUT     /admin/lead-followups/{followup}               → lead-followups.update
DELETE  /admin/lead-followups/{followup}               → lead-followups.destroy
POST    /admin/lead-followups/{followup}/complete      → lead-followups.complete

// Client routes (resource)
GET     /admin/clients                     → clients.index
GET     /admin/clients/create              → clients.create
POST    /admin/clients                     → clients.store
GET     /admin/clients/{client}            → clients.show
GET     /admin/clients/{client}/edit       → clients.edit
PUT     /admin/clients/{client}            → clients.update
DELETE  /admin/clients/{client}            → clients.destroy
GET     /admin/clients-search              → clients.search (JSON autocomplete)

// Client Followup (inline, 4 routes)
POST    /admin/clients/{client}/followups              → clients.followups.store
PUT     /admin/client-followups/{followup}             → client-followups.update
DELETE  /admin/client-followups/{followup}             → client-followups.destroy
POST    /admin/client-followups/{followup}/complete    → client-followups.complete

// CRM Overview
GET     /admin/crm                         → crm.overview
```

---

## 7. VIEWS STRUCTURE

```
resources/views/admin/
├── crm/
│   └── overview.blade.php          (CRM dashboard with KPIs + recent activity)
├── leads/
│   ├── index.blade.php             (filter + table + pagination)
│   ├── create.blade.php            (uses _form)
│   ├── edit.blade.php              (uses _form)
│   ├── show.blade.php              (left col info + right col inline followups + promote button)
│   └── _form.blade.php             (shared partial)
└── clients/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    ├── show.blade.php              (left col info + right col inline followups + status history)
    └── _form.blade.php
```

NO `lead-followups/` or `client-followups/` folders — all inline in parent show page.

---

## 8. KEY UI PATTERNS

### Lead Detail Page (`leads/show.blade.php`)
**Layout**: 2-column (1/3 info, 2/3 followups)

**Right column structure**:
- "+ Add Followup" inline expand form (Alpine collapse pattern)
- Pending Followups list (yellow card style, future scheduled)
- Completed Followups list (gray card style, past with outcome)
- Edit Followup Modal (single global, dispatch event)
- Complete Followup Modal (single global, dispatch event with outcome dropdown)

**Header actions**:
- Edit Lead button
- Delete Lead button
- "Promote to Client" button (visible only when `lead.canPromote()` — Phase 14.8: status=`screened` AND interest filled)

**Status banner (Phase 14.8 dual-state)**:
- Purple banner "Lead siap di-handover ke tim Sales" if `canPromote()`
- Amber warning banner "Interested In masih kosong" if status=`screened` but interest empty (with link to edit)

### Client Detail Page (`clients/show.blade.php`)
**Layout**: 2-column (1/3 info, 2/3 followups + status history)

**Right column**:
- Followup management (same pattern as Lead)
- Status History timeline (auto-populated by ClientObserver, read-only)

### Interested In Field Pattern (Phase 14.8)
Used in 4 admin forms (Lead create/edit + Client create/edit) AND public `/contact` form.

**Alpine local scope wrapper** (conditional freetext reveal):
```blade
<div x-data="{
        interest: '{{ old('interested_in', $model->interested_in ?? '') }}',
        otherText: '{{ old('interested_in_other', $model->interested_in_other ?? '') }}',
        init() {
            this.$watch('interest', (val) => {
                if (val !== 'others') this.otherText = '';
            });
        }
     }">
    <!-- main select with options from INTERESTED_IN_OPTIONS const -->
    <!-- freetext input with x-show="interest === 'others'" x-cloak x-transition -->
</div>
```

**English labels in admin** (Agency, Academy, Partnership, Other); **Indonesian labels in public form** with descriptive context (e.g., "Agency — Kelola Google Ads untuk bisnis Anda").

### Lead Promote Flow (Updated Phase 14.8)
1. Click "Promote to Client" button → button visible ONLY if `lead.canPromote()` (status=`screened` AND interest filled)
2. Confirm dialog → POST to `leads.promote` → `LeadPromotionService::promote($lead, [])`
3. Service:
   - Guard: throws `InvalidArgumentException` if `!canPromote()` (defense layer)
   - Create new Client with `status='prospect'`
   - Set `promoted_from_lead_id`
   - Copy fields: business_name, contact_*, industry (if set in lead), **interested_in + interested_in_other (Phase 14.8)**
   - Update Lead status to `promoted`
4. Redirect to new Client show page

### Client Status Update
- ALWAYS manual via Edit Client form
- Followup outcome (positive/negative/no_response) does NOT auto-update Client.status
- ClientObserver detects status change → log to client_status_history + auto-fill client_since on first activation

### Filter Pattern (Lead/Client Index)
```blade
<form method="GET" action="..." class="mb-6 flex flex-wrap gap-2">
    <select name="month">All Months + 1-12</select>
    <select name="year">last 4 years</select>
    <select name="status">All + per-status with count</select>
    <select name="source">...</select>           {{-- Lead only --}}
    <select name="account_manager_id">...</select>  {{-- Client only, admin/super --}}
    <select name="interest">All Interests + INTERESTED_IN_OPTIONS</select>  {{-- Phase 14.8, both --}}
    <input type="text" name="search" placeholder="Search business or contact name">
    <button>Apply</button>
    <a>Reset</a>
</form>
```

### Followup Card (Lead/Client Index — top of page)
**Purpose**: Top-of-page reminder card so marketing/admin tidak lupa follow-up. Card menampilkan compact summary (count per timeline) + expand untuk list detail.

**Lokasi**: Di atas main table card, setelah session success message, sebelum filter form.

**Visibility & Scope per role**:

| Page | Role | Scope ditampilkan | Title |
|---|---|---|---|
| Lead index | super_admin, admin | SEMUA FU dimana `lead.assigned_to IS NOT NULL` (oversight marketing team) | "Team Followups"|
| Lead index | marketing | FU dimana `lead.assigned_to = self` OR `created_by = self` | "My Followups" |
| Client index | super_admin, admin | SEMUA FU client (oversight) | "Team Followups" |
| Client index | AM, advertiser | — (tidak akses Client module) | — |

**Scope timeline (3 sections)**:
- **Overdue**: `scheduled_at < now() AND completed_at IS NULL` (red color)
- **Today**: `whereDate('scheduled_at', today()) AND completed_at IS NULL` (yellow color)
- **Upcoming**: `scheduled_at >= now() AND scheduled_at <= now()+3 days AND completed_at IS NULL` (blue color)

**Display rules**:
- Card hidden entirely kalau sum 3 timeline = 0 (`@if(sum > 0)`)
- No limit per section (scroll if many)
- Klik item → ke parent detail page (`admin.leads.show` / `admin.clients.show`)
- No action button (no Mark Complete / Reschedule di card — must go ke parent)

**Design lock (konsistensi 100% Lead vs Client)**:
- Toggle: `Show me ▼` / `Hide ▲` (caret unicode U+25BC + U+25B2)
- Compact label: plain text + color cue (no emoji)
- Section header: plain text + color cue (no emoji)
- Container: `bg-white shadow-sm sm:rounded-lg mb-4` (separate card)

**Controller logic pattern** (Lead role-aware):
```php
if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
    $followupBaseQuery = LeadFollowup::whereHas('lead', fn($q) => $q->whereNotNull('assigned_to'));
    $myFollowupsTitle = 'Team Followups';
} else {
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
```

**Eager load partial column — IMPORTANT**:
- Lead display: `business_name ?? contact_name ?? 'Lead removed'` (no `name` column in `leads` table)
- Client display: `business_name ?? 'Client removed'`

**Blade structure** (compact + expand via Alpine `x-data="{ expanded: false }"`):
```blade
@if($myFollowupsCount['overdue'] + $myFollowupsCount['today'] + $myFollowupsCount['upcoming'] > 0)
<div x-data="{ expanded: false }" class="bg-white shadow-sm sm:rounded-lg mb-4">
    <div class="p-4">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-4 flex-wrap text-sm">
                <span class="font-semibold text-gray-700">{{ $myFollowupsTitle }}</span>
                @if($myFollowupsCount['overdue'] > 0)<span class="text-red-700">{{ $myFollowupsCount['overdue'] }} overdue</span>@endif
                @if($myFollowupsCount['today'] > 0)<span class="text-yellow-700">{{ $myFollowupsCount['today'] }} today</span>@endif
                @if($myFollowupsCount['upcoming'] > 0)<span class="text-blue-700">{{ $myFollowupsCount['upcoming'] }} upcoming (3 days)</span>@endif
            </div>
            <button type="button" @click="expanded = !expanded" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                <span x-show="!expanded">Show me ▼</span>
                <span x-show="expanded" x-cloak>Hide ▲</span>
            </button>
        </div>
        <div x-show="expanded" x-cloak class="mt-4 space-y-4">
            {{-- 3 sections: Overdue (red), Today (yellow), Upcoming (blue) --}}
        </div>
    </div>
</div>
@endif
```

---

## 9. WORKFLOWS

### Public Lead Submission
1. Visitor → `/contact` page → fill form (incl. **required `interested_in` field**, Phase 14.8)
2. POST `/contact` → `PublicContactController::store()`
3. Validation via `StoreContactLeadRequest` (rate limit, honeypot, idempotency 30s dedup on email+phone, **Indonesian custom messages for interested_in**)
4. Lead created with `source='contact_form'`, UTM params captured, **interested_in + interested_in_other (if 'others')**
5. NewLeadNotification mail sent to all super_admin/admin/marketing users (includes Tertarik field)
6. Redirect to `/thank-you` page

### Manual Lead Creation (Admin)
1. Admin → `/admin/leads/create` → fill form (interested_in is **nullable** in admin, marketing can fill later)
2. POST → store with `source='manual'`, `created_by=auth.id`
3. Status defaults to `new`

### Lead Followup
1. From lead show page, click "+ Add Followup" → expand form
2. Fill: scheduled_at (datetime), notes
3. Submit → POST `lead-followups.store`
4. Followup appears in "Pending" section
5. When completed: click "Complete" → modal with outcome + notes
6. POST `lead-followups.complete` → set `completed_at` + outcome → moves to "Completed" section

### Lead Qualification → Promotion
1. Followup outcome positive + screening done → admin updates Lead status to `screened` AND fills `interested_in` (Phase 14.8 — required)
2. "Promote to Client" button now visible (only when `canPromote()` returns true)
3. Click → confirm → service creates Client (status=prospect, interest inherited from Lead) + updates Lead (status=promoted)
4. Client now in CRM separate from Lead pipeline

### Client Lifecycle
1. New Client starts at `status='prospect'`
2. Admin manually updates to `active` when paid retainer signed → ClientObserver auto-fills `client_since`
3. Subsequent transitions: `active → inactive → churned` (or back to active)
4. Each transition logged to `client_status_history` by ClientObserver

### Client Followup
- Same pattern as Lead Followup
- Outcome positive does NOT auto-promote/activate — sales must manually update Client status

---

## 10. CRM OVERVIEW DASHBOARD

**Path**: `/admin/crm`
**Access**: super_admin, admin

### Sections
1. **Row 1 — Lifecycle KPI Cards** (4 cards, responsive 1→2→4 cols):
   - Total Clients
   - Active Clients (with % of total)
   - MRR (Active) — sum of monthly_retainer for active clients
   - **Total Prospects** (Phase 14.8.1) — action card with yellow ring outline conditional

2. **Row 2 — This-Month Flow Metrics** (2 cards):
   - New Active This Month + delta vs last month
   - Lost This Month + delta vs last month

3. **Trend chart** — 12-month activations vs losses

4. **Recent Activity** — Latest 10 leads + client status changes

### Prospects Action Card (Phase 14.8.1)
**Logic** (Opsi D — ALL prospects with age breakdown):
- `$totalProspects` = ALL prospects, no time filter
- `$freshProspects` = prospects with `created_at >= now()->subDays(30)`
- `$agedProspects` = totalProspects - freshProspects
- `$agedProspectsNeedFu` = aged prospects WITHOUT pending followup → **urgency trigger**

**Display**:
- Main number: `$totalProspects` (yellow-700 color if `$agedProspectsNeedFu > 0`, else gray-900)
- Sub-text: `X in last 30d · Y older` (durational wording, self-explanatory)
- If `$agedProspectsNeedFu > 0`: append `· Z need FU` in yellow-700 bold
- Yellow ring outline ONLY when `$agedProspectsNeedFu > 0`

**Click target**: `/admin/clients?status=prospect` (entire card wrapped in `<a>` tag, hover:shadow-md transition)

**Use case**: Admin nudge — don't forget to FU prospects that were recently promoted by marketing role.

**Why "aged need FU" as urgency trigger (NOT total)**:
- Fresh prospects without FU is normal (just promoted, FU not yet scheduled)
- Aged prospects (>30d) without FU = truly forgotten zone, needs attention
- Future-proof: card will auto-flag urgency when prospects age without progress

---

## 11. KEY VALIDATIONS

### Lead create/update
- `business_name`: required, string, max 255
- `contact_email`: nullable, email
- `contact_phone`: nullable, string, valid phone format
- `status`: must be in STATUSES enum
- `source`: nullable string
- `interested_in` (Phase 14.8): nullable in admin, `Rule::in(array_keys(INTERESTED_IN_OPTIONS))`
- `interested_in_other` (Phase 14.8): nullable string max 255, REQUIRED if `interested_in='others'` (`Rule::requiredIf`)
- `assigned_to`: nullable, must reference user with `role='marketing'` AND `is_active=true`
  - Validation: `Rule::exists('users','id')->where('role', User::ROLE_MARKETING)->where('is_active', true)`
  - Frontend dropdown: `User::byRole(User::ROLE_MARKETING)->active()->orderBy('name')->get(['id', 'name'])`
  - **Hard lock** at both layers (defense in depth)

### Lead promote (Updated Phase 14.8)
- Lead status MUST be `screened`
- Lead `interested_in` MUST be filled (enforced by `Lead::canPromote()` → `LeadPromotionService` throws `InvalidArgumentException` if not)
- If `interested_in='others'`, then `interested_in_other` freetext MUST be filled
- Client `business_name` will inherit from Lead
- Client `interested_in` + `interested_in_other` will inherit from Lead (Phase 14.8)

### Client create/update
- `business_name`: required, unique (with SoftDeletes consideration)
- `status`: must be in STATUSES enum
- `interested_in` (Phase 14.8): nullable, same rules as Lead
- `interested_in_other` (Phase 14.8): same rules as Lead
- `account_manager_id`: nullable, must reference user with role=`account_manager` if set
- `monthly_retainer`, `acquisition_cost`: nullable, numeric, ≥ 0

### Followup create/update
- `scheduled_at`: required, date
- `notes`: nullable, string

### Followup complete
- `outcome`: required, must be in OUTCOMES enum
- `notes`: nullable, string

### Public Contact Form (StoreContactLeadRequest — Phase 14.8)
- `interested_in`: **REQUIRED** (different from admin which is nullable)
- `interested_in_other`: conditional required via `Rule::requiredIf` lambda
- Custom Indonesian messages: "Pilih salah satu layanan yang Anda minati.", "Sebutkan layanan yang Anda minati."
- `passedValidation()` method = defensive clear `interested_in_other` if not 'others' OR whitespace-only

---

## 12. EMAIL NOTIFICATIONS

### NewLeadNotification
- Mail class: `App\Mail\NewLeadNotification`
- Trigger: Public contact form submission (PublicContactController)
- Recipients: `User::whereIn('role', ['super_admin','admin','marketing'])->get()`
- Format: Plain text (template at `resources/views/emails/new-lead-text.blade.php`)
- Template includes (Phase 14.8): `Tertarik    : {{ $lead->interested_in_label ?? 'Belum ditentukan' }}`
- Wrapped in try-catch — email failure does NOT break form submission UX
- Idempotency: 30s dedup on email+phone (skip create + skip email if duplicate within window)

---

## 13. KNOWN GOTCHAS

### Client Search Sort Exception
`Client::search()` (used by autocomplete in invoice/income/project forms) returns `orderBy('business_name', 'asc')` — alphabetical for UX.

All other Client queries default `orderBy('created_at', 'desc')`.

### Active-Only Constraint (Project context)
When creating Project (Operations module), client status MUST be `active`. Backend rejects on store, allows on update for wrap-up scenarios.

### Status History Auto-Fill on First Activation
When Client first transitions to `status='active'`:
- ClientObserver `updating()` event auto-fills `client_since = now()` if currently null
- Skipped if `client_since` already set (preserves manually-edited dates)

### Lead Promotion Inheritance
Promotion copies these fields Lead → Client:
- business_name, contact_name, contact_email, contact_phone
- industry (if set on lead)
- notes (concatenated with promotion timestamp)
- **interested_in + interested_in_other** (Phase 14.8)

Does NOT copy: source, status (Client starts at prospect), UTM params.

### Soft-Delete + Unique Constraint Hazard
If adding unique constraint to Lead/Client (mis. unique business_name slug), MUST use `withTrashed()` in any query checking uniqueness or generating sequence. (See Bug 1 in general notes.)

### Marketing Role Limited Access
Marketing role can:
- Create + read leads (for cold outreach campaigns)
- Read clients (for visibility, not edit)
- Cannot access Operations or Finance modules
- Cannot promote leads (admin-only action)

### Role Assignment Constraint (Lead vs Client)
**Hard locked at frontend dropdown + backend validation**:
- `Lead.assigned_to` accepts ONLY `role='marketing'` + `is_active=true`
- `Client.account_manager_id` accepts ONLY `role='account_manager'` + `is_active=true`

**Business rule**: Super admin + admin tidak boleh di-assign ke Lead atau Client (they are operators, not handlers). System enforce ini di kedua layer (frontend dropdown filter + backend `Rule::exists()` with role check).

Pre-existing data integrity verified clean — 0 Lead/Client assigned to wrong role.

### Followup Card Display Eager Load
**Pitfall**: Lead model TIDAK punya kolom `name`. Eager load `with('lead:id,name')` akan crash dengan SQL error `Column not found: 1054 Unknown column 'name'`.

**Correct pattern**:
```php
->with('lead:id,business_name,contact_name')  // Lead
->with('client:id,business_name')             // Client
```

**Display logic** (fallback chain):
- Lead: `$fu->lead->business_name ?? $fu->lead->contact_name ?? 'Lead removed'`
- Client: `$fu->client->business_name ?? 'Client removed'`

### Followup Scope `today()` Does NOT Exclude Completed
**Important**: `LeadFollowup::today()` dan `ClientFollowup::today()` cuma filter `whereDate('scheduled_at', today())` — TIDAK exclude `completed_at IS NOT NULL`.

Unlike `scopeOverdue()` and `scopeUpcoming()` yang chain `whereNull('completed_at')` automatically.

**Workaround**: Chain manual saat pakai today():
```php
LeadFollowup::today()->whereNull('completed_at')->get();
```

Kalau lupa, FU yang sudah completed hari ini masih muncul di "today" count.

### Interested In Field Defensive Clear (Phase 14.8)
**Pattern**: `interested_in` is enum + `interested_in_other` is freetext (only relevant when `interested_in='others'`).

**Defensive normalizer** required di 2 layer:
- **Admin Controller** (`LeadController`, `ClientController`): private method `normalizeInterestFields()` called in `store()` + `update()` — clears `interested_in_other` if `interested_in !== 'others'`
- **Public FormRequest** (`StoreContactLeadRequest`): `passedValidation()` method does same defensive clear

**Why both layers**: Even if frontend Alpine `x-model` watcher clears `otherText`, hidden form fields or direct API submission can bypass. Always defensive at backend.

**Display accessor** (`Lead::getInterestedInLabelAttribute()`):
- Returns freetext if `interested_in='others'` AND `interested_in_other` filled
- Otherwise returns label from `INTERESTED_IN_OPTIONS` const
- Used in: show pages, email notification, activity log

### Promote Requires Interest (Phase 14.8 — Defense in Depth)
**4 enforcement layers** for `Lead → Client` promotion:
1. **Model** (`Lead::canPromote()`): returns true only if `status === 'screened'` AND `isReadyForPromotion()` (interest filled, freetext filled if 'others')
2. **Service** (`LeadPromotionService::promote()`): throws `InvalidArgumentException` with specific message if `!canPromote()`
3. **Controller** (`LeadController::promote()`): catches exception → flash error → redirect back
4. **View** (`leads/show.blade.php`): button hidden if `!canPromote()`, plus amber warning banner with link to edit

**Banner UX**: When status='screened' but interest empty → amber banner: "Status sudah Screened, tapi field Interested In masih kosong. Edit Lead untuk isi minat layanan."

**Inheritance on promote**: `LeadPromotionService::mapLeadToClient()` copies `interested_in` + `interested_in_other` from Lead → Client.

---

## CLIENT: Status Lost + AM Rule + Month-Filter Fix (June 3, 2026)

**Status**: COMPLETE, tested end-to-end.

### 1. Bug fix — "month filter returns zero"
Client adalah entitas ONGOING, bukan record per-bulan. Filter bulan/tahun (`forMonth` di `created_at`) salah konsep → client created Mei hilang saat filter Juni. **Filter bulan/tahun DIBUANG** dari ClientController index (query + countQuery + $year/$month + compact) dan dari view (dropdown month/year dihapus). Index sekarang tampilkan semua client, filter via status/AM/interest/search. statusCounts = kondisi terkini (no month filter).

### 2. Status baru: `lost`
Prospect yang gagal closing. TERPISAH dari `churned` (churned = client pernah-active yang berhenti; jaga integritas churn rate vs conversion rate).
- **Enum DB**: ditambah via migration `ALTER TABLE clients MODIFY COLUMN status ENUM(...,'lost')` (raw, hindari doctrine/dbal di shared hosting). PENTING: nambah status di PHP saja TIDAK cukup — enum DB wajib diupdate, kalau tidak → "Data truncated for column status".
- **Transisi** (STATUS_TRANSITIONS): `prospect → [active, lost]` (lost GANTIKAN inactive di jalur prospect), `lost → [prospect]` (re-engage balik ke awal pipeline). active/inactive/churned tidak berubah.
- **Create** (admin-only, route `role:super_admin,admin`): cuma `prospect`/`active`. inactive/lost/churned bukan initial state. (`canTransitionTo(null,...)` + `getAllowedTargetsFrom(null)` = ['prospect','active'].)
- **Badge**: `bg-orange-100 text-orange-800` (index baris ~148 + show baris ~8). Filter dropdown + statusCounts + label array di validasi: semua tambah lost.

### 3. Auto-close followup saat jadi lost
Di `ClientObserver::updated()` (BUKAN controller — biar berlaku di semua jalur ubah status). Saat `status === 'lost'` (setelah `wasChanged('status')` guard), semua followup pending client di-set `completed_at=now()`, `outcome='closed_lost'` → hilang dari Followup Card.

### 4. Guard: cegah tambah FU ke client lost (defense in depth)
- **Backend**: `ClientFollowupController::store()` — tolak + redirect kalau `$client->status === 'lost'`.
- **UI**: show.blade form "Add Followup" dibungkus `@if($client->status === 'lost')` → tampilkan pesan amber "re-engage to Prospect", `@else` form normal `@endif`.

### 5. Account Manager rule (status-dependent)
Field AM editable HANYA saat `active`. Logika tunggal di `ClientController::resolveAccountManager($validated, $client)`, dipanggil di store (client=null) + update (client=existing):
- **active** → pakai input form
- **inactive/churned** → PRESERVE AM lama dari DB (`$client->account_manager_id`) — penting karena `<select disabled>` tidak ter-submit, jadi JANGAN andalkan request
- **prospect/lost** → force NULL
- View (create+edit): `:disabled="clientStatus !== 'active'"` (semua non-active read-only). Validasi closure AM-vs-status DIHAPUS (redundant; resolveAccountManager jadi single source of truth).

### TEMUAN PENTING — client_status_history sudah aktif
Tabel `client_status_history` (singular) + `ClientObserver` (created+updated events) sudah jalan sejak 3 Mei 2026. Merekam tiap transisi status (`status_from`, `status_to`, `changed_at`, `changed_by`). Per 2 Jun: 155 baris. Kolom `stage_from`/`stage_to` ADA tapi belum dipakai. Ini FONDASI untuk analytics historis.

### TODO — Lapisan 3: Analytics historis per bulan
Dari `client_status_history`: closing rate, retention/churn, growth per status per bulan (untuk nilai kualitas leads, tim closing, tim retention). Chart "Activation vs Lost" yang ada SEKARANG pakai proxy kasar (data numpuk di Mei, tidak akurat) — perlu dibetulin pakai data transisi beneran. Catatan: `inactive` historis (sebelum Juni) campuran prospect-gagal + churn, perlu dipilah manual; dari Juni ke depan `lost` bikin bersih.