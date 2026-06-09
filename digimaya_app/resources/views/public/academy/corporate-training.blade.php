@extends('layouts.public')

@section('meta_title', 'Corporate Training Google Ads: Bangun Tim Internal yang Kompeten | Digimaya')
@section('meta_description', 'Program corporate training Google Ads custom untuk perusahaan. Trainer praktisi aktif, materi terupdate, format fleksibel (onsite/online/hybrid), kurikulum disesuaikan industri kamu.')

{{-- SEO Schema JSON-LD for this academy page --}}
@push('head_schema')
    <x-seo.schema-service
        name="Corporate Training Google Ads"
        description="Program corporate training Google Ads custom untuk perusahaan. Trainer praktisi aktif, materi terupdate, format fleksibel (onsite/online/hybrid), kurikulum disesuaikan industri kamu."
        serviceType="Corporate Digital Marketing Training"
    />
    <x-seo.schema-faq :faqs="$faqs" />
@endpush

@section('content')


{{-- ============== SECTION 1 — HERO (dual audience: decision maker + peserta) ============== --}}
<section class="relative overflow-x-clip bg-gradient-to-b from-brand-50/30 to-white">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="max-w-3xl mx-auto text-center">

            <p class="eyebrow eyebrow-pill">
                <span class="w-1.5 h-1.5 rounded-full bg-brand inline-block"></span>
                Corporate Training Service
            </p>

            <h1 class="heading-hero mb-6">
                Bangun Tim Google Ads Internal
                <span class="block bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                    yang Kompeten dan Mandiri.
                </span>
            </h1>

            <p class="body-lead mb-10 max-w-2xl mx-auto">
                Kurangi ketergantungan pada agency eksternal. Latih tim marketing internal kamu langsung dengan praktisi aktif Google Ads, dengan kurikulum yang disesuaikan kebutuhan bisnis dan industri kamu.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Corporate%20Training%20Google%20Ads"
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
                    Trainer Praktisi Aktif
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kurikulum Custom
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Onsite / Online / Hybrid
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 2 — STATISTIK WHY TRAIN (UNIQUE, justify ke decision maker) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Kenapa Tim Kamu Butuh Training
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Skill Gap di Tim Marketing Itu Real
            </h2>
            <p class="body-text">
                Survei industri global mengungkap pola yang konsisten. Bisnis yang investasi training dapat keuntungan kompetitif yang signifikan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">

            <div class="bg-gradient-to-br from-brand-50 to-brand-100/40 border border-brand/20 rounded-2xl p-8 text-center">
                <p class="text-5xl lg:text-6xl font-bold text-brand mb-3 tracking-tight">
                    60%
                </p>
                <p class="font-semibold text-gray-900 mb-2">
                    CMO Mengaku Skill Gap
                </p>
                <p class="body-default">
                    60% CMO global melaporkan tim mereka punya skill gap di area digital marketing termasuk Google Ads.
                </p>
            </div>

            <div class="bg-gradient-to-br from-brand-50 to-brand-100/40 border border-brand/20 rounded-2xl p-8 text-center">
                <p class="text-5xl lg:text-6xl font-bold text-brand mb-3 tracking-tight">
                    70%
                </p>
                <p class="font-semibold text-gray-900 mb-2">
                    Marketer Anggap Krusial
                </p>
                <p class="body-default">
                    70% marketer percaya skill digital marketing adalah essential untuk tetap kompetitif di industri masing-masing.
                </p>
            </div>

            <div class="bg-gradient-to-br from-brand-50 to-brand-100/40 border border-brand/20 rounded-2xl p-8 text-center">
                <p class="text-5xl lg:text-6xl font-bold text-brand mb-3 tracking-tight">
                    20%
                </p>
                <p class="font-semibold text-gray-900 mb-2">
                    Peningkatan ROI Rata-Rata
                </p>
                <p class="body-default">
                    Bisnis yang prioritaskan training digital marketing dapat ROI rata-rata 20% lebih tinggi dibanding yang tidak.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 3 — PAIN POINTS (UNIQUE - dual frame, B2B + individual) ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Apakah Ini Situasi Kamu?
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Tanda-Tanda Tim Kamu Butuh Training
            </h2>
            <p class="body-text">
                Dari sisi manajemen sampai sisi tim yang eksekusi, berikut pain point yang paling sering kami temui di perusahaan klien.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">

            {{-- LEFT: Perspektif Perusahaan --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b border-gray-100">
                    <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Dari Sisi Perusahaan
                    </h3>
                </div>

                <ul class="space-y-5">
                    <li>
                        <div class="flex items-start gap-3 mb-1">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="font-semibold text-gray-900">Ketergantungan tinggi pada agency eksternal</p>
                        </div>
                        <p class="body-default ml-8">
                            Mau bawa Google Ads in-house tapi tim internal belum punya capability untuk handle sendiri.
                        </p>
                    </li>
                    <li>
                        <div class="flex items-start gap-3 mb-1">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="font-semibold text-gray-900">Budget iklan naik, hasil stagnan</p>
                        </div>
                        <p class="body-default ml-8">
                            Spend Google Ads makin besar tapi tidak ada yang bisa jelasin kenapa hasil tidak proporsional dengan investasi.
                        </p>
                    </li>
                    <li>
                        <div class="flex items-start gap-3 mb-1">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="font-semibold text-gray-900">Turnover staf bikin reset capability</p>
                        </div>
                        <p class="body-default ml-8">
                            Setiap kali ada staf marketing baru, harus mulai onboarding dari nol — knowledge perusahaan tidak terbangun.
                        </p>
                    </li>
                </ul>
            </div>

            {{-- RIGHT: Perspektif Peserta --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b border-gray-100">
                    <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Dari Sisi Tim
                    </h3>
                </div>

                <ul class="space-y-5">
                    <li>
                        <div class="flex items-start gap-3 mb-1">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="font-semibold text-gray-900">Pegang budget, dituntut hasil</p>
                        </div>
                        <p class="body-default ml-8">
                            Tim marketing pegang budget iklan jutaan rupiah tapi belum yakin apakah strategy yang dijalankan udah optimal.
                        </p>
                    </li>
                    <li>
                        <div class="flex items-start gap-3 mb-1">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="font-semibold text-gray-900">Optimasi tebak-tebak, tanpa framework</p>
                        </div>
                        <p class="body-default ml-8">
                            Optimasi campaign berdasarkan CTR dan impresi tanpa link ke leads dan revenue. Decision making based on vanity metrics.
                        </p>
                    </li>
                    <li>
                        <div class="flex items-start gap-3 mb-1">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="font-semibold text-gray-900">Mau scale tapi takut salah langkah</p>
                        </div>
                        <p class="body-default ml-8">
                            Disuruh scale 2-3x lipat budget tapi tidak yakin cara aman supaya budget tidak boncos di percobaan pertama.
                        </p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 4 — KURIKULUM OVERVIEW (8 area) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Kurikulum Training
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                8 Area Utama yang Dipelajari Tim Kamu
            </h2>
            <p class="body-text">
                Materi disusun berdasarkan kasus nyata dari 100+ akun klien Digimaya. Setiap topik bisa di-customize sesuai industri dan stage bisnis kamu.
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
                    Account Setup & Structure
                </h3>
                <p class="body-default">
                    Setup akun yang benar dari awal, struktur campaign yang scalable, dan naming convention yang clean untuk reporting.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Keyword Research & Strategy
                </h3>
                <p class="body-default">
                    Riset keyword, match type strategy, negative keywords, dan competitor analysis untuk identify peluang growth.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Ad Copy & Assets
                </h3>
                <p class="body-default">
                    Menulis Responsive Search Ads yang efektif, headline framework, dan optimasi asset/extension untuk CTR maksimal.
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
                    Setup GA4, GTM, dan conversion action — pastikan data yang masuk akurat dan bisa dipakai untuk decision making.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Smart Bidding & Budget
                </h3>
                <p class="body-default">
                    Kapan pakai manual vs Smart Bidding, target CPA vs ROAS, dan alokasi budget yang efisien per campaign.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Optimization & Reporting
                </h3>
                <p class="body-default">
                    Search term analysis, Quality Score improvement, dan format reporting yang executive-ready untuk manajemen.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Performance Max (Advanced)
                </h3>
                <p class="body-default">
                    Asset groups, audience signals, PMax vs Search comparison, dan strategi untuk transparansi channel di black-box PMax.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Remarketing & Audience
                </h3>
                <p class="body-default">
                    Display remarketing, Customer Match, YouTube audience targeting, dan strategi nurture lead lewat audience layering.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 5 — FORMAT TRAINING (UNIQUE - 3 format options) ============== --}}
<section class="bg-gray-50/40 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Format Training
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Pilih Format yang Paling Cocok dengan Tim Kamu
            </h2>
            <p class="body-text">
                Setiap format punya keunggulan masing-masing. Kami akan diskusikan format yang paling efektif sesuai kondisi tim dan kebutuhan perusahaan kamu.
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
                    Onsite
                </h3>
                <p class="body-default mb-5">
                    Trainer langsung datang ke kantor kamu. Format paling efektif karena bisa langsung pakai konteks bisnis dan akun internal sebagai bahan praktek.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Praktek pakai akun internal</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Privasi data terjaga</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Interaksi tatap muka maksimal</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Online
                </h3>
                <p class="body-default mb-5">
                    Via Zoom atau platform video pilihan kamu. Cocok untuk tim yang tersebar di beberapa kota atau yang butuh fleksibilitas waktu.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Fleksibel, tidak perlu travel</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Recording session untuk reference</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Hemat biaya transportasi</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Hybrid
                </h3>
                <p class="body-default mb-5">
                    Kombinasi sesi onsite untuk hands-on workshop, dengan follow-up online untuk Q&A dan praktek lanjutan. Best of both worlds.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Intensif tatap muka + flexibility</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Follow-up online untuk Q&A</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Cocok untuk tim multi-lokasi</p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 6 — HOW IT WORKS (4-step process) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cara Kerja Engagement
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                4 Tahap dari Konsultasi sampai Post-Training
            </h2>
            <p class="body-text">
                Setiap corporate training engagement ikutin proses yang sama. Predictable, terstruktur, dan disesuaikan kebutuhan spesifik tim kamu.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-lg mb-5">
                    01
                </div>
                <h3 class="heading-card-md mb-3">
                    Needs Assessment
                </h3>
                <p class="body-default">
                    Sesi discovery untuk pahami business goals, kondisi tim sekarang, skill gap, dan goal yang mau dicapai pasca training.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-lg mb-5">
                    02
                </div>
                <h3 class="heading-card-md mb-3">
                    Custom Curriculum
                </h3>
                <p class="body-default">
                    Tim kami susun kurikulum yang disesuaikan industri, stage bisnis, dan level skill peserta. Bukan template generic.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-lg mb-5">
                    03
                </div>
                <h3 class="heading-card-md mb-3">
                    Delivery
                </h3>
                <p class="body-default">
                    Training berjalan sesuai format yang disepakati (onsite/online/hybrid). Setiap sesi praktek langsung pakai akun atau data nyata.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-lg mb-5">
                    04
                </div>
                <h3 class="heading-card-md mb-3">
                    Post-Training Support
                </h3>
                <p class="body-default">
                    Akses konsultasi via WhatsApp group untuk follow-up question, plus sesi refresher untuk pastikan ilmunya tetap aktif.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 7 — DELIVERABLES (UNIQUE - value-based, no monetary) ============== --}}
