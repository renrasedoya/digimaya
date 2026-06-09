<x-academy-layout>
    @section('title', $module->title)

    <x-slot name="header">
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <a href="{{ route('academy.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
            <span>›</span>
            <span class="text-gray-700">{{ $module->title }}</span>
        </div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $module->title }}
        </h2>
        @if($module->description)
            <p class="mt-1 text-sm text-gray-600">{{ $module->description }}</p>
        @endif

        {{-- Progress bar --}}
        @if($totalCount > 0)
            <div class="mt-4 max-w-md">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs text-gray-600">
                        <span class="font-semibold text-gray-900">{{ $completedCount }}</span> dari <span class="font-semibold text-gray-900">{{ $totalCount }}</span> materi selesai
                    </span>
                    <span class="text-xs font-semibold {{ $progressPercent === 100 ? 'text-green-600' : 'text-brand' }}">
                        {{ $progressPercent }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="{{ $progressPercent === 100 ? 'bg-green-500' : 'bg-brand' }} h-2 rounded-full transition-all"
                         style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($module->materials->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-4 text-base font-semibold text-gray-700">Materi belum tersedia</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Material untuk module ini akan segera ditambahkan oleh admin.
                        </p>
                    </div>
                </div>
            @else
                {{-- Section heading --}}
                <div class="mb-4">
                    <h3 class="text-base font-semibold text-gray-900">Daftar Materi</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $totalCount }} materi dalam module ini</p>
                </div>

                <div class="space-y-3">
                    @foreach($module->materials as $index => $mat)
                        @php
                            $isCompleted = in_array($mat->id, $completedIds);
                        @endphp
                        <a href="{{ route('academy.material.show', [$module, $mat]) }}"
                           class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition group">
                            <div class="flex items-center gap-4 p-4">
                                {{-- YouTube thumbnail --}}
                                <div class="flex-shrink-0 w-32 sm:w-40 aspect-video bg-gray-100 relative overflow-hidden rounded-md">
                                    <img src="https://img.youtube.com/vi/{{ $mat->youtube_id }}/mqdefault.jpg"
                                         alt="{{ $mat->title }}"
                                         class="w-full h-full object-cover">
                                    {{-- Play icon overlay --}}
                                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/30 transition flex items-center justify-center">
                                        <div class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center transition">
                                            <svg class="w-4 h-4 text-gray-900 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </div>

                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <h3 class="font-semibold text-gray-900 group-hover:text-brand transition text-sm sm:text-base">
                                                <span class="text-gray-400 font-normal">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}.</span>
                                                {{ $mat->title }}
                                            </h3>
                                            @if($isCompleted)
                                                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Done
                                                </span>
                                            @endif
                                    </div>

                                    <div class="flex items-center text-xs font-medium {{ $isCompleted ? 'text-green-600' : 'text-brand' }} transition">
                                        @if($isCompleted)
                                            Tonton ulang
                                        @else
                                            Tonton sekarang
                                        @endif
                                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-academy-layout>
