<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Certificate — Digimaya</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ time() }}">
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">

    <header class="border-b border-gray-100 bg-white">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="https://digimaya.com" class="flex items-center gap-2">
                <img src="https://digimaya.com/images/logo/logo-blue.png" alt="Digimaya" class="h-7 w-auto">
            </a>
            <span class="text-sm text-gray-500">Certificate Verification</span>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-16">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-semibold text-gray-900 mb-3">Verify a Certificate</h1>
            <p class="text-sm text-gray-600">
                Enter the certificate number below to verify its authenticity.
            </p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8">
            <form method="GET" action="{{ route('certificate.verify.form') }}" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Certificate Number</label>
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="e.g. DGMY-2026-A8F3E9"
                           required
                           autofocus
                           autocomplete="off"
                           class="w-full border border-gray-300 rounded-md px-4 py-3 text-base focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand">
                </div>
                <button type="submit"
                        class="w-full bg-brand text-white text-sm font-semibold rounded-md px-4 py-3 hover:opacity-90 transition">
                    Verify Certificate
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-500 mt-8">
            Issued by Digimaya — Google Premier Partner
        </p>
    </main>

</body>
</html>
