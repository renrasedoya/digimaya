@extends('layouts.public')

@section('meta_title', 'Google Ads Consulting: Akses Langsung ke Pakar Premier Partner | Digimaya')
@section('meta_description', 'Consulting Google Ads dari Renra Sedoya dan tim senior Digimaya. Strategi langsung dari praktisi, tanpa account manager junior. Cocok untuk tim internal dan in-house marketer.')

{{-- SEO Schema JSON-LD for this service page --}}
@push('head_schema')
    <x-seo.schema-service
        name="Google Ads Consulting"
        description="Consulting Google Ads dari Renra Sedoya dan tim senior Digimaya. Strategi langsung dari praktisi, tanpa account manager junior. Cocok untuk tim internal dan in-house marketer."
        serviceType="Digital Advertising Consulting"
    />
    <x-seo.schema-faq :faqs="$faqs" />
@endpush

@section('content')


{{-- ============== SECTION 1 — HERO (premium positioning, akses langsung ke pakar) ============== --}}
<section class="relative overflow-x-clip bg-gradient-to-b from-brand-50/30 to-white">

    <div aria-hidden="true" class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-brand-100/30 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-50/50 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-28 pb-20 lg:pb-28">

        <div class="max-w-3xl mx-auto text-center">

            <p class="eyebrow eyebrow-pill">
                <span class="w-1.5 h-1.5 rounded-full bg-brand inline-block"></span>
                Google Ads Consulting Service
            </p>

            <h1 class="heading-hero mb-6">
                Akses Langsung ke Pakar Google Ads
                <span class="block bg-gradient-to-r from-brand-700 to-brand bg-clip-text text-transparent">
                    untuk Tim Kamu.
                </span>
            </h1>

            <p class="body-lead mb-10 max-w-2xl mx-auto">
                Bukan account manager junior, bukan template generic. Strategi Google Ads langsung dari Renra Sedoya dan tim senior Digimaya, disesuaikan dengan situasi bisnis dan kapasitas tim kamu.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads%20Consulting"
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
                    Senior-Led
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tanpa Conflict of Interest
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Bahasa Indonesia
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 2 — COMPARISON MATRIX (UNIQUE - clear positioning vs Management vs Agency) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Bedanya dari Service Lain
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Consulting Bukan Management, Bukan Audit Sekali Jalan
            </h2>
            <p class="body-text">
                Banyak yang masih bingung bedanya. Berikut tabel posisi yang jelas supaya kamu pilih layanan yang paling pas untuk situasi bisnis kamu sekarang.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6">

            {{-- Management --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-gray-300 hover:shadow-md">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Management</h3>
                </div>
                <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Done-for-You</p>
                <p class="body-default mb-5">
                    Tim Digimaya yang ngurusin seluruh akun Google Ads kamu. Mulai dari strategy, setup, optimasi harian, sampai reporting bulanan.
                </p>
                <ul class="space-y-2 mb-5 pb-5 border-b border-gray-100">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Eksekusi hands-on oleh tim Digimaya</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Akun Manager dedicated</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Komitmen bulanan ongoing</p>
                    </li>
                </ul>
                <p class="text-xs text-gray-500">Cocok kalau kamu mau serahkan semua dan fokus ke bisnis</p>
            </div>

            {{-- Consulting (FEATURED) --}}
            <div class="bg-white border-2 border-brand rounded-2xl p-6 sm:p-7 shadow-lg relative">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-brand text-white">
                        Halaman Ini
                    </span>
                </div>
                <div class="flex items-center gap-3 mb-5 mt-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Consulting</h3>
                </div>
                <p class="text-xs sm:text-sm font-semibold text-brand uppercase tracking-wide mb-3">Strategic Advisory</p>
                <p class="body-default mb-5">
                    Strategi dan bimbingan dari pakar untuk tim kamu yang menjalankan Google Ads. Kamu eksekusi, kami yang advise.
                </p>
                <ul class="space-y-2 mb-5 pb-5 border-b border-gray-100">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-700">Sesi strategis terjadwal + async support</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-700">Langsung dengan Renra Sedoya & senior team</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-700">Ongoing relationship, kamu yang eksekusi</p>
                    </li>
                </ul>
                <p class="text-xs text-brand font-medium">Cocok kalau punya tim sendiri dan butuh strategic guidance</p>
            </div>

            {{-- Audit --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-gray-300 hover:shadow-md">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Audit</h3>
                </div>
                <p class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">One-Time Diagnostic</p>
                <p class="body-default mb-5">
                    Diagnosis menyeluruh akun Google Ads kamu plus action plan konkret. Engagement satu kali, output report lengkap.
                </p>
                <ul class="space-y-2 mb-5 pb-5 border-b border-gray-100">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Report tertulis + debrief call</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Engagement satu kali (project-based)</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Implementasi terserah kamu</p>
                    </li>
                </ul>
                <p class="text-xs text-gray-500">Cocok kalau cuma mau tau apa yang salah dengan akun sekarang</p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 3 — WHEN YOU NEED CONSULTING (5 scenarios) ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Kapan Kamu Butuh Consulting
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                5 Situasi di Mana Consulting Lebih Tepat dari Hire Agency
            </h2>
            <p class="body-text">
                Bukan setiap masalah Google Ads butuh dikelola dari awal. Kadang yang dibutuhkan cuma arahan strategis dari pakar yang berpengalaman.
            </p>
        </div>

        <div class="space-y-4">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <span class="text-brand font-bold text-lg">1</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="heading-card-md mb-2">
                            Punya Tim Internal yang Butuh Senior Advisor
                        </h3>
                        <p class="body-default">
                            Tim marketing kamu udah handle Google Ads sendiri, tapi kadang ada keputusan strategis yang ragu-ragu. Consulting jadi backup pakar yang bisa dipanggil untuk second opinion.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <span class="text-brand font-bold text-lg">2</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="heading-card-md mb-2">
                            Baru Bawa Google Ads In-House dari Agency
                        </h3>
                        <p class="body-default">
                            Sebelumnya pakai agency, sekarang mau handle sendiri untuk kontrol lebih. Tim baru butuh ramp-up cepet dengan bimbingan pakar supaya nggak balik ke titik nol.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <span class="text-brand font-bold text-lg">3</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="heading-card-md mb-2">
                            Campaign Stagnan, Butuh Fresh Perspective
                        </h3>
                        <p class="body-default">
                            Akun udah jalan tahunan tapi performance plateau. Tim kamu mungkin udah terlalu dekat sama datanya. Pakar eksternal bisa kasih perspektif yang nggak terjebak di asumsi internal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <span class="text-brand font-bold text-lg">4</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="heading-card-md mb-2">
                            Lagi di Persimpangan Strategis Besar
                        </h3>
                        <p class="body-default">
                            Mau scale 2-3x lipat budget, masuk pasar baru, atau launching produk besar. Keputusan kayak gini butuh strategic input dari pakar sebelum eksekusi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <span class="text-brand font-bold text-lg">5</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="heading-card-md mb-2">
                            Frustasi dengan Agency, Belum Siap Pindah Lagi
                        </h3>
                        <p class="body-default">
                            Pernah pakai agency, hasilnya nggak sesuai harapan. Mau ambil kendali tapi butuh bimbingan dari pihak independen yang nggak punya konflik kepentingan jualan service.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 4 — WHAT YOU'LL DISCUSS (9 topics) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Topik yang Bisa Kamu Diskusikan
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                9 Area Strategis yang Sering Jadi Bahan Diskusi
            </h2>
            <p class="body-text">
                Setiap sesi consulting fleksibel sesuai prioritas kamu. Berikut topik yang paling sering dibahas dengan klien Digimaya.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Business Goals & Economics
                </h3>
                <p class="body-default">
                    Bedah LTV, lead-to-customer rate, dan target CPA/ROAS realistis berdasarkan unit economics bisnis kamu.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    What to Advertise
                </h3>
                <p class="body-default">
                    Diskusi produk/layanan mana yang paling profitable untuk di-advertise, mana yang sebaiknya organic saja.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Conversion Tracking
                </h3>
                <p class="body-default">
                    Strategi implementasi tracking yang akurat: GA4, GTM, call tracking, atau CRM integration sesuai kebutuhan.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Bidding Strategy
                </h3>
                <p class="body-default">
                    Manual vs Smart Bidding, kapan pakai tCPA vs tROAS vs Maximize Conversions sesuai stage akun kamu.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Campaign Type Selection
                </h3>
                <p class="body-default">
                    Search vs Performance Max vs Display vs Demand Gen — pilihan campaign type sesuai dengan situasi bisnis kamu.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Keyword & Negative Strategy
                </h3>
                <p class="body-default">
                    Strategi keyword dari fundamental: match type, intent matching, negative keyword maintenance, dan Quality Score.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Ad Copy & Creative Direction
                </h3>
                <p class="body-default">
                    Strategi ad copy yang resonate, asset coverage di RSA, dan diferensiasi positioning vs kompetitor.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Landing Page Optimization
                </h3>
                <p class="body-default">
                    Message match antara ad dan landing page, prinsip CRO, dan mobile optimization yang dampak ke konversi.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <h3 class="heading-card-sm mb-2">
                    Growth & Scaling
                </h3>
                <p class="body-default">
                    Strategi scaling budget, ekspansi audience, dan diversifikasi campaign tanpa kehilangan profitability.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 5 — HOW CONSULTING WORKS (UNIQUE format details) ============== --}}
<section class="bg-gray-50/40 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cara Kerja Consulting
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Format yang Fleksibel, Bukan Template Kaku
            </h2>
            <p class="body-text">
                Setiap engagement consulting kami sesuaikan dengan kebutuhan dan ritme tim kamu. Berikut format umum yang sering dipakai.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 lg:p-8 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Sesi Strategis Terjadwal
                    </h3>
                </div>
                <p class="body-default mb-4">
                    Sesi video call rutin untuk bahas progress, prioritas baru, dan diskusi keputusan strategis. Frekuensi disesuaikan sama ritme bisnis dan kompleksitas akun kamu.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Video call live atau via WhatsApp call</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Agenda flexible sesuai prioritas kamu</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Rekaman sesi untuk reference tim</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 lg:p-8 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Async Support
                    </h3>
                </div>
                <p class="body-default mb-4">
                    Di antara sesi, tim kamu bisa tanya kapan aja via WhatsApp atau email. Cocok untuk pertanyaan cepat, review draft, atau second opinion sebelum eksekusi.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Response time terjamin di hari kerja</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Review screenshot/data via WhatsApp</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Approval cepat untuk decision penting</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 lg:p-8 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Documentation & Action Plan
                    </h3>
                </div>
                <p class="body-default mb-4">
                    Setiap rekomendasi dan keputusan terdokumentasi rapi. Tim kamu nggak perlu nebak-nebak "kemarin disepakatin apa" karena semuanya tertulis dan accessible.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Action plan dengan prioritas dan deadline</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Catatan sesi yang shareable ke tim internal</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Tracking progress dari satu sesi ke berikutnya</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 lg:p-8 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Read-Only Account Access
                    </h3>
                </div>
                <p class="body-default mb-4">
                    Akses read-only ke akun Google Ads dan GA4 kamu. Kami bisa lihat data realtime untuk kasih rekomendasi yang konkret, tapi nggak akan ubah apa-apa tanpa approval kamu.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Akun tetap punyamu 100%</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Bisa di-revoke kapan saja</p>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="flex-shrink-0 w-4 h-4 text-brand mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm text-gray-600">Data yang kami lihat selalu fresh</p>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 6 — DIRECT ACCESS PROMISE (UNIQUE differentiator dengan Renra) ============== --}}
