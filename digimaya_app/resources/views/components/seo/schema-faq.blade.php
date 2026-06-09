{{--
    SEO Schema: FAQPage (JSON-LD)

    Usage:
        @push('head_schema')
            <x-seo.schema-faq :faqs="$faqs" />
        @endpush

    Renders schema.org FAQPage type. Powers Google's FAQ rich result
    (expandable accordion in SERP). Significant SERP real estate boost
    for informational queries.

    Sanitization: answers are stripped of HTML + entity-decoded for
    safety and JSON cleanliness. Google requirement satisfied because
    plain text content is what matters for FAQ rich results.

    Props:
        $faqs (Illuminate\Support\Collection|iterable) — collection of
              Faq records, each must have ->question and ->answer.

    Renders nothing if $faqs is empty.

    Docs: https://schema.org/FAQPage
          https://developers.google.com/search/docs/appearance/structured-data/faqpage
--}}
@props(['faqs'])

@php
    // Normalize input: accept Collection, array, or null
    $faqItems = collect($faqs ?? [])
        ->filter(fn($f) => !empty($f->question) && !empty($f->answer))
        ->map(function ($f) {
            // Strip HTML, decode entities, normalize whitespace
            $cleanAnswer = trim(
                preg_replace('/\s+/', ' ',
                    html_entity_decode(
                        strip_tags((string) $f->answer),
                        ENT_QUOTES | ENT_HTML5,
                        'UTF-8'
                    )
                )
            );

            $cleanQuestion = trim(
                html_entity_decode(
                    strip_tags((string) $f->question),
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                )
            );

            return [
                'question' => $cleanQuestion,
                'answer'   => $cleanAnswer,
            ];
        })
        ->filter(fn($item) => !empty($item['question']) && !empty($item['answer']))
        ->values();
@endphp

@if($faqItems->isNotEmpty())
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($faqItems as $item)
        {
            "@type": "Question",
            "name": @json($item['question']),
            "acceptedAnswer": {
                "@type": "Answer",
                "text": @json($item['answer'])
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif
