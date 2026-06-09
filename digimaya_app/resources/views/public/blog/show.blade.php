@extends('layouts.public')

{{-- ============== SEO ============== --}}
@php
    $primaryCat   = $post->categories->first();
    $authorName   = $post->author->name ?? 'Digimaya';
    $publishedISO = $post->published_at ? $post->published_at->toIso8601String() : '';
    $publishedHuman = $post->published_at ? $post->published_at->translatedFormat('d M Y') : '';
    $readMin      = max(1, (int) ceil(str_word_count(strip_tags($post->content ?? '')) / 200));
    $authorInitial = strtoupper(mb_substr($authorName, 0, 1));
    // Author avatar deterministic color from name (8 brand-friendly options)
    $colorPool = ['#165DFF', '#0E47CC', '#7C3AED', '#DB2777', '#EA580C', '#16A34A', '#0891B2', '#CA8A04'];
    $authorColor = $colorPool[crc32($authorName) % count($colorPool)];
@endphp

@section('meta_title', ($post->meta_title ?: $post->title) . ' | Blog Digimaya')
@section('meta_description', $metaDescription)
@section('canonical', $post->permalink)

@section('og_type', 'article')
@section('og_title', $post->title)
@section('og_description', $metaDescription)
@section('og_url', $post->permalink)
@if ($post->thumbnail_url)
    @section('og_image', $post->thumbnail_url)
@endif

@if ($publishedISO)
    @section('article_published_time', $publishedISO)
@endif
@section('article_author', $authorName)


@section('content')

{{-- ============== BREADCRUMB ============== --}}
<nav class="border-b border-gray-100" aria-label="Breadcrumb">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <ol class="flex items-center gap-2 text-sm text-gray-500 flex-wrap">
            <li>
                <a href="https://digimaya.com" class="hover:text-brand transition">Home</a>
            </li>
            <li class="text-gray-300">/</li>
            <li>
                <a href="{{ route('public.blog.index') }}" class="hover:text-brand transition">Blog</a>
            </li>
            @if ($primaryCat)
                <li class="text-gray-300">/</li>
                <li>
                    <a href="{{ route('public.blog.index', ['category' => $primaryCat->slug]) }}"
                       class="hover:text-brand transition">
                        {{ $primaryCat->name }}
                    </a>
                </li>
            @endif
            <li class="text-gray-300">/</li>
            <li class="text-gray-900 font-medium truncate max-w-xs">{{ $post->title }}</li>
        </ol>
    </div>
</nav>

{{-- ============== ARTICLE ============== --}}
<article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">

    {{-- Categories (above title) --}}
    @if ($post->categories->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-5">
            @foreach ($post->categories as $cat)
                <a href="{{ route('public.blog.index', ['category' => $cat->slug]) }}"
                   class="inline-block px-3 py-1 text-xs font-semibold text-brand bg-brand-50 hover:bg-brand-100 rounded-full transition">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Title --}}
    <h1 class="text-3xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
        {{ $post->title }}
    </h1>

    {{-- Author + meta --}}
    <div class="flex items-center gap-3 pb-6 mb-8 border-b border-gray-100">
        <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-base flex-shrink-0"
             style="background-color: {{ $authorColor }};">
            {{ $authorInitial }}
        </div>
        <div>
            <div class="text-sm font-semibold text-gray-900">{{ $authorName }}</div>
            <div class="micro-text flex items-center gap-2">
                <span>{{ $publishedHuman }}</span>
                <span class="text-gray-300">&bull;</span>
                <span>{{ $readMin }} min read</span>
            </div>
        </div>
    </div>

    {{-- YouTube embed (if exists) --}}
    @if ($post->youtube_video_id)
        <div class="mb-10 aspect-video rounded-2xl overflow-hidden bg-black">
            <iframe src="https://www.youtube.com/embed/{{ $post->youtube_video_id }}"
                    title="{{ $post->title }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    class="w-full h-full"></iframe>
        </div>
    @endif

    {{-- Content (sanitized) --}}
    <x-prose-content class="body-text">
        {!! $sanitizedContent !!}
    </x-prose-content>

</article>

{{-- ============== RELATED POSTS ============== --}}
@if ($related->isNotEmpty())
    <section class="border-t border-gray-100 bg-gray-50/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Related posts</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($related as $rel)
                    @include('public.blog._post_card', ['card' => $rel])
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection