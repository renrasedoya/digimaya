@extends('layouts.public')

{{-- ============== SEO ============== --}}
@section('meta_title', $activeCategory ? 'Kategori: ' . $activeCategory->name . ' | Blog Digimaya' : 'Blog Digimaya')
@section('meta_description', 'Insight, strategi, dan praktik terbaik Google Ads untuk bisnis di Indonesia.')

@section('content')

{{-- ============== HERO SECTION (only when no filter active) ============== --}}
@if (! $hasFilter && $featured)
    @php
        $featuredCat   = $featured->categories->first();
        $featuredDate  = $featured->published_at ? $featured->published_at->translatedFormat('d M Y') : '';
        $featuredRead  = max(1, (int) ceil(str_word_count(strip_tags($featured->content ?? '')) / 200));
    @endphp

    <section class="bg-gradient-to-b from-brand-50/40 to-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">

                {{-- Featured image --}}
                <a href="{{ $featured->permalink }}" class="block aspect-video bg-gray-100 rounded-2xl overflow-hidden group">
                    @if ($featured->thumbnail_type === 'video')
                        <img src="{{ $featured->thumbnail_url }}"
                             alt="{{ $featured->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                             onerror="this.onerror=null; this.src='{{ $featured->thumbnail_fallback_url }}';">
                    @else
                        <div class="w-full h-full flex items-center justify-center p-8"
                             style="background-color: {{ $featured->thumbnail_color }};">
                            <span class="text-white text-2xl font-bold leading-snug line-clamp-3 text-center">
                                {{ $featured->thumbnail_text }}
                            </span>
                        </div>
                    @endif
                </a>

                {{-- Featured content --}}
                <div>
                    <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-brand rounded-full mb-4">
                        Featured
                    </span>

                    @if ($featuredCat)
                        <a href="{{ route('public.blog.index', ['category' => $featuredCat->slug]) }}"
                           class="inline-block ml-2 mb-4 px-3 py-1 text-xs font-semibold text-brand bg-brand-50 hover:bg-brand-100 rounded-full transition">
                            {{ $featuredCat->name }}
                        </a>
                    @endif

                    <h1 class="heading-page-lg mb-4">
                        <a href="{{ $featured->permalink }}" class="hover:text-brand transition">
                            {{ $featured->title }}
                        </a>
                    </h1>

                    <p class="body-card-large mb-6 line-clamp-3">
                        {{ \Illuminate\Support\Str::limit(strip_tags($featured->content ?? ''), 220) }}
                    </p>

                    <div class="micro-text flex items-center gap-2 mb-6">
                        <span>{{ $featuredDate }}</span>
                        <span class="text-gray-300">&bull;</span>
                        <span>{{ $featuredRead }} min read</span>
                    </div>

                    <a href="{{ $featured->permalink }}"
                       class="btn-outline btn-sm">
                        Read more
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- ============== PAGE HEADER (when filter active, no hero) ============== --}}
@if ($hasFilter)
    <section class="border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="heading-page">
                @if ($activeCategory)
                    Kategori: {{ $activeCategory->name }}
                @elseif ($search !== '')
                    Hasil pencarian: <span class="text-brand">{{ $search }}</span>
                @endif
            </h1>
            <div class="mt-2 flex items-center gap-3 text-sm text-gray-500 flex-wrap">
                <span>{{ $posts->total() }} post ditemukan</span>
                <span class="text-gray-300">&bull;</span>
                <a href="{{ route('public.blog.index') }}" class="text-brand hover:underline">
                    Reset filter
                </a>
            </div>
        </div>
    </section>
@endif

{{-- ============== GRID SECTION ============== --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if ($posts->isEmpty())
        {{-- Empty state --}}
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="heading-empty-state mb-1">Belum ada post</h3>
            <p class="micro-text">
                @if ($hasFilter)
                    Coba ganti filter atau kata kunci pencarian.
                @else
                    Post akan muncul di sini setelah dipublikasikan.
                @endif
            </p>
        </div>
    @else
        @if (! $hasFilter)
            <h2 class="heading-sub mb-8">Latest posts</h2>
        @endif

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                @include('public.blog._post_card', ['card' => $post])
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($posts->hasPages())
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @endif
    @endif
</section>

@endsection