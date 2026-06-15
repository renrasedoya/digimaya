<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page with all groups loaded.
     */
    public function index(): View
    {
        $company = Setting::group('company');
        $invoice = Setting::group('invoice');
        $tracking = Setting::group('tracking');
        $bankAccounts = BankAccount::ordered()->get();

        return view('admin.settings.index', compact('company', 'invoice', 'tracking', 'bankAccounts'));
    }

    /**
     * Update Company Info group.
     */
    public function updateCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name'           => 'required|string|max:255',
            'company_address_line_1' => 'nullable|string|max:255',
            'company_address_line_2' => 'nullable|string|max:255',
            'company_email'          => 'nullable|email|max:255',
            'company_phone'          => 'nullable|string|max:50',
            'company_npwp'           => 'nullable|string|max:50',
        ]);

        $this->saveGroup($validated);

        return redirect()
            ->route('admin.settings.index', ['tab' => 'company'])
            ->with('success', 'Company info updated.');
    }

    /**
     * Update Invoice Settings group.
     */
    public function updateInvoice(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_number_prefix'    => 'required|string|max:20|regex:/^[A-Z0-9]+$/',
            'invoice_due_offset_days'  => 'required|integer|min:0|max:365',
            'invoice_default_tax_rate' => 'required|numeric|min:0|max:100',
            'invoice_footer_notes'     => 'nullable|string|max:1000',
        ], [
            'invoice_number_prefix.regex' => 'Invoice prefix may only contain uppercase letters and digits.',
        ]);

        $this->saveGroup($validated);

        return redirect()
            ->route('admin.settings.index', ['tab' => 'invoice'])
            ->with('success', 'Invoice settings updated.');
    }

    /**
     * Update Tracking / Custom Code group (Insert Headers & Footers).
     *
     * Raw HTML/JS snippets are stored verbatim and rendered unescaped on public pages,
     * so this is restricted to super_admin via the route group. No sanitisation is applied
     * by design — the whole point is to embed third-party scripts (GTM, GA4, pixels).
     */
    public function updateTracking(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tracking_code_head'       => 'nullable|string|max:65535',
            'tracking_code_body_open'  => 'nullable|string|max:65535',
            'tracking_code_body_close' => 'nullable|string|max:65535',
        ]);

        $this->saveGroup($validated);

        return redirect()
            ->route('admin.settings.index', ['tab' => 'tracking'])
            ->with('success', 'Tracking & custom code updated.');
    }

    /**
     * Bulk-save validated key/value pairs via the Setting model helper.
     * Keys not present in the settings table are silently skipped (Setting::set returns false).
     */
    private function saveGroup(array $validated): void
    {
        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }
    }
}
