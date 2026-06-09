# Digimaya CRM — Finance Module Notes

> **Pair with**: `notes-general.md`
> **Scope**: Invoice, Income, Expense, Service (master), BankAccount (master), Finance Overview
> **Audience access**: super_admin, admin only

---

## 1. PHILOSOPHY

Finance module tracks **money in** (Invoice, Income) and **money out** (Expense). Plus master data needed for billing (Service, BankAccount).

**Principle**:
- Invoice = formal billing document (with PDF generation, sent to client)
- Income = actual cash received (may or may not have invoice — flexibility)
- Expense = operating costs (categorized)

Invoice and Income are NOT auto-linked. Admin can record income from invoice payment OR record income directly without invoice.

---

## 2. ROLE STAKEHOLDERS

| Role | Finance Access |
|---|---|
| `super_admin` | Full |
| `admin` | Full |
| `marketing`, `account_manager`, `advertiser` | NO ACCESS |

---

## 3. SUB-MODULES OVERVIEW

| Sub-module | Purpose | Master/Transactional |
|---|---|---|
| Invoice + InvoiceItem | Billing documents | Transactional |
| Income | Revenue records | Transactional |
| Expense | Cost records | Transactional |
| Expense Categories | Expense taxonomy | Master |
| Service | Service offerings (used as invoice line item template) | Master |
| Bank Account | Bank info for invoice PDF | Master |
| Finance Overview | Dashboard | — |

---

## 4. DATA MODEL

### `invoices`
```
id, invoice_number (unique, format: PREFIX/YYYY/MM/SEQ),
client_id (FK clients),
issue_date (date), due_date (date),
status enum('unpaid','paid') default 'unpaid',
paid_at (datetime, nullable),
notes (text, nullable),
tax_rate (decimal:5,2 default 0 — percentage),
subtotal, tax_amount, total (decimal:12,2 — computed),
bank_account_id (FK bank_accounts, nullable — selected for this invoice's PDF),
created_by (FK users),
timestamps, deleted_at (SoftDeletes)
```

**Critical**: `invoice_number` UNIQUE constraint applies to ALL rows including soft-deleted. Use `withTrashed()` in any uniqueness check or sequence generator.

### `invoice_items`
```
id, invoice_id (FK invoices, cascadeOnDelete),
service_id (FK services, nullable — references master service for default values),
description (text — line item description),
quantity (decimal), unit_price (decimal:12,2),
line_total (decimal:12,2 — computed: quantity * unit_price),
display_order (integer),
timestamps
```

### `incomes`
```
id, client_id (FK clients, nullable — income may not have client),
invoice_id (FK invoices, nullable — link to invoice if applicable),
source_category (string — mis. 'retainer', 'project', 'consulting', 'other'),
amount (decimal:12,2),
received_date (date),
payment_method (string, nullable — mis. 'bank_transfer', 'cash'),
notes (text, nullable),
created_by (FK users),
timestamps
```

### `expenses`
```
id, expense_category_id (FK expense_categories),
amount (decimal:12,2),
expense_date (date),
description (text),
vendor (string, nullable),
receipt_path (string, nullable — uploaded receipt image),
notes (text, nullable),
created_by (FK users),
timestamps
```

### `expense_categories` (master data)
```
id, name (unique), description, display_order, is_active, timestamps
```
Categorization for expenses. CRUD via admin.

### `services` (master data)
```
id, name, category (string — service grouping),
description (text),
default_price (decimal:12,2 — used as default unit_price when added to invoice),
unit (string — mis. 'monthly', 'one-time', 'per project'),
is_active (boolean default true),
display_order, timestamps
```

### `bank_accounts` (master data)
```
id, bank_name, account_holder, account_number,
swift_code (nullable — for international),
is_default (boolean default false — only one can be default),
is_active (boolean default true),
timestamps
```

---

## 5. MODELS

