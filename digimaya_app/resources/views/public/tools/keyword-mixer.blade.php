@extends('layouts.public')

@section('meta_title', 'Keyword Mixer — Generate Kombinasi Keyword Google Ads | Digimaya')
@section('meta_description', 'Tool gratis untuk generate kombinasi keyword Google Ads dari multiple seed list. Hasilnya siap copy/download untuk Google Ads Editor. Hemat waktu riset keyword berjam-jam.')

@section('content')


{{-- ============== TOOL WIDGET (only section) ============== --}}
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16"
         x-data="keywordMixer()"
         x-init="init()">

        {{-- Center hero with inline sample link --}}
        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-5 tracking-tight leading-[1.1]">
                Keyword Mixer
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                Generate kombinasi keyword Google Ads dengan cepat. Klik
                <button type="button"
                        @click="loadSample()"
                        class="text-brand hover:text-brand-700 font-semibold underline underline-offset-2 transition">load sample data</button>
                untuk lihat contoh hasilnya.
            </p>
        </div>

        {{-- Add Box button (subtle, white with border) --}}
        <div class="flex justify-end mb-4">
            <button type="button"
                    @click="addBox()"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:border-brand hover:text-brand rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Box
            </button>
        </div>

        {{-- Boxes grid --}}
        <div class="grid gap-4 sm:gap-5 mb-8"
             :class="{
                'grid-cols-1 md:grid-cols-2 lg:grid-cols-3': boxes.length <= 3,
                'grid-cols-1 md:grid-cols-2 lg:grid-cols-4': boxes.length === 4,
                'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5': boxes.length >= 5,
             }">

            <template x-for="(box, idx) in boxes" :key="box.id">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    {{-- Box header (solid dark) --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-700">
                        <span class="text-sm font-semibold text-white" x-text="box.label"></span>
                        <button type="button"
                                @click="removeBox(idx)"
                                x-show="boxes.length > 1"
                                class="text-gray-300 hover:text-white transition"
                                :aria-label="`Hapus ${box.label}`">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Textarea — auto-normalize on blur --}}
                    <textarea x-model="box.text"
                              @input="generate()"
                              @blur="normalizeBox(idx)"
                              placeholder="Satu keyword per baris..."
                              rows="7"
                              class="block w-full px-4 py-3 text-sm bg-white border-0 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-inset transition resize-y leading-relaxed"></textarea>
                </div>
            </template>

        </div>

        {{-- Match types — toggle switch, center --}}
        <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 mb-10 lg:mb-12">

            <label class="inline-flex items-center gap-3 cursor-pointer">
                <button type="button"
                        @click="matchTypes.exact = !matchTypes.exact; generate()"
                        role="switch"
                        :aria-checked="matchTypes.exact"
                        :class="matchTypes.exact ? 'bg-brand' : 'bg-gray-200'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                    <span :style="matchTypes.exact ? 'transform: translateX(1.5rem)' : 'transform: translateX(0.25rem)'"
                          class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Exact</span>
            </label>

            <label class="inline-flex items-center gap-3 cursor-pointer">
                <button type="button"
                        @click="matchTypes.phrase = !matchTypes.phrase; generate()"
                        role="switch"
                        :aria-checked="matchTypes.phrase"
                        :class="matchTypes.phrase ? 'bg-brand' : 'bg-gray-200'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                    <span :style="matchTypes.phrase ? 'transform: translateX(1.5rem)' : 'transform: translateX(0.25rem)'"
                          class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Phrase</span>
            </label>

            <label class="inline-flex items-center gap-3 cursor-pointer">
                <button type="button"
                        @click="matchTypes.broad = !matchTypes.broad; generate()"
                        role="switch"
                        :aria-checked="matchTypes.broad"
                        :class="matchTypes.broad ? 'bg-brand' : 'bg-gray-200'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                    <span :style="matchTypes.broad ? 'transform: translateX(1.5rem)' : 'transform: translateX(0.25rem)'"
                          class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Broad</span>
            </label>

        </div>

        {{-- Output area (subtle background) --}}
        <div class="bg-gray-50/60 border border-gray-200 rounded-2xl px-5 sm:px-6 py-6 sm:py-7">

            {{-- Output header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                <div>
                    <p class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">
                        <span x-text="output.length"></span> Keywords Generated
                    </p>
                    <p class="text-xs text-gray-500 mt-1" x-show="output.length > 0">
                        Dari <span x-text="boxes.filter(b => parseKeywords(b.text).length > 0).length"></span> box · <span x-text="selectedMatchCount()"></span> match type
                    </p>
                    <p class="text-xs text-gray-400 mt-1" x-show="output.length === 0">
                        Isi keyword untuk lihat hasil
                    </p>
                </div>

                <div class="flex items-center gap-2" x-show="output.length > 0" x-cloak>

                    {{-- View toggle --}}
                    <div class="inline-flex bg-white border border-gray-200 rounded-lg p-1">
                        <button type="button"
                                @click="viewMode = 'list'"
                                :class="viewMode === 'list' ? 'bg-brand text-white' : 'text-gray-600 hover:text-gray-900'"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold rounded transition"
                                aria-label="List view">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <button type="button"
                                @click="viewMode = 'table'"
                                :class="viewMode === 'table' ? 'bg-brand text-white' : 'text-gray-600 hover:text-gray-900'"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold rounded transition"
                                aria-label="Table view">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>

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
                        <span x-text="viewMode === 'table' ? 'Download CSV' : 'Download TXT'"></span>
                    </button>
                </div>
            </div>

            {{-- Empty state --}}
            <div x-show="output.length === 0"
                 class="bg-white border border-dashed border-gray-200 rounded-xl p-10 text-center">
                <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm text-gray-500 mb-4">
                    Mulai isi keyword di box-box di atas untuk lihat hasil kombinasi
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

            {{-- LIST VIEW --}}
            <div x-show="output.length > 0 && viewMode === 'list'"
                 x-cloak
                 class="max-h-96 overflow-y-auto bg-white rounded-xl border border-gray-200 shadow-sm">
                <ul class="divide-y divide-gray-100">
                    <template x-for="(item, idx) in output" :key="idx">
                        <li class="px-4 sm:px-5 py-3 text-sm text-gray-900 leading-relaxed hover:bg-gray-50/50 transition">
                            <span x-text="item.formatted"></span>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- TABLE VIEW --}}
            <div x-show="output.length > 0 && viewMode === 'table'"
                 x-cloak
                 class="max-h-96 overflow-y-auto rounded-xl border border-gray-200 shadow-sm bg-white">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-200">
                        <tr>
                            <th class="px-4 sm:px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Keyword</th>
                            <th class="px-4 sm:px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap w-40">Criterion Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, idx) in output" :key="idx">
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 sm:px-5 py-3 text-gray-900 leading-relaxed" x-text="item.raw"></td>
                                <td class="px-4 sm:px-5 py-3 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold"
                                          :class="{
                                            'bg-blue-50 text-blue-700': item.matchType === 'Exact',
                                            'bg-purple-50 text-purple-700': item.matchType === 'Phrase',
                                            'bg-green-50 text-green-700': item.matchType === 'Broad',
                                          }"
                                          x-text="item.matchType"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</section>


