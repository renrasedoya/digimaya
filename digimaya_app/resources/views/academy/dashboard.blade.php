<x-academy-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Halo, {{ $member->name }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Selamat datang di Digimaya Academy.
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if($modules->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-4 text-base font-semibold text-gray-700">Belum ada module tersedia</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Module akan muncul di sini setelah admin mempublikasikan content.
                        </p>
                    </div>
                </div>
            @else
                {{-- Summary stats --}}
                @php
                    $totalModules = $modules->count();
                    $startedModules = $modules->filter(fn($m) => $m->completed_materials > 0)->count();
                    $completedModules = $modules->filter(fn($m) => $m->total_published_materials > 0 && $m->completed_materials === $m->total_published_materials)->count();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-xs uppercase text-gray-500 font-medium">Total Modules</p>
                        <p class="mt-2 flex items-baseline gap-1.5">
                            <span class="text-2xl font-bold text-gray-900">{{ $totalModules }}</span>
                            <span class="text-sm text-gray-500">{{ Str::plural('module', $totalModules) }}</span>
                        </p>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-xs uppercase text-gray-500 font-medium">In Progress</p>
                        <p class="mt-2 flex items-baseline gap-1.5">
                            <span class="text-2xl font-bold text-brand">{{ $startedModules - $completedModules }}</span>
                            <span class="text-sm text-gray-500">{{ Str::plural('module', $startedModules - $completedModules) }}</span>
                        </p>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg p-5">
                        <p class="text-xs uppercase text-gray-500 font-medium">Completed</p>
                        <p class="mt-2 flex items-baseline gap-1.5">
                            <span class="text-2xl font-bold text-green-600">{{ $completedModules }}</span>
                            <span class="text-sm text-gray-500">{{ Str::plural('module', $completedModules) }}</span>
                        </p>
                    </div>
                </div>

                {{-- Section heading --}}
                <div class="mb-4">
                    <h3 class="text-base font-semibold text-gray-900">Module Belajar Kamu</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $totalModules }} {{ Str::plural('module', $totalModules) }} tersedia</p>
                </div>

                {{-- Module cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($modules as $module)
                        @php $isLocked = ! $member->canAccessModule($module); @endphp
                        <a href="{{ $isLocked ? route('academy.upgrade') : route('academy.module.show', $module) }}"
                           class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition group {{ $isLocked ? 'opacity-90' : '' }}">
                            <div class="p-6 flex gap-4">
                                {{-- LEFT: Cover image OR initial fallback --}}
                                <div class="flex-shrink-0">
                                    @if($module->cover_image)
                                        <img src="{{ $module->cover_image_url }}" alt="{{ $module->title }}"
                                             class="w-16 h-16 object-cover rounded-xl border border-gray-100 shadow-sm {{ $isLocked ? 'grayscale' : '' }}">
                                    @else
                                        @php
                                            // Generate consistent pastel color from title hash
                                            $hash = crc32($module->title);
                                            $hue = $hash % 360;
                                        @endphp
                                        <div class="w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold text-white shadow-sm {{ $isLocked ? 'grayscale' : '' }}"
                                             style="background: hsl({{ $hue }}, 65%, 55%);">
                                            {{ strtoupper(mb_substr($module->title, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- RIGHT: Title + description + progress --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-gray-900 group-hover:text-brand transition flex items-center gap-1.5">
                                                @if($isLocked)
                                                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                                <span class="truncate">{{ $module->title }}</span>
                                            </h3>
                                            @if($module->description)
                                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ Str::limit($module->description, 100) }}</p>
                                            @endif
                                        </div>
                                        @if($isLocked)
                                            <span class="flex-shrink-0 inline-flex items-center px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800 font-medium">Paid</span>
                                        @elseif($module->total_published_materials > 0 && $module->completed_materials === $module->total_published_materials)
                                            <span class="flex-shrink-0 inline-flex items-center px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Done
                                            </span>
                                        @endif
                                    </div>

                                    @if($isLocked)
                                        {{-- Locked: no progress bar, show upgrade hint --}}
                                        <div class="mt-3">
                                            <p class="text-xs text-gray-500">Upgrade ke Paid untuk akses module ini.</p>
                                        </div>

                                        <div class="mt-3 flex items-center text-xs font-medium text-amber-700">
                                            Lihat detail Paid Member
                                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    @else
                                        {{-- Unlocked: progress bar + CTA --}}
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-medium text-gray-600">
                                                    {{ $module->completed_materials }} / {{ $module->total_published_materials }} {{ Str::plural('material', $module->total_published_materials) }}
                                                </span>
                                                <span class="text-xs font-semibold {{ $module->progress_percent === 100 ? 'text-green-600' : 'text-brand' }}">
                                                    {{ $module->progress_percent }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="{{ $module->progress_percent === 100 ? 'bg-green-500' : 'bg-brand' }} h-2 rounded-full transition-all"
                                                     style="width: {{ $module->progress_percent }}%"></div>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex items-center text-xs font-medium text-brand">
                                            @if($module->completed_materials === 0)
                                                Mulai belajar
                                            @elseif($module->progress_percent === 100)
                                                Review module
                                            @else
                                                Lanjutkan belajar
                                            @endif
                                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-academy-layout>
