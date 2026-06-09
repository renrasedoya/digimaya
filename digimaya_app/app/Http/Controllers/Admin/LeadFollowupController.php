<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFollowup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadFollowupController extends Controller
{
    /**
     * Store a new followup for the given lead.
     * Uses nested route /admin/leads/{lead}/followups (this works on hosting).
     */
    public function store(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        $validated['lead_id'] = $lead->id;

        LeadFollowup::create($validated);

        return redirect()
            ->route('admin.leads.show', $lead)
            ->with('success', 'Follow-up berhasil ditambahkan.');
    }

    /**
     * Update an existing followup.
     * Uses flat route /admin/lead-followups/{followup}/update.
     */
    public function update(Request $request, LeadFollowup $followup): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        $followup->update($validated);

        return redirect()
            ->route('admin.leads.show', $followup->lead)
            ->with('success', 'Follow-up berhasil diperbarui.');
    }

    /**
     * Soft-delete the followup.
     * Uses flat POST route /admin/lead-followups/{followup}/delete.
     */
    public function destroy(LeadFollowup $followup): RedirectResponse
    {
        $lead = $followup->lead;
        $followup->delete();

        return redirect()
            ->route('admin.leads.show', $lead)
            ->with('success', 'Follow-up berhasil dihapus.');
    }

    /**
     * Mark followup as completed via Modal Complete.
     * Outcome is REQUIRED. Auto-updates Lead status based on outcome:
     *   - positive    → Lead status = 'screened' (ready to promote)
     *   - negative    → Lead status = 'disqualified'
     *   - no_response → Lead status unchanged (AM continues with new attempt)
     *
     * Idempotent: re-completing already-completed followup updates outcome/notes
     * without changing completed_at timestamp.
     */
    public function complete(Request $request, LeadFollowup $followup): RedirectResponse
    {
        $validated = $request->validate([
            'outcome' => ['required', Rule::in(array_keys(LeadFollowup::OUTCOMES))],
            'notes'   => ['nullable', 'string', 'max:5000'],
        ]);

        // Set completed_at only on first complete (idempotent)
        if (is_null($followup->completed_at)) {
            $validated['completed_at'] = now();
        }

        $followup->update($validated);

        // Auto-update Lead status based on outcome (refined model: complete = trigger decision)
        $lead = $followup->lead;
        $statusMessage = '';

        if ($validated['outcome'] === 'positive' && $lead->status !== 'screened') {
            $lead->update(['status' => 'screened']);
            $statusMessage = ' Lead status diperbarui menjadi "Screened" — siap untuk promote.';
        } elseif ($validated['outcome'] === 'negative' && $lead->status !== 'disqualified') {
            $lead->update(['status' => 'disqualified']);
            $statusMessage = ' Lead status diperbarui menjadi "Disqualified".';
        }
        // no_response: tidak ubah Lead status

        return redirect()
            ->route('admin.leads.show', $lead)
            ->with('success', 'Follow-up ditandai selesai.' . $statusMessage);
    }

    /**
     * Validation rules for store/update.
     */
    private function validationRules(): array
    {
        return [
            'scheduled_at'     => ['required', 'date'],
            'completed_at'     => ['nullable', 'date'],
            'next_followup_at' => ['nullable', 'date', 'after_or_equal:scheduled_at'],

            'method'           => ['required', Rule::in(array_keys(LeadFollowup::METHODS))],
            'outcome'          => ['nullable', Rule::in(array_keys(LeadFollowup::OUTCOMES))],
            'notes'            => ['nullable', 'string', 'max:5000'],
        ];
    }
}
