@extends('layouts.public')

@section('meta_title', 'Google Ads Audit: Temukan Kebocoran di Akun Iklanmu | Digimaya')
@section('meta_description', 'Audit independen Google Ads dari tim Digimaya, Google Premier Partner Indonesia. Temukan pemborosan budget, masalah tracking, dan peluang growth yang selama ini terlewat.')

{{-- SEO Schema JSON-LD for this service page --}}
@push('head_schema')
    <x-seo.schema-service
        name="Google Ads Audit"
        description="Audit independen Google Ads dari tim Digimaya, Google Premier Partner Indonesia. Temukan pemborosan budget, masalah tracking, dan peluang growth yang selama ini terlewat."
        serviceType="Digital Advertising Audit"
    />
    <x-seo.schema-faq :faqs="$faqs" />
@endpush

@section('content')


{{-- ============== SECTION 1 — HERO (storytelling pain-point, beda dengan management page) ============== --}}
<section class="relative overflow-x-clip bg-gradient-to-b from-brand-50/30 to-white">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="max-w-3xl mx-auto text-center">

            <p class="eyebrow eyebrow-pill">
                <span class="w-1.5 h-1.5 rounded-full bg-brand inline-block"></span>
                Google Ads Audit Service
            </p>

            <h1 class="heading-hero mb-6">
                Dashboard Google Ads-mu Penuh Klik, Tapi Bisnis
                <span class="block bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                    Belum Juga Untung?
                </span>
            </h1>

            <p class="body-lead mb-10 max-w-2xl mx-auto">
                Saatnya cari tahu di mana budgetmu bocor. Audit independen oleh tim Premier Partner yang ungkap kenapa iklan kamu nggak menghasilkan seperti yang seharusnya, plus action plan konkret untuk diperbaiki.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads%20Audit"
                   target="_blank" rel="noopener"
                   class="btn-secondary">
                    <svg class="w-5 h-5 text-brand" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Tanya via WhatsApp
                </a>
            </div>

            {{-- Trust row --}}
            <div class="mt-12 lg:mt-16 pt-8 border-t border-gray-200/70 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-xs sm:text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Audit Independen
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Read-Only Access
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Premier Partner Indonesia
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 2 — RED FLAG (UNIQUE NEW SECTION - Self-Diagnose Checklist) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Tanda-Tanda Akun Butuh Audit
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Kenali Gejala-Gejala Akun Google Ads yang Tidak Sehat
            </h2>
            <p class="body-text">
                Kalau salah satu dari 6 sinyal ini muncul di akun kamu, ini saatnya audit. Semakin lama dibiarkan, semakin besar pemborosan yang terjadi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="heading-card-md mb-2">
                            Cost per Lead Terus Naik
                        </h3>
                        <p class="body-default">
                            Biaya per lead naik bulan demi bulan, tapi kualitas lead-nya nggak makin bagus. Ini sinyal campaign decay yang nggak terdiagnosis.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="heading-card-md mb-2">
                            CTR Turun Drastis
                        </h3>
                        <p class="body-default">
                            Click-through rate turun dari level normal ke setengahnya dalam beberapa bulan terakhir. Ads udah nggak relevan atau messaging udah basi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="heading-card-md mb-2">
                            Quality Score di Bawah 6
                        </h3>
                        <p class="body-default">
                            Mayoritas keyword punya Quality Score rendah. Indikasi misalignment antara keyword, ad copy, dan landing page yang bikin CPC mahal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="heading-card-md mb-2">
                            Konversi Google Ads Beda dari CRM
                        </h3>
                        <p class="body-default">
                            Angka konversi di Google Ads nggak sama dengan data aktual lead atau sales di CRM. Conversion tracking-mu rusak diam-diam.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="heading-card-md mb-2">
                            Campaign Tidak Disentuh 30+ Hari
                        </h3>
                        <p class="body-default">
                            Akun dibiarkan auto-pilot tanpa optimasi rutin. Set-and-forget syndrome bikin budget terbuang ke kombinasi keyword-audience yang udah nggak relevan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="heading-card-md mb-2">
                            Bingung Campaign Mana yang Profitable
                        </h3>
                        <p class="body-default">
                            Nggak bisa jawab dengan yakin: "Campaign mana yang ngasih customer paling profitable?" Tanpa data ini, kamu spending blind.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 3 — SCOPE AUDIT (8 kategori grid) ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Ruang Lingkup Audit
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                8 Area yang Kami Bedah di Akun Google Ads-mu
            </h2>
            <p class="body-text">
                Setiap audit Digimaya mencakup 8 area kritis ini. Bukan cuma surface-level metric, tapi deep analysis sampai ke akar masalahnya.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Account Structure
                </h3>
                <p class="body-default">
                    Apakah struktur campaign, ad group, dan naming convention-mu mendukung performa, atau diam-diam menghambat?
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Conversion Tracking
                </h3>
                <p class="body-default">
                    Verifikasi tracking GA4, GTM, form, dan telepon. Data yang masuk ke kamu bener-bener bisa dipercaya, atau ada yang bocor?
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Keyword Strategy
                </h3>
                <p class="body-default">
                    Search term analysis, match type optimization, dan negative keyword. Ungkap keyword yang diam-diam ngabisin budget tanpa hasil.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Ad Copy & Creative
                </h3>
                <p class="body-default">
                    Evaluasi Responsive Search Ads, headline, deskripsi, dan relevansi dengan intent pencari. Apakah copy-mu menarik atau membosankan?
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Bidding & Budget
                </h3>
                <p class="body-default">
                    Review Smart Bidding strategy, target CPA/ROAS, dan alokasi budget per campaign. Apakah bidding-mu maximize value atau ngerugi?
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Landing Page Alignment
                </h3>
                <p class="body-default">
                    Analisis message match antara ad dan landing page. Klik mungkin dapet, tapi konversi hilang di tahap mana?
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Audience & Targeting
                </h3>
                <p class="body-default">
                    Remarketing lists, Customer Match, location, dan dayparting. Iklanmu nyampe ke orang yang tepat, di waktu yang tepat?
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Performance Max
                </h3>
                <p class="body-default">
                    Asset quality, audience signal, alokasi budget, dan transparansi channel di PMax. Black box yang sering ngabisin tanpa kamu sadar.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 4 — METHODOLOGY (5-step process) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cara Kerja Audit
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                5 Tahap dari Briefing sampai Debrief
            </h2>
            <p class="body-text">
                Setiap audit Digimaya ikutin proses yang sama. Predictable, terstruktur, dan transparan dari awal sampai selesai.
            </p>
        </div>

        <div class="space-y-4">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-14 h-14 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-xl">
                        01
                    </div>
                    <div class="flex-1">
                        <h3 class="heading-card-md mb-2">
                            Initial Briefing
                        </h3>
                        <p class="body-default">
                            Sesi pembuka untuk pahamin objective bisnismu, target market, kompetitor, dan ekspektasi spesifik. Kami juga akan minta akses read-only ke akun Google Ads-mu di sini.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 lg:flex items-start gap-5">
                    <div class="flex items-start gap-5">
                        <div class="flex-shrink-0 w-14 h-14 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-xl">
                            02
                        </div>
                        <div class="flex-1">
                            <h3 class="heading-card-md mb-2">
                                Account Health Check
                            </h3>
                            <p class="body-default">
                                Review awal terhadap struktur akun, settings, dan setup tracking. Kami identifikasi quick wins yang bisa diperbaiki segera dan area yang butuh deep dive lebih lanjut.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-14 h-14 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-xl">
                        03
                    </div>
                    <div class="flex-1">
                        <h3 class="heading-card-md mb-2">
                            Deep Analysis
                        </h3>
                        <p class="body-default">
                            Bedah 8 area kritis: account structure, conversion tracking, keyword, ad copy, bidding, landing page, audience, dan Performance Max. Setiap temuan didokumentasikan dengan data.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-14 h-14 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-xl">
                        04
                    </div>
                    <div class="flex-1">
                        <h3 class="heading-card-md mb-2">
                            Report & Action Plan
                        </h3>
                        <p class="body-default">
                            Susun laporan tertulis lengkap dengan executive summary, waste analysis, quick wins, dan strategic action plan yang diprioritaskan berdasarkan dampak ke bisnis.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 lg:flex items-start gap-5">
                    <div class="flex items-start gap-5">
                        <div class="flex-shrink-0 w-14 h-14 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-xl">
                            05
                        </div>
                        <div class="flex-1">
                            <h3 class="heading-card-md mb-2">
                                Debrief Call
                            </h3>
                            <p class="body-default">
                                Sesi penjelasan lengkap untuk bahas temuan, jawab pertanyaan, dan diskusi langkah implementasi. Kamu pulang dengan pemahaman penuh, bukan cuma laporan PDF.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 5 — DELIVERABLES (sticky left + 6 deliverables right) ============== --}}
