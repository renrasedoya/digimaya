@props(['items' => []])

@if(count($items) > 0)
    <nav class="flex items-center text-sm text-gray-500" aria-label="Breadcrumb">
        @foreach($items as $index => $item)
            @if($index > 0)
                <span class="mx-2 text-gray-400">&rsaquo;</span>
            @endif

            @if(isset($item['url']) && !$loop->last)
                <a href="{{ $item['url'] }}" class="hover:text-gray-700 transition-colors">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="text-gray-700 font-medium">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
