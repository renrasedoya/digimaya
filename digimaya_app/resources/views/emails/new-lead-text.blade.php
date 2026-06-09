Halo Tim,

Lead baru masuk via halaman /contact.

═══════════════════════════════════
DETAIL LEAD
═══════════════════════════════════

Nama        : {{ $lead->contact_name }}
Email       : {{ $lead->contact_email }}
WhatsApp    : {{ $lead->contact_phone }}
Bisnis      : {{ $lead->business_name ?? '-' }}
Website     : {{ $lead->website_url ?? '-' }}
Budget      : {{ $lead->budget_label ?? '-' }}
Source      : {{ $lead->source_label }}
Tertarik    : {{ $lead->interested_in_label ?? 'Belum ditentukan' }}

@if ($lead->message)
Pesan:
{{ $lead->message }}

@endif
@if ($lead->utm_source || $lead->utm_medium || $lead->utm_campaign)
═══════════════════════════════════
ATTRIBUTION
═══════════════════════════════════

UTM Source    : {{ $lead->utm_source ?? '-' }}
UTM Medium    : {{ $lead->utm_medium ?? '-' }}
UTM Campaign  : {{ $lead->utm_campaign ?? '-' }}
Referrer      : {{ $lead->referrer_url ?? '-' }}

@endif
═══════════════════════════════════

Buka di admin panel:
{{ route('admin.leads.show', $lead) }}

Submitted at: {{ $lead->created_at->format('d M Y, H:i') }} WIB

—
Digimaya CRM
