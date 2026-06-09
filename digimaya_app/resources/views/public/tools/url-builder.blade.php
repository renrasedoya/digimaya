@extends('layouts.public')

@section('meta_title', 'URL Builder — Generate Tracking URL UTM & ValueTrack Google Ads | Digimaya')
@section('meta_description', 'Tool gratis untuk generate URL tracking dengan UTM parameters dan ValueTrack untuk Google Ads. Support Final URL dan Tracking Template mode. Auto encode dan copy ready.')

@section('content')


{{-- ============== TOOL WIDGET (only section) ============== --}}
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16"
         x-data="urlBuilder()"
         x-init="init()">

        {{-- Center hero with inline sample link --}}
        <div class="max-w-3xl mx-auto text-center mb-10 lg:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-5 tracking-tight leading-[1.1]">
                URL Builder
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                Bikin URL tracking dengan UTM dan ValueTrack untuk Google Ads. Klik
                <button type="button"
                        @click="loadSample()"
                        class="text-brand hover:text-brand-700 font-semibold underline underline-offset-2 transition">load sample data</button>
                untuk lihat contoh hasilnya.
            </p>
        </div>

        {{-- Mode tabs (segmented control, center) --}}
        <div class="flex justify-center mb-8">
            <div class="inline-flex bg-gray-100 rounded-xl p-1">
                <button type="button"
                        @click="mode = 'final'; generate()"
                        :class="mode === 'final' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="px-5 py-2 text-sm font-semibold rounded-lg transition">
                    Final URL
                </button>
                <button type="button"
                        @click="mode = 'tracking'; generate()"
                        :class="mode === 'tracking' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="px-5 py-2 text-sm font-semibold rounded-lg transition">
                    Tracking Template
                </button>
            </div>
        </div>

        {{-- Mode hint --}}
        <div class="max-w-2xl mx-auto text-center mb-8">
            <p class="text-xs sm:text-sm text-gray-500" x-show="mode === 'final'" x-cloak>
                Mode <span class="font-semibold text-gray-700">Final URL</span> untuk Display, YouTube, Social, atau campaign tanpa Tracking Template.
            </p>
            <p class="text-xs sm:text-sm text-gray-500" x-show="mode === 'tracking'" x-cloak>
                Mode <span class="font-semibold text-gray-700">Tracking Template</span> untuk Search campaign di Google Ads. Base URL otomatis pakai <code class="px-1 py-0.5 bg-gray-100 rounded text-gray-700">{lpurl}</code>.
            </p>
        </div>

        {{-- ============== FORM ============== --}}
        <div class="max-w-3xl mx-auto">

            {{-- Base URL --}}
            <div class="mb-5">
                <label for="baseUrl" class="block text-sm font-semibold text-gray-700 mb-2">
                    Base URL <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="baseUrl"
                       x-model="fields.baseUrl"
                       @input="generate()"
                       :disabled="mode === 'tracking'"
                       :placeholder="mode === 'tracking' ? '{lpurl}' : 'https://digimaya.com/landing/promo'"
                       :class="mode === 'tracking' ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white'"
                       class="block w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition">
                <p x-show="mode === 'final' && warnings.baseUrl" x-cloak class="mt-1.5 text-xs text-amber-700 flex items-start gap-1.5">
                    <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                    </svg>
                    <span x-text="warnings.baseUrl"></span>
                </p>
            </div>

            {{-- UTM fields grid --}}
            <div class="grid sm:grid-cols-2 gap-4 mb-5">

                {{-- UTM Source --}}
                <div>
                    <label for="utm_source" class="block text-sm font-semibold text-gray-700 mb-2">
                        UTM Source
                    </label>
                    <input type="text"
                           id="utm_source"
                           list="presetSource"
                           x-model="fields.source"
                           @input="generate()"
                           placeholder="google"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition">
                    <datalist id="presetSource">
                        <option value="google">
                        <option value="facebook">
                        <option value="instagram">
                        <option value="tiktok">
                        <option value="youtube">
                        <option value="linkedin">
                        <option value="x">
                        <option value="email">
                        <option value="whatsapp">
                        <option value="organic">
                    </datalist>
                    <p x-show="warnings.source" x-cloak class="mt-1.5 text-xs text-amber-700 flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                        </svg>
                        <span x-text="warnings.source"></span>
                    </p>
                </div>

                {{-- UTM Medium --}}
                <div>
                    <label for="utm_medium" class="block text-sm font-semibold text-gray-700 mb-2">
                        UTM Medium
                    </label>
                    <input type="text"
                           id="utm_medium"
                           list="presetMedium"
                           x-model="fields.medium"
                           @input="generate()"
                           placeholder="cpc"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition">
                    <datalist id="presetMedium">
                        <option value="cpc">
                        <option value="organic">
                        <option value="social">
                        <option value="email">
                        <option value="referral">
                        <option value="display">
                        <option value="video">
                        <option value="banner">
                        <option value="push">
                    </datalist>
                    <p x-show="warnings.medium" x-cloak class="mt-1.5 text-xs text-amber-700 flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                        </svg>
                        <span x-text="warnings.medium"></span>
                    </p>
                </div>

                {{-- UTM Campaign --}}
                <div class="sm:col-span-2">
                    <label for="utm_campaign" class="block text-sm font-semibold text-gray-700 mb-2">
                        UTM Campaign
                    </label>
                    <input type="text"
                           id="utm_campaign"
                           x-model="fields.campaign"
                           @input="generate()"
                           placeholder="search_promo_q2"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition">
                    <p x-show="warnings.campaign" x-cloak class="mt-1.5 text-xs text-amber-700 flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                        </svg>
                        <span x-text="warnings.campaign"></span>
                    </p>
                </div>

                {{-- UTM Term --}}
                <div>
                    <label for="utm_term" class="block text-sm font-semibold text-gray-700 mb-2">
                        UTM Term <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <input type="text"
                           id="utm_term"
                           x-model="fields.term"
                           @input="generate()"
                           placeholder="jasa_google_ads"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition">
                </div>

                {{-- UTM Content --}}
                <div>
                    <label for="utm_content" class="block text-sm font-semibold text-gray-700 mb-2">
                        UTM Content <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <input type="text"
                           id="utm_content"
                           x-model="fields.content"
                           @input="generate()"
                           placeholder="text_ad_v1"
                           class="block w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-transparent transition">
                </div>

            </div>

            {{-- ValueTrack section --}}
            <div class="mt-8 mb-8 p-5 sm:p-6 bg-gray-50/60 border border-gray-200 rounded-2xl">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">ValueTrack Parameters</h3>
                    <p class="text-xs text-gray-500">Auto-fill data dari Google Ads (campaign ID, keyword, device, dll). Paling cocok dipakai di mode Tracking Template.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <template x-for="(label, key) in valueTrackOptions" :key="key">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <button type="button"
                                    @click="valueTrack[key] = !valueTrack[key]; generate()"
                                    role="switch"
                                    :aria-checked="valueTrack[key]"
                                    :class="valueTrack[key] ? 'bg-brand' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0">
                                <span :style="valueTrack[key] ? 'transform: translateX(1.5rem)' : 'transform: translateX(0.25rem)'"
                                      class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform"></span>
                            </button>
                            <span class="text-xs sm:text-sm text-gray-700" x-text="label"></span>
                        </label>
                    </template>
                </div>

                <p x-show="warnings.valueTrack" x-cloak class="mt-4 text-xs text-amber-700 flex items-start gap-1.5">
                    <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                    </svg>
                    <span x-text="warnings.valueTrack"></span>
                </p>
            </div>

        </div>
        {{-- ============== END FORM ============== --}}

        {{-- Output area --}}
        <div class="max-w-3xl mx-auto bg-gray-50/60 border border-gray-200 rounded-2xl px-5 sm:px-6 py-6 sm:py-7">

            {{-- Output header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                <div>
                    <p class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">
                        Preview URL
                    </p>
                    <p class="text-xs text-gray-500 mt-1" x-show="output" x-cloak>
                        <span x-text="paramCount"></span> parameter aktif
                    </p>
                    <p class="text-xs text-gray-400 mt-1" x-show="!output">
                        Isi Base URL untuk lihat hasil
                    </p>
                </div>

                <div class="flex items-center gap-2" x-show="output" x-cloak>
                    <button type="button"
                            @click="copyToClipboard()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:border-brand hover:text-brand rounded-lg transition shadow-sm">
                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <svg x-show="copied" x-cloak class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-show="!copied">Copy</span>
                        <span x-show="copied" x-cloak class="text-green-600">Copied</span>
                    </button>

                    <button type="button"
                            @click="downloadFile()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand hover:bg-brand-700 rounded-lg transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download TXT
                    </button>
                </div>
            </div>

            {{-- Empty state --}}
            <div x-show="!output"
                 class="bg-white border border-dashed border-gray-200 rounded-xl p-8 sm:p-10 text-center">
                <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                <p class="text-sm text-gray-500 mb-4">
                    Mulai isi Base URL di atas untuk lihat hasil tracking URL
                </p>
                <button type="button"
                        @click="loadSample()"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-brand bg-white border border-brand/30 hover:bg-brand-50 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Load Sample Data
                </button>
            </div>

            {{-- URL Preview --}}
            <div x-show="output"
                 x-cloak
                 class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-5">
                <p class="text-sm font-mono text-gray-900 leading-relaxed break-all" x-text="output"></p>
            </div>

        </div>

    </div>
</section>


{{-- ============== ALPINE LOGIC ============== --}}
@push('scripts')
<script>
function urlBuilder() {
    return {
        mode: 'final',
        fields: {
            baseUrl: '',
            source: '',
            medium: '',
            campaign: '',
            term: '',
            content: '',
        },
        valueTrackOptions: {
            campaignid: '{campaignid}',
            adgroupid: '{adgroupid}',
            matchtype: '{matchtype}',
            keyword: '{keyword}',
            synthetic_keyword: '{synthetic_keyword}',
            device: '{device}',
            creative: '{creative}',
            placement: '{placement}',
        },
        valueTrack: {
            campaignid: false,
            adgroupid: false,
            matchtype: false,
            keyword: false,
            synthetic_keyword: false,
            device: false,
            creative: false,
            placement: false,
        },
        output: '',
        warnings: {
            baseUrl: '',
            source: '',
            medium: '',
            campaign: '',
            valueTrack: '',
        },
        paramCount: 0,
        copied: false,

        init() {
            this.generate();
        },

        loadSample() {
            this.mode = 'final';
            this.fields = {
                baseUrl: 'https://digimaya.com/landing/promo',
                source: 'google',
                medium: 'cpc',
                campaign: 'search_promo_q2',
                term: 'jasa_google_ads',
                content: 'text_ad_v1',
            };
            this.valueTrack = {
                campaignid: true,
                adgroupid: false,
                matchtype: false,
                keyword: true,
                synthetic_keyword: false,
                device: true,
                creative: false,
                placement: false,
            };
            this.generate();
        },

        // STRICT ENCODING (sesuai konvensi tool lama):
        // - Spasi → underscore (_)
        // - ValueTrack tokens {xxx} stay literal (no encode)
        // - Percent-encode untuk !'()*
        // - Skip kalau pure ValueTrack token
        strictEncode(val) {
            const trimmed = (val || '').trim();
            if (!trimmed) return '';
            // Pure ValueTrack token: {xxx}
            if (/^\{[a-z_]+\}$/i.test(trimmed)) return trimmed;
            return encodeURIComponent(trimmed)
                .replace(/%20/g, '_')
                .replace(/[!'()*]/g, c => '%' + c.charCodeAt(0).toString(16).toUpperCase());
        },

        // Lint: check if value violates Google Analytics best practice
        // Rule: lowercase + underscore (no spasi, no uppercase)
        checkUtmFormat(val) {
            if (!val) return '';
            if (/[A-Z]/.test(val)) return 'Sebaiknya pakai huruf kecil semua.';
            if (/\s/.test(val)) return 'Sebaiknya ganti spasi dengan underscore (_).';
            return '';
        },

        checkBaseUrl(val) {
            if (!val) return '';
            if (!/^https?:\/\//i.test(val)) return 'Base URL perlu pakai https:// atau http://.';
            if (/^http:\/\//i.test(val)) return 'Sebaiknya pakai https:// (Google Ads sekarang prefer secure URL).';
            return '';
        },

        anyValueTrackActive() {
            return Object.values(this.valueTrack).some(v => v);
        },

        updateWarnings() {
            this.warnings.baseUrl = this.mode === 'final' ? this.checkBaseUrl(this.fields.baseUrl) : '';
            this.warnings.source = this.checkUtmFormat(this.fields.source);
            this.warnings.medium = this.checkUtmFormat(this.fields.medium);
            this.warnings.campaign = this.checkUtmFormat(this.fields.campaign);

            // ValueTrack hint: kalau aktif di Final URL mode dengan real base URL (bukan {lpurl}),
            // hint user pakai Tracking Template aja
            if (this.mode === 'final' && this.anyValueTrackActive() && this.fields.baseUrl && !this.fields.baseUrl.includes('{lpurl}')) {
                this.warnings.valueTrack = 'Untuk Search campaign, sebaiknya pakai mode Tracking Template biar ValueTrack auto-resolve.';
            } else {
                this.warnings.valueTrack = '';
            }
        },

        generate() {
            this.updateWarnings();

            // Base URL: Tracking Template mode pakai {lpurl}, Final URL pakai input
            const base = this.mode === 'tracking' ? '{lpurl}' : (this.fields.baseUrl || '').trim();

            if (!base) {
                this.output = '';
                this.paramCount = 0;
                return;
            }

            const params = [];

            // UTM params
            const utmMap = [
                ['utm_source', this.fields.source],
                ['utm_medium', this.fields.medium],
                ['utm_campaign', this.fields.campaign],
                ['utm_term', this.fields.term],
                ['utm_content', this.fields.content],
            ];
            utmMap.forEach(([key, val]) => {
                if (val && val.trim()) {
                    params.push(key + '=' + this.strictEncode(val));
                }
            });

            // ValueTrack params (literal, no encode)
            Object.keys(this.valueTrack).forEach(key => {
                if (this.valueTrack[key]) {
                    params.push(key + '={' + key + '}');
                }
            });

            this.paramCount = params.length;

            if (params.length === 0) {
                this.output = base;
                return;
            }

            const separator = base.includes('?') ? '&' : '?';
            this.output = base + separator + params.join('&');
        },

        copyToClipboard() {
            if (!this.output) return;

            navigator.clipboard.writeText(this.output).then(() => {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2000);
            }).catch(err => {
                // Fallback for older browsers
                const ta = document.createElement('textarea');
                ta.value = this.output;
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

        downloadFile() {
            if (!this.output) return;

            const timestamp = new Date().toISOString().slice(0, 10);
            const blob = new Blob([this.output], { type: 'text/plain;charset=utf-8;' });
            const url = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = url;
            link.download = `url-builder-${timestamp}.txt`;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },
    };
}
</script>
@endpush

@endsection
