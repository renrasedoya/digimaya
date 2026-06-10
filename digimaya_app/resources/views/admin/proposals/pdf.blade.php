<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 36px 44px; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Serif', Georgia, 'Times New Roman', serif; color: #1a1a1a; font-size: 12px; line-height: 1.7; }
        h1 { font-size: 26px; font-weight: bold; margin: 0 0 4px; line-height: 1.25; }
        h2 { font-size: 17px; font-weight: bold; margin: 0 0 6px; }
        h3 { font-size: 13px; font-weight: bold; margin: 8px 0 4px; }
        p { margin: 0 0 8px; }
        .muted { color: #8a8a8a; }
        .eyebrow { font-size: 9px; letter-spacing: 2px; text-transform: uppercase; color: #9a9a9a; }
        .rule { border-top: 1px solid #111; width: 48px; margin: 8px 0; }
        .cover { padding: 60px 0 40px; border-bottom: 1px solid #e3e3e3; margin-bottom: 28px; }
        .section { margin-bottom: 26px; }
        .doc-body ul { margin: 0 0 8px 18px; }
        .doc-body ol { margin: 0 0 8px 18px; }
        .doc-body a { color: #165DFF; }
        table { width: 100%; border-collapse: collapse; }
        .price th { border-bottom: 2px solid #111; text-align: left; padding: 7px 0; font-weight: bold; }
        .price td { border-bottom: 1px solid #ddd; padding: 7px 0; }
        .price .right { text-align: right; }
        figure { margin: 10px 0; }
        figure img { max-width: 100%; }
        figcaption { font-size: 10px; color: #9a9a9a; font-style: italic; text-align: center; margin-top: 3px; }
        .logo-cell { width: 25%; text-align: center; vertical-align: middle; padding: 10px; }
        .logo-cell img { max-height: 42px; max-width: 100%; }
        .quote { font-style: italic; margin: 0 0 4px; }
        .testi-meta { font-size: 11px; color: #555; }
        .footer { margin-top: 30px; padding-top: 12px; border-top: 1px solid #e3e3e3; text-align: center; color: #9a9a9a; font-size: 10px; }
    </style>
</head>
<body>

    {{-- Cover --}}
    <div class="cover">
        @php $logo = public_path('images/logo/logo-blue.png'); @endphp
        @if(file_exists($logo))
            <img src="{{ $logo }}" alt="Digimaya" style="height:30px; margin-bottom:36px;">
        @endif
        <div class="rule"></div>
        <h1>{{ $proposal->title }}</h1>
        <p class="eyebrow" style="margin-top:24px;">Disiapkan untuk</p>
        <p style="font-size:18px; margin-top:2px;">{{ $proposal->client->business_name ?? '-' }}</p>
        <p class="muted" style="margin-top:24px;">
            Disiapkan oleh: Digimaya<br>
            {{ $proposal->published_at?->format('d F Y') ?? now()->format('d F Y') }}
        </p>
    </div>

    {{-- Sections --}}
    @php $sectionNo = 0; @endphp
    @forelse($blocks as $block)
        @php
            $type = $block['type'] ?? null;
            $heading = ($type === 'custom' || $type === 'snippet') ? ($block['title'] ?? '') : ($block['heading'] ?? '');
            $hasHeading = trim($heading) !== '';
            if ($hasHeading) { $sectionNo++; }
        @endphp

        <div class="section">
            @if($hasHeading)
                <p class="eyebrow">Bagian {{ str_pad($sectionNo, 2, '0', STR_PAD_LEFT) }}</p>
                <h2>{{ $heading }}</h2>
                <div class="rule"></div>
            @endif

            @if($type === 'custom' || $type === 'snippet')
                <div class="doc-body">{!! $block['body'] ?? '' !!}</div>
                @if(!empty($block['image_url']))
                    <figure>
                        <img src="{{ $block['image_url'] }}" alt="{{ $block['caption'] ?? '' }}">
                        @if(!empty($block['caption']))
                            <figcaption>{{ $block['caption'] }}</figcaption>
                        @endif
                    </figure>
                @endif
                @if(!empty($block['images']) && is_array($block['images']))
                    @foreach($block['images'] as $img)
                        <figure>
                            <img src="{{ $img }}" alt="" style="max-width:100%;">
                        </figure>
                    @endforeach
                @endif

            @elseif($type === 'pricing')
                @if(!empty($block['rows']))
                    <table class="price">
                        <thead>
                            <tr>
                                <th>Budget Iklan / Bulan</th>
                                <th class="right">Agency Fee / Bulan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($block['rows'] as $row)
                                <tr>
                                    <td>Rp {{ number_format($row['budget'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="right">Rp {{ number_format($row['agency_fee'] ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="muted">Belum ada data harga.</p>
                @endif

            @elseif($type === 'reference' && ($block['source'] ?? '') === 'logo_wall')
                @php $items = $block['items'] ?? []; $chunks = array_chunk($items, 4); @endphp
                <table>
                    @foreach($chunks as $chunk)
                        <tr>
                            @foreach($chunk as $item)
                                <td class="logo-cell">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] ?? '' }}">
                                    @else
                                        {{ $item['name'] ?? '' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>

            @elseif($type === 'reference' && ($block['source'] ?? '') === 'testimonials')
                @foreach($block['items'] ?? [] as $item)
                    <div style="margin-bottom:14px;">
                        <p class="quote">"{{ $item['quote'] ?? '' }}"</p>
                        <p class="testi-meta">
                            <strong>{{ $item['name'] ?? '' }}</strong>{{ trim((!empty($item['position']) ? ' — ' . $item['position'] : '') . (!empty($item['company']) ? ', ' . $item['company'] : '')) }}
                        </p>
                    </div>
                @endforeach

            @elseif($type === 'reference' && ($block['source'] ?? '') === 'case_studies')
                @foreach($block['items'] ?? [] as $item)
                    <div style="margin-bottom:14px;">
                        @if(!empty($item['thumbnail']))
                            <img src="{{ $item['thumbnail'] }}" alt="{{ $item['title'] ?? '' }}" style="max-width:100%; margin-bottom:6px;">
                        @endif
                        <p class="eyebrow">{{ $item['industry'] ?? '' }}</p>
                        <h3>{{ $item['title'] ?? '' }}</h3>
                        @if(!empty($item['client_name']))
                            <p class="muted">{{ $item['client_name'] }}</p>
                        @endif
                        @if(!empty($item['problem']))
                            <p><strong>Tantangan:</strong> {{ $item['problem'] }}</p>
                        @endif
                        @if(!empty($item['solution']))
                            <p><strong>Solusi:</strong> {{ $item['solution'] }}</p>
                        @endif
                    </div>
                @endforeach

            @elseif($type === 'reference' && ($block['source'] ?? '') === 'services')
                @foreach($block['items'] ?? [] as $item)
                    <div style="margin-bottom:10px;">
                        <h3>{{ $item['title'] ?? '' }}</h3>
                        @if(!empty($item['description']))
                            <p>{{ $item['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    @empty
        <p class="muted" style="text-align:center;">Proposal masih kosong.</p>
    @endforelse

    <div class="footer">
        Digimaya &bull; Google Ads Agency &bull; {{ now()->year }}
    </div>

</body>
</html>
