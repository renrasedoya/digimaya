# Digimaya CRM — Operations Module Notes

> **Pair with**: `notes-general.md`
> **Scope**: Operations module — Project + ProjectReport + Issue Categories + Operations Overview Dashboard
> **Audience access**: super_admin, admin, account_manager, advertiser

---

## 1. PHILOSOPHY (CRITICAL — READ FIRST)

**This module is a communication & accountability tool, NOT a project management tool.**

- ❌ NOT Asana / Monday / Jira replacement
- ❌ NOT a place for full briefs, audit, or strategy docs
- ❌ NOT a replacement for WhatsApp discussions
- ✅ IS a periodic reporting touchpoint
- ✅ IS an audit trail of issue + AM feedback
- ✅ IS a monitoring dashboard for team supervision

**Core principle**: Brief detail, full strategy, deep discussion → outside system (PDF + WhatsApp). System captures **what's happening + who said what + status**.

---

## 2. ROLE STAKEHOLDERS & ACCESS

| Role | Access | Scope |
|---|---|---|
| `super_admin` | Full | All projects, all reports, all clients |
| `admin` | Full operational | All projects, all reports, all clients |
| `marketing` | NO ACCESS | — |
| `account_manager` (AM) | Read+write own scope | Projects of clients where `account_manager_id = auth.id` |
| `advertiser` | Read+write own scope | Projects where `advertiser_id = auth.id` |

### Hierarchy Constraints
- **Hard**: `projects.advertiser_id` MUST belong to Advertiser whose `parent_am_id == clients.account_manager_id`
- **Hard**: `users.parent_am_id` (when role=advertiser) MUST point to user with role=`account_manager`
- **Validation**: Enforced at `ProjectController::validateProject()` on store + update

---

## 3. DATA MODEL

### Tables

#### `projects`
```
id, client_id (FK clients), advertiser_id (FK users), name, account_url (nullable),
status enum('active','paused','completed') default 'active',
started_at, ended_at, notes (textarea plain),
timestamps, deleted_at (SoftDeletes)
```

**FK behaviors**:
- `client_id` → cascadeOnDelete (project deleted when client deleted)
- `advertiser_id` → restrictOnDelete (cannot delete advertiser if has projects)

**Indexes**: composite on `(client_id, status)`, `(advertiser_id, status)`, `created_at`.

#### `project_reports`
```
id, project_id (FK projects, cascadeOnDelete),
submitted_by (FK users, restrictOnDelete),
period_start (date), period_end (date),
summary (text),
health enum('healthy','needs_attention','critical') default 'healthy',
issue_category_id (FK issue_categories, nullOnDelete, nullable),
issue_sub_category_id (FK issue_sub_categories, nullOnDelete, nullable),
status enum('open','in_progress','resolved') default 'open',
reviewed_by (FK users, nullOnDelete, nullable),
reviewed_at (timestamp, nullable),
am_feedback (text, nullable),
acknowledged_at (timestamp, nullable, indexed),
timestamps, deleted_at (SoftDeletes)
```

**Phase 14.6 — Acknowledgment Lifecycle**:
- `acknowledged_at` recorded when submitter (advertiser) acknowledges AM's review.
- 3-state report lifecycle: pending_review → pending_ack → acknowledged.
- See Section 11 (Acknowledgment Lifecycle) for full flow.

#### `issue_categories` (master data)
```
id, name, slug, description, display_order, is_active, timestamps
```
9 categories. Hierarchy: 1 category → many sub-categories.

#### `issue_sub_categories` (master data)
```
id, issue_category_id (FK), name, slug, description, display_order, is_active, timestamps
```
27 sub-categories total, distributed across 9 categories.

### Issue Categories Taxonomy (9 categories)
1. **Performance** (Conversion drop, CPA increase, ROAS decline, CTR decline, CPC spike, Lead quality declining)
2. **Tracking & Measurement** (Tracking issue, Tracking discrepancy)
3. **Budget & Bidding** (Budget over-spend, Budget under-spend)
4. **Creative & Asset** (Ad/Asset rejected, Creative fatigue, Asset performance low, Brand asset missing)
5. **Account & Policy** (Account suspended, Account restriction, Policy issue, Billing issue)
6. **Audience & Targeting** (Audience underperforming, Audience overlap, Targeting issue)
7. **Client-side** (Landing page issue, Client request strategy change)
8. **Strategy & Scaling** (Scaling opportunity, External context shift, Strategy review needed)
9. **Other** (Other - please specify in notes)

