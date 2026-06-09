@extends('layouts.public')

@section('meta_title', 'Google Ads Management: Kelola Iklan Google dengan Tim Premier Partner | Digimaya')
@section('meta_description', 'Service Google Ads Management dari Digimaya, Google Premier Partner Indonesia. Strategi terstruktur, tracking presisi, dan optimasi konsisten supaya budget iklanmu balik dengan revenue terukur.')

{{-- SEO Schema JSON-LD for this service page --}}
@push('head_schema')
    <x-seo.schema-service
        name="Google Ads Management"
        description="Service Google Ads Management dari Digimaya, Google Premier Partner Indonesia. Strategi terstruktur, tracking presisi, dan optimasi konsisten supaya budget iklanmu balik dengan revenue terukur."
        serviceType="Digital Advertising Management"
    />
    <x-seo.schema-faq :faqs="$faqs" />
@endpush

@section('content')


{{-- ============== SECTION 1 — HERO (center-aligned, beda dengan homepage yang left-aligned) ============== --}}
<section class="relative overflow-x-clip bg-gradient-to-b from-brand-50/30 to-white">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="max-w-3xl mx-auto text-center">

            <h1 class="heading-hero mb-6">
                Google Ads Agency yang Transparan, Bergaransi dan Fokus ke Penjualan –
                <span class="bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                    untuk Bisnis Skala Kecil-Menengah.
                </span>
            </h1>

            <p class="body-lead mb-10 max-w-2xl mx-auto">
                Kami membantu bisnis menjalankan Google Ads dengan pendekatan yang lebih strategis, teknikal, dan fokus ke penjualan. Mulai dari tracking, struktur campaign, hingga optimasi performa — semuanya dirancang agar budget iklan bekerja lebih efektif dan menghasilkan growth yang bisa diukur.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads%20Management"
                   target="_blank" rel="noopener"
                   class="btn-secondary">
                    <svg class="w-5 h-5 text-brand" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Tanya via WhatsApp
                </a>
            </div>

            {{-- Trust row --}}
            <div class="mt-12 lg:mt-16 pt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-xs sm:text-sm text-gray-600">
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
                    500+ Bisnis Teroptimasi
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Open Dashboard
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 2 — WHAT IS (2-col text + visual, NEW section, not on homepage) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- LEFT: Content --}}
            <div>

                <h2 class="heading-section mb-6 leading-[1.2]">
                   Sistem Google Ads yang Dibangun untuk Jangka Panjang
                </h2>

                <p class="body-text mb-6">
                   Performa Google Ads yang stabil bukan datang dari “hack” dan setting instan, tapi dari strategi, data, dan optimasi yang dijalankan secara konsisten.
                </p>

                <p class="body-text mb-8">
                    Kami membantu bisnis mengelola Google Ads secara lebih terstruktur — mulai dari setup awal sampai scaling — agar budget iklan bisa bekerja lebih efektif seiring pertumbuhan bisnis.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Strategi mengikuti objective bisnis</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Tracking dan reporting yang sangat transparan</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Optimasi rutin untuk menjaga performa tetap sehat</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Visual stat block --}}
            <div class="relative">
                <div class="bg-gradient-to-br from-brand-50 to-brand-100/40 rounded-3xl p-8 lg:p-10 border border-brand/20">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-4xl lg:text-5xl font-bold text-brand mb-2">10+</p>
                            <p class="body-default">Tahun Pengalaman</p>
                        </div>
                        <div>
                            <p class="text-4xl lg:text-5xl font-bold text-brand mb-2">500+</p>
                            <p class="body-default">Klien Teroptimasi</p>
                        </div>
                        <div>
                            <p class="text-4xl lg:text-5xl font-bold text-brand mb-2">Premier</p>
                            <p class="body-default">Google Partner</p>
                        </div>
                        <div>
                            <p class="text-4xl lg:text-5xl font-bold text-brand mb-2">24/7</p>
                            <p class="body-default">Akses Transparan</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 3 — WHEN TO HIRE US (2-col qualification, NEW) ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Kapan Hire Digimaya
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Service Ini Efektif Buat Kondisi Tertentu
            </h2>
            <p class="body-text">
                Biar nggak salah expectation, cek dulu apakah situasimu match sebelum lanjut konsultasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">

            {{-- For --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Cocok kalau kamu
                    </h3>
                </div>

                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Punya bisnis dengan produk atau service yang sudah jelas dan siap dijual</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Mau scale lewat Google Ads tapi nggak punya waktu kelola sendiri</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Pernah jalanin Google Ads sendiri atau pakai vendor lain tapi belum puas hasilnya</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Cari partner yang transparan dengan reporting yang bisa kamu pahamin</p>
                    </li>
                </ul>
            </div>

            {{-- Not for --}}
            <div class="bg-gray-50/60 border border-gray-100 rounded-2xl p-6 sm:p-7 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Belum cocok kalau kamu
                    </h3>
                </div>

                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Budget iklan di bawah 5 juta per bulan (kurang ruang untuk optimasi)</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Cari iklan yang instan profitable tanpa periode optimasi</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Belum punya landing page atau website yang siap nerima traffic</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Mau jalanin sendiri dan cuma butuh kursus (cek Google Ads Academy)</p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 4 — OUR PROCESS (4-step horizontal, NEW) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Proses Kerja
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                4 Tahap dari Onboarding sampai Scale
            </h2>
            <p class="body-text">
                Setiap engagement Google Ads Management Digimaya ikutin proses yang sama. Predictable, transparan, dan terstruktur.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-lg mb-5">
                    01
                </div>
                <h3 class="heading-card-md mb-3">
                    Discovery
                </h3>
                <p class="body-default">
                    Deep dive bisnismu: produk, target audience, kompetitor, dan goals. Kami susun strategy yang disesuaikan, bukan template.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-lg mb-5">
                    02
                </div>
                <h3 class="heading-card-md mb-3">
                    Setup
                </h3>
                <p class="body-default">
                    Audit akun existing atau setup dari nol. Tracking, conversion, audience, dan struktur campaign disiapkan sebelum spend pertama.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-lg mb-5">
                    03
                </div>
                <h3 class="heading-card-md mb-3">
                    Launch
                </h3>
                <p class="body-default">
                    Campaign live dengan konfigurasi yang sudah teruji. Kami monitor harian terutama di minggu pertama untuk capture data awal.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand text-white rounded-xl flex items-center justify-center font-bold text-lg mb-5">
                    04
                </div>
                <h3 class="heading-card-md mb-3">
                    Optimize
                </h3>
                <p class="body-default">
                    Routine optimasi mingguan dan reporting bulanan. Setiap insight di-translate jadi action, bukan cuma grafik di slide.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 5 — WHAT YOU'LL GET (deliverables 2x3 grid, sticky pattern) ============== --}}
<section class="bg-gray-50/40 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            {{-- LEFT: Sticky heading --}}
            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    Yang Kamu Dapetin
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Bukan Cuma Pasang Iklan, Tapi Sistem Komprehensif
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Setiap engagement Google Ads Management Digimaya termasuk 6 komponen di samping ini. Semua disesuaikan dengan kebutuhan dan stage bisnismu.
                </p>

                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Diskusikan Detail
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
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Account Strategy
                        </h3>
                    </div>
                    <p class="body-default">
                        Dokumen strategy lengkap dari audience targeting, campaign structure, sampai bidding plan. Disusun di awal engagement dan di-update tiap quarter.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Tracking Setup
                        </h3>
                    </div>
                    <p class="body-default">
                        Google Tag Manager dan conversion tracking dipasang sampai akurat. Foundation paling penting biar data campaign bisa dipercaya untuk optimasi.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Campaign Build & Launch
                        </h3>
                    </div>
                    <p class="body-default">
                        Setup campaign Search, Performance Max, Display, atau YouTube sesuai strategy. Termasuk keyword, ad copy, asset, dan extension.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Weekly Optimization
                        </h3>
                    </div>
                    <p class="body-default">
                        Setiap minggu kami review performance, ambil action konkret untuk improve hasil. Bid adjustment, negative keyword, ad rotation, sampai budget reallocation.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Monthly Reporting
                        </h3>
                    </div>
                    <p class="body-default">
                        Report bulanan yang bukan cuma screenshot dashboard. Berisi insight, win, masalah, dan rekomendasi action plan untuk bulan berikutnya.
                    </p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Dedicated Account Manager
                        </h3>
                    </div>
                    <p class="body-default">
                        Satu Account Manager khusus untuk akun kamu. Komunikasi via WhatsApp dan email, paham konteks bisnismu tanpa harus jelasin dari nol setiap bulan.
                    </p>
                </div>

            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 6 — COMPARISON TABLE (from CMS comparison_rows, hide if empty) ============== --}}
