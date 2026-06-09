# Digimaya CRM — Academy Module Notes

> **Pair with**: `notes-general.md`
> **Scope**: Members, Modules, Materials, Member Progress, Member Auth (separate guard), Tier System (Free/Paid)
> **Audience access**: super_admin, admin (manage); members (consume content via separate auth)
> **Phase reference**: Phase A-D MVP complete (10 May 2026), UI polish + public navbar (May 2026), Tier System Phase E complete (21 May 2026), Announcements Feed Phase F complete (22 May 2026)

---

## 1. PHILOSOPHY

Academy = **content delivery platform** for paid/invited members to consume Digimaya's educational content (Google Ads, marketing, etc.). NOT a public LMS.

**Key principles**:
- **Invite-only enrollment**: Admin creates member → email with setup token → member sets password. No self-registration.
- **Member ≠ Admin user**: `members` table is fully separate from `users` table. Separate auth guard (`member`). No role overlap.
- **YouTube-embedded video + Quill notes**: No native video upload. Content lives on YouTube (embed only), supplementary notes via Quill HTML editor.
- **Simple progress model**: Per-material binary completion flag (`completed_at` timestamp). No quiz, no certificate, no prerequisites.
- **Single token system**: Same `setup_token` field used for new enrollment, admin-triggered reset, AND forgot password flow.
- **Free trial via tier system** (Phase E): Module + Member both have a `tier` field (`free`/`paid`). Free members access free modules only. Paid members access everything. Upgrade page (`/academy/upgrade`) routes interested members to WhatsApp admin.
- **Activity feed for content discovery** (Phase F): Announcements page (`/academy/announcements`) aggregates newly published modules, materials, and blog articles in a unified feed sorted by date desc. Real-time DB polling (no separate `announcements` table). Helps members discover updates without manual checking.

---

## 2. ROLE STAKEHOLDERS & ACCESS

| Role | Admin Academy (CMS) | Member Auth | Member Learning |
|---|---|---|---|
| `super_admin` | Full | — | — |
| `admin` | Full | — | — |
| `marketing`, `account_manager`, `advertiser` | NO | — | — |
| **Member (Free tier)** | NO | Own login + setup + forgot password | Free modules only |
| **Member (Paid tier)** | NO | Own login + setup + forgot password | Free + Paid modules |

**Important**: Member is NOT a user `role` — it's a separate model with its own auth guard. Admin manages members via CMS, but admin cannot "log in as member" (separate guards). Tier is a property of Member (not a separate role).

---

## 3. SUB-MODULES OVERVIEW

| Sub-module | Purpose |
|---|---|
| **Members** | Admin manages member accounts (CRUD with tier dropdown, resend setup link, regenerate token, toggle active) |
| **Modules** | Admin manages learning modules (CRUD with tier dropdown, cover image, publish toggle) |
| **Materials** | Admin manages materials per module (CRUD, YouTube ID, Quill notes, display_order) |
| **Member Auth** | Separate auth system for members (login at `/login`, forgot password, setup password via token) |
| **Member Learning** | Member-facing dashboard + module browser + material viewer + progress tracking + tier gate |
| **Member Profile** | Member self-service (view info, change password) |
| **Upgrade Page** | Static landing for free members showing benefits + WA CTA (`/academy/upgrade`) |
| **Announcements** | Activity feed of newly published modules + materials + blog articles, paginated 15/page, sorted by date desc (`/academy/announcements`) |

---

## 4. DATA MODEL

### `members`
```
id, name, email (unique), password (nullable — null until setup),
is_active (boolean default true),
tier (enum 'free'|'paid' default 'free', indexed as idx_members_tier),
setup_token (string 64, nullable, indexed),
setup_token_expires_at (timestamp, nullable),
last_login_at (timestamp, nullable),
enrolled_by (FK users, nullOnDelete, nullable),
notes (text, nullable),
remember_token, timestamps, deleted_at (SoftDeletes)
```

**Indexes**:
- `setup_token` (for token lookup)
- `[is_active, deleted_at]` (active member queries)
- `tier` (for tier filtering queries — added Phase E)

**Password lifecycle**:
- On enroll: `password=null`, `setup_token` generated, member must set via email link
- After setup: `password` hashed, `setup_token` + `setup_token_expires_at` cleared
- On forgot password: NEW `setup_token` generated (single token system)

**Tier lifecycle**:
- On enroll: admin selects tier in create form (default `free`)
- Promote to paid: admin edits member → change tier to `paid` → save (manual flow only, no payment integration)
- Demote to free: admin edits member → change tier to `free` → save

### `modules`
```
id, title, slug (unique), description (text, nullable),
cover_image (string 500, nullable — file path OR external URL),
display_order (integer default 0),
is_published (boolean default false),
tier (enum 'free'|'paid' default 'free', indexed as idx_modules_tier),
timestamps
```

**Indexes**: 
- `[is_published, display_order]` (published list queries)
- `tier` (for tier-scoped queries — added Phase E)

**Cover image dual pattern** (matches LogoWallItem):
- If `cover_image` starts with `http` → external URL
- Else → file path under `storage/app/public/` (rendered via `asset('storage/' . $path)`)

**Tier semantics**:
- `free`: open to all logged-in members (default, safe choice for new modules)
- `paid`: only Paid tier members can access
- Tier is admin-set at module level (no per-material override — see Phase E decisions)

### `materials`
```
id, module_id (FK modules, cascadeOnDelete),
title, youtube_id (string 20),
notes (text, nullable — Quill HTML sanitized via Purifier),
display_order (integer default 0),
is_published (boolean default false),
timestamps
```

**Indexes**: `[module_id, display_order]`, `is_published`

**No SoftDeletes** — hard delete (different from most other modules).

**No tier**: Material inherits tier from its parent module. Gate logic checks module tier only.

### `member_progress`
```
id, member_id (FK members, cascadeOnDelete),
material_id (FK materials, cascadeOnDelete),
completed_at (timestamp default CURRENT_TIMESTAMP),
timestamps
```

**Unique constraint**: `[member_id, material_id]` (`uniq_member_material`) — one progress record per member per material.

**No update flow**: Record exists = completed. Record absent = not completed. To "un-mark", delete record (but current UI is one-way — see workflows section).

---

## 5. MODELS

### `Member` (extends `Authenticatable`)
```php
namespace App\Models;

class Member extends Authenticatable {
    use HasFactory, Notifiable, SoftDeletes;
    
    // Tier constants
    public const TIER_FREE = 'free';
    public const TIER_PAID = 'paid';
    public const TIERS = [self::TIER_FREE, self::TIER_PAID];
    
    protected $fillable = ['name', 'email', 'password', 'is_active', 'enrolled_by',
                            'notes', 'setup_token', 'setup_token_expires_at', 'last_login_at', 'tier'];
    
    protected $hidden = ['password', 'remember_token', 'setup_token'];
    
    protected $casts = [
        'is_active' => 'boolean',
        'enrolled_by' => 'integer',
        'last_login_at' => 'datetime',
        'setup_token_expires_at' => 'datetime',
        'password' => 'hashed',          // Laravel 10 auto-hash
        'tier' => 'string',
    ];
    
    // Relations
    enroller(): BelongsTo (User, via enrolled_by)
    progress(): HasMany MemberProgress
    completedMaterials(): BelongsToMany Material (via member_progress pivot)
    certificates(): HasMany Certificate
    certificateRequests(): HasMany CertificateRequest
    
    // Helpers
    hasCompletedMaterial(int $materialId): bool
    isSetupTokenValid(): bool  // token NOT null AND expires_at in future
    generateSetupToken(): string  // 64-char hex, 24h expiry, saves model
    clearSetupToken(): void  // null both fields, saves
    
    // Tier helpers (Phase E)
    isPaid(): bool        // tier === 'paid'
    isFree(): bool        // tier === 'free'
    canAccessModule(Module $module): bool   // CENTRAL GATE METHOD — free module open to all, paid module only for paid members
    
    // Scopes
    scopeActive() — where is_active = true
    scopeFree() — where tier = 'free'   (Phase E)
    scopePaid() — where tier = 'paid'   (Phase E)
}
```

**Gate logic (`canAccessModule`)**:
```php
public function canAccessModule(Module $module): bool
{
    return $module->isFree() || $this->isPaid();
}
```
Free member + Free module = ACCESS. Free member + Paid module = BLOCKED. Paid member = ACCESS to all.

