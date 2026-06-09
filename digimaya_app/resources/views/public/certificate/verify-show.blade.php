<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate {{ $number }} — Digimaya</title>
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
            <a href="{{ route('certificate.verify.form') }}" class="text-sm text-gray-500 hover:text-brand">
                ← Verify another
            </a>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-12">

        @if(!$certificate)
            {{-- NOT FOUND --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 class="text-xl font-semibold text-gray-900 mb-2">Certificate Not Found</h1>
                <p class="text-sm text-gray-600 mb-1">No certificate exists with the number:</p>
                <p class="text-base font-semibold text-gray-800 mb-6">{{ $number }}</p>
                <p class="text-xs text-gray-500">
                    Please check the number and try again. Certificate numbers follow the format DGMY-YYYY-XXXXXX.
                </p>
                <div class="mt-6">
                    <a href="{{ route('certificate.verify.form') }}"
                       class="inline-block bg-brand text-white text-sm font-semibold rounded-md px-4 py-2 hover:opacity-90 transition">
                        Try Another Number
                    </a>
                </div>
            </div>

        @elseif($certificate->isRevoked())
            {{-- REVOKED --}}
            <div class="bg-white border border-red-200 rounded-lg shadow-sm overflow-hidden">
                <div class="bg-red-50 border-b border-red-200 px-8 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-red-900">Certificate Revoked</h2>
                        <p class="text-xs text-red-700">This certificate is no longer valid.</p>
                    </div>
                </div>
                <div class="px-8 py-6 space-y-4 text-sm">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Certificate Number</p>
                        <p class="font-semibold text-gray-900">{{ $certificate->certificate_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Issued To</p>
                        <p class="text-gray-900">{{ $certificate->recipient_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Program</p>
                        <p class="text-gray-900">{{ $certificate->program_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Revoked On</p>
                        <p class="text-gray-700">{{ $certificate->revoked_at?->format('d F Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>

        @else
            {{-- VALID --}}
            <div class="bg-white border border-green-200 rounded-lg shadow-sm overflow-hidden">
                <div class="bg-green-50 border-b border-green-200 px-8 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-green-900">Certificate Verified</h2>
                        <p class="text-xs text-green-700">This is a valid certificate issued by Digimaya.</p>
                    </div>
                </div>
                <div class="px-8 py-6 space-y-5 text-sm">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Certificate Number</p>
                        <p class="font-semibold text-gray-900">{{ $certificate->certificate_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Issued To</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificate->recipient_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Program</p>
                        <p class="text-gray-900">{{ $certificate->program_name }}</p>
                        @if($certificate->program_description)
                            <p class="text-xs text-gray-500 italic mt-1">{{ $certificate->program_description }}</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Completion Date</p>
                            <p class="text-gray-900">{{ $certificate->completion_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Issued Date</p>
                            <p class="text-gray-900">{{ $certificate->issued_date->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Issued By</p>
                        <p class="text-gray-900">Digimaya — Google Premier Partner</p>
                    </div>
                </div>
            </div>
        @endif

        <p class="text-center text-xs text-gray-500 mt-8">
            For inquiries, contact <a href="https://digimaya.com/contact" class="text-brand hover:underline">Digimaya</a>.
        </p>
    </main>

</body>
</html>
