<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Favicon --}}
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ filemtime(public_path('css/tailwind.css')) }}">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>[x-cloak] { display: none !important; }</style>
        @stack("styles")
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- Auto-disable submit button on form submit (prevent double-click duplicates) --}}
        <script>
            (function () {
                const submittedForms = new WeakSet();

                document.addEventListener('submit', function (event) {
                    const form = event.target;
                    if (!(form instanceof HTMLFormElement)) return;

                    // Skip kalau form udah pernah submit di session ini
                    if (submittedForms.has(form)) {
                        event.preventDefault();
                        return;
                    }

                    // Skip kalau ada attribute opt-out
                    if (form.hasAttribute('data-no-disable')) return;

                    submittedForms.add(form);

                    // Find all submit buttons (button[type=submit], input[type=submit], button without type)
                    const submitButtons = form.querySelectorAll(
                        'button[type="submit"], input[type="submit"], button:not([type])'
                    );

                    // Only the actually-clicked button gets the spinner.
                    // Other submit buttons in the same form are just disabled
                    // (prevents double-submit without making them all look "loading").
                    const clicked = event.submitter;

                    submitButtons.forEach(function (btn) {
                        if (btn === clicked) {
                            if (btn.tagName === 'BUTTON') {
                                btn.dataset.originalHtml = btn.innerHTML;
                                btn.innerHTML = '<span style="display:inline-flex;align-items:center;gap:6px;">' +
                                    '<svg style="animation:spin 1s linear infinite;width:14px;height:14px;" fill="none" viewBox="0 0 24 24">' +
                                        '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"></circle>' +
                                        '<path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>' +
                                    '</svg>' +
                                    'Memproses...' +
                                '</span>';
                            } else {
                                btn.dataset.originalValue = btn.value;
                                btn.value = 'Memproses...';
                            }
                        }

                        btn.disabled = true;
                        btn.style.opacity = '0.7';
                        btn.style.cursor = 'not-allowed';
                    });
                }, true);

                // Restore button state on bfcache (browser back button)
                window.addEventListener('pageshow', function (event) {
                    if (event.persisted) {
                        document.querySelectorAll('button[disabled], input[type="submit"][disabled]').forEach(function (btn) {
                            if (btn.dataset.originalHtml) {
                                btn.innerHTML = btn.dataset.originalHtml;
                                delete btn.dataset.originalHtml;
                            }
                            if (btn.dataset.originalValue) {
                                btn.value = btn.dataset.originalValue;
                                delete btn.dataset.originalValue;
                            }
                            btn.disabled = false;
                            btn.style.opacity = '';
                            btn.style.cursor = '';
                        });
                    }
                });
            })();
        </script>
        <style>
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        </style>

        @stack('scripts')
    </body>
</html>