**Seeder**: `IssueCategorySeeder` (idempotent via `firstOrCreate`). Run via `php artisan db:seed --class=IssueCategorySeeder`.

---

## 4. MODELS

### `Project`
```php
namespace App\Models;

class Project extends Model {
    use HasFactory, SoftDeletes, LogsActivity;
    
    // STATUSES const: active, paused, completed
    // STATUS_LABELS map for display
    
    protected $casts = [
        'client_id' => 'integer',
        'advertiser_id' => 'integer',
        'started_at' => 'date',
        'ended_at' => 'date',
    ];
    
    // Relations
    client(): BelongsTo
    advertiser(): BelongsTo // User
    accountManager(): via client.accountManager (chain)
    reports(): HasMany ProjectReport (orderBy period_start desc)
    
    // Scopes
    scopeActive(), scopePaused(), scopeCompleted()
    scopeForAdvertiser($id), scopeForAccountManager($id), scopeForClient($id)
    
    // Helpers
    isActive(): bool
    isPaused(): bool
    isCompleted(): bool
    status_label accessor
}
```

### `ProjectReport`
```php
class ProjectReport extends Model {
    use HasFactory, SoftDeletes, LogsActivity;
    
    // HEALTHS const: healthy, needs_attention, critical
    // STATUSES const: open, in_progress, resolved
    
    protected $casts = [
        'project_id' => 'integer',
        'submitted_by' => 'integer',
        'issue_category_id' => 'integer',
        'issue_sub_category_id' => 'integer',
        'reviewed_by' => 'integer',
        'period_start' => 'date',
        'period_end' => 'date',
        'reviewed_at' => 'datetime',
        'acknowledged_at' => 'datetime',     // Phase 14.6
    ];
    
    // Relations
    project(): BelongsTo
    submitter(): BelongsTo // User who submitted
    issueCategory(): BelongsTo
    issueSubCategory(): BelongsTo
    reviewer(): BelongsTo // User who reviewed (AM)
    
    // Scopes
    scopeForProject($id), scopeForAdvertiser($id)
    scopeByStatus($status), scopeByHealth($health)
    scopeUnreviewed(), scopeReviewed()
    scopeAcknowledged()              // whereNotNull('acknowledged_at')
    scopePendingAcknowledgment()     // whereNotNull('reviewed_at') AND whereNull('acknowledged_at')
    
    // Helpers
    isReviewed(): bool                       // reviewed_at IS NOT NULL
    isResolved(): bool
    isHealthy(): bool
    isAcknowledged(): bool                   // acknowledged_at IS NOT NULL
    isPendingAcknowledgment(): bool          // reviewed_at NOT NULL AND acknowledged_at NULL
    canBeEditedBy(User $user): bool          // Returns false if resolved (advertiser locked out)
    
    // Accessors
    health_label, status_label, period_label (formatted "02 May - 09 May 2026")
}
```

### `IssueCategory`
```php
class IssueCategory extends Model {
    // Relations: subCategories (hasMany), activeSubCategories (filtered)
    // Scopes: active(), ordered() (by display_order)
}
```

### `IssueSubCategory`
```php
class IssueSubCategory extends Model {
    // Relations: issueCategory (belongsTo)
    // Scopes: active(), ordered()
}
```

---

## 5. CONTROLLERS

### `Admin\ProjectController`
**Methods**:
- `index()` — paginated list with filter (status, AM, advertiser, client search). Role-scoped query.
- `create()`, `store()` — create project. Hierarchy validation. Client must be `active` on CREATE.
- `show()` — detailed view + reports list (paginated 15) with filter (month/year/health/report_status).
- `edit()`, `update()` — edit project. Skip client.status check on UPDATE (allow wrap-up after client churn).
- `destroy()` — soft delete project.

**Access logic** (`ensureCanViewProject` private method):
- super_admin/admin: all
- account_manager: `project.client.account_manager_id === auth.id`
- advertiser: `project.advertiser_id === auth.id`
- marketing: rejected

**Validation**:
- `validateProject($request, $project = null)` — `$project` parameter signals UPDATE (skip active-client check)
- Hierarchy: `advertiser.parent_am_id === client.account_manager_id` (when client AM assigned)
- Client status active-only: ONLY on CREATE
- Returns validated array

