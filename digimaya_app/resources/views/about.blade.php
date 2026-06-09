@extends('layouts.public')

@section('meta_title', 'Tentang Digimaya | Google Ads Agency Indonesia')
@section('meta_description', 'Digimaya adalah agency Google Ads yang fokus membantu bisnis di Indonesia tumbuh dengan strategi transparan, data jujur, dan pendekatan yang berpihak pada klien.')

@section('content')

{{-- ============== SECTION 1 — HERO (gradient mesh + dual CTA + stats cards) ============== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-brand-50/40 to-white">

    {{-- Decorative gradient mesh blobs --}}
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-100/40 rounded-full blur-3xl -translate-y-1/3 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/60 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
    </div>

    {{-- Decorative geometric shape (top right) --}}
    <svg aria-hidden="true" class="absolute top-20 right-10 w-32 h-32 text-brand/10 hidden lg:block" fill="currentColor" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="2"/>
        <circle cx="20" cy="20" r="2"/>
        <circle cx="80" cy="20" r="2"/>
        <circle cx="20" cy="80" r="2"/>
        <circle cx="80" cy="80" r="2"/>
        <circle cx="50" cy="20" r="2"/>
        <circle cx="50" cy="80" r="2"/>
        <circle cx="20" cy="50" r="2"/>
        <circle cx="80" cy="50" r="2"/>
    </svg>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-12 lg:pb-16">
        <div class="max-w-4xl">

            {{-- Eyebrow --}}
            <p class="eyebrow eyebrow-pill mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
                Tentang Digimaya
            </p>

            {{-- Headline (shorter, accent gradient) --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 !leading-snug mb-6 tracking-tight">
                Bukan agency Google Ads.
                <span class="block bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                    Kami partner pertumbuhan.
                </span>
            </h1>

            {{-- Subhead (trim to punchy 1-line) --}}
            <p class="body-lead mb-10 max-w-2xl">
                Transparansi penuh. Data jujur. Setiap rupiah klien adalah tanggung jawab kami.
            </p>

            {{-- Dual CTA --}}
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Primary: Konsultasi Gratis --}}
                <a href="{{ route('public.contact.show') }}"
                   class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                {{-- Secondary: WhatsApp --}}
                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads"
                   target="_blank"
                   rel="noopener"
                   class="btn-secondary">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    WhatsApp
                </a>
            </div>
        </div>

        {{-- Stats cards with border --}}
        <div class="mt-16 lg:mt-20">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">

                <div class="bg-white border border-gray-200 rounded-2xl p-5 lg:p-6 hover:border-brand/30 hover:shadow-sm transition">
                    <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 tracking-tight">3+</div>
                    <div class="text-sm text-gray-500 leading-snug">Tahun melayani bisnis Indonesia</div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 lg:p-6 hover:border-brand/30 hover:shadow-sm transition">
                    <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 tracking-tight">16+</div>
                    <div class="text-sm text-gray-500 leading-snug">Klien aktif di berbagai industri</div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 lg:p-6 hover:border-brand/30 hover:shadow-sm transition">
                    <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 tracking-tight">Rp 5M+</div>
                    <div class="text-sm text-gray-500 leading-snug">Adspend ke-manage per bulan</div>
                </div>

                <div class="bg-white border border-brand/20 rounded-2xl p-5 lg:p-6 hover:border-brand/40 hover:shadow-sm transition">
                    <div class="text-3xl lg:text-4xl font-bold text-brand mb-2 tracking-tight">Premier</div>
                    <div class="text-sm text-gray-500 leading-snug">Google Premier Partner</div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ============== SECTION 2 — STORY + FOUNDER (asymmetric) ============== --}}
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

            {{-- LEFT: Founder photo (40%) --}}
            <div class="lg:col-span-5">
                <div class="aspect-[4/5] bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl overflow-hidden flex items-center justify-center sticky top-24">
                    <div class="text-center px-6">
                        <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Foto Renra Sedoya</p>
                        <p class="text-xs text-gray-500 mt-1">Founder, Digimaya</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Story narrative (60%) --}}
            <div class="lg:col-span-7">
                <p class="eyebrow eyebrow-pill mb-5">
                    Awal Mulanya
                </p>

                <h2 class="heading-page-lg mb-8">
                    Dari frustrasi seorang praktisi.
                </h2>

                <div class="space-y-5 text-base lg:text-lg text-gray-700 leading-relaxed">
                    <p>
                        Digimaya tidak lahir dari ruang rapat ber-AC.
                    </p>
                    <p>
                        Awalnya, <strong class="text-gray-900">Renra Sedoya</strong> &mdash; founder Digimaya &mdash; adalah praktisi Google Ads yang melayani klien-klien lokal di Indonesia.
                    </p>
                    <p>
                        Yang dia lihat berulang kali: banyak bisnis yang mau scale, tapi terjebak dalam siklus "iklan jalan, hasil ga jelas." Bukan karena Google Ads tidak efektif. Tapi karena mereka tidak punya partner yang benar &mdash; yang mau pelan-pelan menjelaskan, transparan soal data, dan benar-benar peduli dengan ROI klien, bukan komisi mereka sendiri.
                    </p>
                </div>

                {{-- Pull-quote --}}
                <blockquote class="my-8 lg:my-10 border-l-4 border-brand pl-6 py-2">
                    <p class="body-pull-quote">
                        "Setiap rupiah iklan klien adalah tanggung jawab kami. Kalau bisnis mereka gak tumbuh, kami yang harus berbenah."
                    </p>
                    <footer class="mt-3 text-sm text-gray-500">
                        — Renra Sedoya, Founder
                    </footer>
                </blockquote>

                <div class="space-y-5 text-base lg:text-lg text-gray-700 leading-relaxed">
                    <p>
                        Dari frustrasi inilah, di tahun <strong class="text-gray-900">2022</strong>, Digimaya berdiri.
                    </p>
                    <p>
                        Yang paling banyak butuh bantuan strategis adalah <strong class="text-gray-900">UMKM Indonesia</strong>. Mereka punya produk bagus, tim yang gigih, dan budget iklan yang tidak banyak &mdash; tapi ingin makin efisien tiap bulannya.
                    </p>
                    <p>
                        Itulah yang Digimaya pilih untuk fokus. Bukan agency Google Ads "untuk semua" &mdash; tapi untuk bisnis-bisnis yang serius dengan pertumbuhan, dan butuh partner yang ngerti realita pasar Indonesia.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============== SECTION 3 — PENDEKATAN KERJA ============== --}}
