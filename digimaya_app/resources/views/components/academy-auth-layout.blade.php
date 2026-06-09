<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Digimaya Academy') — Digimaya Academy</title>
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
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">

    {{-- Minimal header: logo only --}}
    <header class="border-b border-gray-100 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo/logo-blue.png') }}" alt="Digimaya" class="h-7 w-auto">
                    <span class="text-gray-300">|</span>
                    <span class="text-sm font-semibold text-gray-700">Academy</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-1 flex items-center justify-center py-12 px-4">
        {{ $slot }}
    </main>

    {{-- Footer minimal --}}
    <footer class="border-t border-gray-100 bg-white py-4">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Digimaya. All rights reserved.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