<section class="bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            {{-- LEFT: Content --}}
            <div>
                <p class="eyebrow">
                    Janji Akses Langsung
                </p>

                <h2 class="heading-section mb-6 leading-[1.2]">
                    Yang Kamu Hire, Yang Kamu Dapet
                </h2>

                <p class="body-text mb-6">
                    Di banyak agency, kamu pitch sama senior partner di awal, tapi yang handle akun kamu setiap hari adalah account manager junior. Di consulting Digimaya, yang ngobrol sama kamu adalah orang yang sama dengan yang kasih advice strategis.
                </p>

                <p class="body-text mb-8">
                    Renra Sedoya, founder Digimaya, terlibat langsung di setiap consulting engagement. Dibantu tim senior specialist yang juga punya track record kelola campaign real, bukan cuma pegang sertifikat.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Senior-led, bukan account manager junior</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Tanpa conflict of interest — fee fixed, bukan persentase ad spend</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Pakar yang aktif kelola akun klien, bukan cuma teori</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 text-brand flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="body-default">Konteks lokal Indonesia, bahasa yang ngerti</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Consultant card --}}
            <div class="relative">
                <div class="bg-gradient-to-br from-brand-50 to-brand-100/40 rounded-3xl p-8 lg:p-10 border border-brand/20">

                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex-shrink-0 w-16 h-16 rounded-full bg-brand text-white flex items-center justify-center font-bold text-2xl">
                            RS
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-lg">Renra Sedoya</p>
                            <p class="text-sm text-gray-600">Founder Digimaya</p>
                        </div>
                    </div>

                    <p class="body-quote mb-6">
                        Saya percaya consulting yang efektif itu ngajarin tim kamu mancing, bukan ngasih ikan. Tujuannya bukan biar kamu terus bergantung, tapi biar tim kamu makin capable seiring waktu.
                    </p>

                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-brand/10">
                        <div>
                            <p class="text-2xl font-bold text-brand mb-1">10+</p>
                            <p class="text-xs text-gray-600">Tahun Praktek</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-brand mb-1">500+</p>
                            <p class="text-xs text-gray-600">Klien</p>
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


