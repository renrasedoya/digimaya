@extends('layouts.public')

@section('meta_title', 'Google Ads Academy: Belajar Google Ads dari Praktisi Premier Partner | Digimaya')
@section('meta_description', 'Program belajar Google Ads self-paced untuk business owner. Materi langsung dari tim Digimaya, Google Premier Partner Indonesia. Akses lifetime, sertifikat resmi.')

{{-- SEO Schema JSON-LD for this academy page --}}
@push('head_schema')
    <x-seo.schema-course
        name="Google Ads Academy"
        description="Program belajar Google Ads self-paced untuk business owner. Materi langsung dari tim Digimaya, Google Premier Partner Indonesia. Akses lifetime, sertifikat resmi."
        courseType="Digital Marketing Education"
    />
@endpush

@section('content')


{{-- ============== SECTION 1 — HERO (with decorative blobs, matches homepage hero) ============== --}}
<section class="relative overflow-x-clip bg-gradient-to-b from-brand-50/30 to-white">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="max-w-3xl mx-auto text-center">

            <p class="eyebrow">
                Google Ads Academy by Digimaya
            </p>

            <h1 class="heading-hero mb-6">
                Belajar Google Ads Langsung dari
                <span class="block bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                    Tim Premier Partner Indonesia.
                </span>
            </h1>

            <p class="body-lead mb-10 max-w-2xl mx-auto">
                Program self-paced untuk business owner yang mau jalanin Google Ads sendiri. Materi praktikal langsung dari tim yang setiap hari kelola campaign klien Digimaya.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#pricing" class="btn-primary">
                    Lihat Detail Program
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="#curriculum" class="btn-secondary">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Cek Kurikulum
                </a>
            </div>

            {{-- Trust row --}}
            <div class="mt-12 lg:mt-16 pt-8 border-t border-gray-200/70 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-xs sm:text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Akses Lifetime
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Materi Update Berkala
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Sertifikat Penyelesaian
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 2 — PROBLEM (centered intro + 4-card grid) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Kalau Kamu Pernah Ngalamin Ini
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Google Ads Seharusnya Bantuin Jualan, Bukan Bikin Pusing
            </h2>
            <p class="body-text">
                Tapi kenyataannya, banyak business owner ngerasa hal-hal ini setelah coba jalanin sendiri tanpa bekal yang cukup.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Budget Habis Tanpa Hasil
                    </h3>
                </div>
                <p class="body-default">
                    Saldo iklan tiap minggu kepotong, tapi leads masuk bisa dihitung pakai jari. Ujung-ujungnya bingung mau diapain dan ragu lanjutin.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Bingung Mulai dari Mana
                    </h3>
                </div>
                <p class="body-default">
                    Search campaign, Performance Max, Display, YouTube. Setting tracking, conversion, audience. Belum jalan udah pusing duluan.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Pernah Dikadalin Freelancer
                    </h3>
                </div>
                <p class="body-default">
                    Bayar mahal, dijanjiin hasil bagus, ternyata report-nya cuma screenshot. Mau komplain juga nggak ngerti report-nya bener atau nggak.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Materi YouTube Bikin Bingung
                    </h3>
                </div>
                <p class="body-default">
                    Setiap video kasih jawaban beda. Yang satu bilang Smart Bidding, yang lain Manual CPC. Bingung mana yang bener buat bisnismu.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 3 — AGITATE (centered narrative) ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center">

            <p class="eyebrow">
                Cost of Doing Nothing
            </p>

            <h2 class="heading-section mb-6 leading-[1.2]">
                Tiap Bulan Nunggu, Tiap Bulan Kompetitor Makin Jauh
            </h2>

            <p class="body-text mb-6">
                Sambil kamu masih ragu-ragu, kompetitor di niche yang sama udah dapet leads konsisten setiap hari dari Google Ads. Mereka bukan lebih jago, cuma lebih dulu paham sistemnya.
            </p>

            <p class="body-text mb-10">
                Setiap bulan tanpa traffic dari Google bukan cuma kehilangan peluang penjualan, tapi juga ngasih ruang ke kompetitor buat ngambil customer yang seharusnya jadi kamu punya.
            </p>

            <div class="bg-white border-l-4 border-brand rounded-r-xl px-6 py-5 max-w-2xl mx-auto text-left shadow-sm">
                <p class="body-card-emphasis">
                    Pertanyaannya bukan "Berapa biaya belajar Google Ads?", tapi:
                    <span class="text-brand">"Berapa biaya tetap nggak ngerti Google Ads sampai tahun depan?"</span>
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 4 — SOLUTION (sticky-left + 3 benefit cards right, matches services pattern) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            {{-- LEFT: Sticky heading + CTA --}}
            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    Solusinya
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Google Ads Academy by Digimaya
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Program belajar Google Ads yang dirancang khusus untuk business owner. Materinya bukan teori, tapi sistem yang dipakai Digimaya sehari-hari sebagai Google Premier Partner.
                </p>

                <a href="#pricing" class="btn-primary">
                    Mulai Belajar Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            {{-- RIGHT: Benefit cards stack --}}
            <div class="space-y-5">

                <div class="group bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Praktikal, Bukan Teori
                        </h3>
                    </div>
                    <p class="body-default">
                        Setiap modul fokus ke yang bisa langsung dipraktekin. Step by step dari setup akun, riset keyword, sampai optimasi campaign harian.
                    </p>
                </div>

                <div class="group bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Belajar di Waktu Kamu
                        </h3>
                    </div>
                    <p class="body-default">
                        Self-paced learning. Akses kapan aja, sebanyak yang kamu butuhin. Cocok untuk business owner yang sibuk dan punya jadwal padat.
                    </p>
                </div>

                <div class="group bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-brand-50 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="heading-card-md">
                            Dari Praktisi Aktif
                        </h3>
                    </div>
                    <p class="body-default">
                        Bukan dari yang cuma teori. Materi disusun oleh tim yang setiap hari kelola budget ratusan juta dari klien Digimaya.
                    </p>
                </div>

            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 5 — CURRICULUM (centered intro + accordion list) ============== --}}
