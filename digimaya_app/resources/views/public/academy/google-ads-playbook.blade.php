@extends('layouts.public')

@section('meta_title', 'Google Ads di Era AI: Edisi 2026 | Digimaya')
@section('meta_description', 'Buku praktis tentang strategi Google Ads di era AI, ditulis langsung oleh Renra Sedoya — praktisi Premier Partner Indonesia. Edisi 2026.')

@section('content')


{{-- ============== SECTION 1 — HERO (FIXED, verified classes only) ============== --}}
<section class="relative overflow-x-clip bg-gradient-to-b from-brand-50/40 to-white">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- LEFT: Text content --}}
            <div class="text-center lg:text-left">

                {{-- Coming Soon badge (no animate-pulse) --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-100 border border-amber-400 rounded-full mb-6">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    <span class="text-xs font-semibold text-amber-800">Coming July 2026</span>
                </div>

                <h1 class="heading-hero mb-6">
                    100 Tips & Trik
                    <span class="block bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                        Google Ads 2026
                    </span>
                </h1>

                <p class="body-lead mb-8 max-w-xl mx-auto lg:mx-0">
                    Buku praktis berisi 100 tips dan trik Google Ads terupdate untuk tahun 2026. Ditulis langsung oleh praktisi Premier Partner Indonesia. Cocok untuk UMKM, marketing manager, dan agency owner.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mb-10">
                    <a href="https://wa.me/6285213228692?text=Halo%20admin%2C%20saya%20mau%20tanya%20tentang%20buku%20Google%20Ads"
                       target="_blank" rel="noopener"
                       class="btn-primary">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Tanya via WhatsApp
                    </a>

                    <a href="#outline" class="btn-secondary">
                        Lihat Daftar Isi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </a>
                </div>

                {{-- Stats row --}}
                <div class="grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0 pt-8 border-t border-gray-200">
                    <div class="text-center lg:text-left">
                        <p class="text-3xl font-bold text-brand mb-1">100</p>
                        <p class="text-xs text-gray-600 leading-tight">Tips & Trik Praktis</p>
                    </div>
                    <div class="text-center lg:text-left">
                        <p class="text-3xl font-bold text-brand mb-1">12</p>
                        <p class="text-xs text-gray-600 leading-tight">Chapter Lengkap</p>
                    </div>
                    <div class="text-center lg:text-left">
                        <p class="text-3xl font-bold text-brand mb-1">2026</p>
                        <p class="text-xs text-gray-600 leading-tight">Update Terbaru</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Book cover (2D flat, no perspective hack) --}}
            <div class="relative flex justify-center lg:justify-end">

                <div class="relative">

                    {{-- Book front cover - fixed width, aspect ratio --}}
                    <div class="relative w-[350px] aspect-[3/4] bg-gradient-to-br from-brand-700 to-brand rounded-lg shadow-2xl overflow-hidden">

                        {{-- Top section: author --}}
                        <div class="absolute top-0 left-0 right-0 p-8">
                            <p class="text-sm font-semibold text-white tracking-wider mb-1">RENRA SEDOYA</p>
                            <p class="text-xs text-white/70 tracking-widest">PREMIER PARTNER INDONESIA</p>
                        </div>

                        {{-- Center section: title --}}
                        <div class="absolute top-1/2 left-0 right-0 -translate-y-1/2 px-8">
                            <p class="text-white/70 text-xs font-semibold uppercase tracking-wider mb-3">Buku Praktis</p>
                            <h2 class="text-white text-4xl font-bold leading-tight tracking-tight mb-2">
                                100 Tips & Trik
                            </h2>
                            <p class="text-white text-2xl font-bold leading-tight mb-2">
                                Google Ads
                            </p>
                            <p class="text-yellow-400 text-4xl font-bold">2026</p>
                        </div>

                        {{-- Bottom section: edition --}}
                        <div class="absolute bottom-0 left-0 right-0 p-8 flex items-end justify-between">
                            <p class="text-xs text-white/70 tracking-widest">EDISI PERTAMA</p>
                            <p class="text-xs text-white/70">by Digimaya</p>
                        </div>

                        {{-- Spine effect (left edge) --}}
                        <div class="absolute left-0 top-0 bottom-0 w-3 bg-black/40"></div>

                        {{-- Decorative line center --}}
                        <div class="absolute top-1/2 left-8 right-8 h-px bg-white/10"></div>
                    </div>

                    {{-- Floating "PRE-LAUNCH" badge (inline-style rotate) --}}
                    <div class="absolute -top-4 -right-4 z-10" style="transform: rotate(-8deg);">
                        <div class="bg-yellow-500 px-4 py-2 rounded-lg shadow-xl border-2 border-white">
                            <p class="text-xs font-bold leading-none text-amber-900">PRE-LAUNCH</p>
                            <p class="text-xs font-semibold leading-none text-amber-800 mt-1">Limited Edition</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 2 — ABOUT BUKU ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Tentang Buku
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Bukan Teori. Tips Praktis dari Lapangan.
            </h2>
            <p class="body-text">
                Setiap tip di buku ini lahir dari ratusan akun klien yang kami kelola di Digimaya. Bukan textbook, bukan teori akademis, tapi insight aktual yang bisa langsung kamu terapkan untuk improve campaign Google Ads kamu hari ini.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    100 Tips Konkret
                </h3>
                <p class="body-default">
                    Tidak ada filler. Setiap tip langsung to the point dengan contoh implementasi yang bisa langsung kamu coba di akun Google Ads.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Update Fitur 2026
                </h3>
                <p class="body-default">
                    Bahas fitur terbaru Google Ads: Performance Max, AI Bidding, Enhanced Conversion, Demand Gen, dan adaptasi kebijakan privasi terkini.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Konteks Bisnis Indonesia
                </h3>
                <p class="body-default">
                    Tips dengan konteks bisnis lokal Indonesia. Contoh, studi kasus, dan strategi yang relevan dengan market behavior dan budget UMKM Indonesia.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 3 — SNEAK PEEK OUTLINE (12 chapters) ============== --}}
