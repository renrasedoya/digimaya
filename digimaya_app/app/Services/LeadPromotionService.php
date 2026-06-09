<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

/**
 * LeadPromotionService — handle Lead → Client conversion.
 *
 * This service encapsulates the business logic for promoting a Lead
 * (which has been screened by the Marketing team) into a Client record
 * (owned by the Sales team).
 *
 * Used by LeadController::promote().
 */
class LeadPromotionService
{
    /**
     * Promote a Lead to a Client.
     *
     * Performs an atomic conversion:
     *   1. Validates Lead is eligible (status = 'screened')
     *   2. Creates Client record with mapped fields from Lead
     *   3. Updates Lead status to 'promoted' with FK to new Client
     *
     * @param  Lead   $lead         The Lead to promote (must be status 'screened')
     * @param  array  $extraData    Additional data from promote modal:
     *                                - lead_quality (good|average|poor)
     *                                - handover_notes (string, optional)
     * @return Client               The newly-created Client record
     *
     * @throws \InvalidArgumentException If Lead status is not 'screened'
     */
    public function promote(Lead $lead, array $extraData = []): Client
    {
        // Guard: Lead must be screened
        if ($lead->status !== 'screened') {
            throw new \InvalidArgumentException(
                "Lead #{$lead->id} cannot be promoted: status is '{$lead->status}', expected 'screened'."
            );
        }

        // Guard: Lead must have interest defined (Phase 14.8 — required pre-promotion)
        if (! $lead->isReadyForPromotion()) {
            $reason = empty($lead->interested_in)
                ? "interested_in field is empty"
                : "interested_in is 'others' but interested_in_other freetext is empty";
            throw new \InvalidArgumentException(
                "Lead #{$lead->id} cannot be promoted: {$reason}. Marketing must complete the screening first."
            );
        }

        // Guard: Lead must not already be promoted (idempotency)
        if (! is_null($lead->promoted_to_client_id)) {
            throw new \InvalidArgumentException(
                "Lead #{$lead->id} is already promoted to Client #{$lead->promoted_to_client_id}."
            );
        }

        return DB::transaction(function () use ($lead, $extraData) {
            // 1. Create Client with mapped fields
            $client = Client::create($this->mapLeadToClient($lead, $extraData));

            // 2. Update Lead with promoted state
            $lead->update([
                'status'                => 'promoted',
                'promoted_at'           => now(),
                'promoted_to_client_id' => $client->id,
            ]);

            return $client;
        });
    }

    /**
     * Map Lead fields to Client field structure.
     *
     * Phase 12.4.c — Lead & Client schema fully aligned.
     * Mapping is now a clean identity copy for shared concept fields.
     */
    private function mapLeadToClient(Lead $lead, array $extraData): array
    {
        $handoverNotes = $extraData['handover_notes'] ?? null;
        $leadQuality = $extraData['lead_quality'] ?? 'average';

        // Build aggregated notes for Client
        $notesParts = ["Promoted from Lead #{$lead->id} on " . now()->format('d M Y H:i')];

        if (! empty($lead->message)) {
            $notesParts[] = "\nOriginal message from Lead:\n{$lead->message}";
        }

        if (! empty($handoverNotes)) {
            $notesParts[] = "\nHandover notes from Marketing:\n{$handoverNotes}";
        }

        return [
            // Identity copy for shared concept fields
            'business_name' => $lead->business_name ?: $lead->contact_name,
            'website_url'   => $lead->website_url,
            'contact_name'  => $lead->contact_name,
            'contact_email' => $lead->contact_email,
            'contact_phone' => $lead->contact_phone,
            'source'        => $lead->source,

            // Interest carried over from Lead (Phase 14.8)
            'interested_in'       => $lead->interested_in,
            'interested_in_other' => $lead->interested_in_other,

            // Auto-slug: leave empty, model boot hook handles generation
            'slug' => '',

            // Client-specific fields
            'industry'     => null, // Not captured at Lead stage; Sales fills
            'status'       => 'prospect', // Default Client status post-promote
            'lead_quality' => $leadQuality,
            'notes'        => implode("\n", $notesParts),

            'created_by' => auth()->id(),
        ];
    }
}