@if ($comparisonRows->isNotEmpty())
<section class="bg-white border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Apa Bedanya
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Digimaya vs Agency Biasa
            </h2>
            <p class="body-text">
                Tidak semua agency Google Ads sama. Ini perbedaan pendekatan yang kamu dapetin sama Digimaya.
            </p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">

            {{-- Table header --}}
            <div class="grid grid-cols-3 bg-gray-50/80 border-b border-gray-100">
                <div class="px-4 sm:px-6 py-4">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide">Aspek</p>
                </div>
                <div class="px-4 sm:px-6 py-4 border-l border-gray-100">
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide">Agency Biasa</p>
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


{{-- ============== SECTION 7 — CASE STUDY (1 featured large card, hide if empty) ============== --}}
@if ($caseStudy)
<section class="bg-gray-900 border-t border-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mb-12 lg:mb-16">
            <p class="text-sm font-semibold text-brand-100 uppercase tracking-wide mb-6">
                Studi Kasus
            </p>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-6 tracking-tight leading-[1.3]">
                Hasil Nyata dari Klien Digimaya
            </h2>
            <p class="text-base lg:text-lg text-gray-400 leading-relaxed">
                Contoh konkret bagaimana strategy Google Ads Management Digimaya bantu klien naikkan revenue dengan budget terukur.
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

                    {{-- Industry badge --}}
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

                    {{-- Client name --}}
                    <p class="text-xs sm:text-sm font-semibold text-brand uppercase tracking-wide mb-3">
                        {{ $caseStudy->client_name }}
                    </p>

                    {{-- Title --}}
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-6 tracking-tight leading-snug">
                        {{ $caseStudy->title }}
                    </h3>

                    {{-- Results metrics --}}
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

                    {{-- Problem & Solution --}}
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