<section id="outline" class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Daftar Isi
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                12 Chapter, 100 Tips, dari Foundation sampai Scaling
            </h2>
            <p class="body-text">
                Struktur buku dirancang sebagai learning journey lengkap. Mulai dari setup akun yang benar, sampai strategi scaling budget puluhan juta per bulan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 01</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">8 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Foundation: Setup Akun yang Benar dari Awal
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Struktur folder, billing, conversion goal, dan settings krusial yang sering dilewatkan pemula.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 02</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">10 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Riset Keyword yang Bikin Iklan Tepat Sasaran
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Cara identifikasi keyword high-intent, match type strategy, dan tools yang efisien untuk riset.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 03</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">9 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Conversion Tracking & Measurement Strategy
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Setup GA4, GTM, conversion action, dan Enhanced Conversion untuk data yang akurat.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 04</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">8 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Struktur Campaign yang Scalable
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Hierarki account, naming convention, dan struktur ad group yang siap di-scale tanpa restart.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 05</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">9 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Bidding Strategy: Manual vs Smart Bidding
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Kapan pakai Maximize Conversions, kapan switch ke Target CPA, dan cara optimasi AI Bidding.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 06</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">10 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Ad Copy yang Klik & Convert
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Framework headline, descriptions yang menjual, dan optimasi Responsive Search Ads.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 07</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">7 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Quality Score Optimization
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Apa yang mempengaruhi Quality Score, dan cara konkret untuk improve dari 5 ke 9+.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 08</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">8 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Landing Page yang Bikin Iklan Worth It
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Element-element LP yang convert, A/B testing framework, dan integrasi dengan campaign.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 09</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">8 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Search Terms Report & Negative Keywords
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Workflow harian review search term, identify waste, dan build negative keyword list yang kuat.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 10</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">9 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Performance Max: Maksimalkan AI Google
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Setup asset group, audience signals, transparansi reporting, dan strategi PMax vs Search.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 11</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">8 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Audience Targeting & Remarketing
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Customer Match, in-market segments, dan strategi remarketing yang relevan di era privasi.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-brand uppercase tracking-wider">Chapter 12</span>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">6 tips</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 leading-tight">
                    Scaling: Dari Rp 1jt ke Rp 100jt+ per Bulan
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Framework scaling budget tanpa boncos, expansion strategy, dan kapan ekspansi ke channel lain.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 4 — WHY THIS BOOK ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Kenapa Buku Ini
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Karena Skill Google Ads Berubah Setiap Tahun
            </h2>
            <p class="body-text">
                Banyak buku Google Ads di pasaran, tapi mayoritas content-nya outdated (2022-2023), terjemahan dari konten asing yang tidak relevan dengan market Indonesia, atau terlalu teoritis tanpa aplikasi nyata.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">

            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 sm:p-7">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-red-200">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Buku Google Ads Lain
                    </h3>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Content tertinggal 1-2 tahun, banyak fitur yang sudah deprecated</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Terjemahan dari konten asing, contoh kasusnya US/UK market</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Teoritis, panjang penjelasan tapi miskin contoh praktis</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Ditulis oleh akademisi atau penulis, bukan praktisi aktif</p>
                    </li>
                </ul>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 sm:p-7">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-green-200">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">
                        100 Tips & Trik Google Ads 2026
                    </h3>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Update terbaru 2026: PMax, AI Bidding, Enhanced Conversion, Demand Gen</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Ditulis langsung dalam Bahasa Indonesia dengan studi kasus lokal</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Format tips singkat dan actionable, langsung bisa diterapkan</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Ditulis oleh praktisi aktif yang setiap hari kelola akun klien real</p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 5 — ABOUT PENULIS ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            <div class="relative">
                <div class="bg-brand-50 rounded-3xl p-8 lg:p-10 border border-brand/20">

                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-brand text-white flex items-center justify-center font-bold text-2xl">
                            RS
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-lg">Renra Sedoya</p>
                            <p class="text-sm text-gray-600">Founder Digimaya · Penulis Buku</p>
                        </div>
                    </div>

                    <p class="body-quote mb-6">
                        Saya nulis buku ini bukan untuk pamer ilmu, tapi untuk share insight yang seharusnya saya tau 10 tahun lalu saat baru mulai. Semua tips di buku ini lahir dari ratusan akun klien yang saya kelola, bukan dari teori buku lain.
                    </p>

                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-brand/20">
                        <div>
                            <p class="text-2xl font-bold text-brand mb-1">10+</p>
                            <p class="text-xs text-gray-600">Tahun Praktek</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-brand mb-1">500+</p>
                            <p class="text-xs text-gray-600">Klien Dikelola</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-brand mb-1">Premier</p>
                            <p class="text-xs text-gray-600">Partner</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <p class="eyebrow">
                    Tentang Penulis
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Ditulis Langsung oleh Praktisi Aktif Google Ads
                </h2>

                <p class="body-text mb-6">
                    Renra Sedoya adalah founder Digimaya, agency Google Ads bersertifikat Premier Partner di Indonesia. Setiap hari masih aktif kelola campaign klien dengan total budget ratusan juta per bulan.
                </p>

                <p class="body-text mb-8">
                    Buku ini ditulis dari catatan praktis 10 tahun pengalaman kelola Google Ads, bukan dari teori akademis atau terjemahan konten asing.
                </p>

                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Google Premier Partner Indonesia</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Trainer Google Ads untuk korporat dan perorangan</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Pengalaman 10+ tahun di industri performance marketing</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 6 — WHO THIS IS FOR ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cocok untuk Siapa
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                3 Profil Pembaca yang Akan Dapat Manfaat Terbesar
            </h2>
            <p class="body-text">
                Format tips dirancang flexible untuk berbagai level. Mulai dari yang baru pertama buka Google Ads, sampai marketer senior yang sudah handle budget besar.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Pemilik UMKM
                </h3>
                <p class="body-default mb-4">
                    Yang mau jalan Google Ads sendiri untuk hemat budget agency, dan paham sebenarnya budget iklan dipakai ke mana.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Tips foundational, mudah diikuti</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Contoh budget UMKM 1-10 juta</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Marketing Manager
                </h3>
                <p class="body-default mb-4">
                    Di perusahaan yang handle budget bulanan 10-100 juta, butuh framework decision making yang lebih structured.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Tips advanced bidding & scaling</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Framework reporting ke management</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="heading-card-md mb-3">
                    Freelancer & Agency
                </h3>
                <p class="body-default mb-4">
                    Yang handle multiple klien dan butuh sistem yang scalable. Plus framework presentasi hasil ke klien.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">SOP onboarding klien baru</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Template proposal & reporting</p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 7 — FAQ ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    FAQ
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Pertanyaan yang Sering Ditanya
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Berikut jawaban untuk pertanyaan yang paling sering muncul tentang buku ini.
                </p>

                <a href="https://wa.me/6285213228692?text=Halo%20admin%2C%20saya%20mau%20tanya%20tentang%20buku%20Google%20Ads"
                   target="_blank" rel="noopener"
                   class="btn-primary">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Tanya via WhatsApp
                </a>
            </div>

            <div x-data="{ open: null }" class="space-y-3">

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 1 ? open = null : open = 1" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Kapan buku ini terbit?</span>
                        <svg :class="open === 1 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 1" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Target launch sekitar Juli 2026. Tanggal pasti akan kami informasikan saat buku siap cetak. Hubungi via WhatsApp untuk dapat update terbaru.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 2 ? open = null : open = 2" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Berapa harga buku ini?</span>
                        <svg :class="open === 2 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 2" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Harga final akan ditentukan dekat launch. Yang sudah tertarik dari sekarang akan dapat akses ke harga early bird khusus pre-launch. Tanya via WhatsApp untuk update.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 3 ? open = null : open = 3" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Apakah ada versi ebook / digital?</span>
                        <svg :class="open === 3 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 3" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Edisi pertama akan tersedia dalam format buku fisik. Versi ebook sedang dipertimbangkan untuk edisi berikutnya berdasarkan demand pembaca.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 4 ? open = null : open = 4" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Apakah cocok untuk yang baru mulai Google Ads?</span>
                        <svg :class="open === 4 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 4" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Ya. Buku dimulai dari foundation (Chapter 1-4) yang cocok untuk pemula. Chapter berikutnya berkembang ke topic yang lebih advanced untuk yang sudah berpengalaman.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 5 ? open = null : open = 5" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Apakah konten buku ini akan outdated cepat?</span>
                        <svg :class="open === 5 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 5" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Mayoritas tips bersifat foundational (struktur, framework, strategy) yang tetap relevan jangka panjang. Untuk fitur spesifik yang bisa berubah, kami akan release edisi update setiap 1-2 tahun.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 6 ? open = null : open = 6" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Apa yang membedakan dari training Google Ads online?</span>
                        <svg :class="open === 6 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 6" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Buku ini cocok sebagai reference yang bisa dibuka kapan saja saat butuh insight cepat. Training online lebih interactive dan cocok untuk belajar struktur dari awal. Keduanya saling melengkapi.
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 8 — CTA CLOSING ============== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-brand-50/30 to-white border-t border-gray-100">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/3"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-100 border border-amber-400 rounded-full mb-6">
                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                <span class="text-xs font-semibold text-amber-800">Coming July 2026</span>
            </div>

            <h2 class="heading-section mb-6 leading-[1.2]">
                Tertarik dengan Buku Ini?
            </h2>

            <p class="body-text mb-10 max-w-xl mx-auto">
                Hubungi kami via WhatsApp untuk informasi lebih lanjut tentang buku "100 Tips & Trik Google Ads 2026", harga early bird, dan jadwal launch yang lebih spesifik.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="https://wa.me/6285213228692?text=Halo%20admin%2C%20saya%20mau%20tanya%20tentang%20buku%20Google%20Ads"
                   target="_blank" rel="noopener"
                   class="btn-primary">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Tanya via WhatsApp
                </a>
            </div>

            <p class="text-xs text-gray-500 mt-8">
                Slot pre-launch terbatas. Yang menghubungi lebih awal akan dapat priority info dan harga early bird.
            </p>
        </div>

    </div>
</section>

@endsection