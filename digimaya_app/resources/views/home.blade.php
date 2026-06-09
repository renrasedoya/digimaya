@extends('layouts.public')

@section('meta_title', 'Digimaya | Google Ads Agency, Premier Partner Indonesia')
@section('meta_description', 'Digimaya adalah Google Ads Agency Premier Partner di Indonesia. Strategi terukur, tracking presisi, transparansi penuh. Konsultasi gratis 30 menit.')

{{-- SEO Schema JSON-LD for homepage --}}
@push('head_schema')
    <x-seo.schema-website />
    <x-seo.schema-organization />
    <x-seo.schema-faq :faqs="$faqs" />
@endpush

@section('content')

@php
    $hasAwards = $awards->isNotEmpty();
@endphp

<section class="relative overflow-x-clip bg-gradient-to-b from-brand-50/30 to-white">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-28">

        @if ($hasAwards)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

                <div>

                    <h1 class="heading-hero mb-6">
                        Google Ads Agency untuk
                        <span class="bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                            Brand yang Fokus pada Konversi.
                        </span>
                    </h1>

                    <p class="body-lead mb-10 max-w-xl">
                       Digimaya membantu bisnis mendapatkan leads dan penjualan melalui strategi Google Ads yang lebih terarah, terukur, dan berbasis data.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('public.contact.show') }}"
                           class="btn-primary">
                            Konsultasi Gratis
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>

                        <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads"
                           target="_blank" rel="noopener"
                           class="btn-secondary">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>

                <div>
                    <div class="bg-gray-50/80 border border-gray-100 rounded-3xl p-6 sm:p-8 lg:p-10 backdrop-blur-sm">
                        <p class="eyebrow eyebrow-card">
                            Google Ads Award &amp; Certification
                        </p>

                        <ul class="divide-y divide-gray-200">
                            @foreach ($awards as $award)
                                <li class="flex items-center gap-5 py-5 first:pt-0 last:pb-0">
                                    @if ($award->image_url)
                                        <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-white border border-gray-100 rounded-2xl flex items-center justify-center p-2 shadow-sm">
                                            <img src="{{ $award->image_url }}" alt="{{ $award->name }}" class="max-w-full max-h-full object-contain" loading="lazy">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-white border border-gray-100 rounded-2xl flex items-center justify-center shadow-sm">
                                            <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">
                                            {{ $award->name }}
                                        </h3>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        @else
            <div class="max-w-3xl mx-auto text-center">
                <p class="eyebrow">
                    Google Premier Partner Indonesia
                </p>

                <h1 class="heading-hero mb-6">
                    Google Ads Agency untuk
                    <span class="block bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                        Brand yang Fokus pada Konversi.
                    </span>
                </h1>

                <p class="body-lead mb-10 max-w-2xl mx-auto">
                    Kami bukan agensi biasa, Digimaya adalah Google-Managed Agency yang 100% disupport oleh Google Indonesia dalam mengelola dan mengoptimasi semua klien.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('public.contact.show') }}"
                       class="btn-primary">
                        Konsultasi Gratis
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads"
                       target="_blank" rel="noopener"
                       class="btn-secondary">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        @endif

    </div>
</section>


{{-- ============== SECTION 2 — LOGO BAR (clients trust signal) ============== --}}
@if ($clients->isNotEmpty())
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">

        {{-- Title --}}
        <p class="logo-bar-text">
            Dipercaya oleh berbagai brand dari beragam industri.
        </p>

        {{-- Logo grid --}}
        <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-8 sm:gap-x-12 lg:gap-x-16 mb-10 lg:mb-12">
            @foreach ($clients as $client)
                @if ($client->image_url)
                    <div class="flex items-center justify-center h-12 sm:h-14">
                        <img src="{{ $client->image_url }}"
                             alt="{{ $client->name }}"
                             class="max-h-full max-w-[140px] sm:max-w-[160px] object-contain grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition duration-300"
                             loading="lazy">
                    </div>
                @else
                    {{-- Text fallback kalau image gak ada --}}
                    <div class="flex items-center justify-center h-12 sm:h-14 text-gray-400 hover:text-gray-700 font-semibold text-base transition">
                        {{ $client->name }}
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Pill badge --}}
        <div class="flex justify-center">
            <span class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-gray-900 rounded-full">
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
                Google Premier Partner — Top 3% di Indonesia
            </span>
        </div>

    </div>
</section>
@endif