### `Module`
```php
class Module extends Model {
    use HasFactory;
    
    // Tier constants (Phase E)
    public const TIER_FREE = 'free';
    public const TIER_PAID = 'paid';
    public const TIERS = [self::TIER_FREE, self::TIER_PAID];
    
    protected $fillable = ['title', 'slug', 'description', 'cover_image',
                            'display_order', 'is_published', 'tier'];
    
    protected $casts = ['display_order' => 'integer', 'is_published' => 'boolean', 'tier' => 'string'];
    
    // Route binding by slug (not id)
    public function getRouteKeyName() { return 'slug'; }
    
    // Auto-slug on save (if empty title-derived)
    // Static helper: uniqueSlug($title, $ignoreId) — handles duplicate by appending -2, -3...
    
    // Cover image helpers
    coverImageIsExternal(): bool       // starts_with($cover_image, 'http')
    getCoverImageUrlAttribute(): ?string  // returns absolute URL (external or storage path)
    
    // Tier helpers (Phase E)
    isPaid(): bool    // tier === 'paid'
    isFree(): bool    // tier === 'free'
    
    // Relations
    materials(): HasMany Material (ordered by display_order)
    publishedMaterials(): HasMany Material (where is_published=true, ordered)
    
    // Scopes
    scopePublished() — where is_published = true
    scopeOrdered() — orderBy display_order, then id
    scopeFree() — where tier = 'free'   (Phase E)
    scopePaid() — where tier = 'paid'   (Phase E)
}
```

### `Material`
```php
class Material extends Model {
    use HasFactory;
    
    protected $fillable = ['module_id', 'title', 'youtube_id', 'notes',
                            'display_order', 'is_published'];
    
    protected $casts = ['module_id' => 'integer', 'display_order' => 'integer',
                         'is_published' => 'boolean'];
    
    // Relations
    module(): BelongsTo
    progress(): HasMany MemberProgress
    
    // Scopes
    scopePublished()
    
    // Accessors
    embed_url      // "https://www.youtube.com/embed/{youtube_id}"
    thumbnail_url  // "https://img.youtube.com/vi/{youtube_id}/maxresdefault.jpg"
}
```

### `MemberProgress`
```php
class MemberProgress extends Model {
    use HasFactory;
    
    protected $table = 'member_progress';
    
    protected $fillable = ['member_id', 'material_id', 'completed_at'];
    
    protected $casts = ['member_id' => 'integer', 'material_id' => 'integer',
                         'completed_at' => 'datetime'];
    
    // Relations
    member(): BelongsTo
    material(): BelongsTo
}
```

---

## 6. CONTROLLERS

### Admin Side (`Admin\Academy\*`)

#### `Admin\Academy\MemberController` (Resource + 3 custom)
**Methods**:
- Resource: index, create, store, show, edit, update, destroy
- `resendSetup($member)` — regenerate token (if expired) + resend welcome email
- `regenerateToken($member)` — generate new token, return updated setup URL (admin manually share)
- `toggleActive($member)` — flip `is_active` flag
- `search(Request)` — AJAX autocomplete endpoint

**Store logic**:
1. Validate name, email (unique), is_active, notes, **tier (required, must be in `Member::TIERS`)**
2. Create member with `password=null` + tier from form
3. Call `generateSetupToken()` → returns token
4. Send `WelcomeMember` mailable to member's email (with setup URL)
5. Redirect to show page with success + display setup URL inline

**Update logic**: Same validation as store, including tier (allows tier change for upgrade/downgrade flow).

**Index filters** (Phase E):
- `status` (active/inactive)
- `tier` (free/paid) — validated against `Member::TIERS`
- `enrolled_by` (admin who enrolled)
- `month` (0-12, where 0 = All Months)
- `year` (current year - 3 through current year)
- `search` (name or email)

Date filter logic:
```php
if ($month > 0) {
    $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
} elseif ($request->filled('year')) {
    $query->whereYear('created_at', $year);
}
```

#### `Admin\Academy\ModuleController` (Resource)
Standard CRUD. Auto-slug on save via Module boot event. Cover image upload via `handleImage()` helper (file OR external URL pattern — matches LogoWallItem).

**Phase E additions**:
- Validation: `'tier' => ['required', Rule::in(Module::TIERS)]`
- Custom messages: `tier.required`, `tier.in` (in Bahasa Indonesia)
- Store + Update both pass `'tier' => $validated['tier']` to model

#### `Admin\Academy\MaterialController`
**Methods**: store, edit, update, destroy (NO index/create/show — managed inline at module show).

**Nested routes**: All under `modules/{module}/materials/*`.

**YouTube ID extraction**: Frontend JS extracts from full URL OR accepts 11-char ID directly.

**Notes sanitization**: `Purifier::clean($notes)` before save.

### Member Side (`Academy\*`)

#### `Academy\LearningController`
**Methods**:
- `dashboard()` — list modules with progress %, overall progress bar. Returns `View`.
- `showModule($module)` — module detail with materials list + per-material completion. Returns `View|RedirectResponse` (Phase E: redirects to upgrade if tier gate fails).
- `showMaterial($module, $material)` — material viewer (YouTube embed + Quill notes + Mark Complete button + Prev/Next nav). Returns `View|RedirectResponse` (Phase E).
- `toggleProgress($material)` — AJAX POST, creates/deletes MemberProgress record, returns JSON `{completed: bool}` or 403 on tier failure (Phase E).
- `upgrade()` — Phase E. Static landing page for upgrade info + WA CTA. Returns `View`. Accessible to all members (free + paid; paid members see "Already Paid" notice).
- `announcements(Request $request)` — Phase F. Activity feed of newly published modules + materials + blog articles. Merges 3 source collections, sorts by date desc, paginates 15/page via `LengthAwarePaginator`. Returns `View`. No tier gate (all members see all updates; tier badges shown on locked paid items).

**Access logic**:
- All require `auth:member` + `member.active` middleware
- Module/Material must be `is_published=true`
- **Phase E: Tier gate** — `showModule`, `showMaterial`, `toggleProgress` all call `$member->canAccessModule($module)`. If false, redirect (or 403 JSON for toggleProgress) to `academy.upgrade` route with flash warning "Module ini hanya untuk Paid member. Upgrade untuk akses."

**Route binding**: `{module:slug}` uses Module slug. `{material}` uses default id binding.

**Return type signatures** (Phase E):
- `showModule(Module $module): View|\Illuminate\Http\RedirectResponse`
- `showMaterial(Module $module, Material $material): View|\Illuminate\Http\RedirectResponse`
- `toggleProgress(...): JsonResponse` (unchanged)

#### `Academy\ProfileController`
**Methods**:
- `edit()` — show profile + change password form
- `updatePassword(Request)` — validate current_password + new password + confirmation, update

**Read-only fields** (admin-only changes): name, email, notes, **tier**. Member can only change password.

### Member Auth (`Auth\Member\*`)

#### `MemberLoginController`
- `create()` — show login form
- `store()` — authenticate via `MemberLoginRequest` (validates creds + is_active), update `last_login_at`, redirect intended `academy.dashboard`
- `destroy()` — logout, **redirect to `route('member.login')`** (Phase E fix: previously `redirect('/')` → home page; now lands on member login form for natural flow)

#### `MemberSetupController`
- `show($token)` — show set-password form (validate token not expired)
- `store($token, Request)` — validate password, hash + save, clear setup_token, auto-login

#### `MemberForgotPasswordController`
- `create()` — show forgot password form (email input)
- `store(Request)` — find member by email, generate NEW setup_token, send setup email (same email template as welcome, OR dedicated reset email)

### Admin Auth (`Auth\*`)

#### `AuthenticatedSessionController` (admin Breeze)
- `destroy()` — logout, **redirect to `route('login')` = `/admin/login`** (Phase E fix: previously `redirect('/')`).

### Auth Middleware (`Http\Middleware\Authenticate`)

**Phase E fix**: Default Breeze `redirectTo()` returned `route('login')` for all guards, which resolved to `/admin/login` (admin login). Caused member-facing routes to redirect unauthenticated members to admin login form.

**Fix**: Path-based routing:
```php
protected function redirectTo(Request $request): ?string
{
    if ($request->expectsJson()) {
        return null;
    }
    
    // Path-based redirect: member area uses /login, admin area uses /admin/login
    if ($request->is('academy', 'academy/*')) {
        return route('member.login');   // /login
    }
    
    return route('login');              // /admin/login
}
```

This handles `/academy` (dashboard root) and `/academy/*` (all subpaths). Other paths default to admin login.

---

## 7. ROUTES

### Admin Academy (super_admin + admin only)

Under `/admin/academy/` prefix, route name prefix `admin.academy.`.

```php
// Custom member actions BEFORE Route::resource (Laravel route order matters)
POST    /admin/academy/members/{member}/resend-setup       → members.resend-setup
POST    /admin/academy/members/{member}/regenerate-token   → members.regenerate-token
POST    /admin/academy/members/{member}/toggle-active      → members.toggle-active
GET     /admin/academy/members-search                      → members.search (AJAX autocomplete)

// Members CRUD (resource)
GET     /admin/academy/members                             → members.index
GET     /admin/academy/members/create                      → members.create
POST    /admin/academy/members                             → members.store
GET     /admin/academy/members/{member}                    → members.show
GET     /admin/academy/members/{member}/edit               → members.edit
PUT     /admin/academy/members/{member}                    → members.update
DELETE  /admin/academy/members/{member}                    → members.destroy

// Modules CRUD (resource)
GET     /admin/academy/modules                             → modules.index
GET     /admin/academy/modules/create                      → modules.create
POST    /admin/academy/modules                             → modules.store
GET     /admin/academy/modules/{module}                    → modules.show
GET     /admin/academy/modules/{module}/edit               → modules.edit
PUT     /admin/academy/modules/{module}                    → modules.update
DELETE  /admin/academy/modules/{module}                    → modules.destroy

// Materials (nested, partial — no index/create/show)
POST    /admin/academy/modules/{module}/materials                       → modules.materials.store
GET     /admin/academy/modules/{module}/materials/{material}/edit       → modules.materials.edit
PUT     /admin/academy/modules/{module}/materials/{material}            → modules.materials.update
DELETE  /admin/academy/modules/{module}/materials/{material}            → modules.materials.destroy
```