<section id="curriculum" class="bg-gray-50/40 border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Yang Akan Kamu Pelajari
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Kurikulum Terstruktur Step by Step
            </h2>
            <p class="body-text">
                Dari fundamental sampai strategi advanced. Disusun berurutan supaya kamu nggak lompat-lompat dan setiap modul punya konteks dari modul sebelumnya.
            </p>
        </div>

        @php
            $modulesList = $modules->count() > 0 ? $modules : collect([
                (object)['title' => 'Fundamental Google Ads', 'description' => 'Konsep dasar Google Ads, cara kerja auction, struktur akun yang bener, dan terminologi penting yang harus kamu paham sebelum mulai.'],
                (object)['title' => 'Riset Keyword dan Audience', 'description' => 'Cara nemuin keyword profitable dan audience yang tepat buat bisnismu. Plus tools yang dipakai tim Digimaya sehari-hari.'],
                (object)['title' => 'Setup Campaign dari Nol', 'description' => 'Praktik langsung bikin Search dan Performance Max campaign step by step. Termasuk best practices untuk struktur akun yang scalable.'],
                (object)['title' => 'Tracking dan Conversion Setup', 'description' => 'Pasang Google Tag Manager dan conversion tracking yang akurat. Ini foundation paling penting biar data campaign kamu bisa dipercaya.'],
                (object)['title' => 'Optimasi Harian dan Mingguan', 'description' => 'Routine optimasi yang dilakukan tim Digimaya. Cara baca data, identify bottleneck, dan ambil action yang bener buat naikin performance.'],
                (object)['title' => 'Strategi Scale-Up', 'description' => 'Cara naikin budget tanpa kehilangan profitability. Framework untuk decide kapan scale, berapa banyak, dan campaign mana yang prioritas.'],
            ]);
        @endphp

        <div x-data="{ open: null }" class="space-y-3">
            @foreach($modulesList as $index => $module)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                    <button type="button"
                            @click="open === {{ $index }} ? open = null : open = {{ $index }}"
                            :aria-expanded="open === {{ $index }} ? 'true' : 'false'"
                            class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <span class="flex-shrink-0 w-10 h-10 bg-brand-50 text-brand rounded-lg flex items-center justify-center font-bold text-sm">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">
                                {{ $module->title }}
                            </span>
                        </div>
                        <svg :class="open === {{ $index }} ? 'rotate-180 text-brand' : 'text-gray-400'"
                             class="flex-shrink-0 w-5 h-5 transition-transform duration-200"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open === {{ $index }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         style="display: none;">
                        <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                            {{ $module->description ?? 'Detail lengkap modul ini bisa kamu akses setelah enroll.' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ============== SECTION 6 — WHO THIS IS FOR / NOT FOR (2-col comparison) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cocok Buat Siapa
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Biar Nggak Salah Expectation
            </h2>
            <p class="body-text">
                Academy ini efektif buat kondisi tertentu. Cek dulu apakah situasimu match sebelum mutusin daftar.
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
                        <p class="body-default">Business owner yang mau jalanin Google Ads sendiri buat bisnis sendiri</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Pernah coba Google Ads tapi hasilnya belum sesuai harapan</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Mau ngerti report dari agency atau freelancer biar nggak gampang dikadalin</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="body-default">Siap meluangkan waktu konsisten 2 sampai 3 jam per minggu untuk belajar</p>
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
                        Nggak cocok kalau kamu
                    </h3>
                </div>

                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Cari shortcut atau strategi viral yang instan tanpa effort</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Mau delegasikan semuanya ke orang lain dan nggak mau ikut ngerti</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Belum punya produk atau service yang siap dijual</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="body-default">Ekspektasi belajar 1 hari langsung jago dan dapet hasil maksimal</p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 7 — INSTRUCTOR (dark accent like Google testimonial section) ============== --}}
<section class="bg-gray-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="text-sm font-semibold text-brand-100 uppercase tracking-wide mb-6">
                Tentang Instruktur
            </p>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-6 tracking-tight leading-[1.3]">
                Diajar Langsung oleh Tim yang Setiap Hari Kelola Campaign Klien
            </h2>

            <p class="text-base lg:text-lg text-gray-400 leading-relaxed">
                Materi disusun oleh <strong class="text-white">Renra Sedoya</strong>, founder Digimaya, bareng tim specialist yang setiap hari kelola campaign klien. Pendekatan yang diajarkan adalah pendekatan yang terbukti di campaign klien real, bukan teori.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6">

            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center">
                <p class="text-3xl sm:text-4xl font-bold text-white mb-2">500+</p>
                <p class="text-sm text-gray-400">Bisnis Terbantu</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center">
                <p class="text-3xl sm:text-4xl font-bold text-white mb-2">10+</p>
                <p class="text-sm text-gray-400">Tahun Pengalaman</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center">
                <p class="text-3xl sm:text-4xl font-bold text-white mb-2">Premier</p>
                <p class="text-sm text-gray-400">Google Partner</p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 8 — PRICING ============== --}}