<section class="bg-gray-50/40 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            {{-- LEFT: Sticky heading --}}
            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    Yang Tim Kamu Dapatkan
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Bukan Cuma Materi, Tapi Sistem Lengkap untuk Implementasi
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Setiap engagement corporate training Digimaya termasuk komponen di samping ini. Semua dirancang supaya tim kamu langsung bisa eksekusi setelah training.
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
                            Materi & Handout
                        </h3>
                    </div>
                    <p class="body-default">
                        Slide deck, worksheet, dan cheat sheet untuk reference pasca training. Bisa dipakai onboarding staf baru di masa depan.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            E-Certificate
                        </h3>
                    </div>
                    <p class="body-default">
                        Sertifikat digital sebagai bukti kompetensi peserta. Bisa di-display di LinkedIn atau dokumen formal perusahaan.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Recording Sesi
                        </h3>
                    </div>
                    <p class="body-default">
                        Video rekaman seluruh sesi training. Berguna untuk peserta yang absent, atau sebagai library knowledge perusahaan jangka panjang.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Template & Tools
                        </h3>
                    </div>
                    <p class="body-default">
                        Keyword research template, optimization checklist, dan reporting dashboard yang bisa langsung dipakai tim untuk eksekusi.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            WhatsApp Group Support
                        </h3>
                    </div>
                    <p class="body-default">
                        Akses ke grup konsultasi pasca training. Tim kamu bisa tanya jawab dengan trainer untuk follow-up question dan implementasi.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Mini Audit Akun
                        </h3>
                    </div>
                    <p class="body-default">
                        Bonus: mini audit akun Google Ads perusahaan kamu sebagai bagian dari training. Tim kamu lihat langsung quick wins yang bisa diterapkan.
                    </p>
                </div>

            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 8 — TRAINER PROFILE (UNIQUE - Renra Sedoya, consistent dengan Consulting) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- LEFT: Content --}}
            <div>
                <p class="eyebrow">
                    Profil Trainer
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Training dari Pelaku, Bukan Pengajar
                </h2>

                <p class="body-text mb-6">
                    Banyak corporate training Google Ads yang trainernya cuma certified, tapi tidak aktif kelola akun klien. Akibatnya, materinya cenderung textbook dan tidak konteksual dengan realita 2026.
                </p>

                <p class="body-text mb-8">
                    Di Digimaya, training dipandu langsung oleh Renra Sedoya — founder Digimaya yang setiap hari masih aktif kelola campaign klien dengan budget ratusan juta. Setiap materi yang diajarkan adalah teknik yang sama yang dipakai tim Digimaya untuk klien real.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Praktisi aktif, bukan instruktur full-time</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Materi dari 100+ akun klien aktif Digimaya</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Update fitur 2026: PMax, Enhanced Conversion, AI Bidding</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Konteks bisnis Indonesia, case study lokal</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Trainer card --}}
            <div class="relative">
                <div class="bg-gradient-to-br from-brand-50 to-brand-100/40 rounded-3xl p-8 lg:p-10 border border-brand/20">

                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-brand text-white flex items-center justify-center font-bold text-2xl">
                            RS
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-lg">Renra Sedoya</p>
                            <p class="text-sm text-gray-600">Founder Digimaya · Lead Trainer</p>
                        </div>
                    </div>

                    <p class="body-quote mb-6">
                        Training itu paling efektif kalau yang ngajar bener-bener masih praktek. Materi yang saya bawa ke training adalah yang sama dengan yang saya pakai untuk klien hari ini, bukan teori yang udah outdated.
                    </p>

                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-brand/10">
                        <div>
                            <p class="text-2xl font-bold text-brand mb-1">10+</p>
                            <p class="text-xs text-gray-600">Tahun Praktek</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-brand mb-1">500+</p>
                            <p class="text-xs text-gray-600">Klien Diaudit</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-brand mb-1">Premier</p>
                            <p class="text-xs text-gray-600">Partner</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 9 — WHO THIS IS FOR (dual: perusahaan & individu) ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cocok Buat Siapa
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Program Ini Dirancang untuk 2 Profil Audiens
            </h2>
            <p class="body-text">
                Decision maker yang cari ROI dari training, dan tim yang akan eksekusi langsung. Kedua-duanya dapat manfaat dari kurikulum yang sama.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 lg:p-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Decision Maker
                    </h3>
                </div>
                <p class="body-default mb-5">
                    CEO, CMO, HR Manager, atau Marketing Lead yang mau bangun kapabilitas tim internal dan reduce dependency pada agency.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Mau scale Google Ads in-house</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Ingin tim yang kompeten & mandiri</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Butuh program training terstruktur</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 lg:p-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Tim Eksekutor
                    </h3>
                </div>
                <p class="body-default mb-5">
                    Marketing staff, digital marketer, atau marketing manager yang handle budget iklan dan dituntut hasil terukur.
                </p>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Skill teknis setup & optimasi campaign</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Framework decision making yang jelas</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Tools & template siap pakai untuk eksekusi</p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 10 — CASE STUDY (CMS, hide if empty) ============== --}}
