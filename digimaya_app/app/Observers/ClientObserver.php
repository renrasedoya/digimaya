<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\ClientStatusHistory;
use Illuminate\Support\Facades\Auth;

class ClientObserver
{
    /**
     * Handle the Client "creating" event.
     * Auto-fill client_since when initial status is 'active'.
     */
    public function creating(Client $client): void
    {
        if ($client->status === 'active' && empty($client->client_since)) {
            $client->client_since = now();
        }
    }

    /**
     * Handle the Client "created" event.
     * Insert initial status history row.
     */
    public function created(Client $client): void
    {
        ClientStatusHistory::create([
            'client_id'   => $client->id,
            'status_from' => null,
            'status_to'   => $client->status,
            'changed_at'  => $client->created_at ?? now(),
            'changed_by'  => Auth::id(),
            'notes'       => 'Initial entry',
        ]);
    }

    /**
     * Handle the Client "updating" event.
     * Auto-fill client_since on first activation if still empty.
     */
    public function updating(Client $client): void
    {
        $statusChangedToActive = $client->isDirty('status')
            && $client->status === 'active'
            && $client->getOriginal('status') !== 'active';

        if ($statusChangedToActive && empty($client->client_since)) {
            $client->client_since = now();
        }
    }

    /**
     * Handle the Client "updated" event.
     * Insert history row if status changed.
     */
    public function updated(Client $client): void
    {
        if (! $client->wasChanged('status')) {
            return;
        }

        ClientStatusHistory::create([
            'client_id'   => $client->id,
            'status_from' => $client->getOriginal('status'),
            'status_to'   => $client->status,
            'changed_at'  => now(),
            'changed_by'  => Auth::id(),
        ]);

        // When a client becomes 'lost' (prospect that won't close), auto-close
        // its pending followups so it stops surfacing in the Followup Card.
        if ($client->status === 'lost') {
            $client->followups()
                ->whereNull('completed_at')
                ->update([
                    'completed_at' => now(),
                    'outcome'      => 'closed_lost',
                ]);
        }
    }
}