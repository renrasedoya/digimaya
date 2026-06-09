<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Store a newly created bank account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        BankAccount::create([
            'bank_name'      => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'label'          => $validated['label'] ?? null,
            'is_active'      => $validated['is_active'] ?? true,
            'sort_order'     => $validated['sort_order'] ?? 0,
        ]);

        return $this->redirectToBankingTab('Bank account added.');
    }

    /**
     * Update the specified bank account.
     */
    public function update(Request $request, BankAccount $bankAccount): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        $bankAccount->update([
            'bank_name'      => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'label'          => $validated['label'] ?? null,
            'is_active'      => $validated['is_active'] ?? false,
            'sort_order'     => $validated['sort_order'] ?? 0,
        ]);

        return $this->redirectToBankingTab('Bank account updated.');
    }

    /**
     * Soft-delete the specified bank account.
     */
    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        $bankAccount->delete();

        return $this->redirectToBankingTab('Bank account deleted.');
    }

    /**
     * Shared validation rules for store + update.
     */
    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:255',
            'label'          => 'nullable|string|max:100',
            'is_active'      => 'sometimes|boolean',
            'sort_order'     => 'nullable|integer|min:0|max:9999',
        ]);
    }

    /**
     * Redirect back to settings page Banking tab with flash message.
     */
    private function redirectToBankingTab(string $message): RedirectResponse
    {
        return redirect()
            ->route('admin.settings.index', ['tab' => 'banking'])
            ->with('success', $message);
    }
}
