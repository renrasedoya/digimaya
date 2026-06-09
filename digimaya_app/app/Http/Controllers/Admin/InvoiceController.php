<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Services\InvoiceNumberGenerator;
use App\Services\InvoicePeriodFormatter;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $overdue = $request->boolean('overdue');

        $query = Invoice::with([
                'client:id,business_name',
                'project:id,name,client_id',
                'bankAccount:id,bank_name',
            ])
            ->byStatus($request->input('status'))
            ->recent();

        if ($month >= 1 && $month <= 12 && $year > 0) {
            $query->forMonth($year, $month);
        }

        if ($overdue) {
            $query->overdue();
        }

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('custom_client_name', 'like', "%{$search}%")
                  ->orWhereHas('client', fn ($c) => $c->where('business_name', 'like', "%{$search}%"))
                  ->orWhereHas('project', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('admin.invoices.index', [
            'invoices' => $invoices,
            'statuses' => Invoice::STATUSES,
            'currentStatus' => $request->input('status'),
            'currentSearch' => $search,
            'year' => $year,
            'month' => $month,
            'overdue' => $overdue,
        ]);
    }

    public function create(): View
    {
        return view('admin.invoices.create', [
            'services' => Service::active()->ordered()->get(['id', 'name', 'category']),
            'bankAccounts' => BankAccount::active()->ordered()->get(),
            'defaults' => [
                'due_offset_days' => (int) Setting::get('invoice_due_offset_days', 7),
                'tax_rate' => (float) Setting::get('invoice_default_tax_rate', 0),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateInvoice($request);

        $invoice = DB::transaction(function () use ($validated) {
            $number = InvoiceNumberGenerator::next($validated['issue_date']);

            $invoice = Invoice::create([
                'invoice_number' => $number,
                'client_id' => $validated['client_id'] ?? null,
                'project_id' => $validated['project_id'] ?? null,
                'custom_client_name' => $validated['custom_client_name'] ?? null,
                'custom_client_address' => $validated['custom_client_address'] ?? null,
                'custom_client_contact' => $validated['custom_client_contact'] ?? null,
                'period_start' => $validated['period_start'] ?? null,
                'period_end' => $validated['period_end'] ?? null,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'status' => Invoice::STATUS_UNPAID,
                'subtotal' => 0,
                'tax_rate' => $validated['tax_rate'],
                'tax_amount' => 0,
                'total' => 0,
                'notes' => $validated['notes'] ?? null,
                'bank_account_id' => $validated['bank_account_id'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $this->syncItems($invoice, $validated['items']);
            $this->recalculateTotals($invoice);

            return $invoice;
        });

        return redirect()
            ->route('admin.invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} created.");
    }

    public function edit(Invoice $invoice): View|RedirectResponse
    {
        if ($invoice->is_locked) {
            return redirect()
                ->route('admin.invoices.index')
                ->with('error', "Invoice {$invoice->invoice_number} is paid and cannot be edited.");
        }

        $invoice->load('items.service', 'client', 'project.client');

        return view('admin.invoices.edit', [
            'invoice' => $invoice,
            'services' => Service::active()->ordered()->get(['id', 'name', 'category']),
            'bankAccounts' => BankAccount::active()->ordered()->get(),
        ]);
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['items.service', 'client', 'project.client', 'bankAccount', 'createdBy', 'income']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        if ($invoice->is_locked) {
            return redirect()
                ->route('admin.invoices.index')
                ->with('error', "Invoice {$invoice->invoice_number} is paid and cannot be edited.");
        }

        $validated = $this->validateInvoice($request);

        DB::transaction(function () use ($invoice, $validated) {
            $invoice->update([
                'client_id' => $validated['client_id'] ?? null,
                'project_id' => $validated['project_id'] ?? null,
                'custom_client_name' => $validated['custom_client_name'] ?? null,
                'custom_client_address' => $validated['custom_client_address'] ?? null,
                'custom_client_contact' => $validated['custom_client_contact'] ?? null,
                'period_start' => $validated['period_start'] ?? null,
                'period_end' => $validated['period_end'] ?? null,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'tax_rate' => $validated['tax_rate'],
                'notes' => $validated['notes'] ?? null,
                'bank_account_id' => $validated['bank_account_id'] ?? null,
            ]);

            $this->syncItems($invoice, $validated['items']);
            $this->recalculateTotals($invoice);
        });

        return redirect()
            ->route('admin.invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} updated.");
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', "Invoice {$invoice->invoice_number} deleted.");
    }

    /**
     * Mark invoice as paid + auto-create linked Income record (atomic).
     * source_category auto-resolved: project mode → 'agency', else admin-selected.
     */
    public function markAsPaid(Request $request, Invoice $invoice): RedirectResponse
    {
        if ($invoice->is_locked) {
            return redirect()
                ->route('admin.invoices.index')
                ->with('error', "Invoice {$invoice->invoice_number} is already paid.");
        }

        $validated = $request->validate([
            'paid_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:bank_transfer,cash,qris,credit_card,other',
            'source_category' => 'required|in:agency,academy,other',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            $invoice->update([
                'status' => Invoice::STATUS_PAID,
                'paid_date' => $validated['paid_date'],
            ]);

            // Auto-resolve service_id: only if ALL items share the same non-null service.
            $serviceIds = $invoice->items()->pluck('service_id')->unique();
            $resolvedServiceId = ($serviceIds->count() === 1 && $serviceIds->first() !== null)
                ? $serviceIds->first()
                : null;

            // Source category: server-side enforce from service.category if service exists.
            // Otherwise fallback to admin's manual selection (Mode 3 / mixed services).
            $sourceCategory = $validated['source_category'];
            if ($resolvedServiceId) {
                $service = \App\Models\Service::find($resolvedServiceId);
                if ($service && $service->category) {
                    $sourceCategory = $service->category;
                }
            }

            Income::create([
                'invoice_id' => $invoice->id,
                'client_id' => $invoice->client_id,
                'service_id' => $resolvedServiceId,
                'created_by' => Auth::id(),
                'source_category' => $sourceCategory,
                'amount' => $invoice->total,
                'received_date' => $validated['paid_date'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $invoice->invoice_number,
                'description' => "Auto-created from invoice {$invoice->invoice_number}",
            ]);
        });

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', "Invoice {$invoice->invoice_number} marked as paid. Income record created.");
    }

    /**
     * Shared validation for store + update with 3-mode matrix.
     *
     * Mode resolved from `mode` field (project|client|custom).
     * - project: project_id + client_id required, client_id must match project.client_id
     * - client:  client_id required, project_id null
     * - custom:  custom_client_name required, client_id + project_id null
     *
     * Period start/end are optional for all modes; if either is provided, both required.
     */
    private function validateInvoice(Request $request): array
    {
        $mode = $request->input('mode');

        $rules = [
            'mode' => ['required', Rule::in([Invoice::MODE_PROJECT, Invoice::MODE_CLIENT, Invoice::MODE_CUSTOM])],
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            'bank_account_id' => 'nullable|exists:bank_accounts,id',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'nullable|exists:services,id',
            'items.*.description' => 'nullable|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01|max:99999',
            'items.*.unit_price' => 'required|numeric|min:0|max:9999999999',
            'period_start' => 'nullable|date|required_with:period_end',
            'period_end' => 'nullable|date|after_or_equal:period_start|required_with:period_start',
        ];

        if ($mode === Invoice::MODE_PROJECT) {
            $rules['project_id'] = 'required|exists:projects,id';
            $rules['client_id'] = 'required|exists:clients,id';
        } elseif ($mode === Invoice::MODE_CLIENT) {
            $rules['client_id'] = 'required|exists:clients,id';
            $rules['project_id'] = 'prohibited';
        } elseif ($mode === Invoice::MODE_CUSTOM) {
            $rules['custom_client_name'] = 'required|string|max:200';
            $rules['custom_client_address'] = 'nullable|string|max:1000';
            $rules['custom_client_contact'] = 'nullable|string|max:200';
            $rules['client_id'] = 'prohibited';
            $rules['project_id'] = 'prohibited';
        }

        $validated = $request->validate($rules, [
            'items.required' => 'Invoice must have at least one line item.',
            'items.min' => 'Invoice must have at least one line item.',
            'project_id.required' => 'Project is required in Project mode.',
            'client_id.required' => 'Client is required.',
            'custom_client_name.required' => 'Client name is required in Custom mode.',
            'project_id.prohibited' => 'Project should not be set in this mode.',
            'client_id.prohibited' => 'Client should not be set in Custom mode.',
        ]);

        // Cross-field check: project's client must match selected client_id
        if ($mode === Invoice::MODE_PROJECT) {
            $project = Project::find($validated['project_id']);
            if ($project && (int) $project->client_id !== (int) $validated['client_id']) {
                throw ValidationException::withMessages([
                    'client_id' => "Selected client doesn't match project's client.",
                ]);
            }
        }

        return $validated;
    }

    /**
     * Replace all items on the invoice with the given array.
     */
    private function syncItems(Invoice $invoice, array $items): void
    {
        $invoice->items()->delete();

        foreach ($items as $idx => $item) {
            $invoice->items()->create([
                'service_id' => $item['service_id'] ?? null,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'sort_order' => $idx + 1,
            ]);
        }
    }

    /**
     * Recompute subtotal, tax_amount, total from current items + tax_rate.
     */
    private function recalculateTotals(Invoice $invoice): void
    {
        $subtotal = $invoice->items()->sum('line_total');
        $taxAmount = round((float) $subtotal * (float) $invoice->tax_rate / 100, 2);

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $subtotal + $taxAmount,
        ]);
    }

    private function buildPdf(Invoice $invoice)
    {
        $invoice->load(['items.service', 'client', 'project.client', 'bankAccount']);

        $company = \App\Models\Setting::group('company');
        $footerNotes = \App\Models\Setting::get('invoice_footer_notes', '');

        return Pdf::loadView('admin.invoices.pdf', [
            'invoice' => $invoice,
            'company' => $company,
            'footerNotes' => $footerNotes,
        ])->setPaper('a4', 'portrait');
    }

    private function pdfFilename(Invoice $invoice): string
    {
        $safe = preg_replace('/[^A-Za-z0-9_.-]/', '-', $invoice->invoice_number);
        return 'Invoice-' . $safe . '.pdf';
    }

    public function downloadPdf(Invoice $invoice): Response
    {
        return $this->buildPdf($invoice)->download($this->pdfFilename($invoice));
    }

    public function previewPdf(Invoice $invoice): Response
    {
        return $this->buildPdf($invoice)->stream($this->pdfFilename($invoice));
    }
}
