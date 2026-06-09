<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="theme-color" content="{{ config('digimaya.brand.theme_color', '#165DFF') }}">

    {{-- ============== SEO META ============== --}}
    <title>@yield('meta_title', 'Digimaya — Google Ads Agency untuk Bisnis Indonesia')</title>
    <meta name="description" content="@yield('meta_description', 'Digimaya — agency Google Ads yang membantu bisnis Indonesia menumbuhkan revenue lewat iklan terukur, tracking presisi, dan strategi yang transparan.')">

    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'Digimaya — Google Ads Agency untuk Bisnis Indonesia')">
    <meta property="og:description" content="@yield('og_description', 'Digimaya — agency Google Ads yang membantu bisnis Indonesia menumbuhkan revenue lewat iklan terukur, tracking presisi, dan strategi yang transparan.')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/logo/logo-blue.png'))">
    <meta property="og:image:width" content="{{ config('digimaya.seo.og_image_w', 1200) }}">
    <meta property="og:image:height" content="{{ config('digimaya.seo.og_image_h', 630) }}">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Digimaya — Google Ads Agency Indonesia')">
    <meta property="og:site_name" content="{{ config('digimaya.seo.site_name', 'Digimaya') }}">
    <meta property="og:locale" content="{{ config('digimaya.seo.locale', 'id_ID') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Digimaya — Google Ads Agency untuk Bisnis Indonesia')">
    <meta name="twitter:description" content="@yield('og_description', 'Digimaya — agency Google Ads yang membantu bisnis Indonesia menumbuhkan revenue lewat iklan terukur, tracking presisi, dan strategi yang transparan.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/logo/logo-blue.png'))">

    @hasSection('article_published_time')
        <meta property="article:published_time" content="@yield('article_published_time')">
    @endif
    @hasSection('article_author')
        <meta property="article:author" content="@yield('article_author')">
    @endif



    {{-- Tailwind compiled --}}
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ filemtime(public_path('css/tailwind.css')) }}">

    {{-- Inter font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Schema JSON-LD injection point (per-page schema components push here) --}}
    @stack('head_schema')

    @stack('styles')
</head>
<body class="bg-white text-gray-900 antialiased font-sans">

@php
    // Resolve categories for Blog dropdown — used in header on every public page.
    $navCategories = \App\Models\BlogCategory::orderBy('name')->get();
    $activeCatSlug = request()->query('category');
@endphp

