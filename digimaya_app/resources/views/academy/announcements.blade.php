<x-academy-layout>
    @section('title', 'Announcements')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Announcements
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Update terbaru dari Academy: module, materi, dan artikel baru.
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if($paginator->isEmpty())
                <div class="bg-white shadow-sm rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <h3 class="mt-4 text-base font-semibold text-gray-700">Belum ada announcement</h3>
                    <p class="mt-2 text-sm text-gray-500">Update akan muncul di sini saat ada module, materi, atau artikel baru.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($paginator as $item)
                        <a href="{{ $item['url'] }}"
                           @if($item['type'] === 'article') target="_blank" rel="noopener noreferrer" @endif
                           class="block bg-white border border-gray-100 hover:border-gray-200 hover:shadow-sm rounded-lg p-4 transition group">
                            <div class="flex gap-4 items-start">

                                {{-- Icon by type --}}
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center
                                    @if($item['type'] === 'module') bg-blue-50 text-blue-800
                                    @elseif($item['type'] === 'material') bg-green-50 text-green-800
                                    @else bg-pink-50 text-pink-800
                                    @endif">
                                    @if($item['type'] === 'module')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    @elseif($item['type'] === 'material')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded
                                            @if($item['type'] === 'module') bg-blue-50 text-blue-800
                                            @elseif($item['type'] === 'material') bg-green-50 text-green-800
                                            @else bg-pink-50 text-pink-800
                                            @endif">
                                            @if($item['type'] === 'module') Module baru
                                            @elseif($item['type'] === 'material') Materi baru
                                            @else Artikel baru
                                            @endif
                                        </span>
                                        @if($item['tier'] === 'paid')
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded bg-amber-100 text-amber-800">Paid</span>
                                        @endif
                                        <span class="text-xs text-gray-500">{{ $item['date']->diffForHumans() }}</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-brand transition truncate">
                                        {{ $item['title'] }}
                                    </h3>
                                    @if($item['subtitle'])
                                        <p class="text-sm text-gray-600 mt-0.5 line-clamp-1">{{ $item['subtitle'] }}</p>
                                    @endif
                                </div>

                                {{-- CTA --}}
                                <div class="flex-shrink-0 text-sm font-medium text-brand whitespace-nowrap self-center">
                                    {{ $item['cta_label'] }} &rarr;
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $paginator->onEachSide(1)->links() }}
                </div>
            @endif

        </div>
    </div>
</x-academy-layout>