<section class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">

        <div class="max-w-2xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow eyebrow-pill mb-5">
                Pendekatan Kerja
            </p>
            <h2 class="heading-page-lg mb-4">
                Tertulis. Terukur. Dijalankan dengan disiplin.
            </h2>
            <p class="body-text">
                Setiap klien yang kami layani mendapatkan pendekatan yang sama &mdash; dari hari pertama sampai berhentinya kerjasama.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 max-w-4xl mx-auto">

            @php
                $approachLeft = [
                    'Audit gratis sebelum mulai kerjasama',
                    'Strategi disesuaikan per bisnis, bukan template',
                    'Ekspektasi & metric jelas dari awal',
                    'Account manager tetap untuk setiap klien',
                ];
                $approachRight = [
                    'Akun Google Ads tetap milik klien — bukan kami',
                    'Akses real-time ke laporan kapan saja',
                    'Tidak ada kontrak panjang yang mengikat',
                    'Pricing transparan, tidak ada biaya tersembunyi',
                ];
            @endphp

            <div class="space-y-5">
                @foreach ($approachLeft as $item)
                    <div class="flex items-start gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <p class="body-list-item">{{ $item }}</p>
                    </div>
                @endforeach
            </div>

            <div class="space-y-5">
                @foreach ($approachRight as $item)
                    <div class="flex items-start gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <p class="body-list-item">{{ $item }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