{{-- ============== HEADER ============== --}}
<header class="border-b border-gray-100 bg-white sticky top-0 z-40"
        x-data="{ openDropdown: null, openSearch: false, openMobile: false }"
        @keydown.escape.window="openDropdown = null; openSearch = false; openMobile = false">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
                <img src="{{ asset('images/logo/logo-blue.png') }}" alt="Digimaya" class="h-7 w-auto">
            </a>

            {{-- ===== DESKTOP NAV ===== --}}
            <nav class="hidden md:flex items-center gap-1">

                {{-- Services dropdown --}}
                <div class="relative" @click.outside="if (openDropdown === 'services') openDropdown = null">
                    <button type="button"
                            @click="openDropdown = openDropdown === 'services' ? null : 'services'"
                            class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium {{ request()->routeIs('public.services.*') ? 'text-brand' : 'text-gray-600 hover:text-brand' }} transition">
                        Services
                        <span class="text-sm inline-block">&#9662;</span>
                    </button>

                    <div x-show="openDropdown === 'services'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute left-0 mt-2 w-64 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden">

                        <a href="{{ route('public.services.management') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.services.management') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Google Ads Management
                        </a>

                        <a href="{{ route('public.services.audit') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.services.audit') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Google Ads Audit
                        </a>

                        <a href="{{ route('public.services.consulting') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.services.consulting') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Google Ads Consulting
                        </a>

                        <a href="{{ route('public.services.consultation') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.services.consultation') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Free Consultation
                        </a>
                    </div>
                </div>

                {{-- Academy dropdown --}}
                <div class="relative" @click.outside="if (openDropdown === 'academy') openDropdown = null">
                    <button type="button"
                            @click="openDropdown = openDropdown === 'academy' ? null : 'academy'"
                            class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium {{ request()->routeIs('public.academy.*') ? 'text-brand' : 'text-gray-600 hover:text-brand' }} transition">
                        Academy
                        <span class="text-sm inline-block">&#9662;</span>
                    </button>

                    <div x-show="openDropdown === 'academy'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute left-0 mt-2 w-64 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden">

                        <a href="{{ route('public.academy.landing') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.academy.landing') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Google Ads Academy
                        </a>

                        <a href="{{ route('public.academy.nextgen') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.academy.nextgen') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Google Ads Next Gen
                        </a>

                        <a href="{{ route('public.academy.corporate') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.academy.corporate') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Corporate Training
                        </a>

                        <a href="{{ route('public.academy.playbook') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.academy.playbook') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Google Ads Playbook
                        </a>
                    </div>
                </div>

                {{-- Blog dropdown --}}
                <div class="relative" @click.outside="if (openDropdown === 'blog') openDropdown = null">
                    <button type="button"
                            @click="openDropdown = openDropdown === 'blog' ? null : 'blog'"
                            class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium {{ request()->routeIs('public.blog.*') ? 'text-brand' : 'text-gray-600 hover:text-brand' }} transition">
                        Blog
                        <span class="text-sm inline-block">&#9662;</span>
                    </button>

                    <div x-show="openDropdown === 'blog'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute left-0 mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden">

                        <a href="{{ route('public.blog.index') }}"
                           class="block px-4 py-2.5 text-sm {{ ! $activeCatSlug && request()->routeIs('public.blog.index') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            All Posts
                        </a>

                        @if ($navCategories->isNotEmpty())
                            <div class="border-t border-gray-100"></div>

                            @foreach ($navCategories as $navCat)
                                <a href="{{ route('public.blog.index', ['category' => $navCat->slug]) }}"
                                   class="block px-4 py-2.5 text-sm {{ $activeCatSlug === $navCat->slug ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                                    {{ $navCat->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Tools dropdown --}}
                <div class="relative" @click.outside="if (openDropdown === 'tools') openDropdown = null">
                    <button type="button"
                            @click="openDropdown = openDropdown === 'tools' ? null : 'tools'"
                            class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium {{ request()->routeIs('public.tools.*') ? 'text-brand' : 'text-gray-600 hover:text-brand' }} transition">
                        Tools
                        <span class="text-sm inline-block">&#9662;</span>
                    </button>

                    <div x-show="openDropdown === 'tools'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute left-0 mt-2 w-64 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden">

                        <a href="{{ route('public.tools.keyword-mixer') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.tools.keyword-mixer') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Keyword Mixer
                        </a>

                        <a href="{{ route('public.tools.campaign-plan') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.tools.campaign-plan') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Campaign Plan Generator
                        </a>

                        <a href="{{ route('public.tools.lp-analyzer') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.tools.lp-analyzer') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            LP Analyzer
                        </a>

                        <a href="{{ route('public.tools.url-builder') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.tools.url-builder') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            URL Builder
                        </a>
                        <a href="{{ route('public.troubleshooter') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.troubleshooter') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Troubleshooter
                        </a>
                    </div>
                </div>

                {{-- Company dropdown --}}
                <div class="relative" @click.outside="if (openDropdown === 'company') openDropdown = null">
                    <button type="button"
                            @click="openDropdown = openDropdown === 'company' ? null : 'company'"
                            class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium {{ request()->routeIs('about') || request()->routeIs('public.contact.*') ? 'text-brand' : 'text-gray-600 hover:text-brand' }} transition">
                        Company
                        <span class="text-sm inline-block">&#9662;</span>
                    </button>

                    <div x-show="openDropdown === 'company'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute left-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden">

                        <a href="{{ route('about') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('about') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            About
                        </a>

                        <a href="{{ route('public.contact.show') }}"
                           class="block px-4 py-2.5 text-sm {{ request()->routeIs('public.contact.*') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                            Contact
                        </a>
                    </div>
                </div>

                {{-- Login (member) / Dashboard (kalau sudah login) --}}
                @auth('member')
                    <a href="{{ route('academy.dashboard') }}"
                       class="ml-2 inline-flex items-center px-4 py-1.5 border border-brand text-brand hover:bg-brand hover:text-white rounded-full text-sm font-semibold transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('member.login') }}"
                       class="ml-2 inline-flex items-center px-4 py-1.5 border border-brand text-brand hover:bg-brand hover:text-white rounded-full text-sm font-semibold transition">
                        Login
                    </a>
                @endauth

                {{-- Search icon (last) --}}
                <button type="button"
                        @click="openSearch = !openSearch"
                        :class="openSearch && 'text-brand'"
                        aria-label="Search"
                        class="p-2 text-gray-600 hover:text-brand transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </nav>

            {{-- ===== MOBILE TOGGLE ===== --}}
            <button type="button"
                    @click="openMobile = !openMobile"
                    aria-label="Menu"
                    class="md:hidden p-2 text-gray-600 hover:text-brand transition">
                <svg x-show="!openMobile" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="openMobile" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- ===== DESKTOP SPOTLIGHT SEARCH ===== --}}
    <div x-show="openSearch"
         x-cloak
         class="hidden md:block fixed inset-0 z-50">

        <div x-show="openSearch"
             x-cloak
             @click="openSearch = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-gray-900/30 backdrop-blur-sm"></div>

        <div x-show="openSearch"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative max-w-xl mx-auto mt-32 px-4">

            <form method="GET" action="{{ route('public.blog.index') }}"
                  class="bg-white rounded-full shadow-2xl overflow-hidden">
                <div class="flex items-center px-5">
                    <span class="text-gray-400 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text"
                           name="q"
                           value="{{ request()->query('q', '') }}"
                           placeholder="Cari post..."
                           x-ref="searchInput"
                           x-init="$watch('openSearch', value => { if (value) $nextTick(() => $refs.searchInput.focus()) })"
                           class="flex-1 py-4 text-base text-gray-900 placeholder-gray-400 bg-transparent border-0 focus:outline-none focus:ring-0">
                    <kbd class="hidden sm:inline-flex ml-3 px-2 py-1 bg-gray-100 text-gray-500 text-xs font-mono rounded border border-gray-200">esc</kbd>
                </div>
            </form>

            <p class="text-center text-xs text-white/70 mt-4">
                Tekan <kbd class="px-1.5 py-0.5 bg-white/20 rounded text-xs font-mono">Enter</kbd> untuk mencari, <kbd class="px-1.5 py-0.5 bg-white/20 rounded text-xs font-mono">Esc</kbd> untuk tutup
            </p>
        </div>
    </div>

    {{-- ===== MOBILE DRAWER ===== --}}
    <div x-show="openMobile"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden fixed left-0 right-0 bottom-0 border-t border-gray-100 bg-white overflow-y-auto" style="top: 4rem;">
        <div class="px-4 py-4 space-y-1">

            {{-- Services --}}
            <div class="px-3 pt-3 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                Services
            </div>
            <a href="{{ route('public.services.management') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.services.management') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Google Ads Management
            </a>
            <a href="{{ route('public.services.audit') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.services.audit') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Google Ads Audit
            </a>
            <a href="{{ route('public.services.consulting') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.services.consulting') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Google Ads Consulting
            </a>
            <a href="{{ route('public.services.consultation') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.services.consultation') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Free Consultation
            </a>

            {{-- Academy --}}
            <div class="px-3 pt-3 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                Academy
            </div>
            <a href="{{ route('public.academy.landing') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.academy.landing') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Google Ads Academy
            </a>
            <a href="{{ route('public.academy.nextgen') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.academy.nextgen') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Google Ads Next Gen
            </a>
            <a href="{{ route('public.academy.corporate') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.academy.corporate') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Corporate Training
            </a>
            <a href="{{ route('public.academy.playbook') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.academy.playbook') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Google Ads Playbook
            </a>

            {{-- Blog --}}
            <div class="px-3 pt-3 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                Blog
            </div>
            <a href="{{ route('public.blog.index') }}"
               class="block px-3 py-2.5 text-sm {{ ! $activeCatSlug && request()->routeIs('public.blog.index') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                All Posts
            </a>
            @foreach ($navCategories as $navCat)
                <a href="{{ route('public.blog.index', ['category' => $navCat->slug]) }}"
                   class="block px-3 py-2.5 text-sm {{ $activeCatSlug === $navCat->slug ? 'text-brand bg-brand-50 font-semibold': 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                    {{ $navCat->name }}
                </a>
            @endforeach

            {{-- Tools --}}
            <div class="px-3 pt-3 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                Tools
            </div>
            <a href="{{ route('public.tools.keyword-mixer') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.tools.keyword-mixer') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Keyword Mixer
            </a>
            <a href="{{ route('public.tools.campaign-plan') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.tools.campaign-plan') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Campaign Plan Generator
            </a>
            <a href="{{ route('public.tools.lp-analyzer') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.tools.lp-analyzer') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                LP Analyzer
            </a>
            <a href="{{ route('public.tools.url-builder') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.tools.url-builder') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                URL Builder
            </a>
            <a href="{{ route('public.troubleshooter') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.troubleshooter') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Troubleshooter
            </a>

            {{-- Company --}}
            <div class="px-3 pt-3 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                Company
            </div>
            <a href="{{ route('about') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('about') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                About
            </a>
            <a href="{{ route('public.contact.show') }}"
               class="block px-3 py-2.5 text-sm {{ request()->routeIs('public.contact.*') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:text-brand hover:bg-gray-50' }} rounded-lg transition">
                Contact
            </a>

            {{-- Login / Dashboard --}}
            @auth('member')
                <a href="{{ route('academy.dashboard') }}"
                   class="block px-3 py-2.5 mt-3 text-sm font-medium text-gray-700 hover:text-brand hover:bg-gray-50 rounded-lg transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('member.login') }}"
                   class="block px-3 py-2.5 mt-3 text-sm font-medium text-gray-700 hover:text-brand hover:bg-gray-50 rounded-lg transition">
                    Login
                </a>
            @endauth

            {{-- Search --}}
            <div class="pt-3">
                <form method="GET" action="{{ route('public.blog.index') }}">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text"
                               name="q"
                               value="{{ request()->query('q', '') }}"
                               placeholder="Cari post..."
                               class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                    </div>
                </form>
            </div>
        </div>
    </div>

</header>

{{-- ============== MAIN ============== --}}
<main>
    @yield('content')
</main>

<footer class="border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-sm text-gray-500 text-center sm:text-left">
                &copy; {{ date('Y') }} Digimaya. All rights reserved.
            </p>
            <div class="flex items-center justify-center gap-4 text-sm">
                <a href="{{ route('privacy') }}" class="text-gray-500 hover:text-brand transition">Privacy Policy</a>
                <span class="text-gray-300">·</span>
                <a href="{{ route('terms') }}" class="text-gray-500 hover:text-brand transition">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<style>[x-cloak] { display: none !important; }</style>

@stack('scripts')
</body>
</html>