{{-- ============== SECTION 8 — TESTIMONIAL FEATURED (1 testimonial, hide if empty) ============== --}}
@if ($testimonial)
<section class="bg-gradient-to-b from-brand-50/30 to-white border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center mb-10 lg:mb-12">
            <p class="eyebrow">
                Testimoni Klien
            </p>
            <h2 class="heading-section leading-[1.2]">
                Apa Kata Klien Digimaya
            </h2>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 sm:p-10 lg:p-12 text-center">

            {{-- Rating stars --}}
            @if ($testimonial->rating)
                <div class="flex items-center justify-center gap-1 mb-6">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
            @endif

            {{-- Quote --}}
            <blockquote class="body-pull-quote mb-8 max-w-2xl mx-auto">
                &ldquo;{{ $testimonial->quote }}&rdquo;
            </blockquote>

            {{-- Author --}}
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


{{-- ============== SECTION 9 — FAQ (sticky left + accordion right, all active) ============== --}}
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
                    Pertanyaan yang Sering Kami Terima
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Berikut jawaban untuk pertanyaan yang paling sering muncul seputar service Google Ads Management Digimaya.
                </p>

                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            {{-- RIGHT: Accordion --}}
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


{{-- ============== SECTION 10 — CTA CLOSING (with decorative blobs) ============== --}}
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
                Saatnya Iklan Google Ads Kamu Naik Level
            </h2>

            <p class="body-text mb-10 max-w-xl mx-auto">
                Konsultasi gratis 30 menit untuk diskusi situasi bisnismu dan apakah Google Ads Management Digimaya cocok untuk kamu.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads%20Management"
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
                    Google Premier Partner
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tanpa Commitment Awal
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