{{-- ============== SECTION 4 — OPTIMASI MINGGUAN ============== --}}
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">

        <div class="max-w-2xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow eyebrow-pill mb-5">
                Disiplin Eksekusi
            </p>
            <h2 class="heading-page-lg mb-4">
                Setiap kampanye direview tiap minggu.
            </h2>
            <p class="body-text">
                Bukan untuk laporan formalitas &mdash; tapi untuk menemukan apa yang bisa diperbaiki minggu berikutnya.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 max-w-5xl mx-auto">

            {{-- Account Manager --}}
            <div class="bg-gray-50 rounded-2xl p-8 lg:p-10 border border-gray-100">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b border-gray-200">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="heading-empty-state">Yang Dicek Account Manager</h3>
                </div>
                @php
                    $amChecklist = [
                        'Performa kampanye minggu lalu',
                        'Data konversi, CPL, dan ROAS',
                        'Untuk e-commerce: AOV, repeat customer rate',
                        'Lead quality: qualified, sales-ready, atau bukan',
                        'Quality control sebelum semua eksekusi optimasi',
                    ];
                @endphp
                <div class="space-y-4">
                    @foreach ($amChecklist as $item)
                        <div class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white border border-brand/30 flex items-center justify-center mt-0.5">
                                <svg class="w-3 h-3 text-brand" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <p class="body-card">{{ $item }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Specialist --}}
            <div class="bg-gray-50 rounded-2xl p-8 lg:p-10 border border-gray-100">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b border-gray-200">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="heading-empty-state">Yang Dicek Specialist</h3>
                </div>
                @php
                    $specChecklist = [
                        'Quality control: budget pacing, status iklan',
                        'Strategi bidding: review & adjust',
                        'Search terms audit: positif & negatif keyword',
                        'Optimasi Display, Video, Performance Max',
                        'Cek rekomendasi sistem & manual approve/reject',
                    ];
                @endphp
                <div class="space-y-4">
                    @foreach ($specChecklist as $item)
                        <div class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white border border-brand/30 flex items-center justify-center mt-0.5">
                                <svg class="w-3 h-3 text-brand" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <p class="body-card">{{ $item }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============== SECTION 5 — STATS (DARK NAVY) ============== --}}
<section class="bg-slate-950 relative overflow-hidden">

    {{-- Decorative blob top right --}}
    <div aria-hidden="true" class="absolute top-0 right-0 w-[600px] h-[600px] bg-brand/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">

        <div class="max-w-2xl mx-auto text-center mb-12 lg:mb-16">
            <p class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold text-brand bg-brand/10 rounded-full mb-5 tracking-wide uppercase">
                <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
                Berdiri di Atas Pengalaman
            </p>
            <h2 class="text-3xl lg:text-4xl font-bold text-white leading-tight mb-4">
                Bukan klaim &mdash; ini realita kerja kami.
            </h2>
            <p class="text-base lg:text-lg text-gray-400 leading-relaxed">
                Setiap angka di bawah ini bisa kami tunjukan datanya kapan saja.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 max-w-5xl mx-auto">

            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-sm hover:bg-white/[0.07] transition">
                <div class="text-4xl lg:text-5xl font-bold text-brand mb-3">3+</div>
                <h4 class="text-base font-semibold text-white mb-2">Tahun Pengalaman</h4>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Melayani bisnis di Indonesia dengan strategi terukur.
                </p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-sm hover:bg-white/[0.07] transition">
                <div class="text-4xl lg:text-5xl font-bold text-brand mb-3">16+</div>
                <h4 class="text-base font-semibold text-white mb-2">Klien Aktif</h4>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Dari berbagai industri di Indonesia mempercayakan pertumbuhannya.
                </p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-sm hover:bg-white/[0.07] transition">
                <div class="text-4xl lg:text-5xl font-bold text-brand mb-3">Rp&nbsp;5M+</div>
                <h4 class="text-base font-semibold text-white mb-2">Adspend Managed</h4>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Total adspend ke-manage per bulan untuk seluruh klien aktif.
                </p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-sm hover:bg-white/[0.07] transition">
                <div class="flex items-center justify-start mb-3">
                    <svg class="w-12 h-12 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h4 class="text-base font-semibold text-white mb-2">Premier Partner</h4>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Salah satu agency Google Ads bersertifikat di Indonesia.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- ============== SECTION 6 — CTA (gradient + asymmetric) ============== --}}
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">

        <div class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand to-brand-500 rounded-3xl p-10 sm:p-14 lg:p-16">

            {{-- Decorative shapes --}}
            <div aria-hidden="true" class="absolute top-0 right-0 w-80 h-80 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
            <div aria-hidden="true" class="absolute bottom-0 left-0 w-60 h-60 bg-white/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>

            <div class="relative grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                <div class="lg:col-span-7">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight mb-4 tracking-tight">
                        Siap mulai pertumbuhan<br class="hidden sm:block">
                        bisnis kamu?
                    </h2>
                    <p class="text-base lg:text-lg text-white/90 leading-relaxed max-w-xl">
                        Konsultasi gratis 30 menit, tanpa komitmen. Kami review akun kamu dan kasih tau potensinya.
                    </p>
                </div>

                <div class="lg:col-span-5 lg:text-right">
                    <a href="{{ route('public.contact.show') }}"
                       class="btn-secondary">
                        Konsultasi Gratis
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection