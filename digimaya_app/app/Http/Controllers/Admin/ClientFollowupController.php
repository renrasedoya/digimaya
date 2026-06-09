<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientFollowup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientFollowupController extends Controller
{
    /**
     * Store a new followup for the given client.
     * Uses nested route /admin/clients/{client}/followups (this works on hosting).
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        // A lost client is out of the follow-up pipeline — no new followups.
        // Re-engage it (lost -> prospect) first if contact resumes.
        if ($client->status === 'lost') {
            return redirect()
                ->route('admin.clients.show', $client)
                ->with('error', 'Client is marked Lost. Re-engage it to Prospect before adding follow-ups.');
        }

        $validated = $request->validate($this->validationRules());

        $validated['client_id'] = $client->id;

        ClientFollowup::create($validated);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Follow-up berhasil ditambahkan.');
    }

    /**
     * Update an existing followup.
     * Uses flat route /admin/client-followups/{followup}/update.
     * Mirror Lead pattern: dual-mode (pending/completed) handled at view layer.
     */
    public function update(Request $request, ClientFollowup $followup): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        $followup->update($validated);

        return redirect()
            ->route('admin.clients.show', $followup->client)
            ->with('success', 'Follow-up berhasil diperbarui.');
    }

    /**
     * Soft-delete the followup.
     * Uses flat POST route /admin/client-followups/{followup}/delete.
     */
    public function destroy(ClientFollowup $followup): RedirectResponse
    {
        $client = $followup->client;
        $followup->delete();

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Follow-up berhasil dihapus.');
    }

    /**
     * Mark followup as completed via Modal Complete.
     * Outcome REQUIRED (3 values: positive/negative/no_response).
     *
     * Phase 12.2 scope: outcome recorded for tracking only.
     * Client.stage NOT auto-updated — Sales manually updates stage via Edit Client.
     * State machine deferred to Phase 12.4.
     *
     * Idempotent: re-completing already-completed followup updates outcome/notes
     * without changing completed_at timestamp.
     */
    public function complete(Request $request, ClientFollowup $followup): RedirectResponse
    {
        $validated = $request->validate([
            'outcome' => ['required', Rule::in(array_keys(ClientFollowup::OUTCOMES))],
            'notes'   => ['nullable', 'string', 'max:5000'],
        ]);

        // Drop empty notes from payload — preserve existing notes if user
        // doesn't fill the textarea at Complete time.
        if (empty($validated['notes'])) {
            unset($validated['notes']);
        }

        // Set completed_at only on first complete (idempotent)
        if (is_null($followup->completed_at)) {
            $validated['completed_at'] = now();
        }

        $followup->update($validated);

        return redirect()
            ->route('admin.clients.show', $followup->client)
            ->with('success', 'Follow-up ditandai selesai.');
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

            'method'           => ['required', Rule::in(array_keys(ClientFollowup::METHODS))],
            'outcome'          => ['nullable', Rule::in(array_keys(ClientFollowup::OUTCOMES))],
            'notes'            => ['nullable', 'string', 'max:5000'],
        ];
    }
}