### `Invoice`
```php
class Invoice extends Model {
    use HasFactory, SoftDeletes, LogsActivity;
    
    // STATUSES const: STATUS_UNPAID, STATUS_PAID
    
    protected $casts = [
        'client_id' => 'integer',
        'bank_account_id' => 'integer',
        'created_by' => 'integer',
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'tax_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    // Relations
    client(): BelongsTo
    items(): HasMany InvoiceItem (ordered by display_order)
    bankAccount(): BelongsTo
    creator(): BelongsTo User
    incomes(): HasMany Income (income records linked to this invoice)
    
    // Scopes
    scopeForMonth($year, $month) // by issue_date
    scopeUnpaid(), scopePaid()
    scopeOverdue() // unpaid AND due_date < now
    
    // Helpers
    isPaid(): bool
    isOverdue(): bool
    days_overdue accessor
    
    // Methods
    markAsPaid($paymentDate = null) // updates status + paid_at
    recalculateTotals() // sum items, apply tax_rate, set subtotal/tax_amount/total
}
```

### `InvoiceItem`
```php
class InvoiceItem extends Model {
    // Relations: invoice (BelongsTo), service (BelongsTo)
    
    // Auto-compute line_total on save:
    // boot() static method with saving event
    // line_total = quantity * unit_price
}
```

### `Income`
```php
class Income extends Model {
    use HasFactory;
    
    protected $casts = [
        'client_id' => 'integer',
        'invoice_id' => 'integer',
        'amount' => 'decimal:2',
        'received_date' => 'date',
    ];
    
    // Relations
    client(): BelongsTo (nullable)
    invoice(): BelongsTo (nullable)
    creator(): BelongsTo User
    
    // Scopes
    scopeForMonth($year, $month) // by received_date
    
    // Constants
    SOURCE_CATEGORIES const: retainer, project, consulting, other
}
```

### `Expense`
```php
class Expense extends Model {
    use HasFactory;
    
    protected $casts = [
        'expense_category_id' => 'integer',
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];
    
    // Relations: category (BelongsTo ExpenseCategory), creator (User)
    // Scopes: forMonth($year, $month) // by expense_date
}
```

### `Service` (master)
```php
class Service extends Model {
    // Scopes: scopeActive(), scopeOrdered()
    // Used in: Invoice line item form (autocomplete + default price)
}
```

### `BankAccount` (master)
```php
class BankAccount extends Model {
    // Scopes: scopeActive(), scopeDefault()
    // Used in: Invoice form (select for PDF), invoice PDF rendering
}
```

### `ExpenseCategory` (master)
```php
class ExpenseCategory extends Model {
    // Scopes: scopeActive(), scopeOrdered()
}
```

---

## 6. INVOICE NUMBER GENERATION (CRITICAL)

### Service: `App\Services\InvoiceNumberGenerator`

**Method**: `next($issueDate, $prefix)`  
**Format**: `{PREFIX}/YYYY/MM/SEQ` — example: `DGMY/2026/05/031`

**Logic**:
1. Parse year + month from `$issueDate`
2. Find max sequence number for current YYYY/MM (matching prefix)
3. Increment by 1, zero-pad to 3 digits
4. Return formatted string

**Critical implementation details**:
- Uses `lockForUpdate()` inside `DB::transaction()` to prevent race condition
- Uses `withTrashed()` to scan ALL rows (including soft-deleted) — sequence number must NOT be reused
- MUST be called inside `DB::transaction()` block in calling code

**Why withTrashed**: DB UNIQUE constraint on `invoice_number` applies to all rows. If soft-deleted invoice has number `DGMY/2026/05/030`, generator without `withTrashed()` may produce `030` again → duplicate constraint violation.

**Prefix source**: `Setting::get('invoice_number_prefix')` (default `DGMY`).

### Usage Pattern in Controller
```php
DB::transaction(function () use ($validated) {
    $invoiceNumber = app(InvoiceNumberGenerator::class)->next(
        $validated['issue_date'],
        Setting::get('invoice_number_prefix', 'DGMY')
    );
    
    $invoice = Invoice::create([
        ...$validated,
        'invoice_number' => $invoiceNumber,
    ]);
    
    foreach ($validated['items'] as $item) {
        $invoice->items()->create($item);
    }
    
    $invoice->recalculateTotals();
    $invoice->save();
    
    return $invoice;
});
```

---

## 7. CONTROLLERS

