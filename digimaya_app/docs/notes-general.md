# Digimaya CRM — General Notes

> **Always upload first** in any new Claude chat session.
> **Purpose**: Base context cartridge — stack, conventions, dev rules.
> **Pair with**: `notes-{module}.md` for specific feature context.

---
## 1. PROJECT IDENTITY

- **Owner**: Renra Sedoya (founder)
- **Company**: Digimaya — Google Premier Partner agency, Indonesia
- **App role**:
  - Public website (homepage, blog, contact, case studies, testimonials)
  - Internal CRM (Lead → Client → Invoice lifecycle, Follow-up tracking)
  - Operations workspace (Account Manager + Advertiser project reporting)
- **Domain**: digimaya.com (production)
- **Server path**: `/home/digimay1/digimaya_app/`
- **Hosting**: DomaiNesia shared hosting

---

## 2. TECH STACK

| Layer | Tech | Version |
|---|---|---|
| Framework | Laravel | 10 |
| PHP | PHP | 8.1 |
| Frontend CSS | Tailwind CSS | 3.4.19 (JIT enabled) |
| Frontend JS | Alpine.js | 3.x |
| DB | MySQL | 8 |
| Auth | Laravel Breeze + Sanctum | — |
| PDF | barryvdh/laravel-dompdf | — |
| Sanitizer | mews/purifier | — |
| Activity Log | spatie/laravel-activitylog | — |
| Build | Vite | 5 |
| Node | Node.js | 19.9.0 (cPanel Setup Node.js App) |

### Build Commands
```bash
tw-build                            # Tailwind rebuild (alias: npx tailwindcss -i resources/css/app.css -o public/css/tailwind.css --minify)
composer dump-autoload -o           # Refresh autoload classmap
php artisan view:clear              # Clear compiled views
php artisan cache:clear             # Clear app cache
php artisan config:clear            # Clear config cache
php artisan route:clear             # Clear route cache
```

---

## 3. AUTH & ROLES (5 ROLES)

| Role | Description | Key Methods |
|---|---|---|
| `super_admin` | Full access (Renra) | `isSuperAdmin()` |
| `admin` | Operational management | `isAdmin()` |
| `marketing` | Marketing module only | `isMarketing()` |
| `account_manager` | AM workflow + assigned clients | `isAccountManager()` |
| `advertiser` | Advertiser workflow + assigned projects | `isAdvertiser()` |

### Role Hierarchy
- **AM ↔ Advertiser**: 1 AM has many Advertisers via `users.parent_am_id` FK
- **Client ↔ AM**: Client assigned to 1 AM via `clients.account_manager_id` FK
- **Project ↔ Advertiser**: Project assigned to 1 Advertiser via `projects.advertiser_id` FK
- **Hard rule**: Advertiser's `parent_am_id` MUST equal Client's `account_manager_id` (enforced in ProjectController)

### Login Enforcement
- `users.is_active` boolean flag — login rejected if false (handled in `LoginRequest::authenticate()`)
- Inactive users preserve historical data, no hard delete

### Helper Methods on User Model
- `isSuperAdmin()`, `isAdmin()`, `isMarketing()`, `isAccountManager()`, `isAdvertiser()`
- `hasAnyRole(array $roles)`
- `role_label` accessor (returns Title Case)
- Constants: `ROLE_SUPER_ADMIN`, `ROLE_ADMIN`, `ROLE_MARKETING`, `ROLE_ACCOUNT_MANAGER`, `ROLE_ADVERTISER`

---

## 4. ARCHITECTURE OVERVIEW

### Lifecycle Flow
```
[Public Visitor] → /contact form → Lead Created
        ↓
   [Lead]   new → contacted → screened → promoted/disqualified
        ↓ (LeadPromotionService.promote(Lead))
   [Client] prospect → active → inactive → churned
        ↓ (manual via Edit Client)
   [Project] (created by AM) active → paused → completed
        ↓ (assigned to Advertiser)
   [ProjectReport] submitted by Advertiser, reviewed by AM, acknowledged by Advertiser
                   pending_review → pending_ack → acknowledged (3-state lifecycle, Phase 14.6)
   [Income/Invoice/Followup tracked per Client]
```

### Top-Level Modules (Top Nav Order)
```
Dashboard | Marketing ▼ | CRM ▼ | Operations ▼ | Finance ▼ | Components ▼ | System ▼
```

### Directory Structure (key folders)
```
app/
├── Http/Controllers/      (Admin/* + Public/* + Auth/*)
├── Http/Middleware/       (PreventDuplicateAdminSubmits, EnsureUserHasRole)
├── Http/Requests/Auth/    (LoginRequest with is_active check)
├── Mail/                  (NewLeadNotification)
├── Models/                (Eloquent models, all use SoftDeletes for Lead/Client/Invoice/Project/ProjectReport)
├── Observers/             (ClientObserver — auto-fill client_since, log status history)
└── Services/              (InvoiceNumberGenerator, LeadPromotionService)
database/
├── migrations/            (chronological migrations, all Ran)
└── seeders/               (IssueCategorySeeder for master data)
resources/views/
├── admin/                 (admin panel, organized per module)
├── public/                (public-facing pages)
├── auth/                  (Breeze auth views)
├── components/            (reusable Blade components)
├── emails/                (email templates)
└── layouts/               (admin.blade.php, app.blade.php, public.blade.php)
routes/web.php             (single routes file, all routes)
```

---

## 5. CONVENTIONS

### UI Copy Style (Indonesian)
- Use **kamu** not "Anda"
- **Title Case** for buttons + nav labels (NOT ALL CAPS)
- Casual, no agency speak
- NO em dashes, arrows, plus/equals signs, emoji in production text
- NO AI-sounding phrases

### Code Style (English)
- PSR-12 PHP standards
- Methods: camelCase
- Constants: UPPER_CASE
- DB columns: snake_case
- Models: singular PascalCase (`Project`, `IssueCategory`)
- Tables: plural snake_case (`projects`, `issue_categories`)

### Filter UI Pattern (consistent across all index pages)
**Used by**: leads, clients, projects, project-reports (in projects show page), incomes, expenses, invoices

```blade
<form method="GET" action="{{ route('...') }}" class="mb-6 flex flex-wrap gap-2">
    <select name="month" class="border ... rounded-md px-3 py-2 text-sm">
        <option value="0">All Months</option>
        @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ ($month ?? 0) == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
        @endforeach
    </select>
    <select name="year">
        @for($y = now()->year; $y >= now()->year - 3; $y--)
            <option value="{{ $y }}" {{ ($year ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>
    {{-- ... other filters: status dropdown with count, search box, etc. ... --}}
    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Apply</button>
    <a href="{{ route('...') }}" class="px-4 py-2 text-sm text-gray-600">Reset</a>
</form>
```

### Pagination Standard
- All paginated lists: `<div class="mt-12">{{ $items->links() }}</div>`
- Default: 15 per page (Lead, Client, Project, Income, Expense, etc.)
- Sidebar contexts (mis. Project Reports inside Project show right column): also 15 per page now (consistent across all list views)

### Sort Order Convention
- All index pages default: `orderBy('created_at', 'desc')` (newest first)
- Exception: `Client::search()` autocomplete = alphabetical by `business_name`

### Status Filter UI
- Dropdown with count: `Status (count)` — NOT tabs
- All Statuses option always at top with total count

### Form Pattern: Inline Expand (Followup-style)
**Used by**: Client Followup (in Client show), Project Reports (in Project show)

```blade
<div x-data="{ open: false }">
    <button @click="open = !open">+ Add Item</button>
    <div x-show="open" x-cloak>
        <form method="POST" action="...">
            @csrf
            ... fields ...
        </form>
    </div>
</div>
```

### Edit/Action Pattern: Single Global Modal + Dispatch
```blade
{{-- Trigger button (in list item) --}}
<button @click="
    $dispatch('open-modal', 'edit-item');
    $dispatch('load-edit-item', { id: {{ $item->id }}, field1: ..., field2: ... });
">Edit</button>

{{-- Single modal at bottom of page --}}
<div x-data="{ id: null, field1: '', field2: '' }"
     x-on:load-edit-item.window="id = $event.detail.id; field1 = $event.detail.field1; field2 = $event.detail.field2;">
    <x-modal name="edit-item">
        <form method="POST" :action="`{{ url('admin/items') }}/${id}`">
            @csrf
            @method('PUT')
            ... fields with x-model ...
        </form>
    </x-modal>
</div>
```

### Conditional Reveal with Alpine Watcher (Phase 14.8 Pattern)
**Used by**: Interested In field (admin + public forms), any enum + "other" freetext pattern.

**Pattern**: Local Alpine `x-data` scope wraps select + conditional freetext input. Watcher clears freetext when main select changes away from trigger value.

```blade
<div x-data="{
        mainValue: '{{ old('field', $model->field ?? '') }}',
        otherText: '{{ old('field_other', $model->field_other ?? '') }}',
        init() {
            this.$watch('mainValue', (val) => {
                if (val !== 'others') this.otherText = '';
            });
        }
     }">
    <select name="field" x-model="mainValue">
        <option value="">-- Select --</option>
        @foreach($options as $key => $label)
            <option value="{{ $key }}" {{ old('field', $model->field ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>

    <div x-show="mainValue === 'others'" x-cloak x-transition>
        <input type="text" name="field_other" x-model="otherText" maxlength="255">
    </div>
</div>
```

**Important**: Pair with backend defensive clear (controller or FormRequest `passedValidation()`) to prevent stale freetext when main field is not 'others'. Frontend watcher is UX only — backend always source of truth.

### Yellow Ring Action Card (Phase 14.8.1 Pattern)
**Used by**: Prospects Action Card in CRM Overview. Pattern for any dashboard KPI card that needs **conditional urgency signal**.

