@extends('layouts.public')

@section('meta_title', 'Workshop Google Ads Next Gen: Belajar Langsung dari Praktisi Premier Partner | Digimaya')
@section('meta_description', 'Workshop offline Google Ads 2 hari full-day di Jogja, 29 & 30 Agustus 2026. Materi lengkap dari basic sampai advanced, langsung oleh Renra Sedoya. Kuota terbatas 30 peserta.')

{{-- SEO Schema JSON-LD for this academy page --}}
@push('head_schema')
    <x-seo.schema-course
        name="Workshop Google Ads Next Gen"
        description="Workshop offline Google Ads 2 hari full-day di Jogja, 29 &amp; 30 Agustus 2026. Materi lengkap dari basic sampai advanced, langsung oleh Renra Sedoya. Kuota terbatas 30 peserta."
        courseType="Digital Marketing Workshop"
    />
@endpush

@section('content')


{{-- ============== SECTION 1 — HERO ============== --}}
<section class="relative overflow-x-clip bg-gradient-to-b from-brand-50/40 to-white">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-24">

        <div class="text-center mb-12">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-100 border border-amber-400 rounded-full mb-6">
                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                <span class="text-xs font-semibold text-amber-800">Workshop Offline 2 Hari Full-Day</span>
            </div>

            <h1 class="heading-hero mb-6">
                Google Ads
                <span class="block bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                    Next Gen 2026
                </span>
            </h1>

            <p class="body-lead mb-8 max-w-2xl mx-auto">
                Workshop offline Google Ads 2 hari full-day yang fokus pada praktek nyata. Mulai dari strategi terbaru di era AI, best practice para spesialis, hingga studi kasus campaign yang sudah terbukti berjalan.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center mb-12">
                <a href="#pricing" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    Lihat Harga Tiket
                </a>
                <a href="#agenda" class="btn-secondary">
                    Lihat Agenda Lengkap
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- City Card --}}
        <div class="max-w-md mx-auto">

            {{-- JOGJA --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-7 shadow-sm hover:shadow-lg transition">
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-brand uppercase tracking-wider mb-1">Batch Agustus</p>
                        <h2 class="text-2xl font-bold text-gray-900">Jogja</h2>
                    </div>
                </div>

                <div class="space-y-3 mb-5">
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">29 & 30 Agustus 2026</p>
                            <p class="text-xs text-gray-600">Sabtu - Minggu, 08:00 - 18:00</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Kolektif Coworking Space &amp; Collaboraction</p>
                            <a href="https://maps.app.goo.gl/i642jSkwZxDvFqkWA" target="_blank" rel="noopener" class="text-xs text-brand hover:underline">
                                Buka Google Maps &rsaquo;
                            </a>
                        </div>
                    </div>
                </div>

                <a href="#pricing" class="block w-full text-center py-3 bg-brand-50 hover:bg-brand-100 text-brand font-semibold text-sm rounded-lg transition">
                    Lihat Detail Tiket Jogja
                </a>
            </div>

        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto pt-10 mt-10 border-t border-gray-200">
            <div class="text-center">
                <p class="text-3xl font-bold text-brand mb-1">2</p>
                <p class="text-xs text-gray-600 leading-tight">Hari Full-Day</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-brand mb-1">16</p>
                <p class="text-xs text-gray-600 leading-tight">Sesi Materi</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-brand mb-1">30</p>
                <p class="text-xs text-gray-600 leading-tight">Max Peserta</p>
            </div>
        </div>

    </div>
</section>


{{-- ============== SECTION 2 — ABOUT NEXT GEN ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Tentang Workshop
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Apa itu Google Ads Next Gen?
            </h2>
            <p class="body-text">
                Next Gen adalah workshop offline Google Ads 2 hari full-day dari Digimaya yang fokus pada praktek nyata. Mulai dari strategi terbaru di era AI, best practice para spesialis Google Ads, hingga studi kasus real dari campaign yang sudah terbukti berjalan.
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-brand-50 to-brand-100/40 border border-brand/20 rounded-3xl p-8 lg:p-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl mb-6 shadow-sm">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Sertifikat Resmi dari Google</h3>
                <p class="body-text mb-6 max-w-2xl mx-auto">
                    Setelah menyelesaikan workshop, kamu akan mendapatkan sertifikat resmi sebagai bukti kompetensi. Nilai tambah untuk profil profesional atau tim marketing kamu.
                </p>

                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-brand/20 rounded-full">
                    <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Sertifikat fisik + digital</span>
                </div>
            </div>
        </div>

    </div>
</section>


{{-- ============== SECTION 3 — ABOUT PEMATERI ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Tentang Pemateri
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Belajar Langsung dari Praktisi Aktif
            </h2>
            <p class="body-text">
                Pemateri Next Gen adalah praktisi Google Ads yang setiap hari masih aktif kelola campaign klien real. Bukan trainer yang cuma teori, tapi yang benar-benar paham realita di lapangan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">

            {{-- Renra --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 shadow-sm">
                <div class="flex items-center gap-4 mb-5 pb-5 border-b border-gray-100">
                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-brand text-white flex items-center justify-center font-bold text-2xl">
                        RS
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-lg">Renra Sedoya</p>
                        <p class="text-sm text-gray-600">Founder Digimaya</p>
                        <p class="text-xs text-brand font-semibold mt-1">Lead Trainer · 15 sesi</p>
                    </div>
                </div>
                <p class="body-default mb-5">
                    Founder Digimaya, agency Google Ads bersertifikat Premier Partner di Indonesia. 10+ tahun pengalaman kelola campaign untuk ratusan klien dengan budget ratusan juta per bulan.
                </p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Google Premier Partner Indonesia</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">500+ klien dikelola</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Trainer Google Ads sejak 2015</p>
                    </div>
                </div>
            </div>

            {{-- Jhonson --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 shadow-sm">
                <div class="flex items-center gap-4 mb-5 pb-5 border-b border-gray-100">
                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-700 text-white flex items-center justify-center font-bold text-2xl">
                        J
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-lg">Jhonson</p>
                        <p class="text-sm text-gray-600">Specialist Pemateri</p>
                        <p class="text-xs text-brand font-semibold mt-1">Specialist · 1 sesi khusus</p>
                    </div>
                </div>
                <p class="body-default mb-5">
                    Specialist dengan fokus pada Offline Conversion dan Google Ads Scripts (GASS). Membawakan sesi khusus tentang implementasi advanced conversion tracking untuk bisnis offline.
                </p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Spesialis Offline Conversion</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Expert Google Ads Scripts (GASS)</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Pengalaman handle B2B & offline business</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 4 — COCOK UNTUK SIAPA ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cocok untuk Siapa
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Workshop Ini untuk 3 Profil Pelaku
            </h2>
            <p class="body-text">
                Materi dirancang flexible untuk berbagai level. Mulai dari yang baru pertama buka Google Ads, sampai marketer senior yang sudah handle budget besar.
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
                    Pemilik Bisnis
                </h3>
                <p class="body-default mb-4">
                    Yang mau handle Google Ads sendiri, paham kemana budget iklan dipakai, dan butuh control penuh atas campaign-nya.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Foundation untuk pemula</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Pendampingan 1 bulan setelah workshop</p>
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
                    Di perusahaan yang handle budget Google Ads bulanan menengah ke atas, butuh framework decision making yang structured.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Materi advanced PMax, Demand Gen</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Strategi scale up budget</p>
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
                    Yang handle multiple klien dan butuh skill complete dari basic sampai advanced. Plus networking dengan praktisi lain.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Audit & troubleshooting mastery</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Networking dengan praktisi senior</p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 5 — WHAT YOU GET ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Yang Kamu Bawa Pulang
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Bukan Cuma Ilmu, Tapi Paket Lengkap
            </h2>
            <p class="body-text">
                Setiap peserta Next Gen akan mendapatkan paket lengkap yang dirancang untuk memastikan investasi waktu dan biaya kamu memberikan return maksimal.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Sertifikat Resmi Google</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Sertifikat fisik dan digital sebagai bukti kompetensi yang bisa kamu cantumkan di CV atau LinkedIn.</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Support 1 Bulan</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Pendampingan dari tim Digimaya selama 1 bulan setelah workshop untuk konsultasi implementasi.</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Grup WhatsApp Privat</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Akses grup diskusi privat dengan alumni Next Gen, tempat sharing problem dan solusi Google Ads.</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Merchandise Eksklusif</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Merchandise dari Digimaya khusus untuk peserta Next Gen sebagai memorabilia workshop.</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Konsumsi Lengkap</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Coffee break pagi, lunch siang, dan coffee break sore selama 2 hari workshop.</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Materi Workshop</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Soft copy materi lengkap, template, dan checklist yang bisa kamu pakai untuk implementasi setelah workshop.</p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 6 — AGENDA ============== --}}
<section id="agenda" class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Agenda Lengkap
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Pelatihan Google Ads Terlengkap
            </h2>
            <p class="body-text">
                Materi dari basic, intermediate, sampai advanced. Disusun progresif supaya kamu dapat foundation dulu sebelum masuk ke topik yang lebih kompleks.
            </p>
        </div>

        <div class="space-y-8 max-w-4xl mx-auto">

            {{-- HARI 1 --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <span class="inline-flex items-center justify-center w-10 h-10 bg-brand text-white rounded-full font-bold text-sm">1</span>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Hari Pertama</h3>
                        <p class="text-sm text-gray-600">Foundation dan core skills Google Ads</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="divide-y divide-gray-100">

                        <div class="flex items-center justify-between gap-4 px-5 py-4 bg-gray-50">
                            <p class="text-sm font-semibold text-gray-700">Registration</p>
                            <p class="text-xs text-gray-500 whitespace-nowrap">08:00 - 08:40</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <p class="text-sm font-semibold text-gray-700">Opening</p>
                            <p class="text-xs text-gray-500 whitespace-nowrap">08:40 - 09:00</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-3 bg-amber-50">
                            <p class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Coffee Break</p>
                            <p class="text-xs text-amber-700">15 menit</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Google Ads Audit Mastery</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">09:00 - 10:00</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Google Tag Manager Mastery</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">10:00 - 12:00</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-3 bg-amber-50">
                            <p class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Lunch Time</p>
                            <p class="text-xs text-amber-700">1 jam</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Click Fraud Analysis</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">13:00 - 14:00</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Search Ads Mastery</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">14:00 - 15:20</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-3 bg-amber-50">
                            <p class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Coffee Break</p>
                            <p class="text-xs text-amber-700">40 menit</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Google Ads Common Issues</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">16:00 - 17:00</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- HARI 2 --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <span class="inline-flex items-center justify-center w-10 h-10 bg-brand text-white rounded-full font-bold text-sm">2</span>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Hari Kedua</h3>
                        <p class="text-sm text-gray-600">Advanced topics dan scaling strategy</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                    <div class="divide-y divide-gray-100">

                        <div class="flex items-center justify-between gap-4 px-5 py-4 bg-gray-50">
                            <p class="text-sm font-semibold text-gray-700">Registration</p>
                            <p class="text-xs text-gray-500 whitespace-nowrap">08:00 - 08:40</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <p class="text-sm font-semibold text-gray-700">Opening</p>
                            <p class="text-xs text-gray-500 whitespace-nowrap">08:40 - 09:00</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-3 bg-amber-50">
                            <p class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Coffee Break</p>
                            <p class="text-xs text-amber-700">15 menit</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Demand Gen Mastery</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">09:00 - 10:30</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Performance Max Mastery</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">10:30 - 12:00</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-3 bg-amber-50">
                            <p class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Lunch Time</p>
                            <p class="text-xs text-amber-700">1 jam</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Offline Conversion with GASS</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">13:00 - 14:00</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Jhonson</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Scale Up Playbook</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">14:00 - 15:00</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-3 bg-amber-50">
                            <p class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Coffee Break</p>
                            <p class="text-xs text-amber-700">30 menit</p>
                        </div>

                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <p class="font-semibold text-gray-900">Google Ads Certification</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">15:30 - 17:00</p>
                            </div>
                            <p class="text-xs text-brand font-medium">Renra Sedoya</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 px-5 py-4 bg-gray-50">
                            <p class="text-sm font-semibold text-gray-700">Closing & Foto Bersama</p>
                            <p class="text-xs text-gray-500 whitespace-nowrap">17:00 - 17:30</p>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 7 — TESTIMONI ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Testimoni
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Kata Peserta Next Gen Sebelumnya
            </h2>
            <p class="body-text">
                Bukan kata kami, ini kata mereka yang sudah pernah ikut Next Gen di batch-batch sebelumnya.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center text-brand font-bold">G</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">George Muhammad</p>
                        <p class="text-xs text-gray-500">Digifolium</p>
                    </div>
                </div>
                <div class="flex gap-0.5 text-yellow-400 mb-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">"Top markotop, penyelenggara acara beneran gak pelit ilmu. Bener kata yang di awal-awal, kalau join Next Gen gak akan nyesel."</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center text-brand font-bold">S</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Stephanie Vanessa</p>
                        <p class="text-xs text-gray-500">Sekolah Ciputra</p>
                    </div>
                </div>
                <div class="flex gap-0.5 text-yellow-400 mb-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">"Materinya sangat bermanfaat, baik untuk pemula maupun yang sudah berpengalaman. Pembicara kredibel dan disupport langsung oleh Digimaya."</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center text-brand font-bold">R</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Reno Andri</p>
                        <p class="text-xs text-gray-500">PT Multi Sertifikasi Indonesia</p>
                    </div>
                </div>
                <div class="flex gap-0.5 text-yellow-400 mb-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">"Sangat direkomendasikan setelah ikut event ini, lalu lanjut ikut mentoring privat. Sukses terus Digimaya."</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center text-brand font-bold">A</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Aswandi</p>
                        <p class="text-xs text-gray-500">Hanifa Store</p>
                    </div>
                </div>
                <div class="flex gap-0.5 text-yellow-400 mb-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">"Bagus banget acara Next Gen kemaren. Banyak update terbaru terkait fitur Google dan insight cara beriklan terbaru yang saat ini berbasis AI."</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center text-brand font-bold">M</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">M. Riyan Apriyanto</p>
                        <p class="text-xs text-gray-500">Wisnumart</p>
                    </div>
                </div>
                <div class="flex gap-0.5 text-yellow-400 mb-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">"Acaranya gokil, insight banget. Ternyata selama ini perubahan Google sudah jauh banget. Cara yang kami biasa pake ternyata banyak yang sudah tidak relevan."</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center text-brand font-bold">A</div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Aulia Wahyuning</p>
                        <p class="text-xs text-gray-500">Alfalah Aqiqah</p>
                    </div>
                </div>
                <div class="flex gap-0.5 text-yellow-400 mb-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">"Alhamdulillah gak nyesel jauh-jauh samperin ke Jogja untuk ikutan eventnya. Dapat insight baru dan link teman-teman pengguna Google Ads."</p>
            </div>

        </div>

        <p class="text-center text-sm text-gray-500 mb-6">Lihat juga testimoni video dari peserta sebelumnya:</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto">
            <div class="aspect-video rounded-2xl overflow-hidden bg-gray-100 shadow-sm">
                <iframe class="w-full h-full" src="https://www.youtube.com/embed/OUgoTOGS6ww" title="Testimoni 1" loading="lazy" allowfullscreen></iframe>
            </div>
            <div class="aspect-video rounded-2xl overflow-hidden bg-gray-100 shadow-sm">
                <iframe class="w-full h-full" src="https://www.youtube.com/embed/fCrNrugaWNs" title="Testimoni 2" loading="lazy" allowfullscreen></iframe>
            </div>
            <div class="aspect-video rounded-2xl overflow-hidden bg-gray-100 shadow-sm">
                <iframe class="w-full h-full" src="https://www.youtube.com/embed/tWKOyaSTZYU" title="Testimoni 3" loading="lazy" allowfullscreen></iframe>
            </div>
        </div>

    </div>
</section>


{{-- ============== SECTION 8 — PRICING ============== --}}
<section id="pricing" class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Pricing
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Siap Jadi The Next Gen?
            </h2>
            <p class="body-text">
                Satu batch, kuota terbatas max 30 orang. Harga terbaik untuk 10 pendaftar pertama.
            </p>
        </div>

        <div class="max-w-md mx-auto">

            {{-- JOGJA --}}
            <div class="bg-white border-2 border-brand rounded-2xl p-6 sm:p-7 shadow-lg transition flex flex-col">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-2 py-1 bg-brand-50 text-brand text-xs font-semibold rounded">Batch Agustus</span>
                    <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">Tersedia</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">Tiket Jogja</h3>
                <p class="text-sm text-gray-600 mb-5">29 &amp; 30 Agustus 2026</p>

                <div class="mb-5">
                    <p class="text-sm text-gray-400 line-through">Rp 5.000.000</p>

                    <div class="flex items-baseline gap-2 mt-1">
                        <p class="text-4xl font-bold text-brand">Rp 1.750.000</p>
                    </div>
                    <p class="text-xs font-bold text-brand uppercase tracking-wider mt-1">Early Bird</p>
                    <p class="text-sm text-gray-700 mt-1">Khusus <span class="font-semibold">10 pendaftar pertama</span></p>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Setelah kuota early bird habis, harga presale
                            <span class="font-semibold text-gray-900">Rp 1.950.000</span>
                        </p>
                    </div>
                </div>

                <ul class="space-y-2 mb-6 flex-1">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm text-gray-700">Akses 2 hari full-day workshop</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm text-gray-700">Sertifikat resmi Google</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm text-gray-700">Konsumsi lengkap 2 hari</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm text-gray-700">Support 1 bulan dari Digimaya</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm text-gray-700">Akses grup WA privat alumni</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm text-gray-700">Merchandise eksklusif</p>
                    </li>
                </ul>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20daftar%20workshop%20Google%20Ads%20Next%20Gen%20Jogja"
                   target="_blank" rel="noopener"
                   class="block w-full text-center py-3 bg-brand hover:bg-brand-700 text-white font-bold text-sm rounded-lg transition">
                    Beli Tiket Jogja
                </a>
            </div>

        </div>

        {{-- Notes --}}
        <div class="max-w-3xl mx-auto mt-10 p-6 bg-gray-50 border border-gray-200 rounded-2xl">
            <h4 class="font-bold text-gray-900 mb-3">Catatan Penting</h4>
            <ul class="space-y-2">
                <li class="flex items-start gap-2">
                    <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-600">Tidak ada seleksi peserta, siapa cepat dia dapat tempat.</p>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-600">Kuota terbatas max 30 orang, amankan seat kamu segera.</p>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-600">Setelah pembayaran, kamu akan dimasukkan ke grup WhatsApp peserta.</p>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-600">Pembayaran via virtual account bank, e-wallet, atau QRIS.</p>
                </li>
            </ul>
        </div>

        {{-- Affiliate Program --}}
        <div class="max-w-3xl mx-auto mt-6 p-6 bg-green-50 border border-green-200 rounded-2xl">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 justify-between">
                <div>
                    <h4 class="font-bold text-green-900 mb-1">Affiliate Program</h4>
                    <p class="text-sm text-green-800">Komisi 20% setiap ada peserta yang join dari link kamu. Cocok untuk influencer atau komunitas digital marketing.</p>
                </div>
                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya%20tentang%20Affiliate%20Program%20Next%20Gen"
                   target="_blank" rel="noopener"
                   class="flex-shrink-0 inline-flex items-center justify-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition whitespace-nowrap">
                    Info Affiliate
                </a>
            </div>
        </div>

    </div>
</section>


{{-- ============== SECTION 9 — FAQ ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">FAQ</p>
                <h2 class="heading-section mb-6 leading-[1.2]">
                    Pertanyaan yang Sering Ditanya
                </h2>
                <p class="body-text mb-10 max-w-md">
                    Jawaban untuk pertanyaan paling umum tentang workshop Next Gen. Kalau masih ada yang ingin ditanyakan, hubungi via WhatsApp.
                </p>
                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya%20tentang%20workshop%20Google%20Ads%20Next%20Gen"
                   target="_blank" rel="noopener" class="btn-primary">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Tanya via WhatsApp
                </a>
            </div>

            <div x-data="{ open: null }" class="space-y-3">

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 1 ? open = null : open = 1" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Apakah workshop ini cocok untuk pemula?</span>
                        <svg :class="open === 1 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 1" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Ya, sangat cocok. Ada materi dasar seperti alur pembuatan campaign Search, Performance Max, dan Demand Gen. Materi disusun progresif dari basic, intermediate, sampai advanced sehingga pemula tetap bisa mengikuti.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 2 ? open = null : open = 2" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Apakah dapat support setelah workshop selesai?</span>
                        <svg :class="open === 2 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 2" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Ya. Kamu akan dapat support 1 bulan dari Digimaya lewat grup WhatsApp privat untuk diskusi dan tanya jawab. Tim siap membantu kalau ada masalah yang belum bisa kamu temukan solusinya.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 3 ? open = null : open = 3" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Apa bedanya Next Gen dibanding pelatihan online?</span>
                        <svg :class="open === 3 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 3" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Next Gen offline jadi kamu bisa langsung tanya jawab dengan pemateri, networking dengan praktisi lain, dan fokus full 2 hari tanpa distraction. Materi juga komprehensif dari basic sampai advanced, plus komunitas alumni aktif.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 4 ? open = null : open = 4" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Pembayaran bisa lewat apa saja?</span>
                        <svg :class="open === 4 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 4" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Kami menggunakan platform UTAS yang mendukung Virtual Account beberapa bank, e-wallet (GoPay, OVO, DANA), dan QRIS. Detail metode pembayaran akan dishare setelah konfirmasi pendaftaran via WhatsApp.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 5 ? open = null : open = 5" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Apakah peserta diberikan handout atau materi?</span>
                        <svg :class="open === 5 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 5" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Ya, kamu akan dapat soft copy materi lengkap, template, dan checklist yang bisa dipakai untuk implementasi setelah workshop. Semua materi bisa diakses kapan saja sebagai reference.
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button" @click="open === 6 ? open = null : open = 6" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">Bagaimana kalau berhalangan hadir setelah daftar?</span>
                        <svg :class="open === 6 ? 'rotate-180 text-brand' : 'text-gray-400'" class="flex-shrink-0 w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 6" x-transition style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            Slot kamu bisa di-transfer ke orang lain atau di-postpone ke batch berikutnya dengan pemberitahuan minimal H-7. Hubungi via WhatsApp untuk koordinasi penggantian.
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 10 — CTA CLOSING ============== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-brand-50/30 to-white border-t border-gray-100">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/3"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-100 border border-amber-400 rounded-full mb-6">
                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                <span class="text-xs font-semibold text-amber-800">Kuota Terbatas 30 Peserta</span>
            </div>

            <h2 class="heading-section mb-6 leading-[1.2]">
                Siap Jadi The Next Gen?
            </h2>

            <p class="body-text mb-10 max-w-xl mx-auto">
                Investasi 2 hari kamu untuk skill Google Ads yang akan kepakai bertahun-tahun ke depan. Sudah banyak praktisi yang upgrade skill mereka di Next Gen, sekarang giliran kamu.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="#pricing" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    Lihat Pilihan Tiket
                </a>
                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya%20tentang%20workshop%20Google%20Ads%20Next%20Gen"
                   target="_blank" rel="noopener"
                   class="btn-secondary">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Tanya Dulu
                </a>
            </div>

            <p class="text-xs text-gray-500 mt-8">
                Pembayaran cepat dan aman via virtual account, e-wallet, atau QRIS.
            </p>
        </div>

    </div>
</section>

@endsection
