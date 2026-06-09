{{--
    SEO Schema: WebSite (JSON-LD)

    Usage:
        @push('head_schema')
            <x-seo.schema-website />
        @endpush

    Renders schema.org WebSite type. Establishes the site as an entity
    to Google. Linked to Organization via @id reference (publisher).

    Docs: https://schema.org/WebSite
--}}
@php
    $siteName  = config('digimaya.brand.name', 'Digimaya');
    $siteUrl   = url('/');
    $orgId     = $siteUrl . '#organization';
    $websiteId = $siteUrl . '#website';
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "@id": "{{ $websiteId }}",
    "url": "{{ $siteUrl }}",
    "name": @json($siteName),
    "inLanguage": "id-ID",
    "publisher": {
        "@id": "{{ $orgId }}"
    }
}
</script>
