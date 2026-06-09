<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Proposal — Digimaya</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v={{ filemtime(public_path('css/tailwind.css')) }}">
    <style>
        body { font-family: Georgia, 'Times New Roman', serif; color: #1a1a1a; }
        .doc-body { font-size: 15px; line-height: 1.8; }
        .doc-body p { margin-bottom: 0.9rem; }
        .doc-body ul { list-style: disc; margin-left: 1.4rem; margin-bottom: 0.9rem; }
        .doc-body ol { list-style: decimal; margin-left: 1.4rem; margin-bottom: 0.9rem; }
        .doc-body a { color: #165DFF; text-decoration: underline; }
        .doc-body strong { font-weight: 700; }
        .doc-body h3 { font-weight: 700; font-size: 1.05rem; margin: 1rem 0 0.5rem; }
        .sheet { width: 100%; max-width: 794px; } /* ~A4 width @96dpi */
    </style>
</head>
<body class="bg-neutral-300 py-0 sm:py-10">

    <div class="sheet mx-auto bg-white">

        {{-- Cover (full page feel) --}}
        <div class="relative overflow-hidden min-h-screen sm:min-h-[1123px] flex flex-col px-12 sm:px-20 py-16 border-b border-neutral-200">
            {{-- Soft abstract background --}}
            <svg class="absolute inset-0 w-full h-full pointer-events-none" preserveAspectRatio="xMidYMid slice" viewBox="0 0 800 1120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="700" cy="120" r="320" fill="#165DFF" opacity="0.05"/>
                <circle cx="80" cy="900" r="260" fill="#165DFF" opacity="0.04"/>
                <circle cx="640" cy="980" r="160" fill="#165DFF" opacity="0.06"/>
                <path d="M0 700 Q400 600 800 760" stroke="#165DFF" stroke-opacity="0.06" stroke-width="2" fill="none"/>
                <path d="M0 760 Q400 660 800 820" stroke="#165DFF" stroke-opacity="0.04" stroke-width="2" fill="none"/>
            </svg>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col flex-1">
                <img src="{{ asset('images/logo/logo-blue.png') }}" alt="Digimaya" class="self-start" style="height:36px;width:auto;max-width:none;object-fit:contain;">
                <div class="flex-1 flex flex-col justify-center">
                    <div class="w-16 h-px bg-neutral-900 mb-8"></div>
                    <h1 class="text-4xl sm:text-5xl font-bold leading-tight tracking-tight">{{ $proposal->title }}</h1>
                    <div class="mt-12">
                        <p class="text-xs uppercase tracking-widest text-neutral-400">Disiapkan untuk</p>
                        <p class="text-2xl mt-1">{{ $proposal->client->business_name ?? '-' }}</p>
                    </div>
                </div>
                <div class="text-sm text-neutral-400 space-y-1">
                    <p>Disiapkan oleh: Digimaya</p>
                    <p>{{ $proposal->published_at?->format('d F Y') }}</p>
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
                                <img src="{{ $block['image_url'] }}" alt="{{ $block['caption'] ?? '' }}" class="w-full">
                                @if(!empty($block['caption']))
                                    <figcaption class="text-xs text-neutral-400 mt-2 text-center italic">{{ $block['caption'] }}</figcaption>
                                @endif
                            </figure>
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
                                        <img src="{{ $item['thumbnail'] }}" alt="{{ $item['title'] ?? '' }}" class="w-full mb-4">
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

        {{-- Footer --}}
        <div class="px-12 sm:px-20 py-10 border-t border-neutral-200 text-center">
            <img src="{{ asset('images/logo/logo-blue.png') }}" alt="Digimaya" class="h-6 mx-auto opacity-50" style="height:24px;width:auto;max-width:none;object-fit:contain;">
            <p class="text-xs text-neutral-400 mt-3">Digimaya • Google Ads Agency • {{ now()->year }}</p>
        </div>

    </div>

</body>
</html>