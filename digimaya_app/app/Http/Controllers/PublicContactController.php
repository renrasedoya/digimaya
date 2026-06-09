<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactLeadRequest;
use App\Mail\NewLeadNotification;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PublicContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact.index', [
            'budgets' => Lead::BUDGETS,
        ]);
    }

    public function store(StoreContactLeadRequest $request): RedirectResponse
    {
        if (filled($request->input('website_hp'))) {
            Log::info('Public contact form: honeypot triggered', [
                'ip' => $request->ip(),
                'ua' => $request->userAgent(),
            ]);
            return redirect()->route('public.contact.thank-you');
        }

        $validated = $request->validated();
        unset($validated['website_hp']);
        $validated['source'] = 'contact_form';

        if (empty($validated['referrer_url'])) {
            $validated['referrer_url'] = $request->headers->get('referer');
        }

        // Idempotency check: prevent duplicate submission within 30 seconds.
        // Match on contact_email + contact_phone (most stable identity signals).
        // If duplicate found, skip create + skip email, but still redirect to thank-you
        // so user UX is identical (they don't realize their double-click was caught).
        $existingLead = Lead::where('contact_email', $validated['contact_email'])
            ->where('contact_phone', $validated['contact_phone'])
            ->where('created_at', '>=', now()->subSeconds(30))
            ->first();

        if ($existingLead) {
            Log::info('Public contact form: duplicate submission within 30s, skipped', [
                'existing_lead_id' => $existingLead->id,
                'ip'               => $request->ip(),
            ]);
            return redirect()->route('public.contact.thank-you');
        }

        $lead = Lead::create($validated);

        Log::info('Public contact form: lead created', [
            'lead_id' => $lead->id,
            'source'  => $lead->source,
            'utm'     => [
                'source'   => $lead->utm_source,
                'medium'   => $lead->utm_medium,
                'campaign' => $lead->utm_campaign,
            ],
        ]);

        // Send email notification to all admin team users (sync, non-blocking on failure)
        try {
            $recipients = User::whereIn('role', ['super_admin', 'admin', 'marketing'])
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();

            if (! empty($recipients)) {
                Mail::to($recipients)->send(new NewLeadNotification($lead));
                Log::info('Public contact form: notification email sent', [
                    'lead_id'    => $lead->id,
                    'recipients' => $recipients,
                ]);
            }
        } catch (\Throwable $e) {
            // Email failure should NOT break the form submission UX.
            // Log error and continue to thank-you page.
            Log::error('Public contact form: notification email FAILED', [
                'lead_id' => $lead->id,
                'error'   => $e->getMessage(),
            ]);
        }

        return redirect()->route('public.contact.thank-you');
    }

    public function thankYou(): View
    {
        return view('public.thank-you');
    }
}