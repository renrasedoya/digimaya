# Digimaya CRM — System Module Notes

> **Pair with**: `notes-general.md`
> **Scope**: Users, Roles, Activity Log, Settings, Issue Categories, Authentication
> **Audience access**: super_admin only (mostly), admin (limited)

---

## 1. PHILOSOPHY

System module = **administrative & meta-management** of the application itself. NOT business operations. This is where you configure WHO can access WHAT, audit WHO did WHAT, and manage taxonomies.

**Principle**: System is the foundation. Get this wrong → everything downstream breaks. Be conservative with changes here.

---

## 2. ROLE STAKEHOLDERS

| Sub-module | super_admin | admin | Other |
|---|---|---|---|
| Users (CRUD admin users) | Full | Full | NO |
| Activity Log viewer | Full | Full | NO |
| Issue Categories | Full | Read | NO |
| Settings | Full | Full | NO |

**Note**: Some System sub-modules are super_admin-exclusive (mis. dangerous Settings changes).

---

## 3. SUB-MODULES OVERVIEW

| Sub-module | Purpose |
|---|---|
| Users | Manage admin users, role assignment, AM↔Advertiser hierarchy |
| Activity Log | Read-only audit log of all model changes |
| Issue Categories | Master data for Project Reports (managed here, used in Operations) |
| Settings | Key-value app config (invoice prefix, company info, etc.) |
| Authentication | Login, registration, password reset (Breeze defaults) |

---

## 4. USER MANAGEMENT

### Data Model: `users`
```
id, name, email (unique), password (hashed),
email_verified_at (datetime, nullable),
role enum('super_admin','admin','marketing','account_manager','advertiser'),
parent_am_id (FK users, nullable — only used when role=advertiser),
is_active (boolean default true),
last_login_at (datetime, nullable),
remember_token, timestamps
```