<section class="bg-gray-50/40 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            {{-- LEFT: Sticky heading --}}
            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    Yang Kamu Dapatkan
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    6 Output Konkret di Akhir Audit
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Audit Digimaya bukan cuma laporan PDF setebal 50 halaman yang nggak kebaca. Setiap output dirancang untuk langsung actionable sama tim kamu.
                </p>

                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            {{-- RIGHT: Deliverables stack --}}
            <div class="space-y-5">

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Executive Summary
                        </h3>
                    </div>
                    <p class="body-default">
                        Ringkasan high-level temuan utama, proyeksi peluang, dan rekomendasi strategis. Cocok dibawa ke meeting dengan stakeholder atau partner bisnis.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Waste Analysis Report
                        </h3>
                    </div>
                    <p class="body-default">
                        Breakdown spesifik area mana saja yang bocor: keyword yang ngabisin budget tanpa hasil, klik yang nggak konversi, dan estimasi recovery yang bisa didapat.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Quick Wins Roadmap
                        </h3>
                    </div>
                    <p class="body-default">
                        Daftar perbaikan high-impact yang bisa dieksekusi dalam 1-2 minggu pertama. Tujuannya: lihat dampak cepat sebelum lanjut ke perbaikan strategis jangka panjang.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Strategic Action Plan
                        </h3>
                    </div>
                    <p class="body-default">
                        Roadmap strategi jangka menengah-panjang yang aligned dengan target bisnis. Mencakup prioritas, urutan eksekusi, dan estimated impact per inisiatif.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Detailed Written Report
                        </h3>
                    </div>
                    <p class="body-default">
                        Dokumen lengkap dengan temuan per kategori, screenshot, data pendukung, dan penjelasan kenapa setiap masalah penting untuk diperbaiki.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Debrief Call
                        </h3>
                    </div>
                    <p class="body-default">
                        Sesi 1-on-1 untuk walk-through temuan, jawab pertanyaan, dan diskusi langkah implementasi. Pemahaman lengkap, bukan cuma file PDF.
                    </p>
                </div>

            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 6 — AUDIT + EDUKASI (UNIQUE differentiator) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- LEFT: Content --}}
            <div>
                <p class="eyebrow">
                    Yang Bikin Beda
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Bukan Cuma Audit, Tapi Edukasi
                </h2>

                <p class="body-text mb-6">
                    Audit dari agency lain biasanya kasih kamu laporan teknis yang sulit dipahami. Setelah itu, kamu tetep nggak tau kenapa masalah-masalah itu terjadi atau gimana cara mencegahnya di masa depan.
                </p>

                <p class="body-text mb-8">
                    Sebagai agency yang juga punya Academy untuk edukasi Google Ads, pendekatan Digimaya beda. Setiap temuan dijelasin dengan konteks kenapa itu masalah, gimana cara fix-nya, dan apa yang harus kamu perhatiin ke depan supaya nggak berulang.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Setiap temuan disertai penjelasan konteks "kenapa" yang mudah dipahami</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Penjelasan dalam Bahasa Indonesia, bukan jargon teknis tanpa konteks</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Diskusi terbuka di debrief call, tim kamu bisa tanya sebanyak yang dibutuhin</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Konteks lokal Indonesia: perilaku searcher, industri, dan dinamika pasar lokal</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Visual quote card --}}
            <div class="relative">
                <div class="bg-gradient-to-br from-brand-50 to-brand-100/40 rounded-3xl p-8 lg:p-10 border border-brand/20">

                    <svg class="w-12 h-12 text-brand mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391C14.017 9.139 16.484 5.49 20.5 3.5l1 2c-2.728 1.394-4.483 4.061-4.483 7.609H21v7.891h-6.983zm-11 0v-7.391C3.017 9.139 5.484 5.49 9.5 3.5l1 2c-2.728 1.394-4.483 4.061-4.483 7.609H10v7.891H3.017z"/>
                    </svg>

                    <p class="body-pull-quote mb-6">
                        Setelah audit, klien Digimaya nggak cuma tahu apa yang salah. Mereka juga paham kenapa itu salah, dan gimana caranya supaya nggak terulang lagi.
                    </p>

                    <div class="flex items-center gap-3 pt-4 border-t border-brand/10">
                        <div class="w-10 h-10 rounded-full bg-brand text-white flex items-center justify-center font-bold">
                            R
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Renra Sedoya</p>
                            <p class="text-xs text-gray-600">Founder Digimaya</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 7 — WHO THIS IS FOR (3 segmen lokal) ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cocok Buat Siapa
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Audit Ini Dirancang untuk Bisnis Indonesia
            </h2>
            <p class="body-text">
                Kami fokus ke 3 segmen yang punya kebutuhan unik dan tantangan spesifik di Google Ads.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Small & Medium Business
                </h3>
                <p class="body-default">
                    UKM yang spending menengah tapi mau ekspansi. Audit fokus ke efisiensi budget supaya setiap rupiah iklan bekerja lebih keras.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Local Business
                </h3>
                <p class="body-default">
                    Klinik, restoran, jasa lokal, atau bisnis fisik dengan target geografis spesifik. Audit fokus ke geo-targeting, call tracking, dan lead lokal.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    E-commerce
                </h3>
                <p class="body-default">
                    Brand online dengan banyak SKU. Audit fokus ke Shopping campaign, product feed quality, ROAS per kategori, dan Performance Max optimization.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 8 — COMPARISON TABLE (from CMS, hide if empty) ============== --}}
