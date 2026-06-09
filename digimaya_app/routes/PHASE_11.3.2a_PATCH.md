# Phase 11.3.2a — Patch Instructions

## 1. Route Definition

**File:** `routes/web.php`

**Lokasi:** Di dalam admin route group (yang sama dengan `Route::resource('clients', ...)`)

**Insert sebelum atau sesudah resource clients (preferensi: SEBELUM, supaya marketing flow muncul dulu):**

```php
// Lead Management (Marketing intake)
Route::get('leads/search', [\App\Http\Controllers\Admin\LeadController::class, 'search'])
    ->name('leads.search');
Route::resource('leads', \App\Http\Controllers\Admin\LeadController::class);
```

**Penting:** `leads/search` HARUS didefinisikan **SEBELUM** `Route::resource('leads', ...)` karena `Route::resource` generate `leads/{lead}` yang akan match `leads/search` dan treat `search` sebagai `{lead}` parameter.

**Verify routes registered:**

```bash
php artisan route:list --name=leads
```

Expected output (8 routes):
- GET    `admin/leads/search`         → leads.search
- GET    `admin/leads`                → leads.index
- POST   `admin/leads`                → leads.store
- GET    `admin/leads/create`         → leads.create
- GET    `admin/leads/{lead}`         → leads.show
- PUT    `admin/leads/{lead}`         → leads.update
- DELETE `admin/leads/{lead}`         → leads.destroy
- GET    `admin/leads/{lead}/edit`    → leads.edit

---

## 2. Sidebar Nav Patch

**File:** `resources/views/layouts/navigation.blade.php`

**Lokasi:** Marketing section (line 17-100 desktop sesuai NOTES)

**Pattern:** Tambahkan link "Leads" di Marketing dropdown, di atas atau di bawah link Blog/Clients yang udah ada (tergantung struktur kamu).

### Snippet Desktop (sesuaikan class dengan pattern yang udah ada di file kamu)

```blade
<x-nav-link
    :href="route('admin.leads.index')"
    :active="request()->routeIs('admin.leads.*')">
    <i class="bi bi-person-plus me-2"></i> Leads
</x-nav-link>
```

### Snippet Mobile (line 192+ sesuai NOTES)

```blade
<x-responsive-nav-link
    :href="route('admin.leads.index')"
    :active="request()->routeIs('admin.leads.*')">
    Leads
</x-responsive-nav-link>
```

**Karena gue gak tau exact structure navigation.blade.php kamu**, coba paste output ini supaya gue bisa kasih patch presisi:

```bash
sed -n '17,100p' resources/views/layouts/navigation.blade.php
```

---

## 3. Smoke Test

Setelah patch route + sidebar applied:

```bash
# 1. Verify routes
php artisan route:list --name=leads

# 2. Verify controller loads (no syntax error)
php artisan tinker --execute="dd(get_class_methods(\App\Http\Controllers\Admin\LeadController::class));"
```

Expected: array dengan methods `index, show, create, store, edit, update, destroy, search`.

```bash
# 3. Try hit index route (akan error karena view belum ada — itu expected)
curl -I http://localhost/admin/leads
```

Expected: 500 dengan `View [admin.leads.index] not found` — confirms route + controller wired correctly. Views bakal dibuat di Phase 11.3.2b.

---

## 4. Phase 11.3.2a Done Criteria

- [x] `app/Http/Controllers/Admin/LeadController.php` created (8 methods)
- [ ] `routes/web.php` updated (resource + search)
- [ ] `resources/views/layouts/navigation.blade.php` updated (desktop + mobile)
- [ ] `php artisan route:list --name=leads` shows 8 routes
- [ ] No syntax errors

**Next phase:** 11.3.2b — Index view (filter card → stats card → table).