{{-- ============== SECTION 7 — WHO THIS IS FOR (4 segmen, beda dengan management & audit) ============== --}}
<section class="bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Cocok Buat Siapa
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Profil Klien yang Paling Cocok dengan Consulting
            </h2>
            <p class="body-text">
                Consulting bukan untuk semua orang. Berikut profil bisnis dan tim yang biasanya paling dapat manfaat dari engagement consulting Digimaya.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Marketing Manager / Lead
                    </h3>
                </div>
                <p class="body-default">
                    Kamu udah handle Google Ads sendiri atau lewat tim, tapi kadang ada keputusan strategis yang butuh second opinion dari pakar. Consulting jadi sounding board yang reliable.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Tim Internal yang Baru Bawa In-House
                    </h3>
                </div>
                <p class="body-default">
                    Sebelumnya pakai agency, sekarang mau handle sendiri. Tim baru butuh ramp-up cepat dengan bimbingan supaya curve belajarnya nggak bikin akun mundur ke titik nol.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Bisnis yang Lagi Scaling Strategis
                    </h3>
                </div>
                <p class="body-default">
                    Mau scale 2-3x lipat budget, ekspansi ke market baru, atau launching produk besar. Sebelum eksekusi, butuh strategic input dari pakar yang udah pernah handle situasi serupa.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-7 transition hover:border-brand/30 hover:shadow-lg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="heading-card-md">
                        Pemilik Bisnis Pasca-Agency
                    </h3>
                </div>
                <p class="body-default">
                    Pernah pakai agency, hasilnya nggak sesuai. Mau ambil kendali tapi butuh bimbingan independen yang nggak punya konflik kepentingan menjual service tambahan.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ============== SECTION 8 — COMPARISON TABLE (CMS, hide if empty) ============== --}}