### `Admin\InvoiceController`
**Methods**:
- `index()` — paginated list with filter (month/year/status/client search). Default: current month.
- `create()`, `store()` — wraps in DB transaction. Generate invoice_number. Create items. Recalculate totals.
- `show()` — detail view with items breakdown.
- `edit()`, `update()` — same transaction pattern. Update items (delete missing, update existing, create new).
- `destroy()` — soft delete.
- `markAsPaid($invoice)` — POST action. Sets status=paid, paid_at=now (or provided date). Optionally creates linked Income.
- `previewPdf($invoice)` — returns PDF inline (browser displays).
- `downloadPdf($invoice)` — returns PDF as download.

**PDF generation**: `barryvdh/laravel-dompdf` package. View: `admin/invoices/pdf.blade.php`. Bank account info from `bank_accounts` table (selected per invoice or default).

### `Admin\IncomeController`
- Resource CRUD
- Filter: month + year + source_category
- Optional: link to invoice (autocomplete via `Client::search()` + `Invoice::byClient()`)

### `Admin\ExpenseController`
- Resource CRUD
- Filter: month + year + expense_category_id
- Receipt upload optional (stored in `storage/app/public/expense-receipts/`)

### `Admin\ServiceController`, `Admin\BankAccountController`, master CRUD
- Resource CRUD
- Active toggle (mark inactive instead of delete to preserve invoice history)

### `Admin\FinanceOverviewController`
- Dashboard at `/admin/finance`
- KPIs: revenue (current month), expenses (current month), net, outstanding invoices, overdue invoices

---

## 8. ROUTES

```php
// Invoice (resource + custom)
Route::resource('invoices', Admin\InvoiceController::class);
Route::post('invoices/{invoice}/mark-as-paid', [Admin\InvoiceController::class, 'markAsPaid'])
    ->name('invoices.mark-as-paid');
Route::get('invoices/{invoice}/preview-pdf', [Admin\InvoiceController::class, 'previewPdf'])
    ->name('invoices.preview-pdf');
Route::get('invoices/{invoice}/download-pdf', [Admin\InvoiceController::class, 'downloadPdf'])
    ->name('invoices.download-pdf');

// Income, Expense
Route::resource('incomes', Admin\IncomeController::class);
Route::resource('expenses', Admin\ExpenseController::class);

// Master data
Route::resource('services', Admin\ServiceController::class);
Route::resource('bank-accounts', Admin\BankAccountController::class);
Route::resource('expense-categories', Admin\ExpenseCategoryController::class); // if exists, else inline in expenses

// Finance Overview
Route::get('/finance', [Admin\FinanceOverviewController::class, 'index'])->name('finance.overview');
```

---

## 9. VIEWS STRUCTURE

```
resources/views/admin/
├── finance/
│   └── overview.blade.php          (Finance dashboard)
├── invoices/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php              (detail with mark-as-paid action, PDF buttons)
│   ├── pdf.blade.php               (PDF template — separate from web view)
│   └── _form.blade.php             (with Alpine repeatable items + Tom Select for client/services)
├── incomes/
│   └── {index, create, edit, show, _form}.blade.php
├── expenses/
│   └── {index, create, edit, _form}.blade.php
├── services/
│   └── {index, create, edit, _form}.blade.php
└── bank-accounts/
    └── {index, create, edit, _form}.blade.php
```

---

## 10. KEY UI PATTERNS

### Invoice Form (Most Complex)
**Required**: Tom Select AJAX for client picker (uses `Admin\ClientController@search` endpoint).

