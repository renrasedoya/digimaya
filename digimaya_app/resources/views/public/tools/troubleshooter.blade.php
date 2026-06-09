@extends('layouts.public')

@section('meta_title', 'Google Ads Troubleshooter — Solusi Masalah Iklan Google | Digimaya')
@section('meta_description', 'Cari solusi cepat untuk masalah Google Ads kamu. Pilih kategori masalah, drill-down ke penyebab spesifik, dan dapatkan solusi yang actionable.')

@section('content')

<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16"
         x-data="troubleshooterWidget()"
         x-init="init()">

        {{-- Hero --}}
        <div class="max-w-3xl mx-auto text-center mb-10 lg:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-5 tracking-tight leading-[1.1]">
                Google Ads Troubleshooter
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                Cari solusi cepat untuk masalah Google Ads kamu. Pilih kategori, drill-down ke penyebab, dapat solusi langsung.
            </p>
        </div>

        {{-- Breadcrumb (Apple-style minimalist) --}}
        <div class="max-w-3xl mx-auto mb-6" x-show="selectedNodeId !== null" x-cloak>
            <nav class="flex items-center text-sm text-gray-500 flex-wrap">
                <button type="button"
                        @click="goHome()"
                        class="hover:text-gray-900 transition">Kategori Masalah</button>
                <template x-for="(crumb, idx) in breadcrumb" :key="crumb.id">
                    <span class="flex items-center">
                        <svg class="w-3.5 h-5 mx-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <template x-if="idx === breadcrumb.length - 1">
                            <span x-text="crumb.label"></span>
                        </template>
                        <template x-if="idx !== breadcrumb.length - 1">
                            <button type="button"
                                    @click="navigateTo(crumb.id)"
                                    class="hover:text-gray-900 transition"
                                    x-text="crumb.label"></button>
                        </template>
                    </span>
                </template>
            </nav>
        </div>

        {{-- Main content --}}
        <div class="max-w-3xl mx-auto">

            {{-- ROOT VIEW: Render root problems --}}
            <template x-if="selectedNodeId === null">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6">
                        Apa masalah yang kamu alami?
                    </h2>
                    <div class="space-y-3">
                        <template x-for="node in rootNodes()" :key="node.id">
                            <button type="button"
                                    @click="navigateTo(node.id)"
                                    class="w-full flex items-center justify-between gap-3 px-5 py-4 bg-white border border-gray-200 rounded-lg hover:border-brand hover:shadow-sm transition text-left group">
                                <span class="text-base font-semibold text-gray-900 group-hover:text-brand transition" x-text="node.label"></span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>
            </template>

            {{-- QUESTION VIEW: Render children sebagai clickable options --}}
            <template x-if="selectedNode && selectedNode.type === 'question'">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2" x-text="selectedNode.label"></h2>
                    <p class="text-gray-600 mb-6">Pilih yang paling cocok dengan masalah kamu:</p>
                    <div class="space-y-3">
                        <template x-for="child in childrenOf(selectedNodeId)" :key="child.id">
                            <button type="button"
                                    @click="navigateTo(child.id)"
                                    class="w-full flex items-center justify-between gap-3 px-5 py-4 bg-white border border-gray-200 rounded-lg hover:border-brand hover:shadow-sm transition text-left group">
                                <span class="text-base font-semibold text-gray-900 group-hover:text-brand transition" x-text="child.label"></span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </template>

                        {{-- Empty state: question without children --}}
                        <template x-if="childrenOf(selectedNodeId).length === 0">
                            <div class="text-center py-12 px-4 bg-gray-50 rounded-lg">
                                <p class="text-gray-600 mb-2">Konten untuk kategori ini sedang disiapkan.</p>
                                <button type="button" @click="goBack()" class="text-brand font-semibold hover:text-brand-700 transition">← Kembali ke menu sebelumnya</button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- LEAF VIEW: Render answers + videos --}}
            <template x-if="selectedNode && selectedNode.type === 'leaf'">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6" x-text="selectedNode.label"></h2>

                    {{-- Answers --}}
                    <template x-if="selectedNode.answers && selectedNode.answers.length > 0">
                        <div class="space-y-4 mb-8">
                            <template x-for="(answer, idx) in selectedNode.answers" :key="idx">
                                <div class="border border-gray-200 rounded-lg p-5 bg-white">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3" x-text="'Jawaban ' + (idx + 1)"></div>

                                    <div x-show="answer.cause" class="mb-4">
                                        <div class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1.5">Kemungkinan Penyebab</div>
                                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap" x-text="answer.cause"></p>
                                    </div>

                                    <div x-show="answer.solution">
                                        <div class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-1.5">Pendekatan Solusi</div>
                                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap" x-text="answer.solution"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Videos --}}
                    <template x-if="selectedNode.videos && selectedNode.videos.length > 0">
                        <div class="mb-8">
                            <h3 class="text-base font-bold text-gray-900 mb-4">Video Tutorial</h3>

                            {{-- Inline Player (visible saat video selected) --}}
                            <div x-show="activeVideoId" x-cloak x-transition class="mb-4 relative">
                                <div class="aspect-video w-full rounded-lg overflow-hidden bg-black shadow-md">
                                    <template x-if="activeVideoId">
                                        <iframe :src="'https://www.youtube.com/embed/' + activeVideoId + '?autoplay=1&rel=0'"
                                                title="Video Tutorial"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen
                                                class="w-full h-full"></iframe>
                                    </template>
                                </div>
                                <button type="button"
                                        @click="activeVideoId = null"
                                        title="Tutup video"
                                        class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center rounded-full bg-black/60 hover:bg-black/80 text-white transition backdrop-blur-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Thumbnails grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <template x-for="(video, idx) in selectedNode.videos" :key="idx">
                                    <button type="button"
                                            x-show="activeVideoId !== video.youtube_id"
                                            x-cloak
                                            @click="activeVideoId = video.youtube_id"
                                            class="block w-full aspect-video rounded-lg overflow-hidden bg-gray-100 relative group cursor-pointer">
                                        <img :src="'https://img.youtube.com/vi/' + video.youtube_id + '/mqdefault.jpg'"
                                             :alt="'Video ' + (idx + 1)"
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition flex items-center justify-center">
                                            <svg class="w-12 h-12 text-white opacity-90 group-hover:scale-110 transition" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Empty state --}}
                    <template x-if="(!selectedNode.answers || selectedNode.answers.length === 0) && (!selectedNode.videos || selectedNode.videos.length === 0)">
                        <div class="text-center py-12 px-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 mb-2">Konten untuk topik ini sedang disiapkan.</p>
                            <button type="button" @click="goBack()" class="text-brand font-semibold hover:text-brand-700 transition">← Kembali</button>
                        </div>
                    </template>


                </div>
            </template>

        </div>
    </div>