### Member Auth (guest:member)

Outside admin block, top-level routes.

```php
GET     /login                                  → member.login
POST    /login                                  → member.login.store

GET     /forgot-password                        → member.password.request
POST    /forgot-password                        → member.password.email

GET     /academy/setup-password/{token}         → member.setup
POST    /academy/setup-password/{token}         → member.setup.store
```

### Member Learning (auth:member + member.active)

Under `/academy/` prefix, route name prefix `academy.`.

```php
GET     /academy                                → academy.dashboard
GET     /academy/announcements                  → academy.announcements      (Phase F)
GET     /academy/upgrade                        → academy.upgrade            (Phase E)
GET     /academy/learn/{module:slug}            → academy.module.show
GET     /academy/learn/{module:slug}/{material} → academy.material.show
POST    /academy/progress/{material}/toggle     → academy.progress.toggle  (AJAX)
GET     /academy/profile                        → academy.profile.edit
POST    /academy/profile/password               → academy.profile.password.update
POST    /academy/logout                         → academy.logout
```

**Route name `progress.toggle` vs implementation**: Route name uses "toggle" but actual implementation per Phase D log is **one-way mark complete** (button disabled after marking). Naming legacy from initial plan, behavior simplified.

---

## 8. AUTH ARCHITECTURE

### Multi-Guard Setup

**`config/auth.php`** has two guards:
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'member' => [
        'driver' => 'session',
        'provider' => 'members',
    ],
],

'providers' => [
    'users' => ['driver' => 'eloquent', 'model' => App\Models\User::class],
    'members' => ['driver' => 'eloquent', 'model' => App\Models\Member::class],
],

'passwords' => [
    'users' => [...],
    'members' => [
        'provider' => 'members',
        'table' => 'password_reset_tokens',
        'expire' => 60,
    ],
],
```

### Custom Middleware: `member.active`

Checks `auth('member')->user()->is_active === true`. If false → logout + redirect to login with error.

### Guard Isolation

- `@auth('member')` checks member auth
- `Auth::guard('member')->user()` accesses member
- Admin `@auth` defaults to `web` guard
- **Cross-login prevented**: Member cannot access admin routes. Admin cannot access member routes (unless explicitly logged in as member too — separate sessions).

### `/login` URL Decision

- `/login` route name `member.login` (PRIMARY consumer-facing route)
- Admin login at `/admin/login` route name `login` (Breeze default)
- Reason: Members are larger audience, deserve cleaner URL

### Auth Redirect Behavior (Phase E)

| Trigger | Path | Redirect To |
|---|---|---|
| Unauthenticated GET `/academy/*` | matches `academy` or `academy/*` | `/login` (member login) |
| Unauthenticated GET `/admin/*` | other | `/admin/login` (admin login) |
| Admin logout | POST `/logout` | `/admin/login` |
| Member logout | POST `/academy/logout` | `/login` |
| Tier gate fail (free → paid module) | GET `/academy/learn/{slug}` or `/academy/learn/{slug}/{material}` | `/academy/upgrade` + flash warning |
| Tier gate fail (toggleProgress AJAX) | POST `/academy/progress/{material}/toggle` | JSON 403 `{error: 'This module requires Paid membership'}` |

---

## 9. VIEWS STRUCTURE

```
resources/views/
├── academy/                              (Member-facing)
│   ├── dashboard.blade.php               (modules grid + overall progress + tier-aware locked badges)
│   ├── announcements.blade.php           (Phase F — feed of new modules/materials/articles)
│   ├── module.blade.php                  (module detail + materials list)
│   ├── material.blade.php                (YouTube embed + Quill notes + Mark Complete)
│   ├── profile.blade.php                 (read-only info + change password form)
│   ├── upgrade.blade.php                 (Phase E — benefit list + WA CTA)
│   └── certificates/                     (certificate views per Phase D)
├── admin/academy/                        (Admin CMS)
│   ├── members/
│   │   └── {index, create, edit, show, _form}.blade.php   (with tier dropdown + tier filter + badge)
│   ├── modules/
│   │   └── {index, create, edit, show, _form}.blade.php   (with tier dropdown + tier column + badge)
│   └── materials/
│       └── {edit, _form}.blade.php       (no index/create — managed inline at module show)
├── emails/academy/                       (Email templates)
│   └── welcome-member.blade.php          (and possibly forgot-password.blade.php)
├── auth/member/                          (Auth views)
│   ├── login.blade.php
│   ├── forgot-password.blade.php
│   └── setup-password.blade.php
└── components/
    ├── academy-layout.blade.php          (Member post-auth wrapper: navbar + avatar dropdown — Phase F: Certificates + Announcements moved into main nav, Avatar dropdown reduced to Profile + Logout only)
    └── academy-auth-layout.blade.php     (Member pre-auth wrapper: minimal centered card)
```

---

## 10. KEY UI PATTERNS

### Two Dedicated Layouts (NOT shared with public)

**`<x-academy-auth-layout>`** — pre-auth pages (login, forgot, setup):
- Minimal centered card design
- No navbar
- `@stack('scripts')` + `@stack('styles')` for per-page injection

**`<x-academy-layout>`** — post-auth pages (dashboard, module, material, profile, upgrade):
- Top navbar with brand logo + avatar dropdown
- Avatar dropdown: `bg-gray-200 text-gray-700` (soft grey, NOT brand color)
- Logout link: `text-red-600` (destructive action signal)
- Brand color throughout: `#165DFF` (Digimaya blue) — replaces all `indigo-*` references

### Tier Badge Color System (Phase E)

| Context | Badge Classes | Meaning |
|---|---|---|
| Free (admin index/show, member dashboard) | `bg-gray-100 text-gray-700` | Neutral, default |
| Paid (admin index/show, member dashboard locked card) | `bg-amber-100 text-amber-800` | Premium, highlight |
| Already-Paid notice (upgrade page banner) | `bg-amber-50 border-amber-200 text-amber-800` | Lighter shade for info banner |

Amber chosen over gold/yellow (`bg-yellow-*` not compiled in Tailwind 3.4.19 build) and over indigo (brand collision with active buttons). Amber = standard SaaS "premium" signal (Notion, Slack, Figma pattern).

### Member Dashboard Card Pattern (Phase E updated)

Tier-aware card rendering: free member sees locked state for paid modules, paid member sees normal state.

```blade
@foreach($modules as $module)
    @php $isLocked = ! $member->canAccessModule($module); @endphp
    <a href="{{ $isLocked ? route('academy.upgrade') : route('academy.module.show', $module) }}"
       class="block bg-white ... {{ $isLocked ? 'opacity-90' : '' }}">
        {{-- Cover: grayscale when locked --}}
        <img ... class="... {{ $isLocked ? 'grayscale' : '' }}">
        
        {{-- Title: with lock icon when locked --}}
        <h3 class="... flex items-center gap-1.5">
            @if($isLocked) <svg ... lock-icon /> @endif
            <span class="truncate">{{ $module->title }}</span>
        </h3>
        
        {{-- Badge: amber "Paid" when locked --}}
        @if($isLocked)
            <span class="... bg-amber-100 text-amber-800">Paid</span>
        @elseif(/* completed */)
            <span class="... bg-green-100 text-green-800">Done</span>
        @endif
        
        {{-- Body: hide progress bar when locked, show upgrade hint instead --}}
        @if($isLocked)
            <p>Upgrade ke Paid untuk akses module ini.</p>
            <div class="... text-amber-700">Lihat detail Paid Member →</div>
        @else
            <progress-bar />
            <div class="... text-brand">Mulai belajar →</div>
        @endif
    </a>
@endforeach
```

### Upgrade Page Layout (Phase E)

`/academy/upgrade` — single-column max-w-3xl card:
- **Header**: "Upgrade ke Paid Member" + tagline
- **Conditional notice** (if paid member): Amber banner "Kamu sudah Paid member."
- **Hero card**: Heading "Apa yang kamu dapat dengan Paid Member?" + 4 benefit items with green checkmark icons
- **CTA section** (gray-50 footer): WhatsApp button (`bg-green-600`, centered via `<div class="flex justify-center">` wrapper) + helper text
- **Back link**: "← Kembali ke Dashboard"

Benefit list (admin-defined, not DB-driven):
1. Akses Semua Module (Free + Paid)
2. Materi Advanced & Strategi Internal Digimaya
3. Komunitas Private
4. Sertifikat Completion

WhatsApp link construction:
```blade
@php
    $waUrl = config('digimaya.contact.whatsapp_wa_url');
    $waText = 'Saya mau upgrade member agar dapat akses ke semua module.';
    $waLink = $waUrl . '?text=' . rawurlencode($waText);
