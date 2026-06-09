<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $module->title }}
                    @if($module->is_published)
                        <span class="ml-2 inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                    @else
                        <span class="ml-2 inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Draft</span>
                    @endif
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Academy'],
                        ['label' => 'Modules', 'url' => route('admin.academy.modules.index')],
                        ['label' => $module->title]
                    ]" />
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.academy.modules.edit', $module) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.academy.modules.destroy', $module) }}" class="inline" onsubmit="return confirm('Delete module \'{{ $module->title }}\'? Materials and member progress will also be removed.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-4 bg-amber-100 border border-amber-400 text-amber-700 px-4 py-3 rounded">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: Module Info --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Module Info</h3>

                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $module->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Display Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $module->display_order }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Tier</dt>
                                    <dd class="mt-1 text-sm">
                                        @if($module->isPaid())
                                            <span class="inline-flex px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800">Paid</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Free</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Materials</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $module->materials->count() }} {{ Str::plural('material', $module->materials->count()) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $module->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                @if($module->updated_at->ne($module->created_at))
                                    <div>
                                        <dt class="text-xs uppercase text-gray-500">Last Updated</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $module->updated_at->diffForHumans() }}</dd>
                                    </div>
                                @endif
                            </dl>

                            @if($module->description)
                                <div class="mt-6 pt-4 border-t">
                                    <dt class="text-xs uppercase text-gray-500 mb-2">Description</dt>
                                    <dd class="text-sm text-gray-700 whitespace-pre-wrap">{{ $module->description }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Materials section --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ showAddForm: false }">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4 pb-2 border-b">
                                <h3 class="font-semibold text-gray-700">Materials ({{ $module->materials->count() }})</h3>
                                <button type="button"
                                        @click="showAddForm = !showAddForm"
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white hover:bg-indigo-700">
                                    <span x-show="!showAddForm">+ Add Material</span>
                                    <span x-show="showAddForm" x-cloak>Cancel</span>
                                </button>
                            </div>

                            {{-- Add form (collapsed by default, expand on click) --}}
                            <div x-show="showAddForm" x-cloak x-transition class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                                <form method="POST" action="{{ route('admin.academy.modules.materials.store', $module) }}"
                                      x-data="materialAddForm()" @submit="handleSubmit($event)">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="add_title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                                        <input type="text" id="add_title" name="title" value="{{ old('title') }}" required maxlength="255"
                                               placeholder="e.g. Setup First Campaign"
                                               class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                        @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="add_youtube_url" class="block text-sm font-medium text-gray-700">YouTube URL or ID <span class="text-red-500">*</span></label>
                                        <input type="text" id="add_youtube_url"
                                               x-model="youtubeInput" @input="extractId()"
                                               placeholder="Paste YouTube URL atau 11-char ID"
                                               class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                        <input type="hidden" name="youtube_id" :value="youtubeId">
                                        <p class="mt-1 text-xs text-gray-500" x-show="youtubeId" x-cloak>Detected ID: <span class="font-semibold" x-text="youtubeId"></span></p>
                                        @error('youtube_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>

                                    <div class="grid grid-cols-3 gap-3 mb-3">
                                        <div>
                                            <label for="add_display_order" class="block text-sm font-medium text-gray-700">Order</label>
                                            <input type="number" id="add_display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0" max="9999"
                                                   class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                        </div>
                                        <div class="col-span-2 flex items-end pb-2">
                                            <label class="inline-flex items-center">
                                                <input type="hidden" name="is_published" value="0">
                                                <input type="checkbox" name="is_published" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">Published</span>
                                            </label>
                                        </div>
                                    </div>

                                    <p class="text-xs text-gray-500 mb-3">Notes (opsional, rich text) bisa di-edit di halaman edit material setelah create.</p>

                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showAddForm = false" class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</button>
                                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md text-sm text-white hover:bg-indigo-700">Create Material</button>
                                    </div>
                                </form>
                            </div>

                            {{-- Materials list --}}
                            @if($module->materials->isEmpty())
                                <div class="text-center py-8 text-sm text-gray-500">
                                    Belum ada material. Klik "Add Material" untuk mulai.
                                </div>
                            @else
                                <div class="divide-y divide-gray-100">
                                    @foreach($module->materials as $mat)
                                        <div class="py-4 flex items-start gap-4">
                                            {{-- YouTube thumbnail --}}
                                            <a href="https://www.youtube.com/watch?v={{ $mat->youtube_id }}" target="_blank" rel="noopener"
                                               class="flex-shrink-0 block w-32 aspect-video rounded-md overflow-hidden bg-gray-100 relative group">
                                                <img src="https://img.youtube.com/vi/{{ $mat->youtube_id }}/mqdefault.jpg"
                                                     alt="{{ $mat->title }}"
                                                     class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </a>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="min-w-0">
                                                        <p class="text-sm text-gray-500">#{{ $mat->display_order }}</p>
                                                        <h4 class="text-sm font-semibold text-gray-900 mt-0.5">{{ $mat->title }}</h4>
                                                        <p class="text-xs text-gray-500 mt-1">{{ $mat->youtube_id }}</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        @if($mat->is_published)
                                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                                                        @else
                                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700">Draft</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="mt-3 text-right">
                                                    <a href="{{ route('admin.academy.modules.materials.edit', [$module, $mat]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                                    <form method="POST" action="{{ route('admin.academy.modules.materials.destroy', [$module, $mat]) }}" class="inline" onsubmit="return confirm('Delete material \'{{ $mat->title }}\'? Member progress for this material will also be removed.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function materialAddForm() {
            return {
                youtubeInput: '',
                youtubeId: '',

                extractId() {
                    const input = this.youtubeInput.trim();
                    if (!input) {
                        this.youtubeId = '';
                        return;
                    }

                    // Pattern 1: full URL with v= parameter
                    let match = input.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
                    if (match) { this.youtubeId = match[1]; return; }

                    // Pattern 2: youtu.be short URL
                    match = input.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
                    if (match) { this.youtubeId = match[1]; return; }

                    // Pattern 3: embed URL
                    match = input.match(/\/embed\/([a-zA-Z0-9_-]{11})/);
                    if (match) { this.youtubeId = match[1]; return; }

                    // Pattern 4: raw 11-char ID
                    if (/^[a-zA-Z0-9_-]{11}$/.test(input)) {
                        this.youtubeId = input;
                        return;
                    }

                    this.youtubeId = '';
                },

                handleSubmit(e) {
                    if (!this.youtubeId) {
                        e.preventDefault();
                        alert('YouTube URL atau ID tidak valid. Pastikan format benar.');
                    }
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