<section id="pricing" class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Investasi Belajar
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Belajar Sekali, Pakai Selamanya
            </h2>
            <p class="body-text">
                Akses lifetime ke seluruh materi. Termasuk update tiap kali Google Ads keluar fitur baru atau ada perubahan signifikan.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Top accent --}}
            <div class="bg-brand text-white text-center py-3 text-xs sm:text-sm font-semibold uppercase tracking-wide">
                Penawaran Spesial Periode Awal
            </div>

            <div class="p-8 sm:p-12 lg:p-16">

                <div class="text-center mb-10">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                        Google Ads Academy
                    </h3>
                    <p class="body-default mb-8 max-w-md mx-auto">
                        Program lengkap untuk business owner yang mau jalanin Google Ads sendiri.
                    </p>

                    <div class="flex items-baseline justify-center gap-3 mb-3">
                        <span class="text-gray-400 line-through text-lg">Rp 4.500.000</span>
                        <span class="text-4xl sm:text-5xl font-bold text-gray-900 tracking-tight">Rp 2.500.000</span>
                    </div>
                    <p class="micro-text">Sekali bayar, akses lifetime</p>
                </div>

                {{-- Features --}}
                <div class="border-t border-gray-100 pt-8 mb-10">
                    <p class="text-sm font-semibold text-gray-900 mb-5 text-center uppercase tracking-wide">
                        Yang Kamu Dapetin
                    </p>

                    <ul class="space-y-4 max-w-md mx-auto">
                        <li class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="body-default">Akses lifetime ke semua modul dan materi</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="body-default">Update materi tiap Google Ads keluar fitur baru</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="body-default">Sertifikat penyelesaian resmi dari Digimaya</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="body-default">Template dan worksheet siap pakai</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="body-default">Belajar di waktu kamu, tanpa jadwal kaku</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col items-center gap-4">
                    <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20daftar%20Google%20Ads%20Academy"
                       target="_blank" rel="noopener"
                       class="btn-primary">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Daftar via WhatsApp
                    </a>

                    <p class="micro-text max-w-md mx-auto text-center">
                        Akses akun langsung diberikan setelah konfirmasi pembayaran
                    </p>
                </div>

            </div>
        </div>

    </div>
</section>