```blade
<a href="{{ route('...') }}"
   class="block bg-white rounded-lg shadow p-5 transition hover:shadow-md {{ $urgencyCount > 0 ? 'ring-1 ring-yellow-300' : '' }}">
    <div class="flex items-center gap-2">
        <span class="inline-block w-2 h-2 rounded-full bg-yellow-500"></span>
        <div class="text-sm font-medium text-gray-500">Label</div>
    </div>
    <div class="mt-2 text-3xl font-bold {{ $urgencyCount > 0 ? 'text-yellow-700' : 'text-gray-900' }}">
        {{ number_format($total) }}
    </div>
    <div class="mt-1 text-xs text-gray-500">
        {{ $breakdownA }} ... · {{ $breakdownB }} ...
        @if($urgencyCount > 0)
            · <span class="text-yellow-700 font-medium">{{ $urgencyCount }} need action</span>
        @endif
    </div>
</a>
```

**Key principles**:
- Entire card wrapped in `<a>` tag → fully clickable area
- Ring + number color BOTH conditional on urgency (not card visibility — card always shown)
- Sub-text breakdown gives context, urgency segment highlighted in yellow
- `hover:shadow-md transition` for visual feedback

---

## 6. CRITICAL: TYPE CAST RULE (Bug Family)

**Rule**: ALL FK numeric columns + numeric polymorphic IDs MUST be cast as `'integer'` in model `$casts` array.

**Why**: Without cast, Eloquent returns column as string (`"10"`). Strict comparison `!==` against integer (`10`) fails. Many bugs in `account_manager_id`, `parent_am_id`, etc.

**Applied to**:
- `Client::$casts` — `'account_manager_id' => 'integer'`
- `User::$casts` — `'parent_am_id' => 'integer'`
- `Project::$casts` — `'client_id' => 'integer'`, `'advertiser_id' => 'integer'`
- `ProjectReport::$casts` — `'project_id'`, `'submitted_by'`, `'issue_category_id'`, `'issue_sub_category_id'`, `'reviewed_by'` all `'integer'`

**When adding new FK column**: ALWAYS add to `$casts` array. Default to `'integer'`.

**Diagnostic** (when validation fails unexpectedly):
```php
$check = ($modelA->fk_id) !== ($modelB->id);
echo gettype($modelA->fk_id) . " vs " . gettype($modelB->id);
// If types differ -> add cast
```

---

## 7. DEV WORKFLOW (CRITICAL)

### File Editing Rules
1. **NEVER paste PHP/Blade content directly to bash** — bash interprets `$variable`, `{{ }}`, etc.
2. **Edit methods**:
   - **cPanel File Manager** → "Edit" button (best for files >200 lines)
   - **File-based heredoc + Python read+replace** (preferred for complex edits with PHP namespaces, see Bug 8)
   - **Python heredoc** (`python3 <<'PYEOF' ... PYEOF`) for surgical edits with verification (`if count(old) == 1: replace`)
   - **Bash heredoc** (`cat > file <<'EOF'`) ONLY for new file creation (no `$` substitution risk if quoted)
   - **AVOID `sed`** for files with `$` or `{{ }}` (escape hell)

### Standard Edit Pattern (Python heredoc)
```bash
cd ~/digimaya_app && python3 <<'PYEOF'
import sys

path = "path/to/file.php"
with open(path, "r") as f:
    content = f.read()

old = """exact_existing_content"""
new = """new_content"""

count = content.count(old)
if count != 1:
    print(f"ABORT: pattern found {count} times, expected 1")
    sys.exit(1)

content = content.replace(old, new)
with open(path, "w") as f:
    f.write(content)

print("OK: change applied")
PYEOF
```

### File-Based Heredoc Pattern (Preferred for Blade/PHP)
**When to use**: any edit where the OLD or NEW content contains PHP namespaces (`\App\Services\X`), Blade `{{ }}`, complex multi-line PHP code.

**Why**: Python triple-string embedded in PYEOF can break with `SyntaxError: (unicode error)` when content contains `\A`, `\U`, `\S` (Bug 8).

**Pattern**:
```bash
# Step 1: Write OLD and NEW content to separate files via bash heredoc (single-quoted EOF)
cat > /tmp/patch_X_old.txt <<'EOFPATCH'
... exact existing content ...
EOFPATCH

cat > /tmp/patch_X_new.txt <<'EOFPATCH'
... new content ...
EOFPATCH

# Step 2: Python reads files (no embedded PHP strings, no escape issues)
cd ~/digimaya_app && python3 <<'PYEOF'
import sys

target = "path/to/file.php"
with open(target, "r") as f:
    content = f.read()
with open("/tmp/patch_X_old.txt", "r") as f:
    old = f.read().rstrip("\n")
with open("/tmp/patch_X_new.txt", "r") as f:
    new = f.read().rstrip("\n")

count = content.count(old)
if count != 1:
    print(f"ABORT: pattern found {count} times, expected 1")
    sys.exit(1)

content = content.replace(old, new)
with open(target, "w") as f:
    f.write(content)

print("OK: applied")
PYEOF

# Step 3: Cleanup
rm /tmp/patch_X_*.txt
```

**Batch variant** (multiple patches in one Python execution): use a list of tuples `(label, target, old_file, new_file)` and iterate. Each iteration validates `count == 1` before write. Stops at first failure for safe rollback.

### Standard New File Pattern
- Files >200 lines → cPanel File Manager paste (faster, no escape headaches)
- Files <200 lines → bash heredoc with `<<'EOF'` (single quotes preserve `$`)

### Audit Before Editing — Use `awk` Numbered, NOT `cat`
**Pitfall**: `cat <file>` output in chat client / terminal can be MISLEADING for files with long lines. Multiple lines may visually collapse into one row due to wrapping, creating false impression of "missing line breaks" or "merged content".

**Symptoms**:
- "I see `</div>                        <div>`  on one line!" → actual file has `</div>\n<div>`, just rendered wrapped
- "Class `shadow-smpx-3` has no space" → actual file has `shadow-sm px-3`, line wrap artifact
- "Border has typo `border-red-400@else`" → actual file is fine, just visual wrap

**Reliable verification methods**:
1. **awk with line numbers** (PREFERRED):
   ```bash
   awk 'NR>=X && NR<=Y {printf "%4d| %s\n", NR, $0}' path/to/file.blade.php
   ```
   This shows exact line boundaries with line numbers. No ambiguity.

2. **cat -A** (show $ for line endings):
   ```bash
   sed -n 'X,Yp' file.blade.php | cat -A
   ```
   `$` marks end-of-line. But still subject to wrap rendering — use awk if uncertain.

3. **grep for specific pattern** (to verify presence, not absence):
   ```bash
   grep -c "exact_pattern" file.blade.php
   ```

**Rule of thumb**: when you THINK you spot a bug in a file, before fixing, verify with `awk` numbered output. Most "bugs" detected from `cat` output are false positives from rendering artifacts.

### Cache Driver
- **MUST use `CACHE_DRIVER=file` in .env** (not `database` — causes connection pool exhaustion)