@endphp
```

CTA button label conditional: "Upgrade Sekarang via WhatsApp" (free) vs "Hubungi via WhatsApp" (already paid).

### Admin Module/Member Form Pattern (Phase E)

Both forms have tier as **select dropdown** (required, default `free`):

```blade
<select id="tier" name="tier" required ...>
    @php $selectedTier = old('tier', $module->tier ?? \App\Models\Module::TIER_FREE); @endphp
    <option value="{{ \App\Models\Module::TIER_FREE }}" {{ $selectedTier === \App\Models\Module::TIER_FREE ? 'selected' : '' }}>Free</option>
    <option value="{{ \App\Models\Module::TIER_PAID }}" {{ $selectedTier === \App\Models\Module::TIER_PAID ? 'selected' : '' }}>Paid</option>
</select>
```

Module form layout: `display_order | tier | status` grid (3 cols). Member form: vertical stack with tier between notes and is_active.

### Admin Member Index Filter (Phase E)

Filter row uses uniform Lead/Client/Invoice pattern:
```
[Month ▼] [Year ▼] [Tier ▼] [Status ▼] [Enrollers ▼] [Search ____] [Apply] [Reset]
```

Month: `0` = All Months (sentinel value). Year: current year - 3 to current year. Tier filter validated against `Member::TIERS` server-side.

### Material Card Numbering

Inline title prefix `"01. Title"` `"02. Title"` (NOT overlay badge on thumbnail). Cleaner visual hierarchy.

### Sidebar Bullet (in material page)

Uniform shape per state:
- **Incomplete**: outline circle with sequence number (1, 2, 3...)
- **Complete**: filled green circle with checkmark

NO conditional shape change (circle vs rectangle). Just color + content swap.

### YouTube ID Input UX (admin material form)

Accept either full YouTube URL OR 11-char ID. JS extracts ID from URL on input:

```js
const match = val.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]{11})/);
youtubeId = match ? match[1] : (val.length === 11 ? val : '');
```

Show preview thumbnail from `https://img.youtube.com/vi/{id}/maxresdefault.jpg` once valid.

### Quill Notes Auto target=_blank

All `http://` and `https://` links inside `.material-notes` div get `target="_blank" rel="noopener noreferrer"` injected via DOMContentLoaded handler in `material.blade.php`. Prevents accidental nav-away from learning page.

### Cover Image Dual Pattern (matches LogoWallItem)

Admin form has two fields:
- `cover_image_file` (upload, takes precedence)
- `cover_image_url` (external URL, used if no file)

Controller `handleImage()` helper:
1. If file uploaded → store in `storage/app/public/academy/modules/`, return path
2. Else if URL provided → return URL as-is
3. Else → keep existing value

Display logic in model: `coverImageIsExternal()` + `cover_image_url` accessor.

### Public Navbar Integration

In `layouts/public.blade.php`, conditional pill button right side:

```blade
@auth('member')
    <a href="{{ route('academy.dashboard') }}"
       class="border border-brand text-brand hover:bg-brand hover:text-white rounded-full ...">
        Dashboard
    </a>
@else
    <a href="{{ route('member.login') }}"
       class="border border-brand text-brand hover:bg-brand hover:text-white rounded-full ...">
        Login
    </a>
@endauth
```

---

## 11. WORKFLOWS

### Admin Enroll New Member (Phase E updated)

1. Admin → `/admin/academy/members/create`
2. Fill: name, email, notes (optional), is_active=true, **tier (Free/Paid, default Free)**
3. Submit → controller validates + creates member with `password=null` + tier
4. Auto-generate setup_token (64-char hex, 24h expiry)
5. Send `WelcomeMember` email to member's email
6. Redirect to member show page with:
   - Success message
   - Setup URL displayed inline (admin can copy + manually share if email fails)
   - Copy button + Resend Email button + Regenerate Token button
   - Member info card shows tier badge

### Admin Promote Member Free → Paid (Phase E)

1. Admin → member show or index → click Edit
2. Change `tier` dropdown from Free to Paid → Update
3. Next time member logs in or refreshes dashboard, paid modules unlock automatically (no session invalidation needed — gate checks tier on every request)

### Member First Login (Setup Password)

1. Member receives email with link `https://digimaya.com/academy/setup-password/{token}`
2. Click link → `member.setup` route
3. Controller validates: token exists + not expired
4. Show form (password + password_confirmation)
5. Submit → validate (min 8, confirmed) → hash + save → `clearSetupToken()`
6. Auto-login via `Auth::guard('member')->login($member)`
7. Redirect to `/academy` (dashboard)

### Member Login (Returning)

1. Member → `/login`
2. Submit email + password
3. `MemberLoginRequest::authenticate()` validates:
   - Standard Auth::attempt() against `member` guard
   - `is_active === true` check (throw ValidationException if false)
4. Update `last_login_at`
5. Redirect intended to `/academy` (dashboard)

### Member Logout (Phase E updated)

1. Member → avatar dropdown → click Logout
2. POST `/academy/logout`
3. `Auth::guard('member')->logout()` + invalidate session + regenerate token
4. **Redirect to `/login`** (member login form — Phase E fix)

### Member Forgot Password

1. Member → `/login` → click "Lupa password?"
2. `/forgot-password` → enter email → submit
3. `MemberForgotPasswordController::store()`:
   - Find member by email
   - Call `generateSetupToken()` (regenerates, sets 24h expiry)
   - Send setup email (same as welcome email OR dedicated reset variant)
4. Member receives email → click setup link → set new password → auto-login

**Note**: Forgot password reuses `setup_token` system (single token mechanism for all password-setting flows).

### Admin Resend Setup Link

1. Admin → member show page
2. If `setup_token` expired/missing → click "Regenerate Token" → confirm
3. Backend regenerates token, returns new URL, optionally sends email
4. Admin manually share URL via WhatsApp/etc. if email fails

### Member Consume Material (Free Tier Member, Paid Module Access Attempt) — Phase E

1. Free member at `/academy` → sees paid module with badge "Paid" + lock icon + grayscale cover
2. Click locked card → href routes to `/academy/upgrade` (not module page)
3. Upgrade page shows benefits + WA CTA button
4. Click "Upgrade Sekarang via WhatsApp" → opens WA with pre-filled text: "Saya mau upgrade member agar dapat akses ke semua module."
5. **Bypass attempt** (paste `/academy/learn/{paid-module-slug}` directly) → server-side gate catches: redirect to `/academy/upgrade` with flash warning "Module ini hanya untuk Paid member. Upgrade untuk akses."
6. **Bypass attempt via material URL** (paste `/academy/learn/{slug}/{material-id}`) → same gate, same redirect
7. **Bypass attempt via toggleProgress POST** (cURL or DevTools) → 403 JSON `{error: 'This module requires Paid membership'}`

### Member Consume Material (Normal Flow)

1. Member at `/academy` → click module → `/academy/learn/{slug}`
2. See materials list with completion indicators
3. Click material → `/academy/learn/{slug}/{materialId}`
4. Watch YouTube embed
5. Read Quill notes (links open in new tab)
6. Click "Tandai Selesai" → AJAX POST `/academy/progress/{material}/toggle`
7. Backend creates `MemberProgress` record, returns JSON
8. Button disabled (one-way mark — see Known Gotchas)
9. Sidebar bullet updates realtime via `updateSidebar(materialId)` JS

### Admin Add Module + Materials (Phase E updated)