@if ($comparisonRows->isNotEmpty())
<section class="bg-white border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Apa Bedanya
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Audit Digimaya vs Audit Lainnya
            </h2>
            <p class="body-text">
                Tidak semua audit Google Ads sama. Ini perbedaan pendekatan yang kamu dapetin di Digimaya.
            </p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">

            {{-- Table header --}}
            <div class="grid grid-cols-3 bg-gray-50/80 border-b border-gray-100">
                <div class="px-4 sm:px-6 py-4">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide">Aspek</p>
                </div>
                <div class="px-4 sm:px-6 py-4 border-l border-gray-100">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide">Lainnya</p>
                </div>
                <div class="px-4 sm:px-6 py-4 border-l border-gray-100 bg-brand-50/40">
                    <p class="text-xs sm:text-sm font-semibold text-brand uppercase tracking-wide">Digimaya</p>
                </div>
            </div>

            {{-- Table rows --}}
            @foreach ($comparisonRows as $row)
                <div class="grid grid-cols-3 border-b border-gray-100 last:border-b-0">
                    <div class="px-4 sm:px-6 py-5">
                        <p class="text-sm sm:text-base font-semibold text-gray-900 leading-snug">
                            {{ $row->aspect }}
                        </p>
                    </div>
                    <div class="px-4 sm:px-6 py-5 border-l border-gray-100">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            {{ $row->value_a }}
                        </p>
                    </div>
                    <div class="px-4 sm:px-6 py-5 border-l border-gray-100 bg-brand-50/20">
                        <p class="text-sm text-gray-900 leading-relaxed">
                            {{ $row->value_b }}
                        </p>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