**FK behavior**:
- `parent_am_id` → nullOnDelete (if AM deleted, advertiser's parent becomes null but record preserved)

### Casts
```php
protected $casts = [
    'email_verified_at' => 'datetime',
    'last_login_at' => 'datetime',
    'is_active' => 'boolean',
    'password' => 'hashed',
    'parent_am_id' => 'integer',  // CRITICAL Bug 4 fix
];
```

### Role Constants
```php
public const ROLE_SUPER_ADMIN = 'super_admin';
public const ROLE_ADMIN = 'admin';
public const ROLE_MARKETING = 'marketing';
public const ROLE_ACCOUNT_MANAGER = 'account_manager';
public const ROLE_ADVERTISER = 'advertiser';

public const ROLES = [
    self::ROLE_SUPER_ADMIN => 'Super Admin',
    self::ROLE_ADMIN => 'Admin',
    self::ROLE_MARKETING => 'Marketing',
    self::ROLE_ACCOUNT_MANAGER => 'Account Manager',
    self::ROLE_ADVERTISER => 'Advertiser',
];
```

### Helper Methods
```php
isSuperAdmin(): bool
isAdmin(): bool          // includes super_admin? Or strict admin only? Check actual implementation
isMarketing(): bool
isAccountManager(): bool
isAdvertiser(): bool

hasAnyRole(array $roles): bool
hasRole(string $role): bool

role_label accessor — returns "Super Admin", "Account Manager", etc. (Title Case)
```

### Relations
```php
parentAm(): BelongsTo (User, foreign key parent_am_id)
advertisers(): HasMany (User, where parent_am_id = this.id)
managedClients(): HasMany (Client, where account_manager_id = this.id)
assignedProjects(): HasMany (Project, where advertiser_id = this.id)
```

### Scopes
```php
scopeActive() — where is_active = true
scopeByRole($role) — filter by role
```

---

## 5. USER CRUD (Admin Panel)

### Path: `/admin/users`
**Controller**: `Admin\UserController`

### Index Page
- Filter: role dropdown, is_active toggle, search (name/email)
- Per-row badge: role with distinct color
  - super_admin → purple
  - admin → blue
  - marketing → amber
  - account_manager → indigo
  - advertiser → cyan
- Show parent_am name for advertiser rows
- Actions: Edit, Toggle Active

### Create Page
**Form fields**:
- Name (required)
- Email (required, unique)
- Password (required on create)
- Role (required, dropdown from ROLES const)
- Parent AM (conditionally shown if role=advertiser via Alpine `x-show`)
- Is Active (default true)

**Conditional UI**:
```blade
<select name="role" x-model="selectedRole">
    @foreach(\App\Models\User::ROLES as $key => $label)
        <option value="{{ $key }}">{{ $label }}</option>
    @endforeach
</select>

<div x-show="selectedRole === 'advertiser'" x-cloak>
    <x-input-label for="parent_am_id" value="Account Manager *" />
    <select name="parent_am_id">
        <option value="">-- Select AM --</option>
        @foreach($accountManagers as $am)
            <option value="{{ $am->id }}">{{ $am->name }}</option>
        @endforeach
    </select>
</div>
```

### Edit Page
- Same form, password optional (leave blank to keep existing)
- Role can be changed (with caution — affects existing relationships)

---

## 6. AUTHENTICATION & AUTHORIZATION

### Auth Stack
- **Laravel Breeze** for login/register/password reset
- **Laravel Sanctum** for API tokens (currently unused but available)

### Login Flow
1. User → `/login` → enter email + password
2. `LoginRequest::authenticate()` runs
3. Standard Auth::attempt() check
4. **Custom check** added: `if (!$user->is_active) throw ValidationException` with message "Akun ini sedang non-aktif. Hubungi admin untuk reaktivasi."
5. Update `last_login_at`
6. Redirect to dashboard

### Password Hashing
- `password` field: `'hashed'` cast (Laravel 10+ feature)
- Auto-hashes on save, no need for manual `bcrypt()` calls
- DO NOT hash twice (Laravel handles it)

### Registration
- Public registration page exists (Breeze default)
- **Recommendation**: Either disable public registration OR restrict to specific email domains (admin invite-only)
- New registrations default to role TBD — check if there's a default role assignment

### Password Reset
- Standard Breeze flow: forgot-password → email link → reset-password
- Email driver: Gmail SMTP (configured in `.env`)

### Authorization (Role Check)
**Middleware**: `EnsureUserHasRole` (alias `role`)

Usage in routes:
```php
Route::middleware('role:super_admin,admin')->group(function () {
    // routes accessible only to super_admin or admin
});

Route::middleware('role:super_admin,admin,account_manager,advertiser')->group(function () {
    // Operations module routes
});
```

**Middleware logic**: Get auth user, check if their role is in the comma-separated list. If not, abort 403.

---

## 7. ACTIVITY LOG

### Package: `spatie/laravel-activitylog`

### Data Model: `activity_log`
```
id, log_name, description, subject_type (model class), subject_id,
causer_type (User class), causer_id (acting user id),
properties (JSON — old/new values), batch_uuid (groups related changes),
event (created/updated/deleted), created_at, updated_at
```

### Models with Activity Log
- `Lead`
- `Client`
- `Invoice`
- `Project`
- `ProjectReport`

### Standard Pattern in Models
```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Xxx extends Model {
    use LogsActivity;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['field1', 'field2', 'status'])
            ->logOnlyDirty()           // only log changed fields
            ->dontSubmitEmptyLogs()    // skip if no fields changed
            ->useLogName('xxx');       // custom log name (mis. 'project', 'lead')
    }
}
```

### Auto-Capture
- `causer`: Automatically set to authenticated user
- `subject`: The model being changed
- `properties`: Diff of old vs new values for fields in `logOnly()`
- `event`: Auto-detected (created/updated/deleted)

### Viewing Logs
**Path**: `/admin/activity-log`  
**Controller**: `Admin\ActivityLogController` (read-only)

**UI**:
- Paginated list (newest first)
- Filter: model type (subject_type), event, causer (user), date range
- Each row: timestamp, causer, action description, subject link
- Click row → expand to show JSON properties (old vs new)

### Performance Considerations
- Activity log table grows fast — implement retention policy
- Index on `subject_type + subject_id` (lookup model history)
- Index on `causer_id + created_at` (user activity timeline)

---

## 8. ISSUE CATEGORIES (Master Data)

**Note**: Although Issue Categories support the Operations module, management UI lives in System (because it's master data taxonomy).

### Path: `/admin/issue-categories`
**Controller**: `Admin\IssueCategoryController`

### Detailed in Operations Notes
See `notes-operations.md` Section 3-4 for full details on:
- Schema (`issue_categories` + `issue_sub_categories`)
- 9 categories × 27 sub-categories taxonomy
- Idempotent seeder (`IssueCategorySeeder`)
- Smart sync logic (update existing, create new, soft-deactivate missing)

### Access in System Module
- Sidebar: System dropdown → "Issue Categories" link
- Visible only to super_admin (master data control)
- CRUD with repeatable Alpine sub-category form

---

## 9. SETTINGS (Key-Value Store)

### Data Model: `settings`
```
id, key (unique), value (text), label, description, group (string, nullable), timestamps
```

### Usage Pattern
```php
// Get setting
$prefix = Setting::get('invoice_number_prefix', 'DGMY');  // default 'DGMY' if not set

// Set setting
Setting::set('invoice_number_prefix', 'NEW');

// All settings
$all = Setting::all()->pluck('value', 'key');
```

### Common Settings Keys
- `invoice_number_prefix` (default: `DGMY`)
- `company_name`
- `company_address`
- `company_phone`
- `company_email`
- `company_website`
- `tax_default_rate` (mis. `11` for 11% PPN)
- `invoice_due_days_default` (mis. `14`)

(Actual list depends on what's seeded/configured.)

### Admin UI
**Path**: `/admin/settings`  
**Controller**: `Admin\SettingController`

- Single-page key-value editor
- Grouped by `group` field (mis. 'company', 'invoice', 'email')
- Each setting: label, current value (input), description (tooltip)
- Save All button

---

## 10. SIDEBAR/NAV STRUCTURE (System Dropdown)

```
System ▼
├── Users
├── Activity Log
├── Issue Categories     (super_admin only — master data control)
└── Settings
```

Visible to: super_admin (all 4 items), admin (Users + Activity Log + Settings; not Issue Categories)

Active state: `routeIs('admin.users.*', 'admin.activity-log.*', 'admin.issue-categories.*', 'admin.settings.*')`

---

## 11. CONTROLLERS

### `Admin\UserController`
- Resource CRUD
- Filter: role, is_active, search
- Validation: email unique, password min 8 chars, role in ROLES enum, parent_am_id required if role=advertiser

### `Admin\ActivityLogController`
- Index only (read-only)
- Filter: subject_type, event, causer_id, date range

### `Admin\IssueCategoryController`
- Resource CRUD (except show)
- Smart sub-category sync logic (handle add/update/deactivate atomically)

### `Admin\SettingController`
- Index (single page editor)
- Update (bulk save all key-value pairs)

### `Auth\*Controller` (Breeze defaults)
- Login, Register, Password Reset, Email Verification

---

## 12. ROUTES

```php
// Admin System routes (mostly super_admin or admin)
Route::resource('users', Admin\UserController::class);
Route::get('/activity-log', [Admin\ActivityLogController::class, 'index'])->name('activity-log.index');
Route::resource('issue-categories', Admin\IssueCategoryController::class)->except(['show']);
Route::get('/settings', [Admin\SettingController::class, 'index'])->name('settings.index');
Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');

// Auth routes (Breeze)
Auth::routes();  // login, register, password reset, email verify
```

---

## 13. VIEWS STRUCTURE

```
resources/views/
├── admin/
│   ├── users/
│   │   └── {index, create, edit}.blade.php
│   ├── activity-log/
│   │   └── index.blade.php
│   ├── issue-categories/
│   │   └── {index, create, edit, _form}.blade.php
│   └── settings/
│       └── index.blade.php (single-page editor)
└── auth/
    ├── login.blade.php
    ├── register.blade.php
    ├── forgot-password.blade.php
    ├── reset-password.blade.php
    ├── confirm-password.blade.php
    └── verify-email.blade.php
```

---

## 14. KEY VALIDATIONS

### User Create
- `name`: required, string, max 255
- `email`: required, email, unique:users
- `password`: required, min 8, confirmed (password_confirmation field)
- `role`: required, in ROLES const keys
- `parent_am_id`: required_if role=advertiser, exists in users with role=account_manager
- `is_active`: boolean

### User Update
- `password`: nullable (leave blank to keep), if provided: min 8, confirmed
- Email change: still unique, ignore current user's record (`unique:users,email,{id}`)
- Role change: allowed but caution — clear parent_am_id if role changed away from advertiser

### Issue Category Create/Update
- `name`: required, unique
- `slug`: required, unique, regex `/^[a-z0-9-]+$/`
- `display_order`: integer
- `is_active`: boolean
- `sub_categories`: array (can be empty)
- `sub_categories.*.name`: required for each
- `sub_categories.*.slug`: required, unique within category
- `sub_categories.*.display_order`: integer

### Settings Update
- `settings`: array of key-value pairs
- Each value: type-specific validation based on key (mis. tax_default_rate must be numeric 0-100)

---

## 15. KEY GOTCHAS

### Type Cast Critical
**`parent_am_id` MUST be cast as integer** in User model `$casts`. Without cast → Eloquent returns string → strict comparison fails → hierarchy validation broken (Bug 4 family).

Same applies to all FK fields. ALWAYS cast.

### `is_active=false` vs Hard Delete
NEVER hard delete users. Always toggle `is_active=false` instead. Reason:
- Preserves historical FK references (created_by, account_manager_id, etc.)
- Activity log entries remain valid
- Easy reactivation if needed

### Role Change Cascade Risk
If you change a user's role from `account_manager` to something else:
- Their managed clients' `account_manager_id` STILL points to this user
- Clients become "orphaned" (no AM)
- Manual cleanup needed: reassign clients before role change, OR set `account_manager_id = null` for affected clients

If you change from `advertiser`:
- Their assigned projects' `advertiser_id` still points
- Same orphan risk
- UI should warn about this

**Recommendation**: Build pre-flight check in role change action (warn admin about orphan risk).

### Hierarchy Constraint Validation
- Advertiser's `parent_am_id` MUST point to user with role=`account_manager`
- Validation in UserController::store/update
- If AM is deleted (or role changed), advertiser's parent_am_id may dangle (currently `nullOnDelete` FK)

### Activity Log Log Name Collision
Each model uses `useLogName('xxx')` to differentiate. Avoid duplicate names — leads to confusing log queries.

Current names: `lead`, `client`, `invoice`, `project`, `project_report`.

### Activity Log Performance
Table grows fast in heavy-usage app. Consider:
- Retention policy (mis. delete logs > 1 year)
- Periodic archive to file
- Pagination on viewer page (default 50 per page)

### Password Reset Token Expiry
Default Laravel: token expires after 60 minutes (configurable in `config/auth.php`).

If user reports "reset link doesn't work":
- Check token expiry
- Resend new token

### Email Verification
- Currently NOT enforced (Breeze default state)
- If enforced (`MustVerifyEmail` interface on User), users with unverified email blocked from app
- Consider enforcing for security

### Settings Cache
Settings reads frequently. Consider caching:
```php
public static function get($key, $default = null) {
    return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
        return self::where('key', $key)->value('value') ?? $default;
    });
}
```

When updating: clear cache (`Cache::forget("setting.{$key}")`).

### Auth User in Subqueries
Always wrap auth checks: `auth()->user()->isAdmin()` (auth()->user() may be null in edge cases like queue workers).

### Middleware Order Matters
Standard order: `auth → role → prevent.duplicate.admin`. If reordered (`role` before `auth`), role check fails because no auth user yet.

### Issue Category Soft Active
Cannot hard-delete `issue_sub_categories` already used in `project_reports` (FK constraint with nullOnDelete). Set `is_active=false` instead. Form filters out inactive from dropdown but historical reports retain valid reference.

### Settings Type Coercion
All settings stored as TEXT. When reading numeric values, manually cast:
```php
$rate = (float) Setting::get('tax_default_rate', 0);
$days = (int) Setting::get('invoice_due_days_default', 14);
```

### User Email Change Notification
When admin updates a user's email, no notification sent by default. Consider adding email notification to old AND new email for security (audit trail).

### Last Login Tracking
`last_login_at` updated in LoginRequest::authenticate(). May want to add `last_login_ip` field for security audit (currently not tracked).

### Inactive AM Hierarchy
If AM marked `is_active=false`:
- Their advertisers can still log in (own `is_active` independent)
- Their clients are still assigned to this AM (no auto-reassign)
- Operations module access for that AM blocked at login level

Recommend: when deactivating AM, also reassign clients to another AM via separate flow.

### Settings Backup
Settings table is critical (invoice prefix, company info, etc.). Include in regular DB backups. Currently no separate backup.