@if ($comparisonRows->isNotEmpty())
<section class="bg-white border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="eyebrow">
                Apa Bedanya
            </p>
            <h2 class="heading-section mb-4 leading-[1.2]">
                Consulting Digimaya vs Consulting Lainnya
            </h2>
            <p class="body-text">
                Tidak semua consulting Google Ads sama. Ini perbedaan pendekatan yang kamu dapetin di Digimaya.
            </p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">

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
                Hasil Klien Setelah Engage Consulting
            </h2>
            <p class="text-base lg:text-lg text-gray-400 leading-relaxed">
                Contoh konkret bagaimana consulting Digimaya bantu tim klien transformasi pendekatan dan unlock potensi yang selama ini tertahan.
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


{{-- ============== SECTION 10 — TESTIMONIAL FEATURED (1 testimonial, hide if empty) ============== --}}
@if ($testimonial)
<section class="bg-gradient-to-b from-brand-50/30 to-white border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

        <div class="text-center mb-10 lg:mb-12">
            <p class="eyebrow">
                Testimoni Klien
            </p>
            <h2 class="heading-section leading-[1.2]">
                Apa Kata Klien Setelah Engage Consulting
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


{{-- ============== SECTION 11 — FAQ (sticky left + accordion right) ============== --}}
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
                    Berikut jawaban untuk pertanyaan yang paling sering muncul seputar service Google Ads Consulting Digimaya.
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
                Saatnya Tim Kamu Dapat Backup Pakar
            </h2>

            <p class="body-text mb-10 max-w-xl mx-auto">
                Konsultasi gratis 30 menit untuk diskusi situasi tim kamu dan apakah consulting Digimaya cocok untuk kondisimu sekarang.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('public.contact.show') }}" class="btn-primary">
                    Konsultasi Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>

                <a href="https://wa.me/6285213228692?text=Halo%20Digimaya%2C%20saya%20mau%20tanya-tanya%20soal%20Google%20Ads%20Consulting"
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
                    Founder-Led
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
                    Response &lt; 24 jam
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
