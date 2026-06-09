{{--
    SEO Schema: Course (JSON-LD)

    Usage:
        @push('head_schema')
            <x-seo.schema-course
                name="Google Ads Academy"
                description="..."
                courseType="Digital Marketing Education"
            />
        @endpush

    Renders schema.org Course type. Used on education/training landing pages
    to signal Google what learning program this page offers.

    Provider linked to Organization (@id homepage) so Google connects
    this course back to the Digimaya entity.

    Generic design — NO date/duration/cohort fields. Stable across
    schedule changes, batch reschedules, format changes.
    If specific batch info is needed in the future (e.g. Event-type schema
    for specific cohort dates), use a separate component.

    Props:
        $name        (required) — Course name, e.g. "Google Ads Academy"
        $description (required) — Brief description, 1-2 sentences
        $courseType  (optional) — Category, e.g. "Digital Marketing Education"
        $url         (optional) — Page URL, defaults to current URL

    Docs: https://schema.org/Course
--}}
@props([
    'name',
    'description',
    'courseType' => 'Digital Marketing Education',
    'url' => null,
])

@php
    $pageUrl = $url ?? url()->current();
    $siteUrl = url('/');
    $orgId   = $siteUrl . '#organization';
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Course",
    "name": @json($name),
    "description": @json($description),
    "courseType": @json($courseType),
    "url": "{{ $pageUrl }}",
    "inLanguage": "id-ID",
    "provider": {
        "@id": "{{ $orgId }}"
    }
}
</script>