{{-- ============== ALPINE LOGIC ============== --}}
@push('scripts')
<script>
function keywordMixer() {
    return {
        boxes: [
            { id: 1, label: 'Buyer Keywords', text: '' },
            { id: 2, label: 'Your Products', text: '' },
            { id: 3, label: 'Product Details', text: '' },
        ],
        nextId: 4,
        matchTypes: {
            exact: false,
            phrase: false,
            broad: true,
        },
        viewMode: 'list',
        output: [],
        copied: false,

        init() {
            this.generate();
        },

        addBox() {
            const newNum = this.boxes.length + 1;
            this.boxes.push({
                id: this.nextId++,
                label: `Box ${newNum}`,
                text: ''
            });
        },

        removeBox(idx) {
            if (this.boxes.length <= 1) return;
            this.boxes.splice(idx, 1);
            this.generate();
        },

        loadSample() {
            this.boxes = [
                { id: 1, label: 'Buyer Keywords', text: 'beli\njual\nharga' },
                { id: 2, label: 'Your Products', text: 'meja tamu\nkursi sofa\nlemari' },
                { id: 3, label: 'Product Details', text: 'minimalis\nmodern\nkayu jati' },
            ];
            this.nextId = 4;
            this.matchTypes = {
                exact: false,
                phrase: false,
                broad: true,
            };
            this.generate();
        },

        // CENTRALIZED NORMALIZATION
        // Rules: split newline → trim → collapse multiple spaces → lowercase → filter empty
        parseKeywords(text) {
            if (!text) return [];
            return text
                .split('\n')
                .map(line => line.trim().replace(/\s+/g, ' ').toLowerCase())
                .filter(Boolean);
        },

        // Auto-normalize textarea content on blur
        normalizeBox(idx) {
            const cleaned = this.parseKeywords(this.boxes[idx].text);
            this.boxes[idx].text = cleaned.join('\n');
            this.generate();
        },

        selectedMatchCount() {
            return Object.values(this.matchTypes).filter(v => v).length;
        },

        cartesian(arrays) {
            return arrays.reduce(
                (acc, curr) => acc.flatMap(a => curr.map(c => [...a, c])),
                [[]]
            );
        },

        generate() {
            const arrays = this.boxes
                .map(box => this.parseKeywords(box.text))
                .filter(arr => arr.length > 0);

            if (arrays.length === 0) {
                this.output = [];
                return;
            }

            const combinations = this.cartesian(arrays).map(combo => combo.join(' '));

            const result = [];
            combinations.forEach(kw => {
                if (this.matchTypes.exact) {
                    result.push({ raw: kw, matchType: 'Exact', formatted: `[${kw}]` });
                }
                if (this.matchTypes.phrase) {
                    result.push({ raw: kw, matchType: 'Phrase', formatted: `"${kw}"` });
                }
                if (this.matchTypes.broad) {
                    result.push({ raw: kw, matchType: 'Broad', formatted: kw });
                }
            });

            this.output = result;
        },

        // Copy adapts to view mode:
        // - List view: formatted keyword (one per line)
        // - Table view: TSV with header "Keyword\tCriterion Type" — paste-friendly to Excel/Sheets
        copyToClipboard() {
            if (this.output.length === 0) return;

            let text;
            if (this.viewMode === 'table') {
                const header = 'Keyword\tCriterion Type';
                const rows = this.output.map(item => `${item.raw}\t${item.matchType}`);
                text = [header, ...rows].join('\n');
            } else {
                text = this.output.map(item => item.formatted).join('\n');
            }

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

        // Download adapts to view mode:
        // - List view: .txt file (formatted keyword, 1 per line)
        // - Table view: .csv file (Google Ads Editor format - Keyword,Criterion Type)
        downloadFile() {
            if (this.output.length === 0) return;

            const timestamp = new Date().toISOString().slice(0, 10);
            let content, mimeType, extension;

            if (this.viewMode === 'table') {
                // CSV format for Google Ads Editor
                const header = 'Keyword,Criterion Type\n';
                const rows = this.output.map(item => {
                    const kw = `"${item.raw.replace(/"/g, '""')}"`;
                    return `${kw},${item.matchType}`;
                }).join('\n');

                content = header + rows;
                mimeType = 'text/csv;charset=utf-8;';
                extension = 'csv';
            } else {
                // Plain text: formatted keyword 1 per line
                content = this.output.map(item => item.formatted).join('\n');
                mimeType = 'text/plain;charset=utf-8;';
                extension = 'txt';
            }

            const blob = new Blob([content], { type: mimeType });
            const url = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = url;
            link.download = `keyword-mixer-${timestamp}.${extension}`;

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