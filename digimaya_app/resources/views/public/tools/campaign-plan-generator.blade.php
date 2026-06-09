@extends('layouts.public')

@section('meta_title', 'Campaign Plan Generator — Generate Prompt Claude AI untuk Google Ads | Digimaya')
@section('meta_description', 'Tool gratis untuk generate prompt Claude AI yang menghasilkan Campaign Plan dan Strategy Google Ads lengkap. Isi form, dapatkan prompt siap pakai, paste ke Claude.')

@section('content')


{{-- ============== TOOL WIDGET ============== --}}
<section class="bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16"
         x-data="campaignPlanGenerator()"
         x-init="init()">

        {{-- Hero --}}
        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-5 tracking-tight leading-[1.1]">
                Campaign Plan Generator
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                Isi form &rsaquo; Generate prompt &rsaquo; Paste ke Claude AI untuk dapat Campaign Plan dan Strategy lengkap. Klik
                <button type="button"
                        @click="loadSample()"
                        class="text-brand hover:text-brand-700 font-semibold underline underline-offset-2 transition">load sample data</button>
                untuk lihat contoh.
            </p>
        </div>

        {{-- Form Card 1: INFORMASI KLIEN --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Informasi Klien</h2>
            </div>
            <div class="p-5 sm:p-6 space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama bisnis / klien <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               x-model="form.client_name"
                               @input="save()"
                               placeholder="e.g. Klinik Gigi Sehat"
                               class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Industri <span class="text-red-500">*</span>
                        </label>
                        <select x-model="form.industry"
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

                {{-- USP Tag input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        USP (Unique Selling Point)
                        <span class="text-xs text-gray-400 ml-1">ketik lalu Enter</span>
                    </label>
                    <div @click="$refs.usp_input.focus()"
                         class="flex flex-wrap items-center gap-2 px-3 py-2 min-h-[3rem] bg-gray-50 border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-brand focus-within:border-brand focus-within:bg-white transition cursor-text">
                        <template x-for="(tag, idx) in form.usp" :key="idx">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand-50 text-brand text-xs font-medium rounded">
                                <span x-text="tag"></span>
                                <button type="button" @click.stop="form.usp.splice(idx, 1); save()" class="text-brand/60 hover:text-brand">&times;</button>
                            </span>
                        </template>
                        <input type="text"
                               x-ref="usp_input"
                               x-model="uspInput"
                               @keydown.enter.prevent="addTag('usp', uspInput); uspInput=''"
                               @keydown="if($event.key === ',') { $event.preventDefault(); addTag('usp', uspInput); uspInput=''; }"
                               @keydown.backspace="if(!uspInput && form.usp.length) { form.usp.pop(); save(); }"
                               placeholder="e.g. Dokter berpengalaman 15 tahun, cicilan 0%, garansi hasil..."
                               class="flex-1 min-w-[120px] bg-transparent text-sm focus:outline-none placeholder:text-gray-400">
                    </div>
                </div>

                {{-- Kompetitor Tag input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Website kompetitor
                        <span class="text-xs text-gray-400 ml-1">ketik lalu Enter</span>
                    </label>
                    <div @click="$refs.comp_input.focus()"
                         class="flex flex-wrap items-center gap-2 px-3 py-2 min-h-[3rem] bg-gray-50 border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-brand focus-within:border-brand focus-within:bg-white transition cursor-text">
                        <template x-for="(tag, idx) in form.competitors" :key="idx">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand-50 text-brand text-xs font-medium rounded">
                                <span x-text="tag"></span>
                                <button type="button" @click.stop="form.competitors.splice(idx, 1); save()" class="text-brand/60 hover:text-brand">&times;</button>
                            </span>
                        </template>
                        <input type="text"
                               x-ref="comp_input"
                               x-model="compInput"
                               @keydown.enter.prevent="addTag('competitors', compInput); compInput=''"
                               @keydown="if($event.key === ',') { $event.preventDefault(); addTag('competitors', compInput); compInput=''; }"
                               @keydown.backspace="if(!compInput && form.competitors.length) { form.competitors.pop(); save(); }"
                               placeholder="e.g. https://kompetitor.com"
                               class="flex-1 min-w-[160px] bg-transparent text-sm focus:outline-none placeholder:text-gray-400">
                    </div>
                </div>

            </div>
        </div>

        {{-- Form Card 2: LAYANAN --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">
                    Layanan yang Diiklankan
                    <span class="text-xs font-normal opacity-70 ml-2">* minimal 1 layanan</span>
                </h2>
            </div>
            <div class="p-5 sm:p-6">
                <p class="text-xs text-gray-500 mb-4">
                    Setiap layanan punya produk, landing page, dan target lokasi masing-masing.
                </p>

                <div class="space-y-4">
                    <template x-for="(service, idx) in form.services" :key="service.id">
                        <div class="bg-gray-50/60 border border-gray-200 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide" x-text="`Layanan ${idx + 1}`"></p>
                                <button type="button"
                                        @click="removeService(idx)"
                                        x-show="form.services.length > 1"
                                        class="inline-flex items-center justify-center w-7 h-7 text-gray-400 hover:text-red-500 hover:bg-white rounded-lg transition"
                                        aria-label="Hapus layanan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                {{-- Nama Layanan --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama layanan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           x-model="service.name"
                                           @input="save()"
                                           placeholder="e.g. Pemasangan Behel"
                                           class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand transition">
                                </div>

                                {{-- Produk Tag input --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Produk / keyword terkait
                                        <span class="text-xs text-gray-400 ml-1">ketik lalu Enter</span>
                                    </label>
                                    <div @click="$refs[`prod_${service.id}`].focus()"
                                         class="flex flex-wrap items-center gap-2 px-3 py-2 min-h-[2.5rem] bg-white border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-brand focus-within:border-brand transition cursor-text">
                                        <template x-for="(tag, tagIdx) in service.products" :key="tagIdx">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand-50 text-brand text-xs font-medium rounded">
                                                <span x-text="tag"></span>
                                                <button type="button" @click.stop="service.products.splice(tagIdx, 1); save()" class="text-brand/60 hover:text-brand">&times;</button>
                                            </span>
                                        </template>
                                        <input type="text"
                                               :x-ref="`prod_${service.id}`"
                                               x-model="service.prodInput"
                                               @keydown.enter.prevent="addServiceTag(service, 'products', service.prodInput); service.prodInput = ''"
                                               @keydown="if($event.key === ',') { $event.preventDefault(); addServiceTag(service, 'products', service.prodInput); service.prodInput = ''; }"
                                               @keydown.backspace="if(!service.prodInput && service.products.length) { service.products.pop(); save(); }"
                                               placeholder="e.g. behel metal, behel keramik..."
                                               class="flex-1 min-w-[150px] bg-transparent text-sm focus:outline-none placeholder:text-gray-400">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Landing Page URL --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            URL landing page
                                        </label>
                                        <input type="url"
                                               x-model="service.url"
                                               @input="save()"
                                               placeholder="e.g. https://klinik.com/behel"
                                               class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand transition">
                                    </div>

                                    {{-- Target Lokasi Tag input --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Target lokasi
                                            <span class="text-xs text-gray-400 ml-1">Enter</span>
                                        </label>
                                        <div @click="$refs[`loc_${service.id}`].focus()"
                                             class="flex flex-wrap items-center gap-2 px-3 py-1.5 min-h-[2.5rem] bg-white border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-brand focus-within:border-brand transition cursor-text">
                                            <template x-for="(tag, tagIdx) in service.locations" :key="tagIdx">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-brand-50 text-brand text-xs font-medium rounded">
                                                    <span x-text="tag"></span>
                                                    <button type="button" @click.stop="service.locations.splice(tagIdx, 1); save()" class="text-brand/60 hover:text-brand">&times;</button>
                                                </span>
                                            </template>
                                            <input type="text"
                                                   :x-ref="`loc_${service.id}`"
                                                   x-model="service.locInput"
                                                   @keydown.enter.prevent="addServiceTag(service, 'locations', service.locInput); service.locInput = ''"
                                                   @keydown="if($event.key === ',') { $event.preventDefault(); addServiceTag(service, 'locations', service.locInput); service.locInput = ''; }"
                                                   @keydown.backspace="if(!service.locInput && service.locations.length) { service.locations.pop(); save(); }"
                                                   placeholder="e.g. Jakarta"
                                                   class="flex-1 min-w-[100px] bg-transparent text-sm focus:outline-none placeholder:text-gray-400">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button"
                        @click="addService()"
                        class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-brand bg-white border border-dashed border-brand/40 hover:bg-brand-50 hover:border-brand rounded-lg transition w-full justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Layanan
                </button>
            </div>
        </div>

        {{-- Form Card 3: TUJUAN CAMPAIGN --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Tujuan Campaign</h2>
            </div>
            <div class="p-5 sm:p-6">
                <div class="flex flex-wrap gap-2">
                    <template x-for="goal in goalOptions" :key="goal.value">
                        <button type="button"
                                @click="form.goal = goal.value; save()"
                                :class="form.goal === goal.value ? 'bg-brand text-white border-brand' : 'bg-white text-gray-700 border-gray-200 hover:border-brand hover:text-brand'"
                                class="px-4 py-2 text-sm font-semibold border rounded-lg transition"
                                x-text="goal.label"></button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Form Card 4: TIPE CAMPAIGN --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Tipe Campaign</h2>
            </div>
            <div class="p-5 sm:p-6">
                <p class="text-xs text-gray-500 mb-4">
                    Pilih lebih dari satu jika dibutuhkan. Brief akan dibuatkan terpisah per tipe campaign.
                </p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="ct in campaignTypeOptions" :key="ct">
                        <button type="button"
                                @click="toggleArrayItem(form.campaignTypes, ct); save()"
                                :class="form.campaignTypes.includes(ct) ? 'bg-brand text-white border-brand' : 'bg-white text-gray-700 border-gray-200 hover:border-brand hover:text-brand'"
                                class="px-4 py-2 text-sm font-semibold border rounded-lg transition"
                                x-text="ct"></button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Form Card 5: BUDGET & TARGET --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Budget & Target</h2>
            </div>
            <div class="p-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Budget bulanan (Rp)
                        </label>
                        <input type="text"
                               x-model="form.budget"
                               @input="save()"
                               placeholder="e.g. 10.000.000"
                               class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Target CPA / CPL
                        </label>
                        <input type="text"
                               x-model="form.cpa"
                               @input="save()"
                               placeholder="e.g. Rp 150.000"
                               class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Target ROAS <span class="text-xs text-gray-400">opsional</span>
                        </label>
                        <input type="text"
                               x-model="form.roas"
                               @input="save()"
                               placeholder="e.g. 4x"
                               class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition">
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Card 6: TARGET AUDIENS --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Target Audiens</h2>
            </div>
            <div class="p-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Segmen audiens utama
                        </label>
                        <textarea x-model="form.audience"
                                  @input="save()"
                                  rows="3"
                                  placeholder="e.g. Orang tua anak 5-15 tahun, income menengah, tinggal di kota besar"
                                  class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition resize-y leading-relaxed"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pain point / motivasi beli
                        </label>
                        <textarea x-model="form.painpoint"
                                  @input="save()"
                                  rows="3"
                                  placeholder="e.g. Ingin gigi anak rapi sebelum masuk SMP, khawatir biaya mahal"
                                  class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition resize-y leading-relaxed"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Card 7: OUTPUT --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">Output yang Dibutuhkan dari AI</h2>
            </div>
            <div class="p-5 sm:p-6">
                <div class="flex flex-wrap gap-2">
                    <template x-for="opt in outputOptions" :key="opt.value">
                        <button type="button"
                                @click="toggleArrayItem(form.outputs, opt.value); save()"
                                :class="form.outputs.includes(opt.value) ? 'bg-brand text-white border-brand' : 'bg-white text-gray-700 border-gray-200 hover:border-brand hover:text-brand'"
                                class="px-4 py-2 text-sm font-semibold border rounded-lg transition"
                                x-text="opt.label"></button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Form Card 8: CATATAN --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-8">
            <div class="px-5 sm:px-6 py-4 bg-gray-700 text-white">
                <h2 class="text-sm font-semibold">
                    Catatan Tambahan
                    <span class="text-xs font-normal opacity-70 ml-2">opsional</span>
                </h2>
            </div>
            <div class="p-5 sm:p-6">
                <textarea x-model="form.notes"
                          @input="save()"
                          rows="3"
                          placeholder="e.g. Klien pernah coba Google Ads tapi CTR rendah, budget ketat di awal, ada promo spesial bulan ini..."
                          class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand focus:bg-white transition resize-y leading-relaxed"></textarea>
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
function campaignPlanGenerator() {
    return {
        // ===== FORM STATE =====
        form: {
            client_name: '',
            industry: '',
            usp: [],
            competitors: [],
            services: [],
            goal: 'Leads (form isi / WhatsApp / telepon)',
            campaignTypes: ['Search'],
            budget: '',
            cpa: '',
            roas: '',
            audience: '',
            painpoint: '',
            outputs: [
                'Struktur ad group',
                'Rekomendasi keyword dan match type',
                'Ad copy (headlines dan descriptions)',
            ],
            notes: '',
        },

        // ===== INPUT BUFFERS (untuk tag input) =====
        uspInput: '',
        compInput: '',

        // ===== OPTIONS =====
        goalOptions: [
            { value: 'Leads (form isi / WhatsApp / telepon)', label: 'Leads' },
            { value: 'Sales / penjualan langsung', label: 'Sales' },
            { value: 'Kunjungan toko / cabang (local store visits)', label: 'Local store visits' },
            { value: 'Brand awareness dan jangkauan', label: 'Awareness' },
            { value: 'Aplikasi diunduh (app install)', label: 'App install' },
        ],

        campaignTypeOptions: ['Search', 'Demand Gen', 'Performance Max', 'Display', 'YouTube'],

        outputOptions: [
            { value: 'Struktur ad group', label: 'Struktur ad group' },
            { value: 'Rekomendasi keyword dan match type', label: 'Keyword & match type' },
            { value: 'Ad copy (headlines dan descriptions)', label: 'Ad copy' },
            { value: 'Negative keyword list', label: 'Negative keyword' },
            { value: 'Bidding strategy dan rekomendasi alokasi budget', label: 'Bidding strategy' },
            { value: 'Rekomendasi perbaikan landing page', label: 'Landing page tips' },
            { value: 'Ad extensions (sitelink, callout, structured snippet)', label: 'Ad extensions' },
            { value: 'Audience targeting dan in-market segments', label: 'Audience targeting' },
        ],

        // ===== OUTPUT STATE =====
        outputVisible: false,
        copied: false,
        nextServiceId: 1,
        typewriterTimeout: null,
        _lastPrompt: '',

        // ===== INIT =====
        init() {
            // Load from localStorage
            const saved = this.loadFromStorage();
            if (saved) {
                this.form = { ...this.form, ...saved };
                // Ensure services have buffer keys
                this.form.services.forEach(s => {
                    if (!('prodInput' in s)) s.prodInput = '';
                    if (!('locInput' in s)) s.locInput = '';
                });
                this.nextServiceId = Math.max(...this.form.services.map(s => s.id), 0) + 1;
            }

            // Ensure at least 1 service
            if (this.form.services.length === 0) {
                this.addService();
            }
        },

        // ===== STORAGE =====
        STORAGE_KEY: 'digimaya_campaign_plan_v1',

        save() {
            try {
                // Strip input buffer keys before saving (not persistent)
                const toSave = JSON.parse(JSON.stringify(this.form));
                toSave.services = toSave.services.map(s => {
                    const { prodInput, locInput, ...rest } = s;
                    return rest;
                });
                localStorage.setItem(this.STORAGE_KEY, JSON.stringify(toSave));
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
                client_name: '',
                industry: '',
                usp: [],
                competitors: [],
                services: [],
                goal: 'Leads (form isi / WhatsApp / telepon)',
                campaignTypes: ['Search'],
                budget: '',
                cpa: '',
                roas: '',
                audience: '',
                painpoint: '',
                outputs: [
                    'Struktur ad group',
                    'Rekomendasi keyword dan match type',
                    'Ad copy (headlines dan descriptions)',
                ],
                notes: '',
            };
            this.nextServiceId = 1;
            this.addService();
            this.outputVisible = false;
            this._lastPrompt = '';
        },

        // ===== SAMPLE DATA =====
        loadSample() {
            this.form = {
                client_name: 'Klinik Gigi Sehat',
                industry: 'Klinik Gigi / Ortodonti',
                usp: ['Dokter berpengalaman 15 tahun', 'Cicilan 0% hingga 12 bulan', 'Garansi hasil 1 tahun'],
                competitors: ['https://klinikgigicompetitor.com'],
                services: [
                    {
                        id: 1,
                        name: 'Pemasangan Behel',
                        products: ['behel metal', 'behel keramik', 'behel damon'],
                        url: 'https://klinikgigisehat.com/behel',
                        locations: ['Jakarta Selatan', 'Tangerang Selatan'],
                        prodInput: '',
                        locInput: '',
                    },
                    {
                        id: 2,
                        name: 'Veneer Gigi',
                        products: ['veneer komposit', 'veneer porcelain', 'veneer estetik'],
                        url: 'https://klinikgigisehat.com/veneer',
                        locations: ['Jakarta Selatan'],
                        prodInput: '',
                        locInput: '',
                    },
                ],
                goal: 'Leads (form isi / WhatsApp / telepon)',
                campaignTypes: ['Search', 'Performance Max'],
                budget: '20.000.000',
                cpa: 'Rp 200.000',
                roas: '',
                audience: 'Orang tua anak 12-17 tahun, income menengah atas, tinggal di Jakarta dan sekitarnya. Atau profesional muda 25-35 tahun yang peduli penampilan.',
                painpoint: 'Ingin gigi anak rapi sebelum masuk SMA, khawatir biaya behel yang mahal. Atau profesional yang malu senyum karena gigi tidak rapi.',
                outputs: [
                    'Struktur ad group',
                    'Rekomendasi keyword dan match type',
                    'Ad copy (headlines dan descriptions)',
                    'Negative keyword list',
                    'Bidding strategy dan rekomendasi alokasi budget',
                ],
                notes: 'Klien baru pertama kali jalan Google Ads. Budget ketat di bulan pertama untuk testing. Ada promo konsultasi gratis 30 menit di bulan ini.',
            };
            this.nextServiceId = 3;
            this.save();
        },

        // ===== HELPERS =====
        addTag(field, value) {
            if (!value || !value.trim()) return;
            this.form[field].push(value.trim());
            this.save();
        },

        addServiceTag(service, field, value) {
            if (!value || !value.trim()) return;
            service[field].push(value.trim());
            this.save();
        },

        toggleArrayItem(arr, item) {
            const idx = arr.indexOf(item);
            if (idx > -1) {
                arr.splice(idx, 1);
            } else {
                arr.push(item);
            }
        },

        addService() {
            this.form.services.push({
                id: this.nextServiceId++,
                name: '',
                products: [],
                url: '',
                locations: [],
                prodInput: '',
                locInput: '',
            });
            this.save();
        },

        removeService(idx) {
            if (this.form.services.length <= 1) return;
            this.form.services.splice(idx, 1);
            this.save();
        },

        // ===== PROMPT GENERATION =====
        generatePrompt() {
            const f = this.form;

            // Validation
            if (!f.client_name.trim()) {
                alert('Nama bisnis / klien wajib diisi.');
                return;
            }
            if (!f.industry) {
                alert('Industri wajib dipilih.');
                return;
            }
            const validServices = f.services.filter(s => s.name.trim() || s.products.length || s.url.trim());
            if (validServices.length === 0) {
                alert('Minimal 1 layanan wajib diisi.');
                return;
            }

            const client = f.client_name.trim();
            const industry = f.industry;
            const usp = f.usp.join(', ');
            const competitors = f.competitors.join(', ');
            const goal = f.goal;
            const campaignTypes = f.campaignTypes.join(', ');
            const budget = f.budget.trim();
            const cpa = f.cpa.trim();
            const roas = f.roas.trim();
            const audience = f.audience.trim();
            const painpoint = f.painpoint.trim();
            const outputs = f.outputs.join(', ');
            const notes = f.notes.trim();
            const isHighIntent = goal.indexOf('Leads') > -1 || goal.indexOf('Sales') > -1;
            const hasSearch = campaignTypes.indexOf('Search') > -1;
            const typeList = campaignTypes ? campaignTypes.split(', ') : ['Search'];
            const hasLP = validServices.some(s => s.url);
            const line = '----------------------------------------------------------------';

            let p = '';
            p += 'PENTING: Ini adalah brief yang harus langsung kamu kerjakan sekarang. Hasilkan dokumen Campaign Plan dan Campaign Strategy secara lengkap berdasarkan data di bawah. Jangan bertanya balik, jangan membangun tool, jangan menjelaskan apa yang akan kamu lakukan. Langsung tulis dokumennya.\n\n';
            p += 'Kamu adalah Google Ads specialist berpengalaman dengan kemampuan riset mendalam.\n\n';
            p += 'TAHAP RISET — Lakukan sebelum menulis output. Jangan tampilkan hasil riset di dokumen akhir.\n';
            p += line + '\n';

            let step = 1;
            if (hasLP) {
                p += step++ + '. Fetch dan analisa setiap landing page klien berikut. Identifikasi offer utama, CTA, angle copy, kekuatan, dan kelemahan halaman.\n';
                validServices.forEach(s => {
                    if (s.url) p += '   - ' + (s.name || 'Layanan') + ': ' + s.url + '\n';
                });
            }
            if (competitors) {
                p += step++ + '. Fetch dan analisa website kompetitor. Identifikasi positioning, angle iklan, offer, dan potensi keyword yang mereka targetkan.\n';
                competitors.split(', ').forEach(c => { p += '   - ' + c + '\n'; });
            }
            if (isHighIntent && hasSearch) {
                p += step++ + '. Riset keyword high-intent untuk bisnis ' + client + ' di industri ' + industry + ' di pasar Indonesia.\n';
                p += '   - Gali keyword transaksional dan komersial selengkap mungkin, termasuk variasi, sinonim, dan long-tail.\n';
                p += '   - Konsolidasi keyword yang setema ke dalam satu ad group. Jangan over-split.\n';
                p += '   - Setiap ad group minimal 8-15 keyword yang relevan.\n';
            }

            p += '\n\nDOKUMEN YANG HARUS DIBUAT\n';
            p += line + '\n\n';
            p += 'CAMPAIGN BRIEF\n';
            p += line + '\n\n';
            if (client)    p += 'Bisnis         : ' + client + '\n';
            if (industry)  p += 'Industri       : ' + industry + '\n';
            if (goal)      p += 'Tujuan         : ' + goal + '\n';
            if (budget)    p += 'Budget bulanan : Rp ' + budget + '\n';
            if (cpa)       p += 'Target CPA/CPL : ' + cpa + '\n';
            if (roas)      p += 'Target ROAS    : ' + roas + '\n';
            if (usp)       p += 'USP            : ' + usp + '\n';
            if (audience)  p += 'Segmen audiens : ' + audience + '\n';
            if (painpoint) p += 'Pain point     : ' + painpoint + '\n';

            if (validServices.length > 0) {
                p += '\nLayanan yang diiklankan:\n';
                validServices.forEach((s, i) => {
                    p += '  ' + (i+1) + '. ' + (s.name || '(layanan)') + '\n';
                    if (s.products.length) p += '     Produk/keyword : ' + s.products.join(', ') + '\n';
                    if (s.url)             p += '     Landing page   : ' + s.url + '\n';
                    if (s.locations.length)p += '     Target lokasi  : ' + s.locations.join(', ') + '\n';
                });
            }
            if (competitors) p += '\nKompetitor     : ' + competitors + '\n';
            if (notes)       p += '\nCatatan        : ' + notes + '\n';

            p += '\n' + line + '\n';
            p += 'CAMPAIGN STRATEGY\n';
            p += line + '\n\n';
            p += 'Buatkan strategy lengkap dan terpisah untuk setiap tipe campaign berikut.\n\n';

            typeList.forEach((t, i) => {
                p += (i+1) + '. ' + t.toUpperCase() + ' CAMPAIGN\n\n';
                const outList = outputs ? outputs.split(', ') : ['Struktur campaign lengkap'];
                outList.forEach((o, j) => { p += '   ' + (j+1) + '. ' + o + '\n'; });

                if (isHighIntent && t.toLowerCase().indexOf('search') > -1) {
                    p += '\n   Ketentuan keyword Search:\n';
                    p += '   - Prioritaskan keyword high-intent (transaksional dan komersial)\n';
                    p += '   - Konsolidasi keyword setema dalam satu ad group, minimal 8-15 keyword per ad group\n';
                    p += '   - Cantumkan match type untuk setiap keyword\n';
                    p += '   - Kelompokkan berdasarkan intent dan tema, bukan hanya kesamaan kata\n';
                }
                if (t.toLowerCase().indexOf('performance max') > -1) {
                    p += '\n   Ketentuan Performance Max:\n';
                    p += '   - Buatkan rekomendasi asset group per layanan\n';
                    p += '   - Sertakan audience signal yang relevan\n';
                    p += '   - Berikan panduan final URL expansion strategy\n';
                }
                p += '\n';
            });

            if (outputs.indexOf('Ad copy') > -1 || outputs === '') {
                p += line + '\n';
                p += 'ATURAN WAJIB AD COPY — TANPA PENGECUALIAN\n';
                p += line + '\n\n';
                p += 'Aturan karakter:\n';
                p += '   - Headline: MAKSIMAL 30 karakter. Hitung karakter secara teliti sebelum menulis.\n';
                p += '   - Description: MAKSIMAL 90 karakter. Hitung karakter secara teliti sebelum menulis.\n';
                p += '   - Jika sebuah headline melebihi 30 karakter, PERSINGKAT terlebih dahulu sebelum ditulis ke output. Jangan menulis versi yang over-limit lalu merevisinya di baris berikutnya.\n';
                p += '   - Jika sebuah description melebihi 90 karakter, PERSINGKAT terlebih dahulu sebelum ditulis ke output. Jangan menulis versi yang over-limit lalu merevisinya di baris berikutnya.\n';
                p += '   - Output yang masuk ke tabel harus sudah final. Tidak boleh ada baris revisi, catatan "persingkat", atau komentar di dalam tabel.\n\n';
                p += 'Format tabel ad copy:\n';
                p += '   - Kolom tabel: No. | Headline | Karakter | Keterangan\n';
                p += '   - Kolom "Karakter" diisi angka saja. Tidak ada komentar, catatan, atau instruksi di kolom ini.\n';
                p += '   - Kolom "Keterangan" diisi fungsi atau reasoning singkat dari headline/description tersebut.\n';
                p += '   - Buat minimal 12 headline dan 4 description per layanan.\n\n';
                p += 'Aturan penulisan:\n';
                p += '   - Gunakan Title Case (setiap kata diawali huruf kapital)\n';
                p += '   - Tidak menggunakan tanda seru berlebihan, simbol, atau karakter non-standar\n';
                p += '   - Setiap headline dan description harus bisa berdiri sendiri dan tetap bermakna\n';
                p += '   - Sertakan keyword utama di minimal 3 headline\n\n';
            }

            p += line + '\n';
            p += 'KETENTUAN FORMAT OUTPUT\n';
            p += line + '\n\n';
            p += 'Dokumen ini harus dibuat dalam format dokumen proposal yang profesional dan siap dikirimkan kepada klien.\n\n';
            p += 'Judul dokumen (tulis tepat seperti ini di bagian paling atas):\n';
            p += 'Campaign Plan & Strategy\n';
            p += 'Bisnis: ' + client + '\n';
            p += 'Website: ' + (validServices.length > 0 && validServices[0].url ? validServices[0].url : '________________') + '\n\n';
            p += 'Ketentuan format dokumen:\n';
            p += '   - Gunakan Bahasa Indonesia\n';
            p += '   - Seluruh konten menggunakan line spacing 1.5\n';
            p += '   - Seluruh teks rata kiri\n';
            p += '   - Semua tabel dibuat full width sesuai lebar maksimal dokumen\n';
            p += '   - Kolom yang berisi angka atau skor dibuat rata tengah, semua kolom lainnya rata kiri\n';
            p += '   - Gunakan bullet list standar atau penomoran untuk daftar\n';
            p += '   - Tidak menggunakan simbol dekoratif seperti em dash, tanda panah, tanda tambah sebagai bullet, atau emoji\n';
            p += '   - Setiap bagian dipisahkan dengan heading yang jelas\n';
            p += '   - Sub judul ditulis sebagai teks biasa dengan format heading, JANGAN menggunakan box, background kotak, atau shading apapun pada heading\n';
            p += '   - Tulis dalam gaya dokumen bisnis yang profesional dan bersih\n';
            p += '   - Setiap rekomendasi disertai reasoning singkat yang jelas\n';
            p += '   - Output adalah dokumen final yang siap dikirimkan langsung kepada klien tanpa perlu diedit ulang\n';
            p += '   - Tidak ada draft, revisi, atau self-correction yang muncul di dalam output\n\n';
            p += 'Ketentuan khusus campaign:\n\n';

            p += 'Ketentuan UMUM (berlaku untuk semua tipe campaign):\n';
            p += '   - Semua line spacing wajib 1.5\n';
            p += '   - Tidak menggunakan simbol em dash, tanda tambah (+), tanda sama dengan (=), atau emoji di seluruh dokumen agar tidak terkesan generated by AI\n';
            p += '   - Headline iklan: Tidak boleh menggunakan tanda seru (!) di manapun\n';
            p += '   - Devices: Gunakan istilah "Devices", bukan "Perangkat"\n';
            p += '   - Ad Rotation: Jangan sertakan baris "Ad Rotation" dalam tabel ringkasan campaign\n';
            p += '   - Bahasa target: Selalu sertakan Bahasa Indonesia dan Bahasa Inggris\n';
            p += '   - Target CTR bulan pertama: >= 8%\n';
            p += '   - Tabel Target KPI: Hapus baris "Estimasi CPL (Cost per Lead)" dan "Quality Score (per keyword)" karena terlalu spekulatif di fase awal\n';
            p += '   - Semua istilah teknis Google Ads tetap dalam Bahasa Inggris (campaign, ad group, bidding, keyword, headline, description, dll)\n\n';

            p += 'Ketentuan SEARCH CAMPAIGN:\n';
            if (isHighIntent) {
                p += '   - Strategi Bidding: Mulai dari Maximize Conversions (tanpa target CPA) selama fase awal pengumpulan data. Setelah campaign mengumpulkan minimal 30-50 konversi per bulan, beralih ke Target CPA dan sesuaikan nilai bid berdasarkan data Cost per Conversion aktual\n';
                p += '   - Keyword: Fokus pada keyword high-intent, transactional, dan commercial. Hindari keyword informational yang tidak menghasilkan konversi\n';
            }
            p += '   - Match Type: Gunakan Phrase Match untuk semua keyword di fase awal\n';
            p += '   - Format keyword Phrase Match wajib menggunakan tanda kutip, contoh: "ganti kaca depan mobil"\n';
            p += '   - Search Terms Report: Review setiap hari di bulan pertama\n\n';

            p += 'Ketentuan DEMAND GEN CAMPAIGN:\n';
            p += '   - Keyword untuk custom segment ditulis biasa tanpa match type. Jangan gunakan tanda kutip atau tanda kurung siku. Jika ada tabel keyword, tidak boleh ada kolom "Match Type"\n';
            p += '   - Negative keyword: Demand Gen TIDAK menggunakan negative keyword. Jangan sertakan penjelasan atau tabel Negative Keyword List untuk Demand Gen\n';
            p += '   - Extensions / Assets: Demand Gen HANYA menggunakan Sitelink. Jangan sertakan extension lain\n';
            p += '   - Frequency Cap: Jangan bahas atau sertakan pengaturan Frequency Cap pada Demand Gen\n\n';

            p += 'Ketentuan PERFORMANCE MAX CAMPAIGN:\n';
            p += '   - Sertakan rekomendasi asset group per layanan\n';
            p += '   - Sertakan audience signal yang relevan\n';
            p += '   - Berikan panduan final URL expansion strategy\n\n';
            p += 'Bagian penutup dokumen (footer):\n';
            p += 'Di bagian paling bawah dokumen, tambahkan satu baris teks kecil berwarna abu-abu:\n';
            p += '"Dokumen ini dibuat oleh Campaign Plan Generator dari digimaya.com dan Claude AI untuk ' + client + '."\n';

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

            // Show full text immediately
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