</section>

@push('scripts')
<script>
    function troubleshooterWidget() {
        return {
            nodes: @json($nodes),
            selectedNodeId: null,
            activeVideoId: null,

            init() {
                // Read URL ?node=X on load
                const params = new URLSearchParams(window.location.search);
                const nodeId = parseInt(params.get('node'), 10);
                if (nodeId && this.nodes.find(n => n.id === nodeId)) {
                    this.selectedNodeId = nodeId;
                }

                // Handle browser back/forward
                window.addEventListener('popstate', (e) => {
                    const params = new URLSearchParams(window.location.search);
                    const nodeId = parseInt(params.get('node'), 10);
                    this.selectedNodeId = (nodeId && this.nodes.find(n => n.id === nodeId)) ? nodeId : null;
                });
            },

            get selectedNode() {
                if (!this.selectedNodeId) return null;
                return this.nodes.find(n => n.id === this.selectedNodeId);
            },

            get breadcrumb() {
                if (!this.selectedNodeId) return [];
                const path = [];
                let current = this.nodes.find(n => n.id === this.selectedNodeId);
                while (current) {
                    path.unshift({ id: current.id, label: current.label });
                    current = current.parent_id ? this.nodes.find(n => n.id === current.parent_id) : null;
                }
                return path;
            },

            rootNodes() {
                return this.nodes.filter(n => n.parent_id === null);
            },

            childrenOf(parentId) {
                return this.nodes.filter(n => n.parent_id === parentId);
            },

            navigateTo(nodeId) {
                this.selectedNodeId = nodeId;
                this.activeVideoId = null;
                this.updateUrl();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            goBack() {
                const node = this.selectedNode;
                if (!node) return;
                if (node.parent_id) {
                    this.selectedNodeId = node.parent_id;
                } else {
                    this.selectedNodeId = null;
                }
                this.updateUrl();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            goHome() {
                this.selectedNodeId = null;
                this.updateUrl();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            updateUrl() {
                const url = new URL(window.location);
                if (this.selectedNodeId) {
                    url.searchParams.set('node', this.selectedNodeId);
                } else {
                    url.searchParams.delete('node');
                }
                window.history.pushState({}, '', url);
            },
        };
    }
</script>
@endpush

@endsection