</section>
@endif


{{-- ============== SECTION 9 — CASE STUDY (1 featured large card, hide if empty) ============== --}}
@if ($caseStudy)
<section class="bg-gray-900 border-t border-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mb-12 lg:mb-16">
            <p class="text-sm font-semibold text-brand-100 uppercase tracking-wide mb-6">
                Studi Kasus
            </p>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-6 tracking-tight leading-[1.3]">
                Hasil Nyata Setelah Audit
            </h2>
            <p class="text-base lg:text-lg text-gray-400 leading-relaxed">
                Contoh konkret bagaimana audit Digimaya bantu klien temukan kebocoran dan unlock potensi yang selama ini ke-block.
            </p>
        </div>

        <div class="bg-white rounded-3xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-0">

                {{-- LEFT: Image (40%) --}}
                <div class="lg:col-span-2 relative aspect-[4/5] lg:aspect-auto bg-gray-100">
                    @if ($caseStudy->thumbnail)
                        <img src="{{ $caseStudy->thumbnail_url }}"
                             alt="{{ $caseStudy->title }}"
                             class="absolute inset-0 w-full h-full object-cover"
                             loading="lazy">
                    @endif

                    @if ($caseStudy->industry)
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/90 text-gray-800 backdrop-blur-sm">
                                {{ $caseStudy->industry }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- RIGHT: Content (60%) --}}
                <div class="lg:col-span-3 p-8 sm:p-10 lg:p-12">

                    <p class="text-xs sm:text-sm font-semibold text-brand uppercase tracking-wide mb-3">
                        {{ $caseStudy->client_name }}
                    </p>

                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-6 tracking-tight leading-snug">
                        {{ $caseStudy->title }}
                    </h3>

                    @if ($caseStudy->results->isNotEmpty())
                        <div class="grid grid-cols-{{ min($caseStudy->results->count(), 3) }} gap-4 mb-8 pb-8 border-b border-gray-100">
                            @foreach ($caseStudy->results->take(3) as $result)
                                <div>
                                    <p class="text-2xl sm:text-3xl font-bold text-brand mb-1 tracking-tight">
                                        {{ $result->value }}
                                    </p>
                                    <p class="text-xs sm:text-sm text-gray-600 leading-tight">
                                        {{ $result->label }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-5">
                        @if ($caseStudy->problem)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                    Tantangan
                                </p>
                                <p class="body-default line-clamp-3">
                                    {{ $caseStudy->problem }}
                                </p>
                            </div>
                        @endif

                        @if ($caseStudy->solution)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                    Solusi Digimaya
                                </p>
                                <p class="body-default line-clamp-3">
                                    {{ $caseStudy->solution }}
                                </p>
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>
@endif


{{-- ============== SECTION 10 — TESTIMONIAL FEATURED (1 testimonial, hide if empty) ============== --}}
@if ($testimonial)
<section class="bg-gradient-to-b from-brand-50/30 to-white border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center mb-10 lg:mb-12">
            <p class="eyebrow">
                Testimoni Klien
            </p>
            <h2 class="heading-section leading-[1.2]">
                Apa Kata Klien Setelah Audit
            </h2>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 sm:p-10 lg:p-12 text-center">

            @if ($testimonial->rating)
                <div class="flex items-center justify-center gap-1 mb-6">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
            @endif

            <blockquote class="body-pull-quote mb-8 max-w-2xl mx-auto">
                &ldquo;{{ $testimonial->quote }}&rdquo;
            </blockquote>

            <div class="flex items-center justify-center gap-4">
                @if ($testimonial->photo)
                    <img src="{{ $testimonial->photo }}"
                         alt="{{ $testimonial->name }}"
                         class="w-12 h-12 rounded-full object-cover"
                         loading="lazy">
                @else
                    <div class="w-12 h-12 rounded-full bg-brand-50 text-brand flex items-center justify-center font-bold">
                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                    </div>
                @endif
                <div class="text-left">
                    <p class="font-semibold text-gray-900">
                        {{ $testimonial->name }}
                    </p>
                    @if ($testimonial->position || $testimonial->company)
                        <p class="text-sm text-gray-600">
                            {{ $testimonial->position }}{{ $testimonial->position && $testimonial->company ? ', ' : '' }}{{ $testimonial->company }}
                        </p>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>
@endif


{{-- ============== SECTION 11 — FAQ (sticky left + accordion right, all active) ============== --}}
@if ($faqs->isNotEmpty())
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    FAQ
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Pertanyaan yang Sering Kami Terima
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Berikut jawaban untuk pertanyaan yang paling sering muncul seputar service Google Ads Audit Digimaya.
                </p>

                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            <div x-data="{ open: null }" class="space-y-3">
                @foreach ($faqs as $idx => $faq)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                        <button type="button"
                                @click="open === {{ $idx }} ? open = null : open = {{ $idx }}"
                                :aria-expanded="open === {{ $idx }} ? 'true' : 'false'"
                                class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                            <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">
                                {{ $faq->question }}
                            </span>
                            <svg :class="open === {{ $idx }} ? 'rotate-180 text-brand' : 'text-gray-400'"
                                 class="flex-shrink-0 w-5 h-5 transition-transform duration-200"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open === {{ $idx }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             style="display: none;">
                            <div class="px-5 sm:px-6 pb-5 pt-1 faq-answer text-sm sm:text-base text-gray-600 leading-relaxed">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</section>
@endif


{{-- ============== SECTION 12 — CTA CLOSING ============== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-gray-50 to-white border-t border-gray-100">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/3"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center">
            <p class="eyebrow">
                Mulai Sekarang
            </p>

            <h2 class="heading-section mb-6 leading-[1.2]">
                Saatnya Berhenti Menebak-Nebak
            </h2>

            <p class="body-text mb-10 max-w-xl mx-auto">
                Konsultasi gratis 30 menit untuk diskusi situasi akun Google Ads-mu dan apakah audit Digimaya cocok untuk kondisimu sekarang.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads%20Audit"
                   target="_blank" rel="noopener"
                   class="btn-secondary">
                    <svg class="w-5 h-5 text-brand" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Tanya via WhatsApp
                </a>
            </div>

            <div class="mt-12 lg:mt-16 pt-8 border-t border-gray-200/70 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-xs sm:text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Read-Only Access
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Independen & Objektif
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Response &lt; 24 jam
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
