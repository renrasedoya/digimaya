{{--
    SEO Schema: Service (JSON-LD)

    Usage:
        @push('head_schema')
            <x-seo.schema-service
                name="Google Ads Management"
                description="..."
                serviceType="Digital Advertising Management"
            />
        @endpush

    Renders schema.org Service type. Used on individual service pages
    to signal Google what specific service this page offers.

    Provider linked to Organization (@id homepage) so Google connects
    this service back to the Digimaya entity established on homepage.

    Props:
        $name        (required) — Service name, e.g. "Google Ads Management"
        $description (required) — Brief description, 1-2 sentences
        $serviceType (optional) — Category, e.g. "Digital Advertising Management"
        $url         (optional) — Page URL, defaults to current URL
        $areaServed  (optional) — Country code, defaults to "ID"

    Docs: https://schema.org/Service
--}}
@props([
    'name',
    'description',
    'serviceType' => 'Digital Advertising Management',
    'url' => null,
    'areaServed' => 'ID',
])

@php
    $pageUrl = $url ?? url()->current();
    $siteUrl = url('/');
    $orgId   = $siteUrl . '#organization';
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": @json($name),
    "description": @json($description),
    "serviceType": @json($serviceType),
    "url": "{{ $pageUrl }}",
    "areaServed": {
        "@type": "Country",
        "name": @json($areaServed)
    },
    "provider": {
        "@id": "{{ $orgId }}"
    }
}
</script>
