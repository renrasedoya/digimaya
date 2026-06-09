{{--
    Reusable post card.
    Required: $card  (BlogPost instance)

    Used in:
    - public/blog/index.blade.php (grid section)
    - public/blog/show.blade.php (related posts section)
--}}

@php
    $thumb         = $card->thumbnail_url;
    $primaryCat    = $card->categories->first();
    $publishedDate = $card->published_at ? $card->published_at->translatedFormat('d M Y') : '';
    $readMin       = max(1, (int) ceil(str_word_count(strip_tags($card->content ?? '')) / 200));
@endphp

<article class="group flex flex-col bg-white rounded-xl border border-gray-100 hover:border-brand-100 hover:shadow-md transition overflow-hidden">

    {{-- Thumbnail --}}
    <a href="{{ $card->permalink }}" class="block aspect-video bg-gray-100 overflow-hidden">
        @if ($card->thumbnail_type === 'video')
            <img src="{{ $card->thumbnail_url }}"
                 alt="{{ $card->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                 loading="lazy"
                 onerror="this.onerror=null; this.src='{{ $card->thumbnail_fallback_url }}';">
        @else
            <div class="w-full h-full flex items-center justify-center p-5"
                 style="background-color: {{ $card->thumbnail_color }};">
                <span class="text-white text-lg font-bold leading-snug line-clamp-3 text-center">
                    {{ $card->thumbnail_text }}
                </span>
            </div>
        @endif
    </a>

    {{-- Body --}}
    <div class="flex-1 flex flex-col p-5">

        {{-- Category badge --}}
        @if ($primaryCat)
            <a href="{{ route('public.blog.index', ['category' => $primaryCat->slug]) }}"
               class="inline-flex self-start mb-3 px-2.5 py-1 text-xs font-semibold text-brand bg-brand-50 hover:bg-brand-100 rounded-full transition">
                {{ $primaryCat->name }}
            </a>
        @endif

        {{-- Title --}}
        <h3 class="text-lg font-bold text-gray-900 leading-snug mb-2 group-hover:text-brand transition">
            <a href="{{ $card->permalink }}">
                {{ $card->title }}
            </a>
        </h3>

        {{-- Excerpt --}}
        <p class="body-card line-clamp-2 mb-4">
            {{ \Illuminate\Support\Str::limit(strip_tags($card->content ?? ''), 120) }}
        </p>

        {{-- Meta row --}}
        <div class="mt-auto flex items-center gap-2 text-xs text-gray-500">
            <span>{{ $publishedDate }}</span>
            <span class="text-gray-300">&bull;</span>
            <span>{{ $readMin }} min read</span>
        </div>
    </div>
</article>