**Repeatable items section** (Alpine x-for):
```blade
<div x-data="invoiceForm()">
    <template x-for="(item, idx) in items" :key="idx">
        <div class="grid grid-cols-12 gap-2">
            <select x-model="item.service_id" @change="onServiceChange(idx)">...</select>
            <input x-model="item.description">
            <input type="number" x-model="item.quantity" @input="updateLineTotal(idx)">
            <input type="number" x-model="item.unit_price" @input="updateLineTotal(idx)">
            <span x-text="formatRupiah(item.line_total)"></span>
            <button @click="removeRow(idx)">X</button>
        </div>
    </template>
    
    <button @click="addRow()">+ Add Item</button>
    
    <!-- Tax + Total summary -->
    <div>Subtotal: <span x-text="formatRupiah(subtotal)"></span></div>
    <div>Tax (<input x-model="taxRate">%): <span x-text="formatRupiah(taxAmount)"></span></div>
    <div>Total: <span x-text="formatRupiah(total)"></span></div>
</div>

<script>
function invoiceForm() {
    return {
        items: @json($initialItems),
        services: @json($services),
        taxRate: parseFloat(@json($initialTaxRate)) || 0,
        
        get subtotal() { return this.items.reduce((s, i) => s + (parseFloat(i.line_total) || 0), 0); },
        get taxAmount() { return this.subtotal * (this.taxRate / 100); },
        get total() { return this.subtotal + this.taxAmount; },
        
        addRow() { this.items.push({service_id: '', description: '', quantity: 1, unit_price: 0, line_total: 0}); },
        removeRow(idx) { if (this.items.length > 1) this.items.splice(idx, 1); },
        
        onServiceChange(idx) {
            const item = this.items[idx];
            const svc = this.services.find(s => String(s.id) === String(item.service_id));
            if (svc) {
                item.description = svc.name;
                item.unit_price = svc.default_price;
                this.updateLineTotal(idx);
            }
        },
        
        updateLineTotal(idx) {
            const item = this.items[idx];
            item.line_total = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
        }
    };
}
</script>
```

### Mark As Paid Flow
- On Invoice show page: button "Mark As Paid"
- Click → confirm dialog (or modal with payment_date picker)
- POST to `invoices.mark-as-paid` → status=paid, paid_at=date
- Optional: prompt "Create Income record?" → redirect to Income create with prefilled fields

### Invoice PDF
- Click "Download PDF" or "Preview PDF" buttons
- View: `admin/invoices/pdf.blade.php` (different layout from web detail view)
- Renders: header with logo, client info, invoice number, dates, items table, subtotal/tax/total, bank account info
- DomPDF generates from blade output

### Filter Pattern
Standard month/year filter (default current month) on Invoice/Income/Expense indexes.

---

## 11. WORKFLOWS

### Create Invoice
1. Admin → Invoices → Create
2. Tom Select: search & pick Client (active or any status — admin discretion)
3. Set issue_date (default today), due_date (default +14 days, configurable)
4. Pick Bank Account from dropdown (default = bank_accounts.is_default)
5. Add line items (Alpine repeatable):
   - Pick service from dropdown → auto-fill description + unit_price
   - Adjust quantity → line_total auto-calculates
   - Add multiple items
6. Set tax_rate (% — applied to subtotal)
7. Add notes (optional)
8. Submit → DB::transaction:
   - Generate invoice_number via `InvoiceNumberGenerator`
   - Create Invoice + items
   - Recalculate totals
9. Redirect to invoice show page

### Edit Invoice
- Same form, pre-filled
- Items sync logic: items with `id` update, no `id` create new, missing existing IDs delete
- Recalculate totals on save

### Mark As Paid
1. From show page → click "Mark As Paid"
2. Confirm → POST → status=paid, paid_at=now
3. Display "Paid on {date}" badge

### Generate PDF
1. From show page → click "Preview PDF" (inline) or "Download PDF"
2. DomPDF renders `pdf.blade.php` view
3. Returns PDF response (inline or attachment)

### Record Income (Without Invoice)
1. Admin → Incomes → Create
2. Select source_category (retainer/project/consulting/other)
3. Optional: link to client + invoice
4. Set amount, received_date, payment_method
5. Save

### Record Income (From Invoice Payment)
1. Mark invoice as paid
2. Optional flow: redirect to Income create with prefilled invoice_id, client_id, amount=invoice.total

### Record Expense
1. Admin → Expenses → Create
2. Select category from dropdown
3. Set amount, expense_date, description, vendor (optional)
4. Upload receipt image (optional)
5. Save

---

## 12. FINANCE OVERVIEW DASHBOARD

**Path**: `/admin/finance`  
**Access**: super_admin, admin

### Suggested Sections
1. **KPI Cards (current month)**:
   - Revenue (sum of incomes for month)
   - Expenses (sum of expenses for month)
   - Net (revenue - expenses)
   - Outstanding Invoices (count + total of unpaid invoices)
   - Overdue Invoices (highlighted if >0)

2. **Recent Invoices** (latest 10 with status badges)

