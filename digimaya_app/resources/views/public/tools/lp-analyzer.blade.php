@extends('layouts.public')

@section('meta_title', 'LP Analyzer — Generate Prompt Claude AI untuk Audit Landing Page | Digimaya')
@section('meta_description', 'Tool gratis untuk generate prompt Claude AI yang menganalisis landing page kompetitor dan mengaudit LP kamu. Dapatkan blueprint LP ideal berdasarkan benchmark kompetitor terbaik.')

@section('content')


{{-- ============== TOOL WIDGET ============== --}}
<section class="bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16"
         x-data="lpAnalyzer()"
         x-init="init()">

        {{-- Hero (no eyebrow) --}}
        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-5 tracking-tight leading-[1.1]">
                LP Analyzer
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                Isi form &rsaquo; Generate prompt &rsaquo; Paste ke Claude AI untuk audit landing page kompetitor dan blueprint LP ideal. Klik
                <button type="button"
                        @click="loadSample()"
                        class="text-brand hover:text-brand-700 font-semibold underline underline-offset-2 transition">load sample data</button>
                untuk lihat contoh.
            </p>
        </div>

        {{-- Form Card 1: DATA BISNIS --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Data Bisnis</h2>
            </div>
            <div class="p-5 sm:p-6 space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama bisnis <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               x-model="form.bizName"
                               @input="save()"
                               placeholder="e.g. Klinik Kecantikan Sekar"
                               class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Industri / niche <span class="text-red-500">*</span>
                        </label>
                        <select x-model="form.bizIndustry"
                                @change="save()"
                                class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                            <option value="">— Pilih industri —</option>
                            <optgroup label="Kesehatan & Medis">
                                <option>Klinik Umum / Puskesmas</option><option>Rumah Sakit</option>
                                <option>Klinik Kecantikan / Aesthetic</option><option>Klinik Gigi / Ortodonti</option>
                                <option>Apotek / Produk Kesehatan</option><option>Laboratorium Klinik</option>
                                <option>Fisioterapi / Rehabilitasi</option><option>Psikolog / Konselor</option>
                                <option>Bidan / Kandungan</option><option>Suplemen & Herbal</option>
                            </optgroup>
                            <optgroup label="Kecantikan & Perawatan">
                                <option>Salon & Hair Care</option><option>Spa & Wellness</option>
                                <option>Barbershop</option><option>Nail Art & Lash Extension</option>
                                <option>Produk Skincare / Kosmetik</option>
                            </optgroup>
                            <optgroup label="Properti & Konstruksi">
                                <option>Developer Properti (Rumah/Apartemen)</option><option>Agen Properti</option>
                                <option>Kontraktor / Renovasi</option><option>Interior Design</option>
                                <option>Arsitek</option><option>Material Bangunan</option>
                            </optgroup>
                            <optgroup label="Pendidikan & Pelatihan">
                                <option>Sekolah (TK/SD/SMP/SMA)</option><option>Universitas / Perguruan Tinggi</option>
                                <option>Bimbel / Les Privat</option><option>Kursus Bahasa</option>
                                <option>Lembaga Pelatihan / Kursus Skill</option><option>E-learning / Online Course</option>
                                <option>Pesantren / Madrasah</option>
                            </optgroup>
                            <optgroup label="Keuangan & Asuransi">
                                <option>Asuransi Jiwa / Kesehatan</option><option>Asuransi Kendaraan / Properti</option>
                                <option>Investasi / Reksa Dana</option><option>Pinjaman / Kredit (Fintech)</option>
                                <option>Konsultan Keuangan / Perencana</option><option>Multifinance / Leasing</option>
                            </optgroup>
                            <optgroup label="Hukum & Konsultansi">
                                <option>Kantor Pengacara / Hukum</option><option>Notaris / PPAT</option>
                                <option>Konsultan Pajak / Akuntansi</option><option>Konsultan Bisnis / Manajemen</option>
                                <option>Konsultan HR / Rekrutmen</option>
                            </optgroup>
                            <optgroup label="Otomotif">
                                <option>Dealer Mobil Baru</option><option>Dealer Motor Baru</option>
                                <option>Mobil / Motor Bekas</option><option>Bengkel / Service Kendaraan</option>
                                <option>Accessories & Variasi</option><option>Rental Kendaraan</option>
                            </optgroup>
                            <optgroup label="E-commerce & Retail">
                                <option>Fashion & Apparel</option><option>Elektronik & Gadget</option>
                                <option>Perabot & Furnitur</option><option>Peralatan Dapur / Rumah Tangga</option>
                                <option>Mainan & Perlengkapan Bayi</option><option>Olahraga & Outdoor</option>
                                <option>Buku & Alat Tulis</option><option>Pertanian / Perkebunan</option>
                            </optgroup>
                            <optgroup label="Makanan & Minuman">
                                <option>Restoran / Rumah Makan</option><option>Cafe / Kedai Kopi</option>
                                <option>Catering & Katering</option><option>Produk F&B (FMCG)</option>
                                <option>Franchise Makanan</option>
                            </optgroup>
                            <optgroup label="Travel & Pariwisata">
                                <option>Agen Perjalanan / Tour Operator</option><option>Hotel / Penginapan</option>
                                <option>Villa / Homestay</option><option>Tiket Wisata / Atraksi</option>
                                <option>Umroh & Haji</option>
                            </optgroup>
                            <optgroup label="Digital & Teknologi">
                                <option>Software / SaaS / Aplikasi</option><option>Digital Agency / Jasa Marketing</option>
                                <option>Web Development / IT Services</option><option>Startup Teknologi</option>
                                <option>Marketplace / Platform</option>
                            </optgroup>
                            <optgroup label="Event & Hiburan">
                                <option>Wedding Organizer / EO</option><option>Fotografi / Videografi</option>
                                <option>Studio Foto / Video</option><option>Entertainment / Hiburan</option>
                                <option>Dekorasi & Florist</option>
                            </optgroup>
                            <optgroup label="Manufaktur & B2B">
                                <option>Manufaktur / Pabrik</option><option>Distributor / Supplier</option>
                                <option>Logistik / Ekspedisi / Freight</option><option>Percetakan / Packaging</option>
                                <option>Chemical / Industrial</option><option>Alat Kesehatan (Medis)</option>
                                <option>Alat Berat / Mesin Industri</option>
                            </optgroup>
                            <optgroup label="Lainnya">
                                <option>Laundry / Dry Clean</option><option>Jasa Pindahan / Angkut</option>
                                <option>Security / Satpam</option><option>Cleaning Service</option>
                                <option>Jasa Lainnya</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi singkat produk / jasa <span class="text-red-500">*</span>
                    </label>
                    <textarea x-model="form.bizProduct"
                              @input="save()"
                              rows="3"
                              placeholder="Jelaskan secara spesifik apa yang dijual, untuk siapa, dan apa benefit utamanya. Contoh: Layanan facial brightening untuk wanita 25-40 tahun yang ingin kulit cerah tanpa efek samping, berbasis bahan alami dengan teknologi LED."
                              class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition resize-y leading-relaxed"></textarea>
                    <p class="text-xs text-gray-500 mt-2">Semakin detail deskripsinya, semakin akurat AI menemukan kompetitor yang tepat.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            URL website kamu <span class="text-xs text-gray-400">opsional</span>
                        </label>
                        <input type="url"
                               x-model="form.bizWebsite"
                               @input="save()"
                               placeholder="https://namabisnis.com"
                               class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                        <p class="text-xs text-gray-500 mt-2">Kosongkan jika belum punya website.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Target audiens
                        </label>
                        <input type="text"
                               x-model="form.bizAudience"
                               @input="save()"
                               placeholder="e.g. Wanita 25-40 tahun, pemilik UMKM..."
                               class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Goal utama landing page
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="opt in goalOptions" :key="opt.value">
                            <button type="button"
                                    @click="form.goal = opt.value; save()"
                                    :class="form.goal === opt.value ? 'bg-brand text-white border-brand' : 'bg-white text-gray-700 border-gray-200 hover:border-brand hover:text-brand'"
                                    class="px-4 py-2 text-sm font-semibold border rounded-lg transition"
                                    x-text="opt.label"></button>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        {{-- Form Card 2: DATA KOMPETITOR (2-mode switcher) --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Data Kompetitor</h2>
            </div>
            <div class="p-5 sm:p-6">
                <p class="text-xs text-gray-500 mb-4">
                    Pilih cara mengidentifikasi kompetitor.
                </p>

                {{-- Mode switcher (2 button cards) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                    <button type="button"
                            @click="form.mode = 'manual'; save()"
                            :class="form.mode === 'manual' ? 'border-brand bg-brand-50/40 ring-2 ring-brand/20' : 'border-gray-200 bg-white hover:border-gray-300'"
                            class="text-left p-4 border rounded-xl transition">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <div :class="form.mode === 'manual' ? 'border-brand bg-brand' : 'border-gray-300 bg-white'"
                                     class="w-4 h-4 rounded-full border-2 flex items-center justify-center">
                                    <div x-show="form.mode === 'manual'" x-cloak class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 mb-1">Saya sudah tahu kompetitornya</p>
                                <p class="text-xs text-gray-500">Input URL kompetitor secara manual</p>
                            </div>
                        </div>
                    </button>

                    <button type="button"
                            @click="form.mode = 'ai'; save()"
                            :class="form.mode === 'ai' ? 'border-brand bg-brand-50/40 ring-2 ring-brand/20' : 'border-gray-200 bg-white hover:border-gray-300'"
                            class="text-left p-4 border rounded-xl transition">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <div :class="form.mode === 'ai' ? 'border-brand bg-brand' : 'border-gray-300 bg-white'"
                                     class="w-4 h-4 rounded-full border-2 flex items-center justify-center">
                                    <div x-show="form.mode === 'ai'" x-cloak class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 mb-1">Minta AI yang carikan</p>
                                <p class="text-xs text-gray-500">AI akan riset dan temukan kompetitor relevan</p>
                            </div>
                        </div>
                    </button>
                </div>

                {{-- Manual mode panel --}}
                <div x-show="form.mode === 'manual'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        URL landing page kompetitor <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2 mb-3">
                        <template x-for="(url, idx) in form.competitorUrls" :key="idx">
                            <div class="flex items-center gap-2">
                                <input type="url"
                                       x-model="form.competitorUrls[idx]"
                                       @input="save()"
                                       placeholder="https://kompetitor.com"
                                       class="flex-1 px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                                <button type="button"
                                        @click="removeUrl(idx)"
                                        x-show="form.competitorUrls.length > 1"
                                        class="flex-shrink-0 w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition"
                                        aria-label="Hapus URL">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button"
                            @click="addUrl()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-brand bg-white border border-dashed border-brand/40 hover:bg-brand-50 hover:border-brand rounded-lg transition w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah URL Kompetitor
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Masukkan minimal 2-3 URL untuk analisis komparasi yang baik.</p>
                </div>

                {{-- AI mode panel --}}
                <div x-show="form.mode === 'ai'" x-cloak>

                    <div class="p-4 bg-blue-50/60 border border-blue-200/60 rounded-lg text-xs text-blue-900 leading-relaxed mb-5">
                        AI akan mencari kompetitor utama berdasarkan deskripsi produk/jasa yang sudah diisi di atas, lalu langsung menganalisis landing page mereka.
                    </div>

                    <div class="space-y-5">
                        {{-- Location tag input --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Lokasi target pasar
                                <span class="text-xs text-gray-400 ml-1">ketik lalu Enter</span>
                            </label>
                            <div @click="$refs.loc_input.focus()"
                                 class="flex flex-wrap items-center gap-2 px-3 py-2 min-h-[3rem] bg-gray-50 border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-brand focus-within:border-brand focus-within:bg-white transition cursor-text">
                                <template x-for="(tag, idx) in form.locations" :key="idx">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand-50 text-brand text-xs font-medium rounded">
                                        <span x-text="tag"></span>
                                        <button type="button" @click.stop="form.locations.splice(idx, 1); save()" class="text-brand/60 hover:text-brand">&times;</button>
                                    </span>
                                </template>
                                <input type="text"
                                       x-ref="loc_input"
                                       x-model="locInput"
                                       @keydown.enter.prevent="addTag('locations', locInput); locInput=''"
                                       @keydown="if($event.key === ',') { $event.preventDefault(); addTag('locations', locInput); locInput=''; }"
                                       @keydown.backspace="if(!locInput && form.locations.length) { form.locations.pop(); save(); }"
                                       placeholder="e.g. Jakarta, Surabaya, seluruh Indonesia..."
                                       class="flex-1 min-w-[160px] bg-transparent text-sm focus:outline-none placeholder:text-gray-400">
                            </div>
                        </div>

                        {{-- Count selector --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah kompetitor yang dianalisis
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="n in [3, 5, 7]" :key="n">
                                    <button type="button"
                                            @click="form.compCount = n; save()"
                                            :class="form.compCount === n ? 'bg-brand text-white border-brand' : 'bg-white text-gray-700 border-gray-200 hover:border-brand hover:text-brand'"
                                            class="px-4 py-2 text-sm font-semibold border rounded-lg transition"
                                            x-text="`${n} kompetitor`"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Form Card 3: PREFERENSI ANALISIS --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-8">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Preferensi Analisis</h2>
            </div>
            <div class="p-5 sm:p-6 space-y-6">

                {{-- Tone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tone / gaya penjelasan
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="opt in toneOptions" :key="opt.value">
                            <button type="button"
                                    @click="form.tone = opt.value; save()"
                                    :class="form.tone === opt.value ? 'bg-brand text-white border-brand' : 'bg-white text-gray-700 border-gray-200 hover:border-brand hover:text-brand'"
                                    class="px-4 py-2 text-sm font-semibold border rounded-lg transition"
                                    x-text="opt.label"></button>
                        </template>
                    </div>
                </div>

                {{-- Language --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bahasa output analisis
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="opt in langOptions" :key="opt.value">
                            <button type="button"
                                    @click="form.lang = opt.value; save()"
                                    :class="form.lang === opt.value ? 'bg-brand text-white border-brand' : 'bg-white text-gray-700 border-gray-200 hover:border-brand hover:text-brand'"
                                    class="px-4 py-2 text-sm font-semibold border rounded-lg transition"
                                    x-text="opt.label"></button>
                        </template>
                    </div>
                </div>

                {{-- Aspek (read-only display, auto-checked) --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
                        Aspek yang Dianalisis
                    </p>
                    <div class="p-4 bg-blue-50/60 border border-blue-200/60 rounded-lg text-xs text-blue-900 leading-relaxed mb-4">
                        Semua aspek berikut akan selalu dianalisis secara otomatis. AI hanya memberikan data dari apa yang benar-benar bisa dianalisis.
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <template x-for="aspect in aspectList" :key="aspect">
                            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50/60 border border-gray-100 rounded-lg">
                                <svg class="flex-shrink-0 w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm text-gray-700" x-text="aspect"></span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-8">
            <button type="button"
                    @click="generatePrompt()"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 text-sm sm:text-base font-bold text-white bg-brand hover:bg-brand-700 rounded-lg transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                Generate Prompt
            </button>
            <button type="button"
                    @click="clearForm()"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:border-red-300 hover:text-red-600 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                </svg>
                Clear All
            </button>
        </div>

        {{-- Output section --}}
        <div x-show="outputVisible" x-cloak class="bg-gray-50/60 border border-gray-200 rounded-2xl overflow-hidden">

            <div class="flex items-center justify-between px-5 sm:px-6 py-4 bg-gray-100/80 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-bold text-gray-900">
                    Prompt Siap Pakai
                </h2>

                <div class="flex items-center gap-2">
                    <button type="button"
                            @click="copyPrompt()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:border-brand hover:text-brand rounded-lg transition shadow-sm">
                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <svg x-show="copied" x-cloak class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-show="!copied">Salin Prompt</span>
                        <span x-show="copied" x-cloak class="text-green-600">Disalin!</span>
                    </button>

                    <a href="https://claude.ai"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-brand hover:bg-brand-700 rounded-lg transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka Claude
                    </a>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <div x-ref="output_box"
                     class="bg-white border border-gray-200 rounded-xl px-4 py-4 text-sm text-gray-800 whitespace-pre-wrap leading-relaxed max-h-[600px] overflow-y-auto"
                     style="min-height: 240px;"></div>

                <div class="mt-4 p-4 bg-amber-50/60 border border-amber-200/60 rounded-lg text-xs text-amber-900 leading-relaxed">
                    <p class="font-semibold mb-1">Cara export ke PDF:</p>
                    <p>Salin prompt, buka Claude, paste dan tunggu hingga selesai, copy hasilnya, paste ke Google Docs, lalu pilih File &rsaquo; Download &rsaquo; PDF Document.</p>
                </div>
            </div>

        </div>

    </div>
</section>


{{-- ============== ALPINE LOGIC ============== --}}
@push('scripts')
<script>
function lpAnalyzer() {
    return {
        // ===== FORM STATE =====
        form: {
            bizName: '',
            bizIndustry: '',
            bizProduct: '',
            bizWebsite: '',
            bizAudience: '',
            goal: 'leads',
            mode: 'manual',
            competitorUrls: [''],
            locations: [],
            compCount: 3,
            tone: 'practical',
            lang: 'id',
        },

        // ===== INPUT BUFFER =====
        locInput: '',

        // ===== OPTIONS =====
        goalOptions: [
            { value: 'leads',    label: 'Dapat Leads / Inquiry' },
            { value: 'sales',    label: 'Direct Sales / Order' },
            { value: 'signup',   label: 'Sign Up / Registrasi' },
            { value: 'download', label: 'Download / Subscribe' },
        ],

        toneOptions: [
            { value: 'practical', label: 'Praktis & to the point' },
            { value: 'beginner',  label: 'Ramah pemula' },
            { value: 'technical', label: 'Teknikal & mendalam' },
        ],

        langOptions: [
            { value: 'id', label: 'Bahasa Indonesia' },
            { value: 'en', label: 'English' },
        ],

        aspectList: [
            'Struktur & Layout LP',
            'Headline & Copywriting',
            'Trust Signal & Bukti Sosial',
            'Kejelasan Offer & CTA',
            'Kejelasan Value Proposition',
            'Mobile Friendliness',
            'Friction Level',
        ],

        // ===== OUTPUT STATE =====
        outputVisible: false,
        copied: false,
        typewriterTimeout: null,
        _lastPrompt: '',

        // ===== STORAGE =====
        STORAGE_KEY: 'digimaya_lp_analyzer_v1',

        init() {
            const saved = this.loadFromStorage();
            if (saved) {
                this.form = { ...this.form, ...saved };
                // Ensure at least 1 URL slot
                if (!this.form.competitorUrls || this.form.competitorUrls.length === 0) {
                    this.form.competitorUrls = [''];
                }
            }
        },

        save() {
            try {
                localStorage.setItem(this.STORAGE_KEY, JSON.stringify(this.form));
            } catch (e) {
                console.warn('Save to localStorage failed:', e);
            }
        },

        loadFromStorage() {
            try {
                const raw = localStorage.getItem(this.STORAGE_KEY);
                return raw ? JSON.parse(raw) : null;
            } catch (e) {
                console.warn('Load from localStorage failed:', e);
                return null;
            }
        },

        clearForm() {
            if (!confirm('Hapus semua input form? Ini tidak bisa di-undo.')) return;
            localStorage.removeItem(this.STORAGE_KEY);
            this.form = {
                bizName: '',
                bizIndustry: '',
                bizProduct: '',
                bizWebsite: '',
                bizAudience: '',
                goal: 'leads',
                mode: 'manual',
                competitorUrls: [''],
                locations: [],
                compCount: 3,
                tone: 'practical',
                lang: 'id',
            };
            this.outputVisible = false;
            this._lastPrompt = '';
        },

        // ===== SAMPLE DATA =====
        loadSample() {
            this.form = {
                bizName: 'Klinik Kecantikan Sekar',
                bizIndustry: 'Klinik Kecantikan / Aesthetic',
                bizProduct: 'Layanan facial brightening dan treatment anti-aging untuk wanita 25-45 tahun yang ingin kulit cerah dan awet muda tanpa efek samping. Menggunakan bahan alami dengan teknologi LED, dilakukan oleh dokter berpengalaman 10+ tahun. Lokasi di Jakarta Selatan dan Tangerang Selatan.',
                bizWebsite: 'https://kliniksekar.com',
                bizAudience: 'Wanita profesional 25-45 tahun, income menengah atas, tinggal di Jakarta dan sekitarnya. Peduli pada perawatan kulit jangka panjang.',
                goal: 'leads',
                mode: 'manual',
                competitorUrls: [
                    'https://kompetitor-klinik-1.com',
                    'https://kompetitor-klinik-2.com',
                    'https://kompetitor-klinik-3.com',
                ],
                locations: [],
                compCount: 3,
                tone: 'practical',
                lang: 'id',
            };
            this.save();
        },

        // ===== HELPERS =====
        addTag(field, value) {
            if (!value || !value.trim()) return;
            this.form[field].push(value.trim());
            this.save();
        },

        addUrl() {
            this.form.competitorUrls.push('');
            this.save();
        },

        removeUrl(idx) {
            if (this.form.competitorUrls.length <= 1) return;
            this.form.competitorUrls.splice(idx, 1);
            this.save();
        },

        // ===== PROMPT GENERATION =====
        generatePrompt() {
            const f = this.form;

            // Validation
            if (!f.bizName.trim()) {
                alert('Nama bisnis wajib diisi.');
                return;
            }
            if (!f.bizIndustry) {
                alert('Industri / niche wajib dipilih.');
                return;
            }
            if (!f.bizProduct.trim()) {
                alert('Deskripsi produk / jasa wajib diisi.');
                return;
            }
            if (f.mode === 'manual') {
                const validUrls = f.competitorUrls.map(u => u.trim()).filter(Boolean);
                if (validUrls.length === 0) {
                    alert('Masukkan minimal 1 URL kompetitor.');
                    return;
                }
            }

            const bizName = f.bizName.trim();
            const bizIndustry = f.bizIndustry;
            const bizProduct = f.bizProduct.trim();
            const bizWebsite = f.bizWebsite.trim();
            const bizAudience = f.bizAudience.trim();
            const hasWebsite = bizWebsite.length > 0;

            const goalLabel = {
                leads:    'Mendapatkan leads / inquiry',
                sales:    'Direct sales / order',
                signup:   'Sign up / registrasi',
                download: 'Download / subscribe',
            }[f.goal];

            const toneLabel = {
                practical: 'praktis dan to the point',
                beginner:  'ramah pemula (hindari jargon teknis)',
                technical: 'teknikal dan mendalam',
            }[f.tone];

            const langLabel = f.lang === 'id' ? 'Bahasa Indonesia' : 'English';

            // Build competitor section based on mode
            let competitorSection = '';
            if (f.mode === 'manual') {
                const urls = f.competitorUrls.map(u => u.trim()).filter(Boolean);
                competitorSection = '\n## DATA KOMPETITOR\nBerikut adalah URL landing page kompetitor yang harus kamu analisis:\n' +
                    urls.map((url, i) => `${i + 1}. ${url}`).join('\n') +
                    '\n\nAkses setiap URL di atas dan analisis konten landing page-nya secara langsung.';
            } else {
                const loc = f.locations.length > 0 ? f.locations.join(', ') : 'Indonesia';
                competitorSection = '\n## IDENTIFIKASI KOMPETITOR\nLakukan riset untuk menemukan ' + f.compCount + ' kompetitor utama dari bisnis berikut:\n\n' +
                    '- Nama Bisnis: ' + bizName + '\n' +
                    '- Industri: ' + bizIndustry + '\n' +
                    '- Deskripsi Produk/Jasa: ' + bizProduct + '\n' +
                    '- Target Pasar: ' + loc + '\n\n' +
                    'Kriteria kompetitor yang dicari:\n' +
                    '- Bisnis yang menjual produk/jasa serupa di industri yang sama\n' +
                    '- Aktif beriklan di Google Ads atau memiliki traffic organik yang signifikan\n' +
                    '- Memiliki landing page yang bisa diakses secara publik\n' +
                    '- Beroperasi di pasar yang sama (' + loc + ')\n\n' +
                    'Setelah menemukan kompetitor, cantumkan daftar nama bisnis dan URL landing page mereka di awal output, lalu lanjutkan dengan analisis lengkap.';
            }

            // Build website section based on whether client has LP
            const websiteSection = hasWebsite
                ? '\n## WEBSITE KLIEN\nKlien sudah memiliki website: ' + bizWebsite + '\n\nSetelah analisis kompetitor selesai, lakukan audit khusus terhadap landing page ini:\n- Bandingkan dengan landing page kompetitor yang sudah dianalisis\n- Identifikasi gap yang perlu diperbaiki\n- Buat daftar prioritas perbaikan (urutkan dari dampak terbesar)\n- Pisahkan antara Quick Wins (bisa dikerjakan minggu ini) dan Long Term Improvements'
                : '\n## KONDISI KLIEN\nKlien belum memiliki website atau landing page.\n\nSetelah analisis kompetitor selesai, buat Blueprint Landing Page Ideal berdasarkan temuan analisis:\n- Struktur dan urutan section yang direkomendasikan\n- Elemen wajib ada berdasarkan benchmark kompetitor terbaik\n- Angle messaging dan headline yang disarankan\n- Trust signals yang perlu disiapkan\n- Panduan CTA yang efektif';

            // Build full prompt
            let p = 'Kamu adalah seorang Landing Page Strategist dan Conversion Rate Optimization (CRO) expert yang berpengalaman menganalisis dan mengoptimasi landing page untuk kampanye Google Ads.\n\n' +
                '## KONTEKS BISNIS\n' +
                '- Nama Bisnis: ' + bizName + '\n' +
                '- Industri: ' + bizIndustry + '\n' +
                '- Deskripsi Produk/Jasa: ' + bizProduct + '\n' +
                '- Target Audiens: ' + (bizAudience || 'Tidak disebutkan') + '\n' +
                '- Goal Utama Landing Page: ' + goalLabel + '\n' +
                competitorSection + '\n\n' +
                '## INSTRUKSI ANALISIS\n\n' +
                'Lakukan analisis mendalam terhadap setiap landing page kompetitor berdasarkan aspek berikut. Berikan penilaian yang jujur dan objektif, hanya berdasarkan apa yang benar-benar ada di landing page tersebut, bukan asumsi.\n\n' +
                'Aspek yang Dianalisis (beri skor 1-10 untuk setiap aspek):\n\n' +
                '1. Struktur dan Layout: Apakah alur halaman logis? Hero section, body, dan CTA placement sudah optimal?\n' +
                '2. Headline dan Copywriting: Seberapa kuat headline-nya? Apakah copy menyentuh pain point audiens?\n' +
                '3. Kejelasan Value Proposition: Apakah pengunjung langsung mengerti apa yang ditawarkan dan apa bedanya?\n' +
                '4. Trust Signals dan Social Proof: Testimonial, review, angka, logo klien, sertifikasi. Seberapa meyakinkan?\n' +
                '5. Kejelasan Offer dan CTA: Seberapa jelas offer-nya? CTA mudah ditemukan dan actionable?\n' +
                '6. Mobile Friendliness: Berdasarkan tampilan dan struktur, apakah LP ini kemungkinan mobile-friendly?\n' +
                '7. Friction Level: Seberapa mudah pengunjung mengambil aksi? Ada hambatan yang tidak perlu?\n\n' +
                'Catatan penting: Jika ada aspek yang tidak bisa dinilai secara akurat dari LP yang tersedia, tuliskan "Tidak dapat dinilai" beserta alasannya.\n\n' +
                '## FORMAT OUTPUT\n\n' +
                'Output wajib dibuat dalam format dokumen proposal (.doc) yang profesional dan siap dikirimkan kepada klien. Ikuti semua ketentuan format berikut:\n\n' +
                'Judul dokumen (tulis tepat seperti ini di bagian paling atas):\n' +
                'Analisis Kompetitor & Audit Landing Page\n' +
                'Nama bisnis: ' + bizName + '\n' +
                'Website: ' + (bizWebsite || '_____________') + '\n\n' +
                'Ketentuan format dokumen:\n' +
                '- Seluruh konten menggunakan line spacing 1.5\n' +
                '- Seluruh teks rata kiri\n' +
                '- Semua tabel dibuat full width sesuai lebar maksimal dokumen\n' +
                '- Gunakan bullet list standar atau penomoran untuk daftar\n' +
                '- Setiap bagian dipisahkan dengan heading yang jelas\n' +
                '- Kata "Kesimpulan" digunakan sebagai penutup setiap bagian analisis, bukan "Bottom line"\n\n' +
                'Struktur dokumen:\n\n' +
                'Bagian 1: Ringkasan Kompetitor\n' +
                'Buat tabel full width yang berisi: Nama Bisnis, URL, Overall Grade, dan 1 kalimat highlight utama untuk setiap kompetitor.\n\n' +
                'Bagian 2: Analisis Detail Per Kompetitor\n' +
                'Untuk setiap kompetitor, tampilkan:\n' +
                '- Skor per aspek dalam tabel full width\n' +
                '- Overall Grade (A / B plus / B / C plus / C / D)\n' +
                '- Kelebihan utama (maksimal 3 poin)\n' +
                '- Kelemahan utama (maksimal 3 poin)\n\n' +
                'Bagian 3: Tabel Komparasi Side-by-Side\n' +
                'Buat tabel komparasi semua kompetitor dalam satu tabel dengan kolom: Aspek | [Kompetitor 1] | [Kompetitor 2] | dst.\n\n' +
                'Bagian 4: Elemen Terbaik dari Kompetitor\n' +
                'List 3-5 elemen, teknik, atau pendekatan terbaik dari semua kompetitor yang bisa langsung diadopsi atau dimodifikasi. Jelaskan mengapa elemen tersebut efektif.\n\n' +
                'Bagian 5: ' + (hasWebsite ? 'Audit Landing Page Klien' : 'Blueprint Landing Page Ideal') + '\n' +
                (hasWebsite
                    ? 'Analisis LP ' + bizWebsite + ' dibandingkan dengan kompetitor:\n- Tabel gap analysis (full width)\n- Daftar prioritas perbaikan\n- Quick Wins (bisa dikerjakan minggu ini)\n- Long Term Improvements\n- Kesimpulan'
                    : 'Blueprint LP ideal berdasarkan benchmark kompetitor:\n- Struktur section yang direkomendasikan\n- Elemen wajib ada\n- Angle messaging\n- Panduan CTA\n- Kesimpulan') + '\n' +
                websiteSection + '\n\n' +
                '## GAYA PENULISAN\n' +
                '- Gunakan ' + langLabel + '\n' +
                '- Tone: ' + toneLabel + '\n' +
                '- Gunakan bahasa yang ' + (f.lang === 'id' ? 'natural dan mudah dipahami oleh pelaku bisnis di Indonesia' : 'clear and practical') + '\n' +
                '- Fokus pada insight yang actionable, bukan sekadar deskripsi\n' +
                '- Hindari generalisasi tanpa bukti dari LP yang dianalisis\n' +
                '- Tidak menggunakan simbol dekoratif seperti em dash, tanda panah, tanda tambah sebagai bullet, atau emoji\n\n' +
                'Ketentuan tabel:\n' +
                '- Kolom yang berisi skor (1-10) dibuat rata tengah\n' +
                '- Semua kolom lainnya dibuat rata kiri\n\n' +
                'Bagian penutup dokumen (footer):\n' +
                'Di bagian paling bawah dokumen, tambahkan satu baris teks kecil berwarna abu-abu dengan isi sebagai berikut:\n' +
                '"Dokumen ini dibuat oleh LP Analyzer dari digimaya.com dan Claude AI untuk keperluan optimasi landing page ' + bizName + '."';

            this._lastPrompt = p;
            this.outputVisible = true;

            this.$nextTick(() => {
                this.$refs.output_box.scrollIntoView({ behavior: 'smooth', block: 'start' });
                setTimeout(() => this.typeWrite(this.$refs.output_box, p, 4), 300);
            });
        },

        // ===== TYPEWRITER =====
        typeWrite(element, text, speed) {
            speed = speed || 4;
            element.textContent = '';
            let i = 0;
            const cursor = document.createElement('span');
            cursor.className = 'inline-block w-0.5 h-4 bg-brand ml-0.5 animate-pulse';
            element.appendChild(cursor);

            if (this.typewriterTimeout) clearInterval(this.typewriterTimeout);

            this.typewriterTimeout = setInterval(() => {
                if (i < text.length) {
                    element.insertBefore(document.createTextNode(text[i]), cursor);
                    element.scrollTop = element.scrollHeight;
                    i++;
                    if (i > 200) speed = 1;
                } else {
                    clearInterval(this.typewriterTimeout);
                    cursor.remove();
                }
            }, speed);
        },

        // ===== COPY =====
        copyPrompt() {
            if (this.typewriterTimeout) clearInterval(this.typewriterTimeout);

            if (this._lastPrompt) {
                this.$refs.output_box.textContent = this._lastPrompt;
            }

            const text = this._lastPrompt || this.$refs.output_box.textContent;
            if (!text) return;

            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2000);
            }).catch(err => {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2000);
                } catch (e) {
                    console.error('Copy failed:', e);
                }
                document.body.removeChild(ta);
            });
        },
    };
}
</script>
@endpush

@endsection
