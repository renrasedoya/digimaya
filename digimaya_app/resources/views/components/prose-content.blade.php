{{--
    <x-prose-content> — renders rich-text/HTML content with consistent typography styling.

    Usage:
        <x-prose-content>
            {!! $post->content !!}
        </x-prose-content>

    Optionally pass extra classes via the $class attribute (default base size: text-base lg:text-lg).
        <x-prose-content class="text-sm">...</x-prose-content>

    Replicates the styling previously defined in <style> blocks at:
    - resources/views/public/blog/show.blade.php
    - resources/views/admin/blog/posts/show.blade.php

    Visual style: spacious public-style (line-height 1.75, brand-blue links, bold strong).
--}}

@props([
    'class' => 'text-base lg:text-lg',
])

<div {{ $attributes->merge(['class' =>
    $class . ' ' .
    'leading-[1.75] text-gray-800 ' .
    '[&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-gray-900 [&_h2]:mt-8 [&_h2]:mb-6 [&_h2]:leading-tight ' .
    '[&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-gray-900 [&_h3]:mt-6 [&_h3]:mb-5 [&_h3]:leading-snug ' .
    '[&_p]:mb-5 ' .
    '[&_a]:text-brand [&_a]:underline hover:[&_a]:text-brand-600 ' .
    '[&_strong]:font-bold [&_strong]:text-gray-900 [&_b]:font-bold [&_b]:text-gray-900 ' .
    '[&_em]:italic [&_i]:italic ' .
    '[&_ul]:mb-5 [&_ul]:pl-6 [&_ul]:list-disc ' .
    '[&_ol]:mb-5 [&_ol]:pl-6 [&_ol]:list-decimal ' .
    '[&_li]:mb-2 ' .
    '[&_li>p]:mb-2'
]) }}>
    {{ $slot }}
</div>