1. `/admin/academy/modules/create` → fill title, description, cover image, is_published, **tier (Free/Paid)**
2. Save → redirect to module show page (tier badge visible in Module Info sidebar)
3. Click "+ Add Material" → navigate to material form (separate page, Quill doesn't fit modal)
4. Fill: title, youtube_id (or URL), notes (Quill), display_order, is_published
5. Save → redirect to module show
6. Reorder materials via display_order field

### Admin Toggle Member Active

1. Admin → member index → click toggle button on member row
2. POST `/admin/academy/members/{id}/toggle-active`
3. `is_active` flipped
4. **Note**: Existing member session NOT immediately invalidated. Member will be kicked out on next request via `member.active` middleware check.

---

## 12. EMAIL NOTIFICATIONS

### `WelcomeMember` Mailable

**Class**: `App\Mail\Academy\WelcomeMember`  
**Trigger**: Admin enrolls new member (MemberController::store)  
**Subject**: "Selamat datang di Digimaya Academy"  
**Format**: Plain text (consistent with NewLeadNotification pattern)

**Content includes**:
- Greeting with member name
- Setup URL (`route('member.setup', $token)`)
- 24-hour expiry notice
- Login URL for future logins
- Contact info if issues

**Error handling**: Wrapped in try-catch in controller — email failure does NOT break enrollment UX.

### Forgot Password Email

Reuses same setup token system. Subject: "Reset password Digimaya Academy" atau similar.

---

## 13. KEY VALIDATIONS

### Member Create (Admin)
- `name`: required, string, max 255
- `email`: required, email, unique:members
- `is_active`: boolean
- `notes`: nullable, string
- **`tier`**: required, `Rule::in(Member::TIERS)` (Phase E)

### Member Update (Admin)
- Same as create, but email unique ignore self (`unique:members,email,{id}`)
- Password NOT editable here (admin uses regenerate token instead)
- Tier editable (allows free → paid upgrade or paid → free downgrade)

### Member Setup Password
- `password`: required, string, min 8, confirmed (matches `password_confirmation`)
- Token validation: `$member->isSetupTokenValid()` must return true

### Member Profile Password Change
- `current_password`: required, must match (use Laravel 10 `current_password:member` rule)
- `password`: required, string, min 8, confirmed

### Module Create/Update
- `title`: required, string, max 255
- `slug`: auto-generated if empty (via Module boot event)
- `description`: nullable, string
- `cover_image_file`: nullable, image, max 1024KB
- `cover_image_url`: nullable, valid URL
- `display_order`: integer, min 0
- `is_published`: boolean
- **`tier`**: required, `Rule::in(Module::TIERS)` (Phase E)

### Material Create/Update
- `title`: required, string, max 255
- `youtube_id`: required, string, regex `/^[\w-]{11}$/`
- `notes`: nullable, string (sanitize with `Purifier::clean()` on save)
- `display_order`: integer, min 0
- `is_published`: boolean

---

## 14. NAV INTEGRATION

### Admin Top Nav

Academy dropdown (visible for super_admin + admin only):
```
Academy ▼
├── Members        → /admin/academy/members
└── Modules        → /admin/academy/modules
```

Active state: `routeIs('admin.academy.*')`

### Public Navbar (External Site)

Right-side pill button (conditional):
- Not logged in: "Login" → `/login`
- Logged in as member: "Dashboard" → `/academy`

### Member Academy Navbar (Post-Auth) (Phase F updated)

Top bar with brand logo + main nav links + avatar dropdown:

**Main nav links** (visible on desktop, in hamburger menu on mobile):
- Dashboard → `/academy`
- Announcements → `/academy/announcements` (Phase F)
- Certificates → `/academy/certificates` (Phase F — promoted out of dropdown)

**Avatar dropdown items** (simplified in Phase F):
- "Logged in as {email}" — info header
- Profile → `/academy/profile`
- Logout → `/academy/logout` (POST, text-red-600)

Active state styling: `text-brand` color on current nav link, gray otherwise.

---

## 15. KNOWN GOTCHAS

### Member ≠ User (Strict Separation)
`members` table is fully independent from `users` table. No FK link except `enrolled_by` (who created the member). Member doesn't have role, doesn't appear in admin user lists, doesn't share authentication.

Implications:
- Activity log on members? Currently NOT instrumented (no LogsActivity trait on Member model).
- Email collision between users + members possible (different tables, different unique constraints). Not enforced cross-table.

### Single Setup Token System
SAME `setup_token` field serves 3 use cases:
1. New member enrollment (admin creates → email)
2. Admin-triggered reset (regenerate token)
3. Member-initiated forgot password

This simplifies code (1 controller handles all setup) but means token lifecycle careful:
- Generating new token INVALIDATES previous one (single-token model)
- If member is mid-setup and admin regenerates, member's link breaks

### Module Route Binding by Slug
`{module:slug}` binding in member routes. Admin routes use default id binding.

If slug changes (admin edits title → slug auto-regenerates), old URLs break. Mitigation: slug auto-generation skips if explicitly set (via boot event `empty($module->slug)` check).

### Material `is_published=false` Hiding
Member learning queries use `publishedMaterials` relation (filters `is_published=true`). Unpublished materials accessible to admin only.

If admin un-publishes a material AFTER member completed it:
- Progress record remains (no cascade on `is_published` flip)
- Dashboard progress % stays accurate (counts based on `publishedMaterials` though — could over-count)

### MemberProgress No SoftDelete
Hard delete. To "un-mark" complete, DELETE the record. No "completed_at_was" history.

### One-Way Mark Complete (Implementation vs Route Name)
Route name is `progress.toggle` but Phase D implementation is **mark only**, button disabled after click. To un-mark, manual DB intervention needed.

If UX needs to support un-mark in future, change `LearningController::toggleProgress()` logic — already supports it conceptually (route accepts POST, action could be `delete if exists, create if not`).

### Cover Image Dual Pattern Confusion
Both `cover_image_file` AND `cover_image_url` form fields exist, but DB column is `cover_image` (single string).

Priority logic in `handleImage()` controller helper:
1. File upload wins (stores to `storage/`, returns path)
2. URL provided → return URL as-is
3. Both empty → keep existing value

Frontend should show preview from whichever is currently set.

### YouTube ID 11-char Validation
Regex `/^[\w-]{11}$/` only matches alphanumeric + underscore + hyphen, exactly 11 chars. Real YouTube IDs follow this but be aware:
- Live URLs `youtu.be/...` etc. need extraction (JS handles)
- Embed URLs `youtube.com/embed/...` need extraction
- Shorts URLs `youtube.com/shorts/...` need extraction (verify regex catches)

### Quill HTML Sanitization
`Purifier::clean($notes)` strips dangerous tags. Custom `config/purifier.php` may need adjustments if Quill output gets stripped (mis. iframes, embeds).

Test specific formatting after save: bold, headings, lists, links, images (if allowed).

### Quill Auto target=_blank
JS handler in `material.blade.php` injects `target="_blank" rel="noopener noreferrer"` to all `http(s)` links INSIDE `.material-notes` div. Doesn't modify DB content, just runtime DOM patch. Safe but means raw HTML in DB doesn't have these attributes.

### Pre-Auth Pages Not Polished
Login, forgot, setup pages still use Phase C era styling (not aligned with post-auth polish pass). Low priority — member rarely sees them after onboarding. If polishing: `<x-academy-auth-layout>` already used, just align button + heading typography.

### Token Expiry Edge Case
If member receives welcome email but waits >24h to click → token expired. Admin must regenerate (no member-side self-recovery for expired setup, except forgot password flow which generates new token).

### `is_active=false` Soft Lock
Member with `is_active=false`:
- Cannot login (validation in `MemberLoginRequest::authenticate()`)
- Existing session continues until next request, then `member.active` middleware kicks out
- Data preserved (SoftDeletes + is_active soft flag)
- Re-activate via admin toggle

### Brand Color Override
All `indigo-*` references replaced by `brand-*` (`#165DFF`) in member views. If new components reuse indigo, they'll look out-of-place in Academy context. Stick to brand palette for consistency.

### Tier Gate is Module-Level Only (Phase E)
Decision: tier locked at **module level**, NOT material level. Reasons:
- Simpler mental model for member ("module ini Paid")
- Matches free trial intent (give whole modules as samples, not partial)
- Easier maintenance for admin (no per-material flag)
- Future extensibility preserved: can add `material.tier` override later if needed

Implication: a Paid module = ALL its materials are locked for free members. No mixed-tier module.

### Tier Gate is Defense-in-Depth (Phase E)

Three independent layers prevent unauthorized access:

1. **DB layer**: enum constraint `tier ENUM('free', 'paid')` rejects invalid values
2. **UI layer**: `LearningController@dashboard` view renders locked cards with redirect href to upgrade page
3. **Server layer**: `LearningController@showModule`, `@showMaterial`, `@toggleProgress` each check `$member->canAccessModule($module)` and short-circuit to redirect or 403

If any one layer fails, the others catch. Removing only UI layer (e.g. for testing) still blocks bypass attempts via URL paste or cURL.

### Tier Gate Return Type Signature (Phase E)
`showModule` and `showMaterial` previously typed `: View`. After Phase E, they can return either View OR RedirectResponse (when tier gate fires). PHP 8.1 union types required:

```php
public function showModule(Module $module): View|\Illuminate\Http\RedirectResponse
```

If you ever extract these methods to traits or test mocks, preserve the union type signature.

### Auth Redirect is Path-Based, Not Guard-Based (Phase E)
`Authenticate::redirectTo()` checks `$request->is('academy', 'academy/*')` to decide between member-login (`/login`) and admin-login (`/admin/login`).

Why path-based not guard-based: Laravel 10 `$this->guards` array in middleware context is not reliably populated for `auth:member` invocation. Path string matching is deterministic.

Implication: if member-facing routes ever move out of `/academy/*` prefix (e.g. to `/learn/*`), update the `is()` pattern. Currently no plan to migrate.

### Admin Dashboard Route Name vs Path (Phase E discovery)
`/admin` (NOT `/admin/dashboard`) is the admin dashboard URL. Route name is `admin.dashboard`. This caused brief confusion during Phase E auth testing — pasting `/admin/dashboard` returns 404 (correct behavior, not a bug).

If building new admin links/redirects, always use named routes (`route('admin.dashboard')`) instead of hardcoded URLs.

### Test Data Cleanup (Phase E)
Production-clean state verified 21 May 2026 after Tier System Phase E:
- 20 production modules, all `tier='free'` (default)
- 2 production members (Sarah, Rica), all `tier='free'` (default)
- No test modules ("TEST Paid Module", "FINAL TEST Paid" → forceDelete-d during Phase E browser tests)
- All `.bak-2026*` files cleaned (31 files removed)

---

## 16. PENDING ITEMS / ROADMAP

### Immediate Polish (post-Phase E)
- [ ] Polish pre-auth pages (login/setup/forgot) — align with member-side design
- [ ] Audit pill button consistency across remaining public/member pages
- [ ] Pre-build content for placeholder modules or replace with real production content

### Security/Ops
- [ ] Rotate Gmail App Password (was leaked in earlier chat session — handle outside notes)
- [ ] Monitor email delivery 24h post-Cloudflare migration

### Tier System Enhancements (Phase E follow-ups, validate demand first)
- Payment integration (Stripe/Midtrans) — currently manual WA flow only
- Subscription tier (monthly/annual) vs lifetime — currently binary
- Multi-tier (Free / Pro / Enterprise) — currently 2 tiers only
- Bulk admin action: promote N members to Paid at once
- Email notification on tier change (member receives "Welcome to Paid" email)
- Tier history/audit log (when did member upgrade/downgrade, by whom)
- Material-level tier override (preview lesson in paid module)
- Tier-based content gating in certificate generation (Paid-only certificate?)

### Future Features (Post-MVP, validate demand first)
- Self-registration with admin approval
- Course completion certificate (PDF generation) — Phase D added partial; Paid-only restriction TBD
- Drip content (modules unlocked over time)
- Discussion forum per module
- Bulk member import (CSV)
- Engagement analytics
- Native video upload (currently YouTube only)
- Search across modules + materials
- Member bookmarks/favorites
- Public preview of select modules

### Migration Path (if Academy grows beyond MVP)
- Database: already isolated, easy to migrate to dedicated DB
- Code: already namespaced (`App\Models\Member`, `Academy\` controllers), easy to extract as service
- URL: could move to subdomain `learn.digimaya.com` with minimal route changes
- Auth: member guard already separate, swap session driver as needed

---

## 17. PHASE E — TIER SYSTEM (21 May 2026)

**Status**: COMPLETE
**Duration**: 1 session, 9 sequential steps + 2 hotfixes (auth + UI polish)
**Owner**: Renra Sedoya + Claude

### Scope

Implementasi Free/Paid tier system untuk Module + Member. Enables free trial flow: admin enroll member sebagai Free → member akses free modules → upgrade prompt via WhatsApp → admin promote ke Paid.

### Files Created

**View** (1):
- `resources/views/academy/upgrade.blade.php` — landing page for upgrade info + WA CTA

### Files Modified

**Migration** (1):
- `database/migrations/2026_05_21_181845_add_tier_to_modules_and_members.php` — adds `tier` enum to both tables with indexes

**Models** (2):
- `app/Models/Module.php` — added TIER_FREE/TIER_PAID/TIERS constants, tier cast, scopeFree/scopePaid, isPaid/isFree helpers
- `app/Models/Member.php` — same as above PLUS `canAccessModule(Module $module): bool` central gate method

**Controllers** (4):
- `app/Http/Controllers/Admin/Academy/ModuleController.php` — validate tier (Rule::in), store+update pass tier
- `app/Http/Controllers/Admin/Academy/MemberController.php` — validate tier on store+update, add tier+month+year filters to index
- `app/Http/Controllers/Academy/LearningController.php` — added `upgrade()` method; tier gates injected into `showModule`, `showMaterial`, `toggleProgress`; signatures updated to union return types
- `app/Http/Middleware/Authenticate.php` — path-based redirect (academy/* → /login, else → /admin/login)
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` — logout redirect changed `/` → `route('login')`
- `app/Http/Controllers/Auth/Member/MemberLoginController.php` — logout redirect changed `/` → `route('member.login')`

**Routes** (1):
- `routes/web.php` — added `GET /academy/upgrade` → `academy.upgrade`

**Admin Views** (6):
- `resources/views/admin/academy/modules/_form.blade.php` — tier dropdown in 3-col grid
- `resources/views/admin/academy/modules/index.blade.php` — Tier column with amber/gray badge, colspan 5→6
- `resources/views/admin/academy/modules/show.blade.php` — Tier row in Module Info sidebar
- `resources/views/admin/academy/members/_form.blade.php` — tier dropdown before Active checkbox
- `resources/views/admin/academy/members/index.blade.php` — Tier column + filter (month/year/tier dropdowns), colspan 7→8
- `resources/views/admin/academy/members/show.blade.php` — Tier row in Informasi Member sidebar

**Member Views** (1):
- `resources/views/academy/dashboard.blade.php` — tier-aware module cards (locked state: amber Paid badge + lock icon + grayscale cover + no progress bar + upgrade hint, conditional href routes to upgrade page)

### Step-by-Step Execution Log

| Step | Description | Files |
|---|---|---|
| 1 | Migration: add tier enum | 1 migration |
| 2 | Module model: constants + helpers | 1 model |
| 3 | Member model: constants + gate | 1 model |
| 4 | Admin Module form + controller | 2 files |
| 4.1 | Admin Module index + show badges | 2 views |
| 5 | Admin Member form + controller + index + show | 4 files |
| 6 | Filter Tier+Month+Year in Member index | 2 files |
| 7 | Upgrade page (route+controller+view) | 3 files |
| 7.1 | Hotfix: Authenticate path-based redirect | 1 middleware |
| 7.2 | Hotfix: Logout redirect (admin + member) | 2 controllers |
| 8 | Member dashboard tier-aware cards | 1 view |
| 9 | Server-side gate (3 controller methods) | 1 controller |
| Polish | Upgrade page button centering + em dash fix | 1 view |

### Patterns Established

1. **Tier constants on models** — `TIER_FREE`, `TIER_PAID`, `TIERS` arrays. Used in validation `Rule::in(Model::TIERS)`, form dropdowns, and gate logic. Avoids magic strings.

2. **Central gate method on Member** — `$member->canAccessModule($module): bool` is the single source of truth. Called from views (UI gating) and controllers (server gating). DRY by design.

3. **Defense-in-depth security** — 3 independent layers (DB enum, UI redirect, server check). Removing any one layer doesn't compromise security.

4. **Path-based middleware redirect** — More deterministic than guard-based in Laravel 10 multi-guard setup. `$request->is('academy', 'academy/*')` is unambiguous.

5. **Tier badge color palette** — Amber for Paid (premium feel), gray for Free (neutral). Avoid yellow (not compiled), avoid indigo (brand collision).

6. **Lazy filter sentinel value** — `month=0` = "All Months" (consistent with Lead/Client/Invoice index pattern). Empty `tier=''` = "All Tiers" (Rule::in validates so empty just skips filter).

7. **Conditional href routing in card** — Free member's locked card uses `href="{{ $isLocked ? route('upgrade') : route('module') }}"` — no JS needed, no event handler. Server-side rendering does the routing.

8. **Union return type for tier-gated controllers** — `View|RedirectResponse` lets methods short-circuit cleanly. PHP 8.1 syntax.

### Lessons Learned

**Lesson #11: Laravel `make:migration --create=` without table name creates broken stub**
When running `php artisan make:migration add_tier_to_modules_and_members --create=` with empty `--create` flag, Laravel creates a migration with empty `Schema::table('modules_and_members', ...)` block (using migration name as table). Discovered when 2 migrations ran (one empty stub, one with real schema). Fix: manually `rm` the stub file + delete the row from `migrations` table.

SOP for future migrations: skip `--create=` flag entirely, use `php artisan make:migration` plain. Or just write the migration from scratch via heredoc.

**Lesson #12: Multi-line tinker commands trigger cosmetic cPanel PHP wrapper warning**
Heredoc tinker commands on cPanel produce `Unsuccessful stat on filename containing newline at /var/cpanel/ea4/ea_php_cli.pm line 87.` This is a wrapper-level warning, NOT a code error. Output is correct, can be safely ignored. To suppress: use single-line tinker or `php artisan tinker` interactive mode.

**Lesson #13: Em dash slipped through (`—`) is a recurring violation**
Despite explicit `notes-general.md` rule "NO em dashes in production text," em dash appeared in the upgrade page benefit copy. Caught during browser visual review by user. Pattern: when generating long-form copy, em dash feels natural to LLMs but violates the rule.

SOP: after creating any production text, grep `grep "—" file.blade.php` to verify zero matches. Add em dash check to verify scripts going forward.

**Lesson #14: Button alignment requires explicit center wrapper, not just `inline-flex`**
`inline-flex items-center justify-center w-full sm:w-auto` makes button stretch full-width on mobile then becomes inline (left-aligned) on desktop. To center on desktop, wrap in `<div class="flex justify-center">` or use `mx-auto` on the button. Lesson: `inline-flex` alone doesn't center the block — that's parent's job.

**Lesson #15: Default Laravel `redirect('/')` after logout is anti-pattern for multi-guard apps**
Logout returning to home page is fine for single-tenant SaaS but unnatural when admin/member auth are split. Best practice: logout returns user to the login form for their context. Saves them a click and confirms the logout visually.

### Tech Debt Added

None. Phase E shipped clean. All decisions documented above; no shortcuts taken.

### Validation Checklist

All tested on production-like state with Sarah (free) and 1 test paid module:

- [x] Migration runs both up and down cleanly
- [x] Module + Member model constants accessible via PHP class constants
- [x] Module + Member cast `tier` to string correctly
- [x] Scopes (`free()`, `paid()`) return correct counts
- [x] `canAccessModule` returns correct bool for all 4 combinations (free×free, free×paid, paid×free, paid×paid)
- [x] Admin Module form dropdown works (create + edit), default Free, persists on save
- [x] Admin Module index shows Tier column with correct badge colors
- [x] Admin Module show sidebar shows Tier row with badge
- [x] Admin Member form dropdown works, default Free, persists on save
- [x] Admin Member index shows Tier column + filter (tier/month/year all working)
- [x] Admin Member show sidebar shows Tier row with badge
- [x] Member dashboard shows locked badges for paid modules when member is Free
- [x] Clicking locked card redirects to /academy/upgrade
- [x] Free member typing `/academy/learn/{paid-slug}` directly is redirected to upgrade page
- [x] Free member typing `/academy/learn/{paid-slug}/{material-id}` directly is redirected
- [x] Free member POST to /academy/progress/{material}/toggle for paid module returns 403 JSON
- [x] Promoting member to Paid via admin immediately unlocks all paid modules on next page load
- [x] Upgrade page renders all 4 benefits + centered WA button + back link
- [x] WA button opens WhatsApp with pre-filled text correctly URL-encoded
- [x] Already-Paid notice shows for paid member visiting upgrade page
- [x] Admin logout redirects to /admin/login
- [x] Member logout redirects to /login
- [x] Unauthenticated GET /academy/upgrade redirects to /login
- [x] Unauthenticated GET /admin redirects to /admin/login
- [x] No em dashes in upgrade page copy

### Rollback Procedure (if needed)

```bash
# 1. Revert migration
cd /home/digimay1/digimaya_app
php artisan migrate:rollback --step=1

# 2. Restore files from version control (if backed up to git)
git checkout HEAD -- app/Models/Module.php app/Models/Member.php app/Http/Controllers/Admin/Academy/ app/Http/Controllers/Academy/LearningController.php app/Http/Controllers/Auth/

# 3. Remove upgrade view
rm resources/views/academy/upgrade.blade.php

# 4. Clear caches
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

Note: `.bak` files from Phase E session were cleaned 21 May 2026 as part of wrap-up. Rollback now requires git or manual reconstruction.

### References

- Phase D MVP Notes (above sections, 10 May 2026)
- Phase B.5 SEO Foundation (in `notes-general.md`, 19 May 2026)
- Lead/Client/Invoice index filter pattern (used as reference for month/year dropdown)
- Laravel 10 Multi-Guard Auth: https://laravel.com/docs/10.x/authentication#guards

---

## 18. PHASE F — ANNOUNCEMENTS FEED (22 May 2026)

**Status**: COMPLETE
**Duration**: 1 session, single step + visual preview iteration
**Owner**: Renra Sedoya + Claude

### Scope

Activity feed page (`/academy/announcements`) showing newly published modules, materials, and blog articles. Members aware of new content without manually browsing. Nav also restructured: Certificates promoted from dropdown into main nav (alongside new Announcements link).

### Files Created

**View** (1):
- `resources/views/academy/announcements.blade.php` — feed card list with per-type icon/color + timestamp + CTA link

### Files Modified

**Routes** (1):
- `routes/web.php` — added `GET /academy/announcements` → `academy.announcements`

**Controller** (1):
- `app/Http/Controllers/Academy/LearningController.php` — added `announcements(Request $request): View` method + `BlogPost` + `LengthAwarePaginator` imports

**Layout** (1):
- `resources/views/components/academy-layout.blade.php` — desktop nav: added Announcements + Certificates links between Dashboard and avatar; avatar dropdown reduced to Profile + Logout only. Mobile menu: added Announcements between Dashboard and Certificates.

### Architecture Decision: Polling DB Realtime (NO announcements table)

Considered 3 approaches, picked option 1:

| Approach | Pros | Cons | Verdict |
|---|---|---|---|
| **Polling DB realtime** | Source of truth in original tables. Zero data drift on edit/delete. No sync logic. | 3 queries per page load. | ✅ **Chosen** |
| New `announcements` table + observer | 1 query per page (fast). Manual control over what announces. | Data duplication. Sync logic for edit/delete. Cleanup needed when source deleted. | ❌ Overkill for current scale |
| Cache 5 min hybrid | Fewer DB hits. | Cache invalidation complexity. Member just-promoted has delay. | ❌ Not worth it |

Trade-off accepted: 3 separate Eloquent queries (Module + Material + BlogPost) merged in PHP Collection, manual paginate. Performance fine up to ~10K combined items, well beyond current scale.

### Feed Sources & Display Logic

**Source 1: Module** (use `created_at` as published date)
- Filter: `is_published = true`
- Visual: Blue ramp icon + badge
- CTA: "Buka" → `route('academy.module.show', $module)`
- Subtitle: module description (truncated 100 chars)
- Tier badge: amber "Paid" if `tier = 'paid'`

**Source 2: Material** (use `created_at`)
- Filter: `is_published = true` AND parent module `is_published = true`
- Visual: Green ramp icon + badge
- CTA: "Tonton" → `route('academy.material.show', [$module, $material])`
- Subtitle: "Di module {parent module title}"
- Tier badge: inherits parent module's tier

**Source 3: BlogPost** (use `published_at`)
- Filter: `status = 'published'` AND `published_at IS NOT NULL` AND `published_at <= now()`
- Visual: Pink ramp icon + badge
- CTA: "Baca" → `route('public.blog.show', ['public_id' => $p->public_id, 'slug' => $p->slug])` — opens in new tab
- Subtitle: none (article excerpt not implemented yet)
- No tier badge (all articles are public)

**Merge & sort**: `$modules->concat($materials)->concat($posts)->sortByDesc('date')->values()`

**Paginate**: Manual `LengthAwarePaginator` with `perPage = 15`. Path + query preserved for pagination links.

### Decision Log

| Question | Answer | Rationale |
|---|---|---|
| Scope of feed items | All items from beginning, paginated | Simpler than "since last login" tracking; gives full visibility |
| Tier filtering for feed visibility | Show all modules/materials/articles to all members | Free members see paid modules but with badge; gate still blocks access on click |
| Filter tabs (Module/Material/Article tabs) | No — mixed feed only | Simpler UX; user can scan by icon/color |
| Timestamp format | Human format ("2 jam lalu") | Casual, scannable |
| Icon + color coding | Per-type (blue/green/pink) | Visual scanning; matches preview mockup |
| Article access target | Public URL (digimaya.com/blog/...) | Articles are public-facing; no need for member-only mirror |
| Article date source | `published_at` | More accurate than `created_at` for scheduled posts |
| Cache strategy | None | Acceptable load; revisit if scale grows |

### Patterns Established

1. **Collection-based merge feed** — when source items live in 3+ tables and need unified chronological display, build as PHP Collections in controller, normalize structure (`['type', 'title', 'subtitle', 'date', 'url', 'cta_label']`), merge + sort + paginate. Avoids UNION SQL complexity, easy to add 4th source.

2. **Manual LengthAwarePaginator** — Laravel's built-in `->paginate()` doesn't work for merged Collections. Manual construction: `new LengthAwarePaginator($items, $total, $perPage, $currentPage, ['path' => $request->url(), 'query' => $request->query()])` preserves query string for pagination links.

3. **Per-type visual coding** — when feed has heterogeneous items, encode type with icon + background color (50-shade fill + 800-shade text). Member can scan visually by category without reading every label.

4. **External CTA opens new tab** — articles route to public site (different URL context), use `target="_blank" rel="noopener noreferrer"` on those cards only. Internal navigation (module/material) stays in same tab.

5. **Nav restructure pattern** — when adding 2nd top-level menu item, promote infrequently-used dropdown items into main nav. Avatar dropdown should only have profile + auth actions, not feature pages.

### Lessons Learned

**Lesson #16: Tailwind JIT scan can miss new classes in freshly created blade files**

After creating `announcements.blade.php` with new pink classes (`bg-pink-50`, `text-pink-800`), CSS audit showed both compiled to 0 matches in `tailwind.css`. Root cause: JIT scan ran before new file was on disk OR scan didn't pick up conditional classes in fresh blade syntax.

**SOP**: After introducing new Tailwind classes in any new file, run audit:

```bash
for c in "new-class-1" "new-class-2"; do
  count=$(grep -cE "\.${c}\b" public/css/tailwind.css)
  echo "${c}: ${count}"
done
```

If any class returns 0, run `tw-build` to force fresh JIT scan. Verify again after rebuild.

This is distinct from the existing "verify class compiled before applying" lesson (which applies to UNUSED classes in already-scanned files). Lesson #16 applies to FILES JUST CREATED that haven't been scanned yet.

### Tech Debt Added

None. Phase F shipped clean.

### Validation Checklist

- [x] Migration: N/A (no schema change)
- [x] Route registered: `GET academy/announcements → academy.announcements`
- [x] Controller method `announcements()` syntax check passes
- [x] BlogPost + LengthAwarePaginator imports added correctly
- [x] View file exists at `resources/views/academy/announcements.blade.php`
- [x] All Tailwind classes used (blue/green/pink ramps) compiled in `public/css/tailwind.css`
- [x] Layout: desktop nav has 3 links (Dashboard | Announcements | Certificates) + avatar dropdown
- [x] Layout: avatar dropdown has Profile + Logout only (Certificates moved out)
- [x] Layout: mobile menu has all 3 main nav items + Profile + Logout
- [x] Feed displays Modules + Materials + Articles merged
- [x] Sort: newest first by published date (modules/materials use created_at, articles use published_at)
- [x] Pagination: 15 per page, query string preserved
- [x] Per-type icon + color rendering correct (blue book / green play / pink article)
- [x] Tier badge ("Paid" amber) appears only for paid module/material rows
- [x] Timestamp human format ("X jam lalu", "X hari lalu") via Carbon `diffForHumans()`
- [x] Module card → opens `/academy/learn/{slug}` (same tab)
- [x] Material card → opens `/academy/learn/{slug}/{id}` (same tab)
- [x] Article card → opens `/blog/{public_id}/{slug}` (new tab via target="_blank")
- [x] Empty state UI works if no published content exists
- [x] Active state highlight on Announcements nav link when on the page

### Tech Debt / Future Enhancements (Phase F follow-ups)

- **Unread/read state tracking**: Currently every member sees same feed. Could add `member_announcement_reads` pivot to track which items each member has seen, show "NEW" badge on unread items.
- **Filter tabs**: Add Module/Material/Article tabs if feed grows beyond ~50 items (currently mixed feed sufficient).
- **Email digest**: Weekly summary of new content emailed to members. Builds on this feed data.
- **Push notification on new content**: Server-Sent Events or polling to alert active members. Out of scope.
- **Per-item subtitle for articles**: Use first paragraph of `content` (strip HTML, truncate). Currently no subtitle for article cards.
- **Search/filter by date range**: For larger feeds, jump-to-month UI.
- **Bookmarking**: Save items to read/watch later.

### Rollback Procedure

```bash
cd /home/digimay1/digimaya_app

# 1. Remove route entry from routes/web.php
# (manually delete the 2 lines added for /academy/announcements)

# 2. Remove controller method
# (manually delete announcements() method + 2 imports from LearningController.php)

# 3. Revert layout to dropdown-only Certificates
# (restore academy-layout.blade.php from git or manual edit)

# 4. Delete view
rm resources/views/academy/announcements.blade.php

# 5. Clear caches
php artisan view:clear
php artisan route:clear
```

Note: `.bak` files from Phase F session were cleaned 22 May 2026 as part of wrap-up.

### References

- Phase E Tier System (Section 17, this file)
- Lead/Client/Invoice index filter pattern (used implicitly via familiar Eloquent + Carbon patterns)
- Laravel 10 Manual Paginator: https://laravel.com/docs/10.x/pagination#manually-creating-a-paginator

## 19. PHASE G — MEMBER FOOTER (22 May 2026)

**Status**: COMPLETE
**Duration**: 1 session, single patch + 1 spacing hotfix
**Owner**: Renra Sedoya + Claude

### Scope

Add footer to member-facing pages (`/academy/*`) with copyright, legal/info links, and "Send feedback" CTA to WhatsApp admin. Footer sticky to bottom of viewport when content is short.

### Files Modified

**Layout** (1):
- `resources/views/components/academy-layout.blade.php` — added `<footer>` element below `<main>`, body changed to `flex flex-col min-h-screen` for sticky footer, main wrapped with `flex-1`.

### Footer Structure

**Container**: `border-t border-gray-100 bg-white mt-12` — separator from main content, white background consistent with header.

**Layout**: Two-section flex (left + right) with responsive stack on mobile.

**Left section**:
- Copyright: `©{{ date('Y') }} Digimaya` (auto-update year via PHP)
- 4 inline links (text-brand, hover underline):
  - Privacy Policy → `route('privacy')` → `/privacy`
  - Terms of Service → `route('terms')` → `/terms`
  - About → `route('about')` → `/about`
  - Contact → `route('public.contact.show')` → `/contact`

**Right section**:
- Outlined button "Send feedback about Digimaya Academy" with chat icon
- Opens `https://wa.me/6285213228692?text={encoded}` in new tab
- Pre-fill text: "Halo Digimaya, saya mau kasih feedback tentang Academy."

### Sticky Footer Pattern

Trio of classes required for footer to stick to bottom when content is short, while still pushed down naturally when content is long:

```html
<body class="font-sans antialiased bg-gray-50 flex flex-col min-h-screen">
    <header>...</header>
    <main class="flex-1">{{ $slot }}</main>
    <footer>...</footer>
</body>
```

- `body`: `flex flex-col min-h-screen` — flex column, minimum height = viewport
- `main`: `flex-1` — grows to fill available space, pushing footer down
- `footer`: no special class needed (sits at end of flex container)

**Common mistake**: using `mt-auto` on footer alone without flex parent — does nothing. Or using `position: fixed` — causes content overlap.

### Decision Log

| Question | Answer | Rationale |
|---|---|---|
| Footer visible in public layout too? | No, member layout only | Public site has different footer needs; keep changes scoped |
| Privacy/Terms/About/Contact — new pages? | No, link to existing routes | All 4 pages already exist; just need link |
| Feedback CTA target | WhatsApp with pre-filled text | Consistent with upgrade page CTA pattern (low friction) |
| Year display | Dynamic via `date('Y')` | No manual update needed each year |

### Patterns Established

1. **Sticky footer via flexbox** — body `flex flex-col min-h-screen` + main `flex-1` + footer (no class) = footer stays at viewport bottom when content short, naturally pushed down when content long.

2. **Footer CTA to WhatsApp** — pattern for "feedback", "support", "contact admin" buttons: outlined button + icon + label, target `_blank`, URL via `config('digimaya.contact.whatsapp_wa_url') . '?text=' . rawurlencode($text)`.

3. **Responsive flex stack** — `flex flex-col md:flex-row md:items-center md:justify-between` for footer two-column layout that stacks vertically on mobile.

### Lessons Learned

**Lesson #17: Tailwind responsive prefix classes (`md:`, `lg:`) require backslash-escape grep**

When auditing whether `md:mb-0` or `md:mr-6` compiled in `public/css/tailwind.css`, naive grep `\.md:mb-0\b` returns 0 matches because Tailwind escapes `:` to `\:` in CSS output (`.md\:mb-0`).

**Wrong pattern** (false negative):
```bash
grep -cE "\.md:mb-0\b" public/css/tailwind.css   # returns 0
```

**Correct pattern**:
```bash
grep -cE "\.md\\\\:mb-0\b" public/css/tailwind.css   # returns 1 if compiled
```

Or simpler: search for the class itself without the leading dot anchor:
```bash
grep -c "md\\\\:mb-0" public/css/tailwind.css   # returns count
```

**SOP**: For responsive classes (anything with `:`), don't rely on grep audit — trust the browser visual instead. If layout looks correct, the class is compiled. Grep audit is only reliable for basic classes without prefixes.

This is distinct from Lesson #16 (Tailwind JIT missing fresh classes) — Lesson #16 is about classes genuinely not compiled, Lesson #17 is about audit script lying about compiled classes.

### Tech Debt Added

None. Phase G shipped clean.

### Validation Checklist

- [x] Body class updated: `flex flex-col min-h-screen`
- [x] Main wrapper has `flex-1`
- [x] Footer renders with copyright + 4 links + feedback button
- [x] All 4 links navigate to correct routes (privacy/terms/about/contact)
- [x] Feedback button opens WhatsApp in new tab with pre-filled text
- [x] Footer visible on all `/academy/*` pages (dashboard, announcements, upgrade, module, material, profile)
- [x] Sticky bottom behavior: footer stays at viewport bottom when content is short
- [x] Sticky bottom behavior: footer pushed down naturally when content is long (scroll past it)
- [x] Mobile responsive: links wrap/stack vertically, button moves below
- [x] Hover states work (links underline, button border changes to brand color)

### Rollback Procedure

```bash
cd /home/digimay1/digimaya_app

# Restore academy-layout.blade.php from git or manual edit:
# 1. Remove <footer>...</footer> block + @php config import block above it
# 2. Remove "flex-1" class from <main>
# 3. Remove "flex flex-col min-h-screen" from <body>, keep "font-sans antialiased bg-gray-50"

php artisan view:clear
```

Note: `.bak` file from Phase G session was cleaned 22 May 2026 as part of wrap-up.

### References

- Phase F Announcements Feed (Section 18, this file) — used same WhatsApp CTA pattern
- Phase E Tier System (Section 17, this file) — used same `config('digimaya.contact.whatsapp_wa_url')` config key
- CSS-Tricks Sticky Footer (flexbox method): https://css-tricks.com/couple-takes-sticky-footer/

---