### After ANY Code Change Touching:
- Routes → `php artisan route:clear`
- Config (.env, config/*) → `php artisan config:clear`
- Views (blade) → `php artisan view:clear`
- Class structure (use, namespace) → `composer dump-autoload -o`
- New Tailwind class → `tw-build`

### Tailwind Workflow
1. Edit blade with new class
2. Run `tw-build` (alias)
3. Hard refresh browser (Cmd+Shift+R)
4. If class missing → check `tailwind.config.js` `content` array

### Database Migration Workflow
1. `php artisan make:migration create_xxx_table` (or alter)
2. Edit migration file (Schema::create / Schema::table)
3. Update Model: `$fillable` + `$casts` BEFORE running migrate
4. Update Observer/Service/Controller logic that reference dropped fields
5. `php artisan migrate`
6. Verify via tinker: `\App\Models\Xxx::first()` to confirm field state

### Testing After Change
- Browser test the feature path (don't batch 10 changes then test)
- Run integration sanity via tinker: `Model::count()`, `Model::first()->relation`
- Check `php artisan route:list --name=xxx` for new routes

---

## 8. PATTERN BUG LIBRARY

### Bug 1: SoftDeletes + Unique Sequence Generator
**Symptom**: `SQLSTATE[23000]: Duplicate entry 'X' for key 'unique_*'`
**Cause**: Eloquent default skips soft-deleted records, but DB UNIQUE constraint applies to all rows including trashed.
**Fix**: Add `->withTrashed()` to generator query.
**Currently fixed in**: `InvoiceNumberGenerator::next()`
**Watch for**: Slug generators on `Client`, `Lead`, `BlogPost`, any model with SoftDeletes + unique field.

### Bug 2: DB Connection Pool Exhaustion
**Symptom**: `SQLSTATE[HY000] [2003] Can't connect to MySQL server (111)` intermittent
**Cause**: `CACHE_DRIVER=database` opens DB connection per cache lookup → pool exhausted.
**Fix**: `.env` set `CACHE_DRIVER=file` + `php artisan config:clear`

### Bug 3: Bash Variable Substitution
**Symptom**: PHP/Blade syntax errors after pasting code through bash terminal.
**Cause**: Bash interprets `$var` and command substitution.
**Fix**: Use Python heredoc for editing, quoted EOF (`<<'EOF'`) for new file creation.

### Bug 4 Family: Type Mismatch in FK Comparison
**Symptom**: Hierarchy validation fails despite values appearing equal (mis. "10" !== 10).
**Cause**: Eloquent returns FK column as string when not in `$casts`. Strict `!==` rejects.
**Fix**: Add `'fk_column' => 'integer'` to model `$casts`.
**Affected**: `Client.account_manager_id`, `User.parent_am_id`, all `Project.*_id` and `ProjectReport.*_id` (all fixed).
**Prevention**: When creating new FK, ALWAYS cast to integer in model.

### Bug 5: Alpine x-model + Dynamic x-for Options
**Symptom**: Pre-selected option in `<select>` not visually selected on page load (edit mode).
**Cause**: `<template x-for>` renders options AFTER Alpine init. x-model fails to sync.
**Fix**: Use `x-init` with `$nextTick` + force `select.value = initialValue` directly via DOM:
```blade
x-init="$nextTick(() => {
    if (initialSubCategoryId) {
        const sel = $el.querySelector('select[name=\'issue_sub_category_id\']');
        if (sel) sel.value = initialSubCategoryId;
        subCategoryId = initialSubCategoryId;
    }
});"
```

### Bug 6: Eager Load Partial Column Without Verifying Schema
**Symptom**: SQL error `Column not found: 1054 Unknown column 'X' in 'field list'` saat halaman load yang pakai eager load.
**Cause**: Eager load `with('relation:id,name')` assume kolom `name` exists, padahal di schema actual nama field beda (mis. `business_name` + `contact_name` di `leads`).
**Examples in this codebase**:
- `leads` table: NO `name` column — pakai `business_name` + `contact_name`
- `clients` table: pakai `business_name`
- `users` table: pakai `name` (only model yang punya `name`)

**Fix**: ALWAYS `Schema::getColumnListing('table_name')` via tinker sebelum eager load partial column. Atau ambil semua kolom (`with('relation')`) kalau ragu.

**Detection**: `php artisan tinker --execute='use Illuminate\Support\Facades\Schema; print_r(Schema::getColumnListing("leads"));'`

### Bug 7: Heredoc Paste Corruption with Unicode in Terminal SSH
**Symptom**: Long Python heredoc dipaste ke cPanel SSH terminal, file tidak ke-create, no error message, command berikutnya jalan (heredoc context ke-skip).
**Cause**: Multi-line heredoc dengan unicode chars (emoji ⚠️, 📅, caret ▼) + Blade syntax `{{ }}` + nested triple-quote string → terminal interpret salah, EOF marker tidak ke-deteksi.

**Reliable mitigations**:
1. **Sed dengan ANSI-C quoting** untuk unicode raw byte replacement (`.md` and `.blade.php` files):
   ```bash
   # Pattern: $'\xNN\xNN\xNN' interprets hex as raw bytes
   sed -i $'s/\xe2\x9a\xa0\xef\xb8\x8f //g' file.blade.php       # Remove ⚠️ (U+26A0 U+FE0F)
   sed -i $'s/\xe2\x96\xbc/REPLACEMENT/g' file.blade.php          # Replace ▼ (U+25BC)
   ```
   Common emoji bytes for reference:
   - ⚠️ = `e2 9a a0 ef b8 8f` (warning + variation selector)
   - 📅 = `f0 9f 93 85` (calendar)
   - 📆 = `f0 9f 93 86` (tear-off calendar)
   - ▼ = `e2 96 bc` (down-pointing triangle)
   - ▲ = `e2 96 b2` (up-pointing triangle)

2. **Verify file exists** sebelum execute Python script:
   ```bash
   ls -la /tmp/patch.py || echo "File not created"
   ```

3. **Avoid surrogate pair encoding** di Python: pakai `\U0001F4C5` (8-digit) BUKAN `\uD83D\uDCC5` (surrogate pair — fails with `UnicodeEncodeError: surrogates not allowed`).

4. **Last resort**: Edit langsung via cPanel File Manager (paling reliable untuk file dengan kombinasi unicode + Blade syntax).

### Bug 8: Python Embedded String + PHP Namespace Backslashes (Phase 14.8)
**Symptom**: Python heredoc fails with `SyntaxError: (unicode error) 'unicodeescape' codec can't decode bytes in position X-X: truncated \UXXXXXXXX escape` when patch content includes PHP namespaces (`\App\Services\X`).

**Cause**: Python regular string literals interpret `\A`, `\U`, `\S` (and other `\` sequences) as unicode escape sequences. `\U` specifically expects 8 hex digits after it. PHP namespaces like `\App\Services\UrlNormalizer` contain `\A` and `\U`, causing Python parser to choke.

**Why it appeared**: Phase 14.8 implementation required editing files referencing `\App\Models\Lead::INTERESTED_IN_OPTIONS` and similar. Embedding these strings in `python3 <<'PYEOF'` heredoc's triple-quoted Python strings triggered the error.

**Fix — File-based heredoc + Python read+replace pattern** (see Section 7 "File-Based Heredoc Pattern"):
1. Write OLD and NEW content to separate `/tmp/patch_X_*.txt` files via `cat <<'EOFPATCH'` (bash single-quoted, no Python parsing)
2. Python script reads files via `f.read()` — no escape interpretation at all
3. Replace + write back

**This pattern is now the preferred approach for any Blade/PHP edit** with potential namespace references. Use Python embedded triple-string ONLY for trivial edits with no `\` characters.

**Variant for Python literals** (if file-based is overkill):
- Use **raw strings** (`r"..."`) — disables escape interpretation
- Use **double backslash** (`\\App\\Services`) — explicit escape

**Detection signal**: If you see PHP namespaces, model FQCNs, or backslashes in patch content → reach for file-based heredoc, not embedded.

---

## 9. KEY MIDDLEWARE

### Custom Middleware
| Alias | Class | Purpose |
|---|---|---|
| `role` | `EnsureUserHasRole` | Role-based route guard. Usage: `middleware('role:super_admin,admin')` |
| `prevent.duplicate.admin` | `PreventDuplicateAdminSubmits` | 5s dedup window via cache (sha256 of user+route+payload). Frontend pair: auto-disable submit button + spinner. Opt-out: `data-no-disable` attribute. |

### Middleware Stack on Admin Routes
```php
Route::middleware(['auth', 'role:...', 'prevent.duplicate.admin'])->prefix('admin')->name('admin.')->group(...);
```

---

## 10. ACTIVITY LOG (spatie/laravel-activitylog)

### Standard Pattern in Models
```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Xxx extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['field1', 'field2', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('xxx');
    }
}
```

### Models with Activity Log
- `Lead`, `Client`, `Invoice`, `Project`, `ProjectReport`

### Viewing Logs
- Admin route: `/admin/activity-log` — read-only audit log viewer

---

## 11. EMAIL & NOTIFICATIONS

### Mail Driver
- Gmail SMTP (configured in `.env`)
- App password used (not regular password)
- DKIM + SPF configured at DNS level

### Mailables
- `NewLeadNotification` — sent to all User WHERE role IN (super_admin, admin, marketing) when public contact form submitted
- Plain text format
- Try-catch wrapped — email failure does NOT break form UX
- Idempotency: 30s dedup on `email + phone` (skip create + skip email if duplicate)

---

## 12. MASTER DATA TABLES (relatively static)

| Table | Purpose | Notes |
|---|---|---|
| `services` | Services offered for invoice line items | CRUD via admin |
| `expense_categories` | Expense categorization | CRUD via admin |
| `bank_accounts` | Bank accounts shown on invoice PDF | CRUD via admin |
| `issue_categories` | Project report issue taxonomy | 9 categories, seeded via `IssueCategorySeeder` |
| `issue_sub_categories` | Sub-categories per issue category | 27 subs, seeded |
| `settings` | Key-value app settings | mis. `invoice_number_prefix`, `company_name` |

---

## 13. COMMON TINKER SNIPPETS

### Type Debug
```php
echo gettype($var) . " value: " . var_export($var, true);
```

### DB Schema Inspect
```php
$cols = \DB::select("SHOW COLUMNS FROM table_name");
foreach ($cols as $c) echo $c->Field . " " . $c->Type . PHP_EOL;
```

### Model Sanity Check
```php
echo "Class: " . (class_exists("\App\Models\Xxx") ? "OK" : "FAIL") . PHP_EOL;
echo "Has relation: " . (method_exists(new \App\Models\Xxx(), "yyy") ? "OK" : "FAIL") . PHP_EOL;
```

### Common Sanity Suite
```bash
php artisan tinker --execute='
echo "Routes: " . count(\Route::getRoutes()->getRoutes()) . PHP_EOL;
echo "Models test: " . (new \App\Models\Project())->getTable() . PHP_EOL;
'
```

---

## 14. ANTI-PATTERNS (DON'T DO)

- ❌ `sed` on PHP/Blade files with `$` or `{{ }}`
- ❌ Paste PHP code directly to bash (without heredoc)
- ❌ `CACHE_DRIVER=database`
- ❌ Add FK column without `'integer'` cast (causes Bug 4 family)
- ❌ Hard delete users (use `is_active=false` instead)
- ❌ Auto-trigger Client status from followup outcome (manual only — see Decision Log)
- ❌ Use ALL CAPS for buttons/nav (use Title Case)
- ❌ Use "Anda" in UI text (use "kamu")
- ❌ Em dashes, arrows, emoji in production copy
- ❌ Manual modify pre-compiled `public/css/tailwind.css` (always go through `tw-build`)
- ❌ Skip `withTrashed()` on uniqueness/sequence checks for SoftDeletes models
- ❌ **Eager load partial column without verifying schema** — `with('relation:id,name')` saat actual column-nya beda (Bug 6). Schema check via tinker dulu.
- ❌ **Python heredoc dengan surrogate pair** untuk emoji (`\uD83D\uDCC5`) — fails with `UnicodeEncodeError`. Pakai 8-digit hex (`\U0001F4C5`) atau bytes-level sed dengan ANSI-C quoting (`$'\xNN\xNN'`).
- ❌ **Long multi-line Python heredoc dengan unicode + Blade syntax** (Bug 7) — terminal corruption. Pakai sed atau cPanel File Manager untuk file dengan kombinasi tricky.
- ❌ **Python embedded triple-string dengan PHP namespace backslashes** (Bug 8) — `\App\Services\X` triggers unicode escape error. Pakai file-based heredoc pattern (Section 7).
- ❌ **Tailwind class yang belum ke-compile** di pre-compiled build — verify dengan `grep -rn "class-name"` di codebase sebelum apply. Avoid `lg:grid-cols-5+`, `bg-yellow-600+`, arbitrary values seperti `px-3.5` atau `left-[-9999px]`.
- ❌ **Assign Lead.assigned_to ke role non-marketing** — hard locked by `Rule::exists('users','id')->where('role', ROLE_MARKETING)`. Same for Client.account_manager_id (AM only).
- ❌ **Trust `cat` output for file structure verification** — chat client / terminal line-wrap can collapse multi-line content visually. ALWAYS verify with `awk` numbered output before declaring "bug found" (see Section 7 Audit pattern).

---

## 15. KEY DECISION LOG

### Architecture
- **Lead pipeline ≠ Client lifecycle**: Each has own status field. Lead: new→contacted→screened→promoted/disqualified. Client: prospect→active→inactive→churned. NO shared `stage` field.
- **Client status = manual transition only**: Followup outcome (positive/negative/no_response) does NOT auto-update Client.status. Sales must explicitly Edit Client. Reason: status `active` = paid retainer (billing fact), not "had positive interaction".
- **Project ↔ Client relationship**: 1 Client → N Projects, 1 Project → 1 Advertiser.
- **Operations module philosophy**: Communication & accountability tool, NOT project management. Brief detail in PDF/WhatsApp, system captures status + AM feedback per period.

### UI/UX
- **Default filter = current month** for all index pages.
- **Sort default**: created_at DESC (newest first). Exception: Client autocomplete = alphabetical.
- **Status badge** shown next to entity name in detail views.
- **Inline expand pattern** for sub-entity management (Followup, Project Reports), NOT separate index pages.

### Permissions
- **Marketing role**: NO access to Operations module
- **Account Manager**: Scoped to clients they manage (`account_manager_id == auth.id`)
- **Advertiser**: Scoped to projects assigned (`advertiser_id == auth.id`)
- **`is_active=false`**: Soft-disable user, preserve historical data, NO hard delete

### Role Assignment Constraints (Hard Lock — Frontend + Backend)
- **Lead.assigned_to** accepts ONLY role=marketing + is_active=true
- **Client.account_manager_id** accepts ONLY role=account_manager + is_active=true
- **Super admin + Admin tidak bisa di-assign** ke Lead atau Client (operators, not handlers)
- Enforcement: dropdown filter (`User::byRole(...)->active()->get()`) + validation (`Rule::exists()->where('role', ...)->where('is_active', true)`)

### Operations Module — Phase 14.6+ Additions
- **Acknowledgment lifecycle**: 3-state (Pending Review → Pending Ack → Acknowledged). Field: `acknowledged_at`.
- **Only submitter (advertiser) can acknowledge own report**. Cannot ack on behalf. Idempotent. No undo.
- **Operations Overview KPI**: 5 cards for super_admin/admin/AM, 4 cards for advertiser (Pending Review hidden — not actionable for them)
- **Review filter**: dropdown REPLACED by 4-state tabs (All / Pending Review / Pending Ack / Acknowledged) with count
- **Filter AM dropdown**: visible ONLY for super_admin + admin (oversight)
- **Stale Projects widget**: HIDDEN for advertiser

### CRM Module — Phase 14.7 Additions (May 11, 2026)
- **Followup Card pattern** at top of Lead + Client index pages (above main table)
- **Lead card scope**: super_admin/admin oversight semua marketing FU; marketing personal view
- **Client card scope**: super_admin + admin oversight all (admin = full access, no per-admin scope)
- **Card design**: compact summary (3 timeline counts) + Alpine expand to detail list
- **No action button** in card — click item → ke parent detail page

### CRM Module — Phase 14.8 Additions (May 15, 2026): Interested In Field
- **New columns**: `leads.interested_in` enum + `leads.interested_in_other` varchar(255). Same on `clients` table.
- **Enum values**: `agency`, `academy`, `partnership`, `others`. Labels: "Agency", "Academy", "Partnership", "Other".
- **Required at public `/contact` form, nullable at admin forms** (different validation rules per context).
- **REQUIRED before Lead promote** — `canPromote()` returns true only if status='screened' AND interest filled.
- **Defense in depth (4 layers)**: Model `canPromote()` → Service throws → Controller catches+flashes → View hides button + warning banner.
- **Field inheritance**: Lead → Client on promote (via `LeadPromotionService::mapLeadToClient()`).
- **English labels in admin** (consistent with other admin labels). **Indonesian labels in public form** with descriptive context (e.g., "Agency — Kelola Google Ads untuk bisnis Anda").
- **Defensive backend clear**: Admin controllers + FormRequest `passedValidation()` both clear `interested_in_other` if `interested_in !== 'others'`.
- **Alpine local scope wrapper pattern** for conditional reveal (see Section 5 "Conditional Reveal with Alpine Watcher").
- **`<style>[x-cloak] { display: none !important; }</style>` added to admin layout** — benefits all existing x-cloak usages globally (was missing before, several views had stale flash).

### CRM Module — Phase 14.8.1 Additions (May 15, 2026): Prospects Action Card
- **Strategic admin nudge** at CRM Overview Row 1 (card ke-4) to prevent admin from forgetting FU on prospects promoted by marketing.
- **Logic (Opsi D — ALL prospects with age breakdown)**:
  - Main number: ALL prospects, no time filter
  - Breakdown: `X in last 30d · Y older` (durational wording)
  - Urgency trigger: `agedProspectsNeedFu > 0` (aged prospects WITHOUT pending followup)
- **Yellow ring outline + yellow number** ONLY when urgency trigger fires.
- **Future-proof**: card automatically signals urgency when prospects age without progress, no manual configuration.
- **Click target**: `/admin/clients?status=prospect` (entire card wrapped in `<a>` tag with hover:shadow-md).
- **Why "aged need FU" not "total need FU"**: fresh prospects without FU is normal (just promoted, FU not yet scheduled). Aged without FU = truly forgotten zone.
- **Row 1 grid**: responsive 1→2→4 cols (mobile→tablet→desktop) to accommodate 4 cards.
- **Pattern reusable**: see Section 5 "Yellow Ring Action Card (Phase 14.8.1 Pattern)".

---

## 16. USEFUL FILE LOCATIONS

| File | Purpose |
|---|---|
| `~/digimaya_app/.env` | Environment config (CACHE_DRIVER=file, DB creds, MAIL config) |
| `~/digimaya_app/app/Http/Kernel.php` | Middleware aliases |
| `~/digimaya_app/app/Providers/AppServiceProvider.php` | Observer registration |
| `~/digimaya_app/routes/web.php` | All routes (single file) |
| `~/digimaya_app/storage/logs/laravel.log` | Application error log |
| `~/digimaya_app/docs/notes-*.md` | Module-specific notes |
| `~/.bashrc` | Aliases (npm, node, npx, tw-build) |

---

## 17. WORKING WITH AI ASSISTANT — RULES OF ENGAGEMENT

1. **Audit BEFORE editing** — use `awk 'NR>=X && NR<=Y {printf "%4d| %s\n", NR, $0}'` for numbered output (PREFERRED). `cat` output can be misleading due to chat/terminal line-wrap rendering. `grep` for pattern presence, `cat -A` to verify line endings, `sed -n` for range read.
2. **Surgical edits with verification** — Python heredoc with `count(old) == 1` check. For Blade/PHP with namespaces, prefer file-based heredoc pattern (Section 7 / Bug 8).
3. **Test in browser after EACH change** — don't batch
4. **Cache clear after every change** affecting views/routes/config
5. **Diagnose before fix** — when bug, use tinker to verify root cause, not guess
6. **Database backup before destructive change** — phpMyAdmin export
7. **Never let AI use `sed` on PHP/Blade** with `$` or `{{ }}`
8. **Trust file content over rendered chat output** — if file state seems "buggy" from `cat` output, verify with `awk` numbered output before fixing. Most "bugs spotted via cat" are false positives from wrap rendering.
9. 

---

## 18. SOFTDELETES BUG FAMILY

**Pattern**: any time a model uses `SoftDeletes`, related code that queries/joins/references that model can silently break because soft-deleted records are filtered out of default Eloquent queries. Three manifestations confirmed so far — assume more will appear as modules grow.

### Affected models (currently using SoftDeletes)
`Lead`, `Client`, `Invoice`, `Project`, `ProjectReport`. Any new model with SoftDeletes inherits this risk surface.

### Manifestation 1 — Unique Sequence Generators (Phase 11.3, Invoice rebuild)
**Symptom**: Duplicate key violation on sequential numbers (Invoice number, Lead public_id, Client slug) even when the visible record count is below the next sequence.

**Cause**: Generator queries `Model::max('sequence_column')` which ignores soft-deleted rows, so it returns a number lower than the actual max — creating collision when soft-deleted record had a higher value.

**Fix**: Always `->withTrashed()` in sequence generator queries.

```php
// WRONG
$next = Invoice::max('sequence') + 1;

// CORRECT
$next = Invoice::withTrashed()->max('sequence') + 1;
```

### Manifestation 2 — belongsTo Relations to SoftDeletes Parent (May 21, 2026)
**Symptom**: View crashes with `UrlGenerationException: Missing required parameter` when rendering links that pass the relation as route parameter. Example error: `route('admin.leads.show', $fu->lead)` → `$fu->lead` returns `null` because parent was soft-deleted.

**Cause**: Child record (e.g. `LeadFollowup`) survives parent soft-delete because there's no cascading SoftDelete. Default `belongsTo` relation filters out trashed parents, so `$child->parent` returns `null`.

**Affected today**: `LeadFollowup::lead()`, `ClientFollowup::client()`. **Audit candidates** (likely have same issue if used in views): any `belongsTo` from a non-SoftDeletes child to a SoftDeletes parent.

**Fix**: Add `->withTrashed()` to the belongsTo relation definition.

```php
// WRONG
public function lead(): BelongsTo {
    return $this->belongsTo(Lead::class);
}

// CORRECT
public function lead(): BelongsTo {
    return $this->belongsTo(Lead::class)->withTrashed();
}
```

### Manifestation 3 — Views Rendering Cross-Soft-Delete Relations
**Symptom**: Same as Manifestation 2, but defensive layer at the view level. Even after fixing the relation with `withTrashed()`, view should still defend against null in case parent was hard-deleted or relation is on a different model that doesn't yet have the fix.

**Fix**: Wrap `route()` calls that pass a relation object in null-check ternary.

```blade
{{-- WRONG --}}
<a href="{{ route('admin.leads.show', $fu->lead) }}">

{{-- CORRECT --}}
<a href="{{ $fu->lead ? route('admin.leads.show', $fu->lead) : '#' }}">
```

### Diagnostic Commands

**Find orphan children** (child rows whose parent FK no longer matches any non-trashed parent):
```bash
php artisan tinker --execute="
\$orphans = \App\Models\LeadFollowup::whereDoesntHave('lead')->count();
echo 'Orphan followups: ' . \$orphans . PHP_EOL;
"
```

**List orphan detail**:
```bash
php artisan tinker --execute="
\App\Models\LeadFollowup::whereDoesntHave('lead')->get(['id','lead_id','created_at'])
    ->each(fn(\$o) => print(\"#{\$o->id} lead_id={\$o->lead_id} created={\$o->created_at}\".PHP_EOL));
"
```

**Verify which parents are actually soft-deleted** (not hard-deleted):
```bash
php artisan tinker --execute="
\$ids = [/* lead_ids from orphan list */];
\App\Models\Lead::withTrashed()->whereIn('id', \$ids)->get(['id','name','deleted_at'])
    ->each(fn(\$l) => print(\"#{\$l->id} {\$l->name} deleted_at={\$l->deleted_at}\".PHP_EOL));
"
```

### Checklist when adding a new model with SoftDeletes
- [ ] All unique sequence generators (slug, public_id, sequence_number) use `->withTrashed()`
- [ ] All `belongsTo` relations FROM other models pointing TO this model use `->withTrashed()` if the child can outlive parent soft-delete
- [ ] All views that pass relation objects to `route()` use defensive null-check ternary
- [ ] Decision documented: should related models also soft-delete via observer? (Currently NO cascading SoftDelete in this codebase)

### Decision recorded (May 21, 2026)
**Followup history is preserved across parent soft-delete.** When a Lead/Client is soft-deleted, its followups remain visible (via `withTrashed()` on relation) so historical context is not lost. Hard deletion of orphan followups is rejected on principle — soft-delete means "archive", not "destroy", and dependent records inherit that intent.

---

## Phase B.4 — Troubleshooter Admin CMS — COMPLETE ✅ (17 Mei 2026)

### Summary
Decision-tree CMS untuk troubleshooter Google Ads. Admin bisa kelola problem tree hierarchical (Question → Question → Leaf), dengan dynamic answers (cause+solution pairs) dan multiple video tutorials per leaf.

### Sub-phases completed
- **B.4.1**: Tree explorer 2-panel layout (tree kiri + edit kanan)
- **B.4.2**: Edit form dengan reactive state Alpine
- **B.4.3**: CRUD lengkap (add child via "+" icon di tree, delete dengan confirm, AJAX save)
- **B.4.4**: Content editors
  - Answers dinamis (card-based, cause + solution per card, add/remove)
  - Videos dinamis (multiple YouTube per leaf, auto-detect ID dari URL)
  - Leaf↔Question type switch dengan confirm guard (warn data loss)
- **B.4.5**: Polish
  - Click label auto-expand tree
  - Confirm dialogs (hapus answer, hapus video, type switch)
  - English labels standardized
  - Inline flash alert (Pattern B styling: bg-green-50)

### Database changes
- Migration 1: causes JSON + solutions JSON → answers JSON (replaced)
- Migration 2: youtube_id varchar + video_caption text → videos JSON (replaced)
- Final schema: `id, parent_id, type (question|leaf), label, answers, videos, sort_order, is_active, timestamps, deleted_at`

### Key files
- Migration: `database/migrations/2026_05_17_*_*_in_troubleshooter_nodes_table.php` (3 total)
- Model: `app/Models/TroubleshooterNode.php`
- Controller: `app/Http/Controllers/Admin/TroubleshooterController.php`
- View: `resources/views/admin/troubleshooter/index.blade.php` (~680 lines)
- Tree partial: `resources/views/admin/troubleshooter/_tree_node.blade.php`
- Routes: 7 routes registered in `routes/web.php`

### Lessons learned
1. Architectural insight pivot (Quill → card-based cause+solution pairs) menyelamatkan dari pattern yang inferior — trust user UX intuition early
2. Pattern verify before code (Tailwind class compilation) — mandatory grep
3. Don't paste illustrative code in chat — only patch scripts
4. Multi-step patch scripts can silent-fail — split to smaller atomic patches dengan verify per step
5. Action ↔ result di kolom yang sama = principle penting untuk multi-panel UI
6. Architecture mismatch (Blade tree + Alpine partial update) = root cause banyak bug

---

**Last updated**: 17 Mei 2026 (sesi sore)
**Status**: Phase A + B (Admin CMS) + C (Public Widget) — ✅ Complete (100%, no dead code)
**Branch**: main (development)

---

## Quick Context for New Chat

Decision-tree troubleshooter untuk Google Ads. Admin kelola tree hierarchical (Question → Question → Leaf) dengan dynamic answers (cause+solution pairs) + multiple YouTube tutorial videos per leaf. End-user akses via public widget di `/troubleshooter` dengan drill-down navigation Alpine state machine.

**Stack**: Laravel 10.50.2, PHP 8.1.34, Alpine.js, Tailwind 3.4.19, MySQL 8
**Path**: `~/digimaya_app/`

---

## ✅ What's Done

### Backend Foundation
- 4 migrations applied (final schema: `id, parent_id, type (question|leaf), label, answers JSON, videos JSON, sort_order, is_active, timestamps, deleted_at`)
- Model: `app/Models/TroubleshooterNode.php` (44 lines, post-cleanup)
- Controllers:
  - `Admin/TroubleshooterController.php` (131 lines, post-cleanup)
  - `Public/TroubleshooterController.php` (~20 lines)
- Active endpoints: index, store, update, destroy (admin) + index (public)
- Model methods kept: `parent()`, `children()` relations only

### Admin CMS (`/admin/troubleshooter`)
- File: `resources/views/admin/troubleshooter/index.blade.php` (post-cleanup, no help text alert, no caret button)
- Tree partial: `resources/views/admin/troubleshooter/_tree_node.blade.php` (94 lines)
- Tree explorer 2-panel, click label auto-expand
- Edit form dengan answers + videos dinamis
- Confirm dialogs untuk destructive actions
- Flash style: konform ke admin standard (`bg-green-100 border-green-400 text-green-700`)
- 4 routes registered (index, store, update, destroy)

### Public Widget (`/troubleshooter`)
- File: `resources/views/public/tools/troubleshooter.blade.php` (278 lines)
- Layout: `@extends('layouts.public')`
- Alpine state machine drill-down, no page reload
- URL state via `?node=X` (shareable, browser back/forward works)
- Breadcrumb Apple-style: caret thin (stroke 1.5, gray-400), "Kategori Masalah" sebagai root, last item non-clickable
- All headings align left
- Inline video player (Google Help pattern):
  - Click thumbnail → player muncul di atas thumbnails grid
  - Active thumbnail di-hide (no redundancy)
  - Click thumbnail lain → swap player
  - Click X (floating top-right) → close player
  - Navigate ke leaf lain → auto-cleanup video

### Navigation Integration
- Troubleshooter link sudah live di nav (desktop dropdown Tools + mobile menu)
- Posisi: setelah URL Builder di section Tools

### Database state
- 7 root nodes preserved (sample test data sudah cleaned)
- Auto-increment reset to 8

---

## 🧹 Cleanup History (17 Mei 2026, sesi sore)

### Mobile Nav Fix (Global)
**Problem**: Hamburger menu mobile auto-expand, semua section terlihat, tapi gak bisa scroll. Banyak menu kepotong di viewport.
**Root cause**: Mobile menu container pakai `max-h-[calc(100vh-4rem)] overflow-y-auto` tapi parent header `sticky` bikin scroll context gak terbentuk. Plus 3 Tailwind class missing dari compiled CSS (`top-16`, `inset-x-0`, `overscroll-contain`).
**Fix**: `resources/views/layouts/public.blade.php` line 362 — ganti positioning ke `fixed left-0 right-0 bottom-0` + inline `style="top: 4rem;"` (avoid missing compiled class).
**Class compiled tersedia**: `bottom-0`, `left-0`, `right-0`, `top-0`, `top-1`, `top-4`, `top-20`, `top-24`. **Missing**: `top-16`, `inset-x-0`, `overscroll-contain`, `inset-x-*` lainnya.

### Dead Code Cleanup (Troubleshooter)
**Removed dari Model `TroubleshooterNode.php`** (7 methods, −45 lines):
- `activeChildren()` relation
- `descendants()` relation
- `scopeActive()`, `scopeRoots()`, `scopeLeaves()` scopes
- `isLeaf()` method
- `breadcrumbPath()` method
- Unused import: `Illuminate\Database\Eloquent\Builder`

**Removed dari Controller `Admin/TroubleshooterController.php`** (4 methods, −64 lines):
- `restore()` + `cascadeRestore()` (soft-delete recovery, no UI)
- `reorder()` (drag-drop, no UI)
- `toggleActive()` (toggle button, no UI)

**Removed dari `routes/web.php`** (3 lines):
- `admin.troubleshooter.reorder`
- `admin.troubleshooter.restore`
- `admin.troubleshooter.toggle-active`

**Total cleanup**: 109 lines dead code, Model −50%, Controller −33%.

### UI Cleanup (Troubleshooter Admin)
- Help text alert "Kelola decision tree..." dihapus (line 20-23)
- Caret button "Collapse all" + function `collapseAll()` dihapus (confirmed non-functional saat di-klik)

### Flash Standardization (TD-001 closed)
- Audit hasil: 17 file admin pakai standard pattern `bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded` — 100% identik (MD5 match)
- Decision: gak bikin component baru, sederhanakan dengan konform 1 outlier ke mayoritas
- Outlier troubleshooter (Alpine inline dengan `bg-green-50 border-green-200 rounded-md`) di-refactor ke standard style tapi tetap pakai Alpine state `flashMessage` (karena admin troubleshooter pakai AJAX, bukan full page reload)
- Final state: 18 admin views konsisten

---

## 🟡 Pending / Optional (defer indefinitely)

### Phase C Enhancements (decided: skip)
- Search/filter di public widget
- Analytics tracking (drill path, video views, dead-ends)
- Embeddable widget version (compact, untuk sidebar/blog)
- Empty state polish ("Konten sedang disiapkan" → suggest contact/related)

Semua low priority, gak ada yang dirasa perlu dikerjakan.

---

## ❌ Tech Debt (NOT troubleshooter-specific, parked)

### TD-002: Mobile Navigation Global Header — ✅ DONE (17 Mei sesi sore)
Resolved sebagai bagian Mobile Nav Fix di atas.

### TD-001: Flash Component Standardization — ✅ DONE (17 Mei sesi sore)
Resolved dengan konformasi pattern instead of new component. 17 admin files udah pakai pattern identik, troubleshooter outlier (1 file) di-konform.

**Pattern standard yang harus diikuti file baru**:
```html
@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif
```

Error variant (8 files pakai):
```html
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
```

Validation errors block: `$errors->any()` pattern di 14 files.

---

## 📂 Key File References

```
~/digimaya_app/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/TroubleshooterController.php       (131 lines, post-cleanup)
│   │   └── Public/TroubleshooterController.php
│   └── Models/TroubleshooterNode.php                 (44 lines, post-cleanup)
├── database/migrations/
│   ├── 2026_05_16_201713_create_troubleshooter_nodes_table.php
│   ├── 2026_05_17_100759_change_causes_solutions_to_longtext_in_troubleshooter_nodes_table.php
│   ├── 2026_05_17_103816_replace_causes_solutions_with_answers_in_troubleshooter_nodes_table.php
│   └── 2026_05_17_122034_replace_youtube_id_video_caption_with_videos_in_troubleshooter_nodes_table.php
├── resources/views/
│   ├── admin/troubleshooter/
│   │   ├── index.blade.php                          (post-cleanup)
│   │   └── _tree_node.blade.php                     (94 lines)
│   ├── public/tools/troubleshooter.blade.php        (278 lines)
│   └── layouts/public.blade.php                     (mobile nav fix line 362, troubleshooter link line 228 desktop / 446 mobile)
└── routes/web.php                                    (line 75-76 public, 152-156 admin: 4 routes)
```

---

## 🧪 Verification Commands

```bash
# State check
cd ~/digimaya_app && wc -l \
  app/Models/TroubleshooterNode.php \
  app/Http/Controllers/Admin/TroubleshooterController.php \
  resources/views/admin/troubleshooter/index.blade.php \
  resources/views/public/tools/troubleshooter.blade.php

# PHP lint
php -l app/Http/Controllers/Public/TroubleshooterController.php
php -l app/Http/Controllers/Admin/TroubleshooterController.php
php -l app/Models/TroubleshooterNode.php

# DB state
php artisan tinker --execute="echo App\Models\TroubleshooterNode::count() . ' nodes' . PHP_EOL;"

# Routes (expected: 4 admin + 1 public)
php artisan route:list | grep troubleshooter

# HTTP check
curl -s -o /dev/null -w "Public: HTTP %{http_code}\n" https://digimaya.com/troubleshooter
curl -s -o /dev/null -w "Admin: HTTP %{http_code} (302 expected)\n" https://digimaya.com/admin/troubleshooter
```

---

## 🚀 Workflow Patterns (locked-in, untuk reference)

### Dev workflow established
- PHP/Blade dengan `$variable`/`{{ }}` **tidak bisa** di-paste langsung ke bash — pakai cPanel File Manager atau Python3 heredoc dengan grep verification
- Tailwind pre-compiled — **selalu grep compiled CSS dulu** sebelum apply class baru (`grep -c "\.classname\b" public/css/tailwind.css`)
- Saat class baru gak ada di compiled CSS, alternatif: pakai inline `style="..."` attribute (works without rebuild)
- Backup before destructive op: `cp file file.bak-$(date +%Y%m%d-%H%M%S)`
- SoftDeletes + unique sequence bug pattern (fix: `->withTrashed()`)
- Alpine hidden input x-bind:value bug → replace dengan visible select + pointer-events-none lock

### Decisions yang sudah locked
- Public widget: Single-page Alpine, URL `?node=X`, standalone page
- Breadcrumb: Apple-style minimalist, "Kategori Masalah" root
- Video: inline player (NOT modal), hide active thumbnail
- Flash style: green = `bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded` (no new component)
- Mobile nav: fixed positioning dengan inline `style="top: 4rem;"` (avoid missing Tailwind class)

### Tips untuk chat baru
- ✅ Paste file ini sebagai context
- ✅ Pick ONE task per session — jangan multi-task
- ✅ Plan upfront untuk cross-cutting issues
- ✅ Sertakan screenshot kalau bug visual
- ✅ Bash command terpisah dengan verify per step (avoid silent failures)
- 
**Last updated**: 17 Mei 2026 (sesi sore, final)
**Status**: Phase A + B (Admin CMS) + C (Public Widget) — ✅ Complete (100%, no dead code)
**Branch**: main (development)

---

## Quick Context for New Chat

Decision-tree troubleshooter untuk Google Ads. Admin kelola tree hierarchical (Question → Question → Leaf) dengan dynamic answers (cause+solution pairs) + multiple YouTube tutorial videos per leaf. End-user akses via public widget di `/troubleshooter` dengan drill-down navigation Alpine state machine.

**Stack**: Laravel 10.50.2, PHP 8.1.34, Alpine.js, Tailwind 3.4.19, MySQL 8
**Path**: `~/digimaya_app/`

---

## ✅ What's Done

### Backend Foundation
- 4 migrations applied (final schema: `id, parent_id, type (question|leaf), label, answers JSON, videos JSON, sort_order, is_active, timestamps, deleted_at`)
- Model: `app/Models/TroubleshooterNode.php` (44 lines, post-cleanup)
- Controllers:
  - `Admin/TroubleshooterController.php` (131 lines, post-cleanup)
  - `Public/TroubleshooterController.php` (~20 lines)
- Active endpoints: index, store, update, destroy (admin) + index (public)
- Model methods kept: `parent()`, `children()` relations only

### Admin CMS (`/admin/troubleshooter`)
- File: `resources/views/admin/troubleshooter/index.blade.php` (post-cleanup, no help text alert, no caret button)
- Tree partial: `resources/views/admin/troubleshooter/_tree_node.blade.php` (94 lines)
- Tree explorer 2-panel, click label auto-expand
- Edit form dengan answers + videos dinamis
- Confirm dialogs untuk destructive actions
- **Flash success persistence across reload** (via sessionStorage):
  - Create root → "X created." flash
  - Create child → "X created." flash
  - Update → flash via Alpine state (no reload needed)
  - Delete → "X node(s) deleted." flash
  - Pickup logic di `init()` line 308-315
  - sessionStorage key: `troubleshooterFlash`
- Flash style: konform ke admin standard (`bg-green-100 border-green-400 text-green-700`)
- 4 routes registered (index, store, update, destroy)

### Public Widget (`/troubleshooter`)
- File: `resources/views/public/tools/troubleshooter.blade.php` (278 lines)
- Layout: `@extends('layouts.public')`
- Alpine state machine drill-down, no page reload
- URL state via `?node=X` (shareable, browser back/forward works)
- Breadcrumb Apple-style: caret thin (stroke 1.5, gray-400), "Kategori Masalah" sebagai root, last item non-clickable
- All headings align left
- Inline video player (Google Help pattern):
  - Click thumbnail → player muncul di atas thumbnails grid
  - Active thumbnail di-hide (no redundancy)
  - Click thumbnail lain → swap player
  - Click X (floating top-right) → close player
  - Navigate ke leaf lain → auto-cleanup video

### Navigation Integration
- Troubleshooter link sudah live di nav (desktop dropdown Tools + mobile menu)
- Posisi: setelah URL Builder di section Tools

### Database state
- 7 root nodes preserved (sample test data sudah cleaned)
- Auto-increment reset to 8

---

## 🧹 Sesi 17 Mei 2026 (sore) — Cleanup History

### 1. Mobile Nav Fix (Global, layouts/public.blade.php)
**Problem**: Hamburger menu mobile auto-expand, semua section terlihat, tapi gak bisa scroll. Banyak menu kepotong di viewport.
**Root cause**: Mobile menu container pakai `max-h-[calc(100vh-4rem)] overflow-y-auto` tapi parent header `sticky` bikin scroll context gak terbentuk. Plus 3 Tailwind class missing dari compiled CSS (`top-16`, `inset-x-0`, `overscroll-contain`).
**Fix**: line 362 — ganti positioning ke `fixed left-0 right-0 bottom-0` + inline `style="top: 4rem;"` (avoid missing compiled class).
**Compiled class tersedia**: `bottom-0`, `left-0`, `right-0`, `top-0`, `top-1`, `top-4`, `top-20`, `top-24`.
**Missing dari compiled CSS**: `top-16`, `inset-x-0`, `overscroll-contain`, sebagian besar `inset-x-*`.

### 2. Troubleshooter Link Added to Nav
- Desktop dropdown Tools: line 228-231
- Mobile menu: line 446-449
- Setelah URL Builder

### 3. Dead Code Cleanup (Troubleshooter)
**Model `TroubleshooterNode.php`** (7 methods, −45 lines):
- `activeChildren()`, `descendants()` relations
- `scopeActive()`, `scopeRoots()`, `scopeLeaves()` scopes
- `isLeaf()`, `breadcrumbPath()` methods
- Unused import `Illuminate\Database\Eloquent\Builder`

**Controller `Admin/TroubleshooterController.php`** (4 methods, −64 lines):
- `restore()` + `cascadeRestore()` (soft-delete recovery, no UI)
- `reorder()` (drag-drop, no UI)
- `toggleActive()` (toggle button, no UI)

**Routes `web.php`** (3 lines):
- `admin.troubleshooter.reorder`, `restore`, `toggle-active`

**Total**: 109 lines dead code removed.

### 4. UI Cleanup (Troubleshooter Admin)
- Help text alert "Kelola decision tree..." removed (line 20-23)
- Caret button "Collapse all" + function `collapseAll()` removed (non-functional)

### 5. TD-001 Flash Standardization — CLOSED
- Audit hasil: 17 file admin pakai standard pattern, 100% identik (MD5 match)
- **Decision**: gak bikin component baru, sederhanakan dengan konform 1 outlier
- Troubleshooter Alpine inline (`bg-green-50 border-green-200`) → konform ke standard (`bg-green-100 border-green-400`), tetap pakai Alpine state karena AJAX-based
- Final state: 18 admin views konsisten

### 6. Flash Success Bug Fix (Troubleshooter Admin)
**Bug**: AJAX action (create root, create child, delete) reload page tapi flash message hilang karena Alpine state reset.
**Fix**: 3 spot setItem sessionStorage sebelum reload + 1 pickup logic di `init()` yang call `flashSuccess()` setelah reload.
**Pattern**:
```javascript
// Before reload in handler
sessionStorage.setItem('troubleshooterFlash', data.message || 'Default msg.');
window.location.reload();

// In init()
const savedFlash = sessionStorage.getItem('troubleshooterFlash');
if (savedFlash) {
    sessionStorage.removeItem('troubleshooterFlash');
    this.$nextTick(() => this.flashSuccess(savedFlash));
}
```

---

## 🟡 Phase C — DECIDED SKIP (semua low priority, no value perceived)

- ❌ Search/filter di public widget
- ❌ Analytics tracking
- ❌ Embeddable widget version
- ❌ Empty state polish

---

## ❌ Tech Debt — ALL CLOSED

### TD-002: Mobile Navigation — ✅ DONE
Resolved sebagai bagian Mobile Nav Fix.

### TD-001: Flash Component Standardization — ✅ DONE
Resolved dengan konformasi pattern instead of new component.

**Pattern standard untuk file baru** (sudah dipakai di 18 admin files):
```html
@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif
```

Error variant (8 files):
```html
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
```

Validation errors block: `$errors->any()` pattern di 14 files.

---

## 📂 Key File References

```
~/digimaya_app/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/TroubleshooterController.php       (131 lines, post-cleanup)
│   │   └── Public/TroubleshooterController.php
│   └── Models/TroubleshooterNode.php                 (44 lines, post-cleanup)
├── database/migrations/
│   ├── 2026_05_16_201713_create_troubleshooter_nodes_table.php
│   ├── 2026_05_17_100759_change_causes_solutions_to_longtext_in_troubleshooter_nodes_table.php
│   ├── 2026_05_17_103816_replace_causes_solutions_with_answers_in_troubleshooter_nodes_table.php
│   └── 2026_05_17_122034_replace_youtube_id_video_caption_with_videos_in_troubleshooter_nodes_table.php
├── resources/views/
│   ├── admin/troubleshooter/
│   │   ├── index.blade.php                          (post-cleanup)
│   │   └── _tree_node.blade.php                     (94 lines)
│   ├── public/tools/troubleshooter.blade.php        (278 lines)
│   └── layouts/public.blade.php                     (mobile nav fix line 362, troubleshooter link line 228 desktop / 446 mobile)
└── routes/web.php                                    (line 75-76 public, 152-156 admin: 4 routes)
```

---

## 🧪 Verification Commands

```bash
# State check
cd ~/digimaya_app && wc -l \
  app/Models/TroubleshooterNode.php \
  app/Http/Controllers/Admin/TroubleshooterController.php \
  resources/views/admin/troubleshooter/index.blade.php \
  resources/views/public/tools/troubleshooter.blade.php

# PHP lint
php -l app/Http/Controllers/Public/TroubleshooterController.php
php -l app/Http/Controllers/Admin/TroubleshooterController.php
php -l app/Models/TroubleshooterNode.php

# DB state
php artisan tinker --execute="echo App\Models\TroubleshooterNode::count() . ' nodes' . PHP_EOL;"

# Routes (expected: 4 admin + 1 public)
php artisan route:list | grep troubleshooter

# HTTP check
curl -s -o /dev/null -w "Public: HTTP %{http_code}\n" https://digimaya.com/troubleshooter
curl -s -o /dev/null -w "Admin: HTTP %{http_code} (302 expected)\n" https://digimaya.com/admin/troubleshooter

# Flash sessionStorage pickup check (expected: 5 occurrences)
grep -nE "troubleshooterFlash" resources/views/admin/troubleshooter/index.blade.php
```

---

## 🚀 Workflow Patterns (locked-in)

### Dev workflow established
- PHP/Blade dengan `$variable`/`{{ }}` **tidak bisa** di-paste langsung ke bash — pakai cPanel File Manager atau Python3 heredoc dengan grep verification
- Tailwind pre-compiled — **selalu grep compiled CSS dulu** sebelum apply class baru:
  ```bash
  grep -c "\.classname\b" public/css/tailwind.css
  ```
- Saat class baru gak ada di compiled CSS, alternatif: pakai inline `style="..."` attribute (works without rebuild)
- Backup before destructive op: `cp file file.bak-$(date +%Y%m%d-%H%M%S)`
- **Python heredoc anchor matching**: hati-hati dengan whitespace, terutama blank line di tengah. Gunakan `cat -A` (show `$` for newline) untuk debug whitespace bila replace gagal.
- SoftDeletes + unique sequence bug pattern (fix: `->withTrashed()`)
- Alpine hidden input x-bind:value bug → replace dengan visible select + pointer-events-none lock

### Decisions yang sudah locked
- Public widget: Single-page Alpine, URL `?node=X`, standalone page
- Breadcrumb: Apple-style minimalist, "Kategori Masalah" root
- Video: inline player (NOT modal), hide active thumbnail
- Flash style: green = `bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded` (no new component)
- Mobile nav: fixed positioning dengan inline `style="top: 4rem;"` (avoid missing Tailwind class)
- Flash persistence across AJAX-triggered reload: sessionStorage pattern

### Tips untuk chat baru
- ✅ Paste file ini sebagai context
- ✅ Pick ONE task per session — jangan multi-task
- ✅ Plan upfront untuk cross-cutting issues
- ✅ Sertakan screenshot kalau bug visual
- ✅ Bash command terpisah dengan verify per step (avoid silent failures)
- ✅ Verbose response → request "langsung kasih command, skip explanation"
- 

## Phase B.5 — Public SEO Foundation (May 19, 2026)

**Status**: COMPLETE (with TD-009 known gap)
**Duration**: 1 session, 18 patches, 0 rollbacks
**Owner**: Renra Sedoya + Claude

### Scope

Implementasi SEO foundation untuk 10 public pages: meta tags (robots, theme-color, OG full, Twitter Card full, canonical) + JSON-LD Schema.org markup (WebSite, Organization, FAQPage, Service, Course).

### Files Created

**Config:**
- `config/digimaya.php` — single source of truth untuk brand, contact, address, social, SEO defaults. Migration-ready ke CMS Settings (TD-003).

**Schema Components** (`resources/views/components/seo/`):
- `schema-website.blade.php` — WebSite type, used homepage only
- `schema-organization.blade.php` — Organization type, used homepage only, referenced from other pages via `@id`
- `schema-faq.blade.php` — FAQPage type, accepts Collection of Faq models, HTML-stripped + JSON-safe
- `schema-service.blade.php` — Service type, props-based (name, description, serviceType), `provider.@id` linked to homepage Organization
- `schema-course.blade.php` — Course type, props-based (name, description, courseType), `provider.@id` linked to homepage Organization, no date/duration fields (generic, stable)

### Files Modified

**Layout:**
- `resources/views/layouts/public.blade.php` — added `<meta name="robots">`, `<meta name="theme-color">`, og:image dims (1200x630), og:image:alt, og:locale, and `@stack('head_schema')` injection point before `@stack('styles')`

**10 Public Pages** — titles standardized (em dash → pipe/colon), Schema components wired via `@push('head_schema')`:

| Page | URL | Schemas Wired |
|---|---|---|
| Homepage | `/` | WebSite + Organization + FAQPage |
| Management | `/google-ads-management` | Service + FAQPage |
| Audit | `/google-ads-audit` | Service + FAQPage |
| Consulting | `/google-ads-consulting` | Service + FAQPage |
| Contact | `/contact` | (meta only) |
| About | `/about` | (meta only) |
| Academy Landing | `/google-ads-academy` | Course |
| Next Gen | `/google-ads-next-gen` | Course |
| Corporate Training | `/corporate-training` | Service + FAQPage |
| Playbook | `/google-ads-playbook` | (meta only, schema postponed — TD-008) |

### Patterns Established

1. **Config-first centralization** — `config('digimaya.X')` di Blade. Migration-ready ke `setting('digimaya.X')` (TD-003) tanpa refactor reference.

2. **`@push('head_schema')` injection point** — di end of `<head>` di layout. Per-page schema push, no layout pollution.

3. **`@id` linked entity referencing** — Service & Course schema `provider.@id` reference `https://digimaya.com#organization` (defined di homepage). Google paham cross-page entity graph.

4. **HTML strip + JSON-safe encoding** untuk WYSIWYG content — `strip_tags()` + `html_entity_decode(ENT_QUOTES|ENT_HTML5)` + `preg_replace('/\s+/', ' ', ...)` untuk FAQ answers dari database.

5. **Generic schema (no dates/specifics)** — Course schema sengaja TIDAK punya `startDate`, `endDate`, `location` agar stable across batch reschedules. Decision aligned dengan strategi "set-and-forget" untuk evergreen marketing pages.

6. **File-based heredoc patching** — Bug 8 safe, atomic patches dengan count==1 validation.

7. **Verify with Python, not awk** — *NEW lesson*. awk `printf` formatting bisa strip whitespace untuk alignment, misleading saat copying for patch pattern. Patch 16 ABORT terjadi karena awk display `2026.Materi` tapi actual file `2026. Materi`. **SOP baru**: untuk verify content sebelum patch, gunakan Python `for line in lines: print(f"{i}| {line}")` — literal text, tidak strip whitespace.

8. **Backup-before, validate-external, cleanup-after SOP** — backup files di `/tmp/`. Cleanup HANYA setelah Google Rich Results Test pass. Tidak boleh hapus backup based on local tinker test saja.

### Validation

All pages tested via:
- Browser hard refresh (Ctrl+Shift+R) + view source
- Google Rich Results Test (https://search.google.com/test/rich-results)
- Schema.org Validator (https://validator.schema.org/)

Final state per Google Rich Results Test: **0 errors, 0 warnings** across all pages with Schema.

### Tech Debt Added

- **TD-006**: Relocate `resources/views/about.blade.php` → `resources/views/public/about/index.blade.php` dan `app/Http/Controllers/AboutController.php` → `app/Http/Controllers/Public/AboutController.php` untuk structural consistency dengan public pages pattern. Effort: ~30 min.

- **TD-007**: Refactor route closures untuk academy pages (`/google-ads-next-gen`, `/google-ads-playbook`) jadi proper controller methods. Saat ini di `routes/web.php` line 83-92 pakai `Route::get(..., function () { return view(...) })`. Inconsistent dengan `/google-ads-academy` & `/corporate-training` yang pakai `AcademyLandingController`. Effort: ~30 min.

- **TD-008**: Book/Product Schema for Playbook page — postponed sampai buku rilis. Saat buku rilis: bikin `<x-seo.schema-book>` atau `<x-seo.schema-product>` dengan author, publisher, image, optional offers/price. Tanpa date fields (stable). Effort: ~45 min (component + wire).

- **TD-009**: FAQ Schema gaps untuk 3 academy pages. Visual FAQ section ada di `/google-ads-academy` (7 FAQs via `$faqList` array), `/google-ads-next-gen` (6 FAQs hardcoded HTML), `/google-ads-playbook` (6 FAQs hardcoded HTML), tapi FAQ Schema belum di-render. 2 opsi solusi:
    - **Opsi A**: Bikin `<x-seo.schema-faq-inline>` component yang accept array `[['q' => ..., 'a' => ...]]`, refactor 2 page HTML hardcoded jadi array pattern + wire component
    - **Opsi B**: Hardcode FAQ schema di `@push('head_schema')` per page (pragmatic, but maintenance overhead)
    - Effort Opsi A: ~60 min. Opsi B: ~45 min.

### Lessons Learned

**Lesson #8 (NEW): awk display dapat misleading untuk file content verification**
- `awk '{printf "%4d| %s\n", NR, $0}'` strip whitespace dalam format alignment
- Bukti: Patch 16 ABORT karena content actual `2026. Materi` vs awk display `2026.Materi` (no space)
- SOP baru: Use Python `with open(target) as f: lines = f.read().split("\n"); for i, line in enumerate(lines, 1): print(f"{i:4}| {line}")` untuk literal content view sebelum patch

**Lesson #9 (NEW): Schema-visual sync is mandatory**
- Schema markup harus represent visual content accurately
- Adding FAQ Schema where no visual FAQ exists = "schema spam", potential Google penalty
- Always verify visual FAQ section exists before wiring `<x-seo.schema-faq>`
- Examples: Homepage punya FAQ visual + Schema (✅), Contact tidak punya FAQ visual = no FAQ Schema (✅)

**Lesson #10 (NEW): Date-dependent schemas are maintenance burden**
- `Event` schema dengan `startDate` perlu update tiap batch
- `Book` schema dengan `datePublished` lock ke tanggal specific
- Untuk evergreen marketing pages dengan schedule berubah-ubah, prefer generic schemas (`Course`, `Service`) tanpa date fields
- Spesifik date schemas worth it HANYA kalau event truly time-bound dan content team committed to update

### References

- Schema.org docs: https://schema.org
- Google Rich Results Test: https://search.google.com/test/rich-results
- Course type: https://schema.org/Course
- Service type: https://schema.org/Service
- FAQPage type: https://schema.org/FAQPage
 
## Soft Delete — Maintenance Note

**Status:** 21 model pakai trait `SoftDeletes`; 22 tabel punya kolom `deleted_at`. Semua entitas inti (lead, client, invoice, finance, content, academy) pakai soft delete.
**Anomali (VERIFIED 22 Mei 2026):** beberapa tabel punya kolom `deleted_at` di DB TAPI model-nya TIDAK pasang trait `SoftDeletes` → kolom vestigial (bukan soft-delete beneran; delete pada model ini = hard delete). Terkonfirmasi: `members`, `faqs`. Kemungkinan ada tabel lain dengan pola sama — cek `method_exists(Model, 'onlyTrashed')` sebelum mengandalkan soft delete.

**Keputusan desain:** TIDAK pakai auto-purge job. Alasan: di shared hosting butuh cron, dan volume data terhapus terlalu kecil (164 row teks = sub-1 MB saat dicek 22 Mei 2026) untuk justify moving parts tambahan. Pembersihan dilakukan MANUAL secara berkala.

**Reminder:** Claude harus sesekali mengingatkan untuk cek volume soft-deleted, terutama setelah fase development besar yang banyak hapus data. Bukan untuk storage (kecil), tapi untuk kebersihan & menghindari kebingungan "data tersembunyi".

**Command audit (count per tabel, read-only):**
\`\`\`bash
cd ~/digimaya_app && php artisan tinker --execute="
\$db = \DB::connection()->getDatabaseName();
\$tables = \DB::select('SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND COLUMN_NAME = ? ORDER BY TABLE_NAME', [\$db, 'deleted_at']);
\$grand = 0;
foreach (\$tables as \$t) { \$n = \DB::table(\$t->TABLE_NAME)->whereNotNull('deleted_at')->count(); \$grand += \$n; if (\$n > 0) echo str_pad(\$t->TABLE_NAME, 28).' : '.\$n.PHP_EOL; }
echo 'TOTAL: '.\$grand.PHP_EOL;
"
\`\`\`

**Command purge (hapus permanen SEMUA soft-deleted, destructive):**
Ganti baris `->count()` jadi `->delete()`, bungkus dengan `SET FOREIGN_KEY_CHECKS=0/1`. Selalu jalankan versi count dulu sebagai dry-run sebelum purge. Pertimbangkan exclude `clients`/`invoices`/`incomes` kalau sudah ada data produksi nyata (nilai histori/audit).

**Catatan terkait — SoftDeletes bug family:** Generator nilai unik (slug, public_id, invoice number, certificate number) HARUS pakai `->withTrashed()` saat cek keunikan. Row soft-deleted masih "memakai" nilai uniknya.
**Audit generator (DONE 22 Mei 2026):**
- `InvoiceNumberGenerator` — AMAN. Sudah `->withTrashed()` + `lockForUpdate()` di dalam transaction. Tidak perlu diubah.
- `CertificateNumberGenerator` — AMAN untuk kondisi sekarang. Nomor di-generate random hex + cek `exists()`. Model `Certificate` HARD delete (tabel `certificates` TIDAK pakai SoftDeletes), jadi tidak ada row trashed tersembunyi. Sudah dipasang komentar peringatan: JIKA `Certificate` nanti adopt SoftDeletes, baris `exists()` WAJIB jadi `->withTrashed()->...`. Catatan: `certificate_requests` (pengajuan) memang pakai SoftDeletes, tapi tidak punya generator nomor unik sendiri.

**Riwayat pembersihan:**
- 22 Mei 2026: purge 164 row (sample data dev). Semua tabel kembali 0.

---

## HTTP Verbs / ModSecurity — VERIFIED 22 Mei 2026

Diuji langsung di production (digimaya.com): aksi `DELETE` (FAQ destroy via `@method('DELETE')`) berhasil — response **302** (redirect normal), row terhapus. Artinya DomaiNesia **tidak** memblokir verb DELETE/PUT/PATCH.

Implikasi:
- Modul CMS (blog-categories, case-studies, faqs, logo-wall, testimonials) pakai verb `DELETE` asli via resource route + `@method('DELETE')` — jalan normal, tidak perlu diubah.
- Asumsi lama "harus flat POST karena ModSecurity blokir DELETE" (jika masih tercatat di tempat lain) sudah TIDAK berlaku.
- Modul yang terlanjur pakai flat POST tetap jalan — JANGAN migrasi yang sudah jalan hanya demi keseragaman verb.