{{-- ============== SECTION 3 — SERVICES (sticky left + scrollable cards right) ============== --}}
@if ($services->isNotEmpty())
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            {{-- LEFT: Sticky heading + CTA --}}
            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    Layanan Kami
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Solusi Google Ads untuk Bisnis yang Ingin Bertumbuh
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Digimaya menyediakan layanan dan program training Google Ads untuk membantu bisnis berkembang dengan strategi digital yang lebih efektif.
                </p>

                <a href="{{ route('public.contact.show') }}"
                   class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            {{-- RIGHT: Service cards stack --}}
            <div class="space-y-5">
                @foreach ($services as $service)
                    <div class="group bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                        <div class="flex items-center gap-4 mb-4">
                            @if ($service->icon_src)
                                <div class="flex-shrink-0 w-14 h-14 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center p-2">
                                    <img src="{{ $service->icon_src }}" alt="{{ $service->title }}" class="max-w-full max-h-full object-contain" loading="lazy">
                                </div>
                            @else
                                <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            @endif

                            <h3 class="heading-card-md">
                                {{ $service->title }}
                            </h3>
                        </div>

                        <p class="body-default">
                            {{ $service->description }}
                        </p>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</section>
@endif


{{-- ============== SECTION 4 — COMPARISON (Typical Agency vs Digimaya) ============== --}}
@if ($comparisonRows->isNotEmpty())
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        {{-- Section header --}}
        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Kenapa Digimaya
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Pendekatan yang Membedakan Digimaya
            </h2>
            <p class="body-text">
                Kami membantu bisnis berkembang melalui strategi Google Ads yang lebih fokus, terukur, dan dikelola langsung oleh tim yang berpengalaman.
            </p>
        </div>

        {{-- Comparison table --}}
        <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header row --}}
            <div class="grid grid-cols-3 border-b border-gray-100">
                <div class="px-4 sm:px-6 py-5 bg-gray-50/50">
                    {{-- empty cell for aspect column --}}
                </div>
                <div class="px-4 sm:px-6 py-5 border-l border-gray-100">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide">
                        Agency Umum
                    </p>
                </div>
                <div class="px-4 sm:px-6 py-5 border-l border-gray-100 bg-brand">
                    <p class="text-xs sm:text-sm font-semibold text-white uppercase tracking-wide">
                        Digimaya
                    </p>
                </div>
            </div>

            {{-- Data rows --}}
            @foreach ($comparisonRows as $row)
                <div class="grid grid-cols-3 border-b border-gray-100 last:border-b-0">
                    {{-- Aspect --}}
                    <div class="px-4 sm:px-6 py-5 bg-gray-50/50 flex items-center">
                        <p class="body-card-title">
                            {{ $row->aspect }}
                        </p>
                    </div>

                    {{-- Value A — negative --}}
                    <div class="px-4 sm:px-6 py-5 border-l border-gray-100 flex items-start gap-2 sm:gap-3">
                        <svg class="flex-shrink-0 w-4 h-4 sm:w-5 sm:h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">
                            {{ $row->value_a }}
                        </p>
                    </div>

                    {{-- Value B — positive --}}
                    <div class="px-4 sm:px-6 py-5 border-l border-gray-100 bg-brand-50/40 flex items-start gap-2 sm:gap-3">
                        <svg class="flex-shrink-0 w-4 h-4 sm:w-5 sm:h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-card-emphasis">
                            {{ $row->value_b }}
                        </p>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- Mobile: Stacked cards (visible di mobile/tablet, hidden di desktop) --}}
        <div class="lg:hidden space-y-4">
            @foreach ($comparisonRows as $row)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Aspect title --}}
                    <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100">
                        <p class="body-card-title">
                            {{ $row->aspect }}
                        </p>
                    </div>

                    {{-- Agency Umum row --}}
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="flex-shrink-0 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Agency Umum
                            </p>
                        </div>
                        <p class="body-default">
                            {{ $row->value_a }}
                        </p>
                    </div>

                    {{-- Digimaya row --}}
                    <div class="px-5 py-4 bg-brand-50/40">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="flex-shrink-0 w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="text-xs font-semibold text-brand uppercase tracking-wide">
                                Digimaya
                            </p>
                        </div>
                        <p class="body-card-emphasis">
                            {{ $row->value_b }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>


        {{-- CTA below table --}}
        <div class="text-center mt-10 lg:mt-12">
            <a href="{{ route('public.contact.show') }}"
               class="btn-primary">
                Konsultasi Gratis
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>
@endif


{{-- ============== SECTION 5 — VIDEO TESTIMONIAL FROM GOOGLE (hardcoded) ============== --}}
<section class="bg-gray-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        {{-- Video card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-12 lg:mb-16">
            <div class="relative w-full aspect-video">
                <iframe
                    class="absolute inset-0 w-full h-full"
                    src="https://www.youtube.com/embed/4FrWL_LdABs?rel=0"
                    title="Digimaya — Featured by Google"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen
                    loading="lazy">
                </iframe>
            </div>
        </div>

        {{-- Heading + body --}}
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-6 tracking-tight leading-[1.3]">
                Didukung langsung oleh Google untuk membantu bisnis berkembang
            </h2>
            <p class="text-base lg:text-lg text-gray-400 leading-relaxed">
                Sebagai Google Premier Partner, Digimaya mendapatkan dukungan dan insight langsung dari tim Google untuk membantu client menjalankan campaign dengan strategi yang lebih optimal.
            </p>
        </div>

    </div>
</section>


{{-- ============== SECTION 6 — TESTIMONIAL CAROUSEL ============== --}}
@if ($testimonials->isNotEmpty())
<section class="bg-gray-50/60 border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div
            x-data="{
                active: 0,
                total: {{ $testimonials->count() }},
                timer: null,
                start() {
                    if (this.total <= 1) return;
                    this.stop();
                    this.timer = setInterval(() => { this.active = (this.active + 1) % this.total }, 5000);
                },
                stop() {
                    if (this.timer) { clearInterval(this.timer); this.timer = null }
                },
                pause() {
                    this.stop();
                    setTimeout(() => this.start(), 10000);
                },
                next() { this.active = (this.active + 1) % this.total; this.pause(); },
                prev() { this.active = (this.active - 1 + this.total) % this.total; this.pause(); },
                goTo(i) { this.active = i; this.pause(); },
            }"
            x-init="start()"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 sm:px-12 lg:px-20 py-12 lg:py-16 relative"
        >
            {{-- Title --}}
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 text-center mb-10 lg:mb-12 tracking-tight">
                Apa Kata Klien Kami
            </h2>

            {{-- Slides container --}}
            <div class="relative min-h-[280px] sm:min-h-[260px]">
                @foreach ($testimonials as $i => $t)
                    <div
                        x-show="active === {{ $i }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="text-center"
                    >
                        {{-- Quote --}}
                        <p class="body-quote mb-8 max-w-3xl mx-auto whitespace-pre-line">
                            &ldquo;{{ $t->quote }}&rdquo;
                        </p>

                        {{-- Author --}}
                        <div class="flex items-center justify-center gap-4">
                            @if ($t->photo)
                                <img src="{{ $t->photo }}"
                                     alt="{{ $t->name }}"
                                     class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-gray-100 flex-shrink-0"
                                     loading="lazy">
                            @endif
                            <div class="text-left">
                                <p class="body-card-title leading-tight">
                                    {{ $t->name }}
                                </p>
                                @if ($t->position || $t->company)
                                    <p class="micro-text leading-tight mt-0.5">
                                        {{ collect([$t->position, $t->company])->filter()->implode(', ') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Nav arrows --}}
            @if ($testimonials->count() > 1)
                <button type="button" @click="prev()"
                        aria-label="Previous testimonial"
                        class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-brand hover:bg-brand-50 transition">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button type="button" @click="next()"
                        aria-label="Next testimonial"
                        class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full text-brand hover:bg-brand-50 transition">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @endif

            {{-- Dots --}}
            @if ($testimonials->count() > 1)
                <div class="flex items-center justify-center gap-2 mt-10 lg:mt-12">
                    @foreach ($testimonials as $i => $t)
                        <button type="button"
                                @click="goTo({{ $i }})"
                                :class="active === {{ $i }} ? 'w-8 bg-brand' : 'w-2 bg-gray-300 hover:bg-gray-400'"
                                class="h-2 rounded-full transition-all"
                                aria-label="Go to testimonial {{ $i + 1 }}"></button>
                    @endforeach
                </div>
            @endif

        </div>

    </div>