{{-- ============== SECTION 9 — FAQ (matches homepage FAQ pattern) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

            {{-- LEFT: Sticky heading + CTA --}}
            <div class="lg:sticky lg:top-28">
                <p class="eyebrow">
                    FAQ
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Hal yang Sering Ditanyain Calon Student
                </h2>

                <p class="body-text mb-10 max-w-md">
                    Berikut jawaban untuk pertanyaan yang paling sering muncul seputar program Google Ads Academy by Digimaya.
                </p>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads%20Academy"
                   target="_blank" rel="noopener"
                   class="btn-primary">
                    Tanya Tim Kami
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            {{-- RIGHT: Accordion --}}
            @php
                $faqList = [
                    ['q' => 'Apa bedanya Academy ini dengan tutorial gratis di YouTube?', 'a' => 'Materi YouTube biasanya fragmented dan beda-beda pendapat. Di Academy ini, kamu dapet sistem yang utuh dan berurutan, mulai dari setup sampai optimasi, semuanya dari sudut pandang praktisi yang setiap hari kelola campaign klien.'],
                    ['q' => 'Apakah cocok buat yang belum pernah pakai Google Ads sama sekali?', 'a' => 'Iya, sangat cocok. Modul awal kita mulai dari fundamental, jadi kamu nggak perlu punya pengalaman sebelumnya. Yang penting kamu siap meluangkan waktu buat ikutin step by step.'],
                    ['q' => 'Berapa lama waktu yang dibutuhin buat menyelesaikan semua modul?', 'a' => 'Karena formatnya self-paced, kecepatan tergantung kamu. Rata-rata student nyelesaikan dalam 3 sampai 6 minggu kalau konsisten 2 sampai 3 jam per minggu.'],
                    ['q' => 'Apakah ada sesi live atau mentoring 1-on-1?', 'a' => 'Academy ini fokus ke self-paced video learning. Kalau kamu butuh mentoring personal, Digimaya punya program Mentoring 1-on-1 yang terpisah. Bisa kamu tanyakan ke tim kami via WhatsApp.'],
                    ['q' => 'Akses materinya berlaku selamanya atau ada masa berlaku?', 'a' => 'Lifetime access. Sekali bayar, kamu bisa akses kapan aja tanpa batas waktu. Termasuk update materi ke depannya kalau ada perubahan signifikan dari Google Ads.'],
                    ['q' => 'Apakah ada garansi uang kembali?', 'a' => 'Karena materi diberikan secara digital dan bisa langsung diakses, kami nggak menyediakan garansi uang kembali. Tapi kamu bisa cek detail kurikulum dan tanya-tanya dulu sebelum mutusin daftar.'],
                    ['q' => 'Gimana cara pendaftarannya?', 'a' => 'Klik tombol Daftar via WhatsApp di section pricing, kamu akan diarahkan ke WhatsApp tim Digimaya buat proses pendaftaran dan pembayaran. Akses akun langsung dikirim ke email setelah konfirmasi.'],
                ];
            @endphp

            <div x-data="{ open: null }" class="space-y-3">
                @foreach($faqList as $idx => $faq)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden transition hover:border-gray-300">
                        <button type="button"
                                @click="open === {{ $idx }} ? open = null : open = {{ $idx }}"
                                :aria-expanded="open === {{ $idx }} ? 'true' : 'false'"
                                class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left">
                            <span class="text-base sm:text-lg font-semibold text-gray-900 leading-snug">
                                {{ $faq['q'] }}
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
                            <div class="px-5 sm:px-6 pb-5 pt-1 text-sm sm:text-base text-gray-600 leading-relaxed">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION CTA — Closing (matches homepage CTA pattern with blob) ============== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-gray-50 to-white border-t border-gray-100">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/3"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center">
            <p class="eyebrow">
                Saatnya Berhenti Tebak-Tebakan
            </p>

            <h2 class="heading-section mb-6 leading-[1.2]">
                Jalanin Google Ads Pakai Sistem yang Terbukti
            </h2>

            <p class="body-text mb-10 max-w-xl mx-auto">
                Berhenti trial and error pakai budget sendiri. Belajar langsung dari tim yang sudah membantu lebih dari 500 bisnis di Indonesia.
            </p>

            <div class="flex flex-col items-center gap-4">
                <a href="#pricing" class="btn-primary">
                    Lihat Detail Program
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <p class="micro-text max-w-md mx-auto">
                    Akses lifetime. Materi update berkala. Sertifikat resmi.
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
                    Materi dari Praktisi Aktif
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Support via WhatsApp
                </div>
            </div>
        </div>

    </div>
</section>

@endsection