3. **Cash Flow Trend** (chart, last 6 months income vs expense)

4. **Top Expense Categories** (current month breakdown)

---

## 13. KEY VALIDATIONS

### Invoice
- `client_id`: required, exists in clients
- `issue_date`, `due_date`: required, dates, due_date >= issue_date
- `status`: must be in STATUSES enum
- `bank_account_id`: nullable, exists in bank_accounts
- `tax_rate`: nullable, numeric, 0-100
- `items`: required, array, min 1 item
- `items.*.description`: required, string
- `items.*.quantity`: required, numeric, > 0
- `items.*.unit_price`: required, numeric, ≥ 0

### Income
- `amount`: required, numeric, > 0
- `received_date`: required, date
- `source_category`: required, must be in SOURCE_CATEGORIES enum
- `client_id`, `invoice_id`: nullable, exists if provided

### Expense
- `expense_category_id`: required, exists
- `amount`: required, numeric, > 0
- `expense_date`: required, date
- `description`: required, string
- `receipt_path`: nullable, image, max 2MB

### Service (master)
- `name`: required, unique
- `default_price`: nullable, numeric, ≥ 0

### Bank Account (master)
- `bank_name`, `account_holder`, `account_number`: required
- `is_default`: boolean (only one can be default — handled in observer or controller)

---

## 14. CALCULATION LOGIC

### Invoice Total
```
subtotal = sum(items.line_total)
tax_amount = subtotal * (tax_rate / 100)
total = subtotal + tax_amount
```

Stored in DB (not computed on read) — for PDF consistency and historical accuracy.

### Recalculation Trigger
`Invoice::recalculateTotals()` called:
- After creating items in store
- After updating items in update
- NEVER call after mark-as-paid (totals frozen)

---

## 15. KNOWN GOTCHAS

### Invoice Number Sequence + SoftDeletes (Bug 1 Family)
**Already fixed** in `InvoiceNumberGenerator::next()`. MUST `withTrashed()` to scan all rows including soft-deleted.

If creating new sequence-based unique fields elsewhere (mis. quote_number, receipt_number), apply same pattern.

### Decimal Precision
All money fields use `decimal:12,2` (12 digits total, 2 after decimal). Max value: 9,999,999,999.99 (~9.9 billion). Sufficient for IDR amounts.

DO NOT use `float` — floating point rounding errors break financial accuracy.

### PDF Rendering Quirks
- DomPDF doesn't support all CSS (no flexbox, limited grid)
- Use table-based layout for PDF view
- Test with various invoice lengths (1 item vs 20 items)
- Page breaks may cut tables awkwardly — consider `<tr style="page-break-inside: avoid;">`

### Invoice Edit After Mark-As-Paid
**Decision**: NO restriction — admin can still edit paid invoices. Reason: corrections needed after mark-as-paid (typo in line items, etc.). Activity log captures changes.

### Income Without Client
Income may have null `client_id` (mis. miscellaneous income, refund, interest). UI should handle null gracefully ("No client" badge).

### Expense Receipt Upload
Stored in `storage/app/public/expense-receipts/`. Public symlink required (`php artisan storage:link`). Path stored in DB.

### Currency
Default IDR (Indonesian Rupiah). Format helper: `formatRupiah($amount)` — converts to "Rp 1.234.567" with thousands separator. No conversion to other currencies (single-currency system).

### Tax Rate Variability
Per-invoice tax_rate (NOT system-wide). Different clients may have different tax obligations. Default 0%. PPN (11%) when applicable.

### Service vs Line Item Independence
Adding a Service to invoice line item is convenience-only:
- Pre-fills description + unit_price
- After creation, line item is INDEPENDENT — service later edited won't affect existing invoice

This is by design (invoice immutability for legal accuracy).

### Bank Account on Paid Invoice
Once invoice issued, even if bank_account is later deleted/edited, the saved `bank_account_id` reference may break in PDF. Soft-delete bank accounts (set `is_active=false`) instead of hard delete.

### Filter Default = Current Month
Invoice/Income/Expense index pages default filter to current month (`year = now()->year`, `month = now()->month`). User must explicitly change to view historical. Saves DB query load + matches typical workflow ("what happened this month?").