@if ($caseStudy)
<section class="bg-gray-900 border-t border-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mb-12 lg:mb-16">
            <p class="text-sm font-semibold text-brand-100 uppercase tracking-wide mb-6">
                Studi Kasus
            </p>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-6 tracking-tight leading-[1.3]">
                Hasil Nyata Setelah Tim Dilatih
            </h2>
            <p class="text-base lg:text-lg text-gray-400 leading-relaxed">
                Contoh konkret transformasi tim klien setelah engagement corporate training Digimaya.
            </p>
        </div>

        <div class="bg-white rounded-3xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-0">

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


{{-- ============== SECTION 11 — TESTIMONIAL (CMS, hide if empty) ============== --}}
@if ($testimonial)
<section class="bg-gradient-to-b from-brand-50/30 to-white border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center mb-10 lg:mb-12">
            <p class="eyebrow">
                Testimoni Klien
            </p>
            <h2 class="heading-section leading-[1.2]">
                Apa Kata Tim Klien Setelah Training
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


{{-- ============== SECTION 12 — FAQ (CMS, all active) ============== --}}
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
                    Berikut jawaban untuk pertanyaan yang paling sering muncul seputar Corporate Training Digimaya.
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


{{-- ============== SECTION 13 — CTA CLOSING ============== --}}
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
                Saatnya Tim Kamu Naik Level
            </h2>

            <p class="body-text mb-10 max-w-xl mx-auto">
                Konsultasi gratis 30 menit untuk diskusi kebutuhan training perusahaan kamu dan diskusi format yang paling cocok.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Corporate%20Training%20Google%20Ads"
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
                    Kurikulum Custom
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Trainer Praktisi
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
