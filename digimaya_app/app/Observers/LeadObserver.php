<?php

namespace App\Observers;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadObserver
{
    /**
     * Handle the Lead "creating" event.
     *
     * Auto-fill created_by from current authenticated user (HTTP context only).
     */
    public function creating(Lead $lead): void
    {
        if (empty($lead->created_by) && Auth::check()) {
            $lead->created_by = Auth::id();
        }
    }

    /**
     * Handle the Lead "updating" event.
     *
     * Auto-set timestamp fields based on status transitions:
     * - When status moves to 'contacted' for first time, set first_contacted_at
     * - Whenever a status update happens (after 'new'), update last_contacted_at
     * - When status moves to 'promoted', set promoted_at if not already set
     * - When status moves to 'disqualified', set disqualified_at if not already set
     */
    public function updating(Lead $lead): void
    {
        if (! $lead->isDirty('status')) {
            return;
        }

        $newStatus = $lead->status;

        // First contact tracking
        if ($newStatus === 'contacted' && empty($lead->first_contacted_at)) {
            $lead->first_contacted_at = now();
        }

        // Last contact tracking — anytime status moves beyond 'new'
        if (in_array($newStatus, ['contacted', 'screened'])) {
            $lead->last_contacted_at = now();
        }

        // Promotion timestamp
        if ($newStatus === 'promoted' && empty($lead->promoted_at)) {
            $lead->promoted_at = now();
        }

        // Disqualification timestamp
        if ($newStatus === 'disqualified' && empty($lead->disqualified_at)) {
            $lead->disqualified_at = now();
        }
    }
}