</section>
@endif


{{-- ============== SECTION CASE STUDY (3-col: 2 case studies + middle hardcoded card) ============== --}}
@if ($caseStudies->count() >= 2)
<section class="bg-gray-50/40 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        {{-- Section heading --}}
        <div class="mb-12 lg:mb-16">
            <p class="eyebrow">
                Studi Kasus
            </p>
            <h2 class="heading-section max-w-3xl leading-[1.2]">
                Hasil Nyata dari Strategy Google Ads yang Tepat
            </h2>
        </div>

        {{-- 3-col grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">

            {{-- Card 1: First case study --}}
            @php $cs1 = $caseStudies[0]; @endphp
            <div class="group relative aspect-[3/4] rounded-2xl overflow-hidden bg-gray-100">
                @if ($cs1->thumbnail)
                    <img src="{{ $cs1->thumbnail }}"
                         alt="{{ $cs1->title }}"
                         class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105"
                         loading="lazy">
                @endif

                {{-- Badge top-right --}}
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-black/40 backdrop-blur-sm border border-white/30 rounded-full">
                        # 01
                    </span>
                </div>

                {{-- Title overlay bottom --}}
                <div class="absolute bottom-4 left-4 right-4">
                    <div class="bg-white rounded-xl p-5 shadow-lg">
                        <h3 class="heading-card-sm">
                            {{ $cs1->title }}
                        </h3>
                    </div>
                </div>
            </div>

            {{-- Card 2: Middle hardcoded "Real Clients, Real Results" --}}
            <div class="aspect-[3/4] rounded-2xl bg-white border border-gray-200 p-6 lg:p-8 flex flex-col relative">
                {{-- Badge top-right --}}
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 border border-gray-300 rounded-full bg-white">
                        # 02
                    </span>
                </div>

                {{-- Spacer top --}}
                <div class="flex-1"></div>

                {{-- Content bottom-aligned --}}
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 leading-tight">
                        Real Campaigns. Real Results.
                    </h3>
                    <p class="body-default">
                        Lihat bagaimana strategi Google Ads yang tepat membantu berbagai bisnis meningkatkan leads, penjualan, dan performa digital secara nyata.
                    </p>
                </div>
            </div>

            {{-- Card 3: Second case study --}}
            @php $cs2 = $caseStudies[1]; @endphp
            <div class="group relative aspect-[3/4] rounded-2xl overflow-hidden bg-gray-100">
                @if ($cs2->thumbnail)
                    <img src="{{ $cs2->thumbnail }}"
                         alt="{{ $cs2->title }}"
                         class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105"
                         loading="lazy">
                @endif

                {{-- Badge top-right --}}
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-black/40 backdrop-blur-sm border border-white/30 rounded-full">
                        # 03
                    </span>
                </div>

                {{-- Title overlay bottom --}}
                <div class="absolute bottom-4 left-4 right-4">
                    <div class="bg-white rounded-xl p-5 shadow-lg">
                        <h3 class="heading-card-sm">
                            {{ $cs2->title }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endif

{{-- ============== SECTION 7 — FAQ (split layout: sticky left + accordion right) ============== --}}
@if ($faqs->isNotEmpty())
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            {{-- LEFT: Sticky heading + CTA --}}
            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    FAQ
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Pertanyaan yang Sering Ditanyakan
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Temukan jawaban untuk beberapa pertanyaan yang paling sering ditanyakan seputar layanan, kerja sama, dan program Google Ads di Digimaya.
                </p>

                <a href="{{ route('public.contact.show') }}"
                   class="btn-primary">
                    Tanya Tim Kami
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            {{-- RIGHT: Accordion --}}
            <div x-data="{ open: null }" class="space-y-3">
                @foreach ($faqs as $faq)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                        <button type="button"
                                @click="open === {{ $faq->id }} ? open = null : open = {{ $faq->id }}"
                                :aria-expanded="open === {{ $faq->id }} ? 'true' : 'false'"
                                class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                            <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">
                                {{ $faq->question }}
                            </span>
                            <svg :class="open === {{ $faq->id }} ? 'rotate-180 text-brand' : 'text-gray-400'"
                                 class="flex-shrink-0 w-5 h-5 transition-transform duration-200"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open === {{ $faq->id }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             style="display: none;">
                            <div class="faq-answer px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                                {!! \Mews\Purifier\Facades\Purifier::clean($faq->answer) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</section>
@endif

{{-- ============== SECTION CTA — Closing (light premium with decorative blobs) ============== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-gray-50 to-white border-t border-gray-100">

    {{-- Decorative blobs (same vibe as hero) --}}
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/3"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center">
            <p class="eyebrow">
                Yuk diskusi Google Ads...
            </p>

            <h2 class="heading-section mb-6 leading-[1.2]">
                Siap Mendiskusikan Strategi Google Ads untuk Bisnis Anda?
            </h2>

            <p class="body-text mb-10 max-w-xl mx-auto">
                Konsultasikan kebutuhan bisnis Anda bersama tim Digimaya. Kami akan membantu mengevaluasi campaign, memberikan insight yang relevan, dan merekomendasikan strategi yang sesuai dengan tujuan bisnis Anda.
            </p>

            <div class="flex flex-col items-center gap-4">
                <a href="{{ route('public.contact.show') }}"
                   class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <p class="micro-text max-w-md mx-auto">
                    Audit gratis. Strategi gratis. Tanpa biaya apapun.
                </p>
            </div>

            {{-- Trust row --}}
            <div class="mt-12 lg:mt-16 pt-8 border-t border-gray-200/70 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-xs sm:text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Google Premier Partner
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Sertifikasi Google
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Respons cepat
                </div>
            </div>
        </div>

    </div>
</section>

@endsection