---

## 16. RECURRING EXPENSE — Draft/Confirm System (June 2, 2026)

**Status**: COMPLETE, tested end-to-end.

### Konsep
`recurring_type` (monthly/yearly) dulu cuma label — nggak ada logika propagasi. Sekarang recurring "ikut" ke bulan berikutnya via **draft + confirm**, bukan auto-commit (alasan: nilai bisa berubah tiap periode — gaji naik, hiring, upgrade langganan — jadi tiap occurrence butuh review sadar, bukan copy diam-diam).

- **monthly** → draft tiap bulan
- **yearly** → draft tiap 12 bulan dari periode sumber (TIDAK dibagi 12, tetap utuh per tahun)
- **one_time** → sekali itu aja, nggak pernah generate draft

### Schema tambahan `expenses` (migration 2026_06_02_205307)
- `status` enum('draft','confirmed','skipped') default 'confirmed'
- `recurring_parent_id` bigint unsigned nullable, FK→expenses.id nullOnDelete, cast integer
- `recurring_until` date nullable (null = jalan terus; set = stop setelah periode ini)

### Model (Expense)
- Const: STATUS_DRAFT/CONFIRMED/SKIPPED + STATUSES
- Scopes: `confirmed()`, `draft()`
- Relasi: `recurringParent()` (BelongsTo), `recurringChildren()` (HasMany)

### Service `App\Services\RecurringExpenseGenerator`
- `run()` — cari ROOT tiap chain (recurring_type monthly/yearly + recurring_parent_id NULL), generate draft buat periode yang kosong sampai bulan sekarang. Idempotent. Lazy (no cron).
- **CRITICAL**: chain di-resolve dari ROOT. `recurring_parent_id` occurrence SELALU = root->id (bukan parent langsung). Nilai default + periode resume diambil dari `latestOccurrence()` (root atau anak terbaru by date) — biar perubahan nilai KEBAWA ke depan. Bug pernah terjadi saat `$lastAmount` diambil dari source statis, bukan occurrence terakhir — fixed.
- Skipped occurrence tetap "mengisi" periodenya (nggak di-generate ulang).
- Tanggal draft = tanggal 1 periode (cuma penanda bulan; hari nggak relevan).

### Trigger (single pintu)
`run()` dipanggil di awal `ExpenseController::index()`. Finance Overview TIDAK trigger, cuma baca confirmed.

### GOTCHA WAJIB — confirmed scope
**Semua sum/count expense WAJIB `->confirmed()`** biar draft + skipped nggak bocor ke laporan. Sudah dipasang di:
- `ExpenseController::index()` — list, summary (total/recurring/one_time/count), categoryBreakdown
- `FinanceOverviewController` — 3 titik (this month, last month, trend 6 bulan)

**Ke depan**: fitur APA PUN yang ngitung expense HARUS pakai `confirmed()`. Lupa = draft kehitung sebagai uang nyata.

### Flow aksi (di Expense index)
- Section amber "Pending Recurring {bulan}" di atas summary, list draft bulan berjalan + tombol Confirm/Edit/Skip
- **Confirm** → status=confirmed (POST `admin.expenses.confirm-recurring`)
- **Skip** → status=skipped (POST `admin.expenses.skip-recurring`)
- **Edit** → buka form edit; SAVE draft = auto-confirm (logika di `update()`: kalau status draft saat disimpan → flip ke confirmed)

### Backfill (done June 2)
16 row recurring Mei jadi root (parent NULL, confirmed). April (id1,2,3) dihapus — data belum lengkap. Generate forward-only mulai Juni.

### UPDATE (June 3, 2026) — Expense Period input: DONE
Input form expense (create + edit) diganti dari date picker → dropdown **Bulan + Tahun**. Kolom DB `expense_date` tetap `date`, disimpan sebagai tanggal 1 bulan terpilih (via `resolveExpenseDate()` di ExpenseController). Validasi: `expense_month` (1-12) + `expense_year` (2026-2030), dirakit jadi `expense_date` di store + update. Dropdown tahun: 2026 s/d now+1. Tabel index: kolom "Date" → "Month", format `F Y` ("June 2026"), tanpa hari. Berlaku semua expense (one-time + recurring).