### `Admin\ProjectReportController`
**Methods (5 total — inline pattern, no dedicated index/create/edit pages)**:
- `store($project)` — submit new report. Validation conditional: issue REQUIRED if `health != healthy`.
- `update($report)` — edit report. Use `canBeEditedBy()` check. NOT allowed to change status/reviewer.
- `destroy($report)` — soft delete report.
- `review($report)` — AM review action. Update status + am_feedback + reviewed_by + reviewed_at.
- `acknowledge($report)` — Advertiser (submitter) acknowledges AM's review. Sets `acknowledged_at = now()`. **Idempotent** (re-acknowledge no-op). **ONLY submitter** can acknowledge own report.

**Access logic**:
- `ensureCanViewProject()` — same as ProjectController
- `ensureCanSubmit()` — only Advertiser assigned to project + project must be `active`
- `ensureCanReview()` — only AM (project's AM) + super_admin/admin
- `ensureCanAcknowledge()` — ONLY `report.submitted_by === auth.id` AND `report.reviewed_at IS NOT NULL`
- `canDelete()` — admin always; AM if their managed project; advertiser only own report not yet resolved
- `canBeEditedBy()` (model method) — false if status=resolved, except super_admin/admin override

**Validation** (`validateReport`):
- `period_start`, `period_end` (after_or_equal:period_start), `summary`, `health`
- Issue category + sub-category: required when `health != healthy`
- Sub-category must belong to selected category (verified via `IssueSubCategory::where('issue_category_id', ...)`)

### `Admin\IssueCategoryController`
**Resource routes** except show. Index, create, store, edit, update, destroy.
- Smart sub-category sync: items with `id` update, no `id` create new, missing existing IDs marked `is_active=false` (preserve FK).
- Validation rules with `sub_categories.*` nested array.
- Used by `super_admin` only (master data).

### `Admin\OperationsController`
**Method**: `overview()` — Operations Overview Dashboard.

**Logic**:
- Build `$projectQuery` + `$reportQuery` scoped per role at start
- KPI metrics: `activeProjects`, `totalProjects`, `reportsThisMonth`, `unreviewedCount` (pending review), `pendingAckCount` (reviewed but not acknowledged), `criticalActive` (health=critical & status≠resolved)
- Review lifecycle counts (for tabs): `all`, `pending_review`, `pending_ack`, `completed` (= acknowledged)
- Filter logic:
  - `report_status` (open/in_progress/resolved)
  - `advertiser_id` (where `submitted_by = X`)
  - `account_manager_id` (super_admin + admin only — via `whereHas('project.client', fn => account_manager_id = X)`)
  - `review` (4-state: pending_review / pending_ack / completed / all — pakai tabs, BUKAN dropdown)
  - `search` (project name OR client business_name via whereHas)
  - `month` + `year`
- `$advertisers` list (filtered to those who have submitted reports in scope)
- `$accountManagers` list (active AMs; **empty collection** if user is not super_admin/admin)
- `$canReviewAny` flag (admin or AM) — controls visibility of Stale Projects widget
- `$staleProjects` (active projects with no report in last 7 days) — only computed for super_admin/admin/AM

---

## 6. ROUTES

All under prefix `admin/`, middleware `auth + role:super_admin,admin,account_manager,advertiser + prevent.duplicate.admin`.

```php
// Operations Overview Dashboard
GET    /admin/operations                  → operations.overview

// Projects (resource)
GET    /admin/projects                    → projects.index
GET    /admin/projects/create             → projects.create
POST   /admin/projects                    → projects.store
GET    /admin/projects/{project}          → projects.show
GET    /admin/projects/{project}/edit     → projects.edit
PUT    /admin/projects/{project}          → projects.update
DELETE /admin/projects/{project}          → projects.destroy

// Project Reports (5 routes only — inline management pattern)
POST   /admin/projects/{project}/reports             → projects.reports.store
PUT    /admin/project-reports/{report}               → project-reports.update
DELETE /admin/project-reports/{report}               → project-reports.destroy
POST   /admin/project-reports/{report}/review        → project-reports.review
POST   /admin/project-reports/{report}/acknowledge   → project-reports.acknowledge  // Phase 14.6

// Issue Categories (super_admin only — master data)
// Resource except show
```

**Note**: NO `index`, `create`, `edit` routes for project-reports. All inline at project show page (Followup pattern).

---

## 7. VIEWS STRUCTURE

```
resources/views/admin/
├── operations/
│   └── overview.blade.php          (Operations Overview Dashboard)
├── projects/
│   ├── index.blade.php             (paginated list with filters)
│   ├── create.blade.php            (uses _form partial)
│   ├── edit.blade.php              (uses _form partial)
│   ├── show.blade.php              (LARGE — left col info + right col inline reports + 2 modals)
│   └── _form.blade.php             (shared partial for create/edit, Tom Select AJAX for client)
└── issue-categories/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    └── _form.blade.php             (Alpine repeatable sub-categories)
```

**No `project-reports/` folder** — all inline in `projects/show.blade.php`.

---

## 8. KEY UI PATTERNS

### Client Picker (Tom Select AJAX)
**Used in**: Project create/edit form

```blade
<select id="client_id" name="client_id" x-model="clientId" @change="onClientChange()" required>
    <option value="">-- Search and select client --</option>
    @if($isEdit && $project->client)
        <option value="{{ $project->client->id }}" selected>{{ $project->client->business_name }}</option>
    @endif
</select>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new TomSelect('#client_id', {
        valueField: 'value', labelField: 'text', searchField: 'text',
        preload: false, maxItems: 1,
        load: function (query, callback) {
            if (!query.length) return callback();
            fetch("{{ route('admin.clients.search') }}?q=" + encodeURIComponent(query) + "&limit=20")
                .then(r => r.json()).then(data => callback(data)).catch(() => callback());
        }
    });
});
</script>
@endpush
```

**Backend**: `Admin\ClientController@search` returns JSON `[{value: id, text: business_name}, ...]`.

**UX rationale**: Show all accessible clients (no UI filter by status/AM). Backend rejects invalid choices on submit (active-only on CREATE, hierarchy on store/update).

### Project Reports — Inline Submit Form (Advertiser only)
Located in `projects/show.blade.php` right column, top.

```blade
<div x-data="{
    open: false,
    health: 'healthy',
    categoryId: '',
    subCategoryId: '',
    categorySubMap: @js($categorySubMap),
    get isIssueRequired() { return this.health !== 'healthy'; },
    get availableSubCategories() {
        if (!this.categoryId) return [];
        return this.categorySubMap[this.categoryId] || [];
    },
    onCategoryChange() { ... reset subCategoryId if not in current category ... }
}">
    <button @click="open = !open">+ Submit Report</button>
    <div x-show="open" x-cloak>
        <form method="POST" action="{{ route('admin.projects.reports.store', $project) }}">
            ... period_start, period_end, health, summary, conditional issue fields ...
        </form>
    </div>
</div>
```

**`$categorySubMap`** built in controller: `{[categoryId] => [{id, name}, ...]}`.

### Project Reports — Card Minimalis (Click Expand)
- Default: header only (period + health badge + status badge + submitter name)
- Click card → toggle expand → show summary + AM feedback + actions
- Alpine `x-data="{ expanded: false }"` per card

### Edit Report Modal
Single global modal with `x-on:load-edit-report.window` event.

```blade
<button @click="
    $dispatch('open-modal', 'edit-report');
    $dispatch('load-edit-report', {
        id: {{ $report->id }},
        period_start: '{{ $report->period_start?->format('Y-m-d') }}',
        period_end: '{{ $report->period_end?->format('Y-m-d') }}',
        summary: @js($report->summary),
        health: '{{ $report->health }}',
        category_id: '{{ $report->issue_category_id ?? '' }}',
        sub_category_id: '{{ $report->issue_sub_category_id ?? '' }}'
    });
">Edit</button>
```

**Critical fix for sub-category preselect** (Bug 5 in general notes):
```blade
x-init="$nextTick(() => {
    if (initialSubCategoryId) {
        const sel = document.querySelector('#edit_sub_category_id');
        if (sel) sel.value = initialSubCategoryId;
        subCategoryId = initialSubCategoryId;
    }
});"
```

### AM Review Modal
Similar pattern: dispatch `open-modal review-report` + `load-review` event with `{id, status, feedback}`.
Status dropdown (open/in_progress/resolved) + textarea feedback.
Submit sets `reviewed_by`, `reviewed_at`, status, `am_feedback`.

---

## 9. WORKFLOWS

### Submit Report (Advertiser)
1. Project must be `status=active`
2. Advertiser must be `project.advertiser_id === auth.id`
3. Click "+ Submit Report" → expand inline form
4. Fill: period_start, period_end, summary (required), health
5. If health ≠ healthy → fill issue category + sub-category (required)
6. Submit → POST to `projects.reports.store` → redirect to project show, success message

### Review Report (AM)
1. AM scope: `project.client.account_manager_id === auth.id` (or admin)
2. Click "Review" / "Update Review" on report card → modal opens
3. Set status (open / in_progress / resolved) + feedback (optional)
4. Save → POST to `project-reports.review` → updates report, sets `reviewed_by` + `reviewed_at`

### Edit Report
- Advertiser: own report, only if not resolved (locked after resolved)
- AM: any report in their scope, anytime
- Admin: any report, anytime
- Edit content fields only (period, summary, health, issue category) — status/reviewer untouchable from edit

### Delete Report
- Advertiser: own report, only if not resolved
- AM: any report in their scope
- Admin: any report
- Soft delete via `deleted_at`

### Project Status Lifecycle
- `active` (default) — advertiser can submit reports
- `paused` — reports cannot be submitted, existing reports remain accessible
- `completed` — reports cannot be submitted, project archived
- Edit project status: admin/AM can change anytime, no restriction

### Client Status Affecting Project
- Project CREATE: client must be `active`
- Project UPDATE: skip check (allow wrap-up after client churn)
- Reports submission: NOT affected by client status (depends on project status)

---

## 10. OPERATIONS OVERVIEW DASHBOARD

**Path**: `/admin/operations`  
**Access**: super_admin, admin, account_manager, advertiser  
**Phase reference**: 14.5 (initial), 14.6+ (acknowledgment), evolved through May 2026

### KPI Cards Layout

**Container**: `flex flex-wrap md:flex-nowrap` (NOT `lg:grid-cols-5` — that class not compiled in pre-compiled Tailwind).

**5 cards for super_admin, admin, AM**:
1. Active Projects (of total)
2. Reports This Month
3. **Pending Review** (yellow ring if >0) — "AM action needed"
4. **Pending Ack** (blue ring if >0) — "waiting advertiser"
5. Critical Active (red ring if >0)

**4 cards for advertiser** (Pending Review HIDDEN — not actionable for them):
1. Active Projects
2. Reports This Month
3. **Pending Ack** — "you need to acknowledge"
4. Critical Active

Visibility wrapped in `@if(!auth()->user()->isAdvertiser())` for the Pending Review card.

### Review Lifecycle Tabs (replaced Review dropdown)

Above the filter form. 4 tabs with count, single-row nav (no full-width border-b wrapper). Pattern consistent with logo-wall/faqs/case-studies index pages.

```
All Reviews (12)  |  Pending Review (4)  |  Pending Ack (3)  |  Acknowledged (1)
```

- Active state: `border-indigo-500 text-indigo-600`
- Inactive state: `border-transparent text-gray-500 hover:text-gray-700`
- Tabs visible for ALL roles
- Click → URL changes to `?review={key}` (or no param for "All Reviews")
- Filter form has hidden input `<input type="hidden" name="review">` to preserve tab state when other filters applied

### Filter Bar (below tabs)

Order (left to right):
- Month / Year (default current)
- Health (All / Healthy / Needs Attention / Critical)
- Status (Open / In Progress / Resolved)
- **Advertiser dropdown** — hidden for advertiser role
- **AM dropdown** ("All AMs" + list of active AMs) — visible ONLY for super_admin + admin (`@if($accountManagers->count() > 0)`)
- Search box (project name OR client business_name)
- Apply / Reset

### Recent Reports Table

Columns:
- Period | Project | Health | Status | **Advertiser** (hidden for advertiser role) | Review

Review column = 3-state badge:
- Yellow: "Pending Review"
- Blue: "Pending Ack" with submitter "reviewed by {name}"
- Green: "Acknowledged" with "by {name}"

Pagination: 15 per page (paginate `withQueryString` to preserve filter state).

### Stale Projects Widget

Below Recent Reports. Shows active projects with NO report in last 7 days, ordered by oldest first.

Query pattern:
```php
Project::where('status', 'active')
    ->whereDoesntHave('reports', fn($q) => $q->where('created_at', '>=', now()->subDays(7)))
    ->withMax('reports as last_report_at', 'created_at')
    ->orderByRaw('last_report_at IS NULL DESC, last_report_at ASC')
    ->get();
```

**Visibility**: super_admin + admin + AM. HIDDEN for advertiser (gated by `$canReviewAny` flag).

### Advertiser Differentiation Summary

| Element | super/admin/AM | advertiser |
|---|---|---|
| KPI cards | 5 cards | **4 cards** (Pending Review hidden) |
| Filter Advertiser dropdown | Visible | **Hidden** |
| Filter AM dropdown | Visible for super/admin only | **Hidden** |
| Table column Advertiser | Visible | **Hidden** |
| Review tabs | Visible | Visible (same) |
| Stale Projects widget | Visible | **Hidden** |

Conditional rendering pattern: `@if(!auth()->user()->isAdvertiser())` wraps the differentiated elements.

### Empty States
- Advertiser: "No reports submitted yet. Go to a project to submit your first report."
- AM: "No reports from projects you manage yet. Wait for advertisers to submit."
- Admin: "No reports submitted across the agency yet."

---

## 11. ACKNOWLEDGMENT LIFECYCLE (Phase 14.6)

**Purpose**: Close the feedback loop between Advertiser (submit) → AM (review) → Advertiser (acknowledge AM's feedback). Captures whether advertiser has actually SEEN and registered the AM's review.

### 3-State Report Lifecycle

| State | Condition | UI Color | Action Available |
|---|---|---|---|
| **Pending Review** | `reviewed_at IS NULL` | Yellow | AM clicks "Review" |
| **Pending Ack** | `reviewed_at NOT NULL AND acknowledged_at IS NULL` | Blue | Submitter clicks "Acknowledge" |
| **Acknowledged** | `acknowledged_at NOT NULL` | Green | Terminal state, no action |

### Acknowledge Endpoint

```
POST /admin/project-reports/{report}/acknowledge
Name: project-reports.acknowledge
```

**Validation** (in controller `acknowledge()` method):
1. `$report->submitted_by === auth()->id()` — ONLY submitter can acknowledge own report
2. `$report->reviewed_at IS NOT NULL` — Can't acknowledge before AM has reviewed
3. **Idempotent**: If `$report->acknowledged_at IS NOT NULL` → no-op (returns "Report already acknowledged" message)

On success: `$report->update(['acknowledged_at' => now()])` + flash success message.

**No undo**: Once acknowledged, no rollback. Forces user to be intentional (confirm dialog in UI).

### UI Pattern (in projects/show.blade.php report card)

3-state visibility:

**Pending Ack** (own report, advertiser viewing):
```blade
<div class="bg-yellow-50 border border-yellow-200 rounded p-3">
    <p class="text-sm text-yellow-800">AM has reviewed this report. Acknowledge to close the loop.</p>
    <div x-data="{ showConfirm: false }">
        <button @click="showConfirm = !showConfirm"
                class="border border-green-400 text-green-700 hover:bg-green-50 ...">
            Acknowledge
        </button>
        <div x-show="showConfirm" x-cloak>
            <p>Are you sure? This action cannot be undone.</p>
            <form action="{{ route('admin.project-reports.acknowledge', $report) }}" method="POST">
                @csrf
                <button type="submit">Yes, Acknowledge</button>
                <button @click="showConfirm = false">Cancel</button>
            </form>
        </div>
    </div>
</div>
```

**Acknowledged** (anyone viewing):
```blade
<div class="bg-green-50 border border-green-200 rounded p-3">
    Acknowledged on {{ $report->acknowledged_at->format('d M Y, H:i') }}
</div>
```

**Pending Ack** (AM/admin/super viewing, not submitter):
- Yellow badge "Waiting for advertiser acknowledgment"
- No button (cannot acknowledge on behalf of submitter)

---

## 12. NAV INTEGRATION

### Top Nav Operations Dropdown
Order in dropdown:
1. Overview → `/admin/operations`
2. Projects → `/admin/projects`

Active state detection: `routeIs('admin.projects.*', 'admin.operations.*')`

Visible to: super_admin, admin, account_manager, advertiser (NOT marketing).

---

## 13. CRITICAL VALIDATIONS

### `Project` create/update
- `client_id`: exists in clients
- `advertiser_id`: exists in users with role=advertiser
- Hierarchy: `advertiser.parent_am_id === client.account_manager_id` (only if client has AM)
- Client status: `active` only on CREATE (skip on UPDATE)

### `ProjectReport` create/update
- `period_end >= period_start`
- `summary`: required, string
- `health`: must be in HEALTHS enum
- Conditional issue: REQUIRED if `health != healthy`
- Sub-category must belong to selected category

### `ProjectReport` review (AM action)
- `status`: must be in STATUSES enum
- `am_feedback`: nullable string
- Auto-set: `reviewed_by = auth.id`, `reviewed_at = now()`

### `ProjectReport` acknowledge (Advertiser/Submitter action) — Phase 14.6
- Caller MUST be `report.submitted_by` (only submitter can ack own report)
- `report.reviewed_at` MUST NOT be null (cannot ack before reviewed)
- Idempotent: if `acknowledged_at` already set, return early (no-op)
- Auto-set: `acknowledged_at = now()`
- No input fields (no body params required)

---

## 14. KNOWN GOTCHAS

### Tom Select + Alpine Conflict
Tom Select replaces native `<select>`. Alpine `x-model` still binds to underlying select element. Native `change` event still triggers. This works correctly for client_id Alpine-driven advertiser filter.

### Sub-Category Cascade Pattern
When changing category in report form, sub-category resets if not valid for new category:
```js
onCategoryChange() {
    const allowedIds = this.availableSubCategories.map(s => s.id);
    if (this.subCategoryId && !allowedIds.includes(parseInt(this.subCategoryId))) {
        this.subCategoryId = '';
    }
}
```

### Report Pagination Cursor Name
Project show page uses `paginate(15, ['*'], 'reports_page')` — custom page param to avoid conflict with potential other pagination on same page.

### Issue Categorization with `is_active` Soft Toggle
Sub-categories that have been used in reports cannot be hard-deleted (FK restrictOnDelete via `nullOnDelete` on report side). Mark `is_active=false` instead. Form filters out inactive sub-categories from dropdown but historical reports retain valid reference.

### Hierarchy Constraint Enforcement
Backend ALWAYS validates `advertiser.parent_am_id === client.account_manager_id` (where client has AM). UI may filter dropdowns optimistically (Alpine), but backend is source of truth.

### Tailwind Palette Safety (Pre-compiled Build)
Tailwind di production di-compile via `tw-build` (cPanel Node app), NOT JIT runtime. Hanya class yang sudah ke-compile di `public/css/tailwind.css` yang berfungsi.

**Safe palette for Operations Overview UI** (verified compiled):
- Text: `text-red-700`, `text-yellow-700`, `text-blue-700`, `text-green-700`, `text-gray-{500,600,700,900}`, `text-indigo-{500,600,900}`
- Background: `bg-red-{50,100}`, `bg-yellow-{50,100}`, `bg-blue-{50,100}`, `bg-green-{50,100}`, `bg-gray-{100,800,700}`, `bg-white`
- Border: `border-red-{200,300,400}`, `border-yellow-{200,300}`, `border-blue-{200,300}`, `border-green-{200,300,400}`, `border-indigo-500`, `border-transparent`
- Ring: `ring-1`, `ring-yellow-300`, `ring-blue-300`, `ring-red-300`

**Avoid** (NOT compiled by default):
- `bg-yellow-600`, `bg-yellow-700` (use yellow-100 / text-yellow-700 instead)
- `lg:grid-cols-5`, `lg:grid-cols-6+` (use `flex flex-wrap md:flex-nowrap` for KPI cards row instead)
- Arbitrary values: `px-3.5`, `left-[-9999px]`, etc.

**Mitigation pattern**: ALWAYS `grep -rn "class-name"` di codebase before applying baru. Kalau tidak ada match (atau cuma di file Tailwind config), kemungkinan belum ke-compile. Pakai class yang sudah verified, atau rebuild dengan `tw-build`.

### Operations Overview Section Numbering Drift
After Phase 14.6 (Acknowledgment Lifecycle) added as Section 11, subsequent sections renumbered (was: 11 Nav, 12 Validations, 13 Gotchas → now: 12 Nav, 13 Validations, 14 Gotchas). Markdown number can drift; references within the doc should be by section TITLE not number.

### Filter AM Dropdown Empty State
`$accountManagers` is `collect()` (empty) for AM and advertiser roles. Frontend gate: `@if($accountManagers->count() > 0)` — dropdown hidden when empty. DRY pattern: backend decides who can filter by AM, not frontend role check.