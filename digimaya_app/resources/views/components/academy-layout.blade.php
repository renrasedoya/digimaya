<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Academy') — Digimaya Academy</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ filemtime(public_path('css/tailwind.css')) }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 flex flex-col min-h-screen">

    @php
        $member = \Illuminate\Support\Facades\Auth::guard('member')->user();
    @endphp

    {{-- ============== HEADER ============== --}}
    <header class="border-b border-gray-100 bg-white sticky top-0 z-40"
            x-data="{ openProfile: false, openMobile: false }"
            @keydown.escape.window="openProfile = false; openMobile = false">

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('academy.dashboard') }}" class="flex items-center gap-2 flex-shrink-0">
                    <img src="{{ asset('images/logo/logo-blue.png') }}" alt="Digimaya" class="h-7 w-auto">
                    <span class="text-gray-300">|</span>
                    <span class="text-sm font-semibold text-gray-700">Academy</span>
                </a>

                {{-- ===== DESKTOP NAV ===== --}}
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('academy.dashboard') }}"
                       class="px-3 py-2 text-sm font-medium {{ request()->routeIs('academy.dashboard') ? 'text-brand' : 'text-gray-600 hover:text-brand' }} transition">
                        Dashboard
                    </a>

                    <a href="{{ route('academy.announcements') }}"
                       class="px-3 py-2 text-sm font-medium {{ request()->routeIs('academy.announcements') ? 'text-brand' : 'text-gray-600 hover:text-brand' }} transition">
                        Announcements
                    </a>

                    <a href="{{ route('academy.certificates.index') }}"
                       class="px-3 py-2 text-sm font-medium {{ request()->routeIs('academy.certificates.*') ? 'text-brand' : 'text-gray-600 hover:text-brand' }} transition">
                        Certificates
                    </a>

                    {{-- Profile dropdown --}}
                    <div class="relative ml-2" @click.outside="openProfile = false">
                        <button type="button"
                                @click="openProfile = !openProfile"
                                class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand transition">
                            <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center text-xs font-semibold">
                                {{ strtoupper(substr($member->name ?? 'M', 0, 1)) }}
                            </div>
                            <span>{{ $member->name ?? 'Member' }}</span>
                            <span class="text-sm inline-block">&#9662;</span>
                        </button>

                        <div x-show="openProfile"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden">

                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Logged in as</p>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $member->email ?? '' }}</p>
                            </div>

                            <a href="{{ route('academy.profile.edit') }}"
                               class="block px-4 py-2.5 text-sm {{ request()->routeIs('academy.profile.*') ? 'text-brand bg-brand-50 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition">
                                Profile
                            </a>

                            <div class="border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('academy.logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
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

        {{-- ===== MOBILE MENU ===== --}}
        <div x-show="openMobile"
             x-cloak
             x-transition
             class="md:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-3 space-y-1">
                <div class="px-3 py-2 mb-2 border-b border-gray-100">
                    <p class="text-xs text-gray-500">Logged in as</p>
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $member->email ?? '' }}</p>
                </div>

                <a href="{{ route('academy.dashboard') }}"
                   class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('academy.dashboard') ? 'text-brand' : 'text-gray-700' }}">
                    Dashboard
                </a>

                <a href="{{ route('academy.announcements') }}"
                   class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('academy.announcements') ? 'text-brand' : 'text-gray-700' }}">
                    Announcements
                </a>

                <a href="{{ route('academy.certificates.index') }}"
                   class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('academy.certificates.*') ? 'text-brand' : 'text-gray-700' }}">
                    Certificates
                </a>
                <a href="{{ route('academy.profile.edit') }}"
                   class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('academy.profile.*') ? 'text-brand' : 'text-gray-700' }}">
                    Profile
                </a>

                <form method="POST" action="{{ route('academy.logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full text-left px-3 py-2 text-sm font-medium text-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- ============== PAGE HEADING ============== --}}
    @if (isset($header))
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </div>
    @endif

    {{-- ============== MAIN ============== --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- ============== FOOTER ============== --}}
    @php
        $waUrl = config('digimaya.contact.whatsapp_wa_url');
        $feedbackText = 'Halo Digimaya, saya mau kasih feedback tentang Academy.';
        $feedbackLink = $waUrl . '?text=' . rawurlencode($feedbackText);
    @endphp
    <footer class="border-t border-gray-100 bg-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                {{-- Left: copyright + links --}}
                <div class="flex flex-col md:flex-row md:items-center text-sm">
                    <span class="text-gray-500 mb-3 md:mb-0 md:mr-6">&copy;{{ date('Y') }} Digimaya</span>
                    <div class="flex flex-wrap items-center">
                        <a href="{{ route('privacy') }}" class="text-brand hover:underline mr-5 mb-2 md:mb-0">Privacy Policy</a>
                        <a href="{{ route('terms') }}" class="text-brand hover:underline mr-5 mb-2 md:mb-0">Terms of Service</a>
                        <a href="{{ route('about') }}" class="text-brand hover:underline mr-5 mb-2 md:mb-0">About</a>
                        <a href="{{ route('public.contact.show') }}" class="text-brand hover:underline">Contact</a>
                    </div>
                </div>

                {{-- Right: feedback button --}}
                <a href="{{ $feedbackLink }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 hover:border-brand text-brand text-sm font-medium rounded-md transition self-start md:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Send feedback about Digimaya Academy
                </a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
