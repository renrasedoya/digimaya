{{--
    SEO Schema: Organization (JSON-LD)

    Usage:
        @push('head_schema')
            <x-seo.schema-organization />
        @endpush

    Renders schema.org Organization type. The foundational entity for
    Digimaya. Used by Google for Knowledge Graph, sameAs verification,
    local SEO signals, and logo recognition.

    Data source: config/digimaya.php (brand, contact, address, social)

    Docs: https://schema.org/Organization
--}}
@php
    $brand   = config('digimaya.brand');
    $contact = config('digimaya.contact');
    $address = config('digimaya.address');
    $social  = config('digimaya.social', []);

    $siteUrl = url('/');
    $orgId   = $siteUrl . '#organization';
    $logoUrl = asset($brand['logo'] ?? 'images/logo/logo-blue.png');

    // Strip null sosmed URLs so sameAs only contains active profiles.
    $sameAs = array_values(array_filter($social ?? [], fn($url) => !empty($url)));
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "@id": "{{ $orgId }}",
    "name": @json($brand['name'] ?? 'Digimaya'),
    "legalName": @json($brand['legal_name'] ?? 'Digimaya'),
    "url": "{{ $siteUrl }}",
    "description": @json($brand['description'] ?? ''),
    "slogan": @json($brand['tagline'] ?? ''),
    "logo": {
        "@type": "ImageObject",
        "url": "{{ $logoUrl }}"
    },
    "image": "{{ $logoUrl }}"
    @if(!empty($contact['whatsapp']))
    ,"contactPoint": {
        "@type": "ContactPoint",
        "telephone": @json($contact['whatsapp']),
        "contactType": "customer service",
        "areaServed": "ID",
        "availableLanguage": ["Indonesian", "English"]
    }
    @endif
    @if(!empty($address['locality']) || !empty($address['region']) || !empty($address['country']))
    ,"address": {
        "@type": "PostalAddress"
        @if(!empty($address['locality'])), "addressLocality": @json($address['locality']) @endif
        @if(!empty($address['region'])), "addressRegion": @json($address['region']) @endif
        @if(!empty($address['country'])), "addressCountry": @json($address['country']) @endif
    }
    @endif
    @if(!empty($sameAs))
    ,"sameAs": {!! json_encode($sameAs, JSON_UNESCAPED_SLASHES) !!}
    @endif
}
</script>
