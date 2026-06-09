<x-academy-layout>
    @section('title', $material->title)

    <x-slot name="header">
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <a href="{{ route('academy.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
            <span>›</span>
            <a href="{{ route('academy.module.show', $module) }}" class="hover:text-gray-700">{{ $module->title }}</a>
            <span>›</span>
            <span class="text-gray-700 truncate">{{ $material->title }}</span>
        </div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $material->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8"
             x-data="materialPage({
                materialId: {{ $material->id }},
                isCompleted: @js($isCompleted),
                csrfToken: '{{ csrf_token() }}',
             })">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: Video + notes + actions --}}
                <div class="lg:col-span-2">

                    {{-- YouTube embed (responsive 16:9) --}}
                    <div class="bg-black overflow-hidden shadow-sm rounded-lg mb-6">
                        <div class="relative w-full" style="padding-bottom: 56.25%;">
                            <iframe
                                src="https://www.youtube.com/embed/{{ $material->youtube_id }}?rel=0"
                                title="{{ $material->title }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                class="absolute top-0 left-0 w-full h-full"></iframe>
                        </div>
                    </div>

                    {{-- Action bar: Prev / Position indicator / Mark Complete + Next --}}
                    <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between gap-3 flex-wrap">

                            {{-- LEFT: Prev (subtle text+icon, no border) --}}
                            <div class="flex items-center">
                                @if($prevMaterial)
                                    <a href="{{ route('academy.material.show', [$module, $prevMaterial]) }}"
                                       class="inline-flex items-center text-sm text-gray-600 hover:text-brand transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span class="hidden sm:inline">Materi sebelumnya</span>
                                        <span class="sm:hidden">Prev</span>
                                    </a>
                                @else
                                    <span class="inline-flex items-center text-sm text-gray-300 cursor-not-allowed">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span class="hidden sm:inline">Materi sebelumnya</span>
                                        <span class="sm:hidden">Prev</span>
                                    </span>
                                @endif
                            </div>

                            {{-- CENTER: Position indicator --}}
                            @php
                                $currentPos = $allMaterials->search(fn($m) => $m->id === $material->id) + 1;
                                $totalPos = $allMaterials->count();
                            @endphp
                            <div class="text-xs text-gray-500 hidden sm:block">
                                Materi <span class="font-semibold text-gray-900">{{ $currentPos }}</span> dari <span class="font-semibold text-gray-900">{{ $totalPos }}</span>
                            </div>

                            {{-- RIGHT: Mark Complete (primary) + Next subtle --}}
                            <div class="flex items-center gap-3">
                                <button type="button"
                                        @click="markComplete()"
                                        :disabled="loading || completed"
                                        :class="completed
                                            ? 'border-green-600 text-green-700 bg-green-50 cursor-default'
                                            : 'border-brand text-brand hover:bg-brand-50'"
                                        class="inline-flex items-center px-4 py-2 border-2 rounded-md text-sm font-semibold disabled:opacity-100 transition">
                                    <svg x-show="completed" x-cloak class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span x-show="completed" x-cloak>Selesai</span>
                                    <span x-show="!completed">Tandai Selesai</span>
                                </button>

                                @if($nextMaterial)
                                    <a href="{{ route('academy.material.show', [$module, $nextMaterial]) }}"
                                       class="inline-flex items-center text-sm text-gray-600 hover:text-brand transition">
                                        <span class="hidden sm:inline">Berikutnya</span>
                                        <span class="sm:hidden">Next</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="inline-flex items-center text-sm text-gray-300 cursor-not-allowed">
                                        <span class="hidden sm:inline">Berikutnya</span>
                                        <span class="sm:hidden">Next</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Notes (Quill HTML render) --}}
                    @if($material->notes)
                        <div class="bg-white shadow-sm rounded-lg p-6">
                                                        <div class="prose prose-sm max-w-none material-notes text-sm text-gray-700">
                                {!! $material->notes !!}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- RIGHT: Sidebar with all materials in this module --}}
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm rounded-lg p-4 sticky top-20">
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-900 text-base line-clamp-2">{{ $module->title }}</h3>

                            {{-- Progress bar (mirror module detail page) --}}
                            @php
                                $sidebarTotal = $allMaterials->count();
                                $sidebarDone = count($completedIds);
                                $sidebarPercent = $sidebarTotal > 0 ? (int) round(($sidebarDone / $sidebarTotal) * 100) : 0;
                            @endphp
                            @if($sidebarTotal > 0)
                                <div class="mt-3">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs text-gray-600">
                                            <span class="font-semibold text-gray-900">{{ $sidebarDone }}</span> dari <span class="font-semibold text-gray-900">{{ $sidebarTotal }}</span> materi selesai
                                        </span>
                                        <span class="text-xs font-semibold {{ $sidebarPercent === 100 ? 'text-green-600' : 'text-brand' }}">
                                            {{ $sidebarPercent }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $sidebarPercent === 100 ? 'bg-green-500' : 'bg-brand' }} h-2 rounded-full transition-all"
                                             style="width: {{ $sidebarPercent }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-1">
                            @foreach($allMaterials as $index => $sidebarMat)
                                @php
                                    $sidebarCompleted = in_array($sidebarMat->id, $completedIds);
                                    $isCurrent = $sidebarMat->id === $material->id;
                                @endphp
                                <a href="{{ route('academy.material.show', [$module, $sidebarMat]) }}"
                                   data-sidebar-material-id="{{ $sidebarMat->id }}"
                                   class="flex items-center gap-2 p-2 rounded-md text-sm transition {{ $isCurrent ? 'bg-brand-50 text-brand-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <div data-sidebar-bullet class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium {{ $sidebarCompleted ? 'bg-green-500 text-white' : 'border border-gray-300 text-gray-500' }}">
                                        @if($sidebarCompleted)
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <span class="truncate">{{ $sidebarMat->title }}</span>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-3">
                            <a href="{{ route('academy.module.show', $module) }}" class="text-xs text-gray-600 hover:text-gray-900 inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali ke module
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Toast notification --}}
            <div x-show="showToast"
                 x-cloak
                 x-transition
                 class="fixed bottom-6 right-6 px-4 py-3 rounded-md shadow-lg text-white text-sm z-50"
                 :class="toastType === 'success' ? 'bg-green-600' : 'bg-red-600'"
                 x-text="toastMessage"></div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Override Tailwind preflight reset for Quill rendered content */
        .material-notes ol { list-style: decimal; padding-left: 1.5em; margin-bottom: 0.75em; }
        .material-notes ul { list-style: disc; padding-left: 1.5em; margin-bottom: 0.75em; }
        .material-notes li { margin-bottom: 0.25em; }
        .material-notes p { margin-bottom: 0.75em; }
        .material-notes a { color: #4f46e5; text-decoration: underline; }
        .material-notes a:hover { color: #3730a3; }
        .material-notes strong { font-weight: 600; }
        .material-notes em { font-style: italic; }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Auto target="_blank" untuk SEMUA link http/https di material notes
        document.addEventListener('DOMContentLoaded', function () {
            const notesContainer = document.querySelector('.material-notes');
            if (!notesContainer) return;
            const links = notesContainer.querySelectorAll('a[href]');
            links.forEach(function (link) {
                const href = link.getAttribute('href');
                if (href && /^https?:\/\//i.test(href)) {
                    link.setAttribute('target', '_blank');
                    link.setAttribute('rel', 'noopener noreferrer');
                }
            });
        });

        function materialPage(config) {
            return {
                completed: config.isCompleted,
                loading: false,
                showToast: false,
                toastMessage: '',
                toastType: 'success',

                async markComplete() {
                    if (this.loading || this.completed) return;
                    this.loading = true;

                    try {
                        const response = await fetch(`/academy/progress/${config.materialId}/toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': config.csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (response.ok && data.success && data.is_completed) {
                            this.completed = true;
                            this.updateSidebar(config.materialId);
                            this.showToastMessage(data.message, 'success');
                        } else {
                            this.showToastMessage(data.error || 'Gagal update progress.', 'error');
                        }
                    } catch (e) {
                        this.showToastMessage('Network error. Coba lagi.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                updateSidebar(materialId) {
                    // Update sidebar item: outlined bullet → green checkmark for current material
                    const sidebarItem = document.querySelector(`[data-sidebar-material-id="${materialId}"]`);
                    if (!sidebarItem) return;

                    const bullet = sidebarItem.querySelector('[data-sidebar-bullet]');
                    if (bullet) {
                        bullet.classList.remove('border', 'border-gray-300', 'text-gray-500');
                        bullet.classList.add('bg-green-500', 'text-white');
                        bullet.innerHTML = '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
                    }
                },

                showToastMessage(msg, type) {
                    this.toastMessage = msg;
                    this.toastType = type;
                    this.showToast = true;
                    setTimeout(() => { this.showToast = false; }, 3000);
                },
            };
        }
    </script>
    @endpush
</x-academy-layout>
