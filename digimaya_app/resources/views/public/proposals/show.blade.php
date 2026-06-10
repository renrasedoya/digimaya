<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Proposal — Digimaya</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ filemtime(public_path('css/tailwind.css')) }}">
    {{-- Carlito = klon metrik Calibri (gratis). Inter = font default aplikasi (fallback). --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Carlito:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Calibri (kalau perangkat punya) -> Carlito (klon, dari Google Fonts) -> Inter (default app) -> sistem */
        body { font-family: Calibri, Carlito, Inter, system-ui, sans-serif; color: #1a1a1a; }
        .doc-body { font-size: 15px; line-height: 1.8; }
        .doc-body p { margin-bottom: 0.9rem; }
        .doc-body ul { list-style: disc; margin-left: 1.4rem; margin-bottom: 0.9rem; }
        .doc-body ol { list-style: decimal; margin-left: 1.4rem; margin-bottom: 0.9rem; }
        .doc-body a { color: #165DFF; text-decoration: underline; }
        .doc-body strong { font-weight: 700; }
        .doc-body h2 { font-weight: 700; font-size: 1.25rem; margin: 1.25rem 0 0.6rem; }
        .doc-body h3 { font-weight: 700; font-size: 1.05rem; margin: 1rem 0 0.5rem; }
        .sheet { width: 100%; max-width: 794px; } /* ~A4 width @96dpi */
    </style>
</head>
<body class="bg-neutral-100 py-6 sm:py-12 px-4 sm:px-8">

    @if(!empty($preview))
        <div class="sticky top-0 z-50 bg-amber-500 text-white text-center text-sm py-2 px-4">
            Mode Preview — beginilah tampilan proposal untuk klien. Perubahan terbaru muncul setelah kamu klik Save Draft.
        </div>
    @endif

    <div class="sheet mx-auto bg-white shadow-lg">

        {{-- Cover (full page feel) --}}
        <div class="relative overflow-hidden min-h-screen sm:min-h-[1123px] flex flex-col px-12 sm:px-20 py-16 border-b border-neutral-200">
            {{-- Soft brand glow background (sengaja, dua sudut) --}}
            <svg class="absolute inset-0 w-full h-full pointer-events-none" preserveAspectRatio="xMidYMid slice" viewBox="0 0 800 1120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs>
                    <radialGradient id="coverGlowTop" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#165DFF" stop-opacity="0.13"/>
                        <stop offset="100%" stop-color="#165DFF" stop-opacity="0"/>
                    </radialGradient>
                    <radialGradient id="coverGlowBottom" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#165DFF" stop-opacity="0.07"/>
                        <stop offset="100%" stop-color="#165DFF" stop-opacity="0"/>
                    </radialGradient>
                </defs>
                <circle cx="770" cy="70" r="380" fill="url(#coverGlowTop)"/>
                <circle cx="40" cy="1060" r="320" fill="url(#coverGlowBottom)"/>
            </svg>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col flex-1">
                <div class="flex items-center justify-between">
                    <img src="{{ asset('images/logo/logo-blue.png') }}" alt="Digimaya" style="height:36px;width:auto;max-width:none;object-fit:contain;">
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand">Proposal</span>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <div class="w-16 h-1 bg-brand rounded-full mb-8"></div>
                    <h1 class="text-5xl sm:text-6xl font-bold leading-[1.05] tracking-tight">{{ $proposal->title }}</h1>
                    <div class="mt-12">
                        <p class="text-xs uppercase tracking-widest text-neutral-400">Disiapkan untuk</p>
                        <p class="text-2xl mt-1">{{ $proposal->client->business_name ?? '-' }}</p>
                    </div>
                </div>
                <div class="text-sm text-neutral-500 space-y-1">
                    <p>Disiapkan oleh <span class="text-neutral-900 font-medium">Digimaya</span></p>
                    <p>{{ $proposal->published_at?->format('d F Y') }}</p>
                    <p class="text-neutral-400">www.digimaya.com</p>
                </div>
            </div>
        </div>

        {{-- Sections --}}
        <div class="px-12 sm:px-20 py-16 space-y-12">
            @php $sectionNo = 0; @endphp
            @forelse($blocks as $block)
                @php
                    $type = $block['type'] ?? null;
                    $heading = ($type === 'custom' || $type === 'snippet') ? ($block['title'] ?? '') : ($block['heading'] ?? '');
                    $hasHeading = trim($heading) !== '';
                    if ($hasHeading) { $sectionNo++; }
                @endphp

                <section>
                    @if($hasHeading)
                        <div class="mb-5">
                            <span class="text-xs uppercase tracking-widest text-neutral-400">Bagian {{ str_pad($sectionNo, 2, '0', STR_PAD_LEFT) }}</span>
                            <h2 class="text-2xl font-bold mt-1 leading-snug">{{ $heading }}</h2>
                            <div class="w-12 h-px bg-neutral-900 mt-3"></div>
                        </div>
                    @endif

                    @if($type === 'custom' || $type === 'snippet')
                        <div class="doc-body">{!! $block['body'] ?? '' !!}</div>
                        @if(!empty($block['image_url']))
                            <figure class="mt-6">
                                <img src="{{ $block['image_url'] }}" alt="{{ $block['caption'] ?? '' }}" class="max-w-full h-auto">
                                @if(!empty($block['caption']))
                                    <figcaption class="text-xs text-neutral-400 mt-2 text-center italic">{{ $block['caption'] }}</figcaption>
                                @endif
                            </figure>
                        @endif
                        @if(!empty($block['images']) && is_array($block['images']))
                            <div class="mt-6 space-y-6">
                                @foreach($block['images'] as $img)
                                    <figure>
                                        <img src="{{ $img }}" alt="" class="max-w-full h-auto rounded">
                                    </figure>
                                @endforeach
                            </div>
                        @endif

                    @elseif($type === 'pricing')
                        @if(!empty($block['rows']))
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr class="border-b-2 border-neutral-900">
                                        <th class="py-3 text-left font-bold">Budget Iklan / Bulan</th>
                                        <th class="py-3 text-right font-bold">Agency Fee / Bulan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($block['rows'] as $row)
                                        <tr class="border-b border-neutral-200">
                                            <td class="py-3">Rp {{ number_format($row['budget'] ?? 0, 0, ',', '.') }}</td>
                                            <td class="py-3 text-right">Rp {{ number_format($row['agency_fee'] ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-neutral-400">Belum ada data harga.</p>
                        @endif

                    @elseif($type === 'reference' && ($block['source'] ?? '') === 'logo_wall')
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-8 items-center">
                            @foreach($block['items'] ?? [] as $item)
                                <div class="flex items-center justify-center">
                                    <img src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] ?? '' }}" class="max-h-12 object-contain">
                                </div>
                            @endforeach
                        </div>

                    @elseif($type === 'reference' && ($block['source'] ?? '') === 'testimonials')
                        <div class="space-y-8">
                            @foreach($block['items'] ?? [] as $item)
                                <blockquote>
                                    <p class="doc-body italic">"{{ $item['quote'] ?? '' }}"</p>
                                    <div class="flex items-center gap-3 mt-4">
                                        @if(!empty($item['photo']))
                                            <img src="{{ $item['photo'] }}" alt="{{ $item['name'] ?? '' }}" class="h-10 w-10 rounded-full object-cover">
                                        @endif
                                        <div>
                                            <p class="text-sm font-bold">{{ $item['name'] ?? '' }}</p>
                                            <p class="text-xs text-neutral-500">{{ trim(($item['position'] ?? '') . (!empty($item['company']) ? ', ' . $item['company'] : '')) }}</p>
                                        </div>
                                    </div>
                                </blockquote>
                            @endforeach
                        </div>

                    @elseif($type === 'reference' && ($block['source'] ?? '') === 'case_studies')
                        <div class="space-y-8">
                            @foreach($block['items'] ?? [] as $item)
                                <div>
                                    @if(!empty($item['thumbnail']))
                                        <img src="{{ $item['thumbnail'] }}" alt="{{ $item['title'] ?? '' }}" class="max-w-full h-auto mb-4">
                                    @endif
                                    <p class="text-xs uppercase tracking-widest text-neutral-400">{{ $item['industry'] ?? '' }}</p>
                                    <h3 class="text-lg font-bold mt-1">{{ $item['title'] ?? '' }}</h3>
                                    @if(!empty($item['client_name']))
                                        <p class="text-sm text-neutral-500">{{ $item['client_name'] }}</p>
                                    @endif
                                    @if(!empty($item['problem']))
                                        <p class="doc-body mt-3"><strong>Tantangan:</strong> {{ $item['problem'] }}</p>
                                    @endif
                                    @if(!empty($item['solution']))
                                        <p class="doc-body mt-2"><strong>Solusi:</strong> {{ $item['solution'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    @elseif($type === 'reference' && ($block['source'] ?? '') === 'services')
                        <div class="space-y-6">
                            @foreach($block['items'] ?? [] as $item)
                                <div class="flex items-start gap-4">
                                    @if(!empty($item['icon']))
                                        <img src="{{ $item['icon'] }}" alt="{{ $item['title'] ?? '' }}" class="h-10 w-10 object-contain flex-shrink-0">
                                    @endif
                                    <div>
                                        <h3 class="text-base font-bold">{{ $item['title'] ?? '' }}</h3>
                                        @if(!empty($item['description']))
                                            <p class="doc-body mt-1">{{ $item['description'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @empty
                <p class="text-center text-neutral-400 py-8">Proposal masih kosong.</p>
            @endforelse
        </div>

        {{-- Closing / Back cover (lawannya cover; tanpa background) --}}
        <div class="min-h-screen sm:min-h-[1123px] flex flex-col px-12 sm:px-20 py-16 border-t border-neutral-200">
            <div class="flex flex-col flex-1">
                {{-- spacer atas (cermin posisi logo di cover) --}}
                <div class="h-9"></div>

                {{-- tengah: Terima Kasih --}}
                <div class="flex-1 flex flex-col justify-center">
                    <div class="w-16 h-1 bg-brand rounded-full mb-8"></div>
                    <h2 class="text-4xl sm:text-5xl font-bold leading-tight tracking-tight">Terima Kasih</h2>
                    <p class="mt-5 text-lg text-neutral-500 max-w-md">Semoga proposal ini bermanfaat.<br>Kami antusias menantikan kesempatan untuk bekerja sama.</p>
                </div>

                {{-- bawah: kontak person --}}
                <div class="text-sm text-neutral-500">
                    <p class="text-xs uppercase tracking-widest text-neutral-400 mb-3">Kontak</p>
                    <p class="text-neutral-900 font-semibold">Rica Annisa</p>
                    <p>085213228692</p>
                    <p class="mt-3">
                        <a href="https://www.digimaya.com" target="_blank" rel="noopener" class="text-neutral-700 hover:text-neutral-900">www.digimaya.com</a>
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>