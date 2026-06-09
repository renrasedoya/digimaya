<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Material: {{ $material->title }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Academy'],
                ['label' => 'Modules', 'url' => route('admin.academy.modules.index')],
                ['label' => $module->title, 'url' => route('admin.academy.modules.show', $module)],
                ['label' => $material->title]
            ]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg"
                 x-data="materialEditForm({
                    initialNotes: @js(old('notes', $material->notes ?? '')),
                    initialYoutubeId: @js($material->youtube_id),
                 })">
                <form action="{{ route('admin.academy.modules.materials.update', [$module, $material]) }}"
                      method="POST" @submit="handleSubmit($event)" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $material->title) }}" maxlength="255" required
                               class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- YouTube URL/ID --}}
                    <div>
                        <label for="youtube_input" class="block text-sm font-medium text-gray-700">
                            YouTube URL or ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="youtube_input"
                               x-model="youtubeInput" @input="extractId()"
                               placeholder="Paste YouTube URL atau 11-char ID"
                               class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <input type="hidden" name="youtube_id" :value="youtubeId">
                        <p class="mt-1 text-xs text-gray-500">
                            Detected ID: <span class="font-semibold" x-text="youtubeId || '(invalid)'"></span>
                        </p>
                        @error('youtube_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror

                        {{-- Preview thumbnail --}}
                        <div x-show="youtubeId" x-cloak class="mt-3">
                            <a :href="`https://www.youtube.com/watch?v=${youtubeId}`" target="_blank" rel="noopener"
                               class="inline-block">
                                <img :src="`https://img.youtube.com/vi/${youtubeId}/mqdefault.jpg`"
                                     class="w-48 aspect-video object-cover rounded-md border border-gray-200" alt="Preview">
                            </a>
                        </div>
                    </div>

                    {{-- Notes (Quill) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (opsional)</label>
                        <div id="quill-editor" class="bg-white"></div>
                        <textarea id="notes-input" name="notes" class="hidden">{{ old('notes', $material->notes) }}</textarea>
                        @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">Format kaya: bold, italic, list, link. Ditampilkan di bawah video saat member nonton.</p>
                    </div>

                    {{-- Order + Published --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-4 border-t border-gray-200">
                        <div>
                            <label for="display_order" class="block text-sm font-medium text-gray-700">Display order</label>
                            <input type="number" id="display_order" name="display_order"
                                   value="{{ old('display_order', $material->display_order) }}" min="0" max="9999"
                                   class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <p class="mt-1 text-xs text-gray-500">Lower = shown first.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-1 flex items-center h-[38px]">
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="is_published" value="0">
                                    <input type="checkbox" name="is_published" value="1"
                                           @checked(old('is_published', $material->is_published))
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 whitespace-nowrap">Published (visible to members)</span>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Uncheck = draft, hidden dari members.</p>
                        </div>
                    </div>

                    {{-- Update + Cancel actions (still inside main form) --}}
                    <div class="flex justify-end items-center gap-2 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.academy.modules.show', $module) }}"
                           class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">Update Material</button>
                    </div>
                </form>

                {{-- Delete form (separate, outside update form to avoid HTML nested form issue) --}}
                <div class="px-6 pb-6">
                    <form method="POST" action="{{ route('admin.academy.modules.materials.destroy', [$module, $material]) }}" onsubmit="return confirm('Delete material \'{{ $material->title }}\'? Member progress for this material will also be removed.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 border border-red-300 text-red-700 hover:bg-red-50 rounded-md text-sm font-medium">Delete Material</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .ql-editor { min-height: 200px; font-size: 14px; }
        .ql-toolbar.ql-snow { border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem; }
        .ql-container.ql-snow { border-bottom-left-radius: 0.375rem; border-bottom-right-radius: 0.375rem; }
        .ql-toolbar svg { width: 18px; height: 18px; display: inline-block; }
        .ql-toolbar button { width: 28px; height: 24px; }
        .ql-editor p { margin-bottom: 0.75em; }
        .ql-editor ol, .ql-editor ul { margin-bottom: 0.75em; }
        .ql-editor li { margin-bottom: 0.25em; }
        .ql-editor ol { list-style: decimal; padding-left: 1.5em; }
        .ql-editor ul { list-style: disc; padding-left: 1.5em; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        function materialEditForm(config) {
            return {
                quill: null,
                youtubeInput: config.initialYoutubeId,
                youtubeId: config.initialYoutubeId,

                init() {
                    this.$nextTick(() => {
                        this.initQuill(config.initialNotes);
                    });
                },

                initQuill(initialContent) {
                    const toolbarOptions = [
                        ['bold', 'italic'],
                        ['link'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['clean'],
                    ];
                    this.quill = new Quill('#quill-editor', {
                        theme: 'snow',
                        placeholder: 'Catatan tambahan untuk material ini...',
                        modules: { toolbar: toolbarOptions },
                    });
                    if (initialContent) {
                        // Inject data-list attribute supaya Quill 2.x render OL/UL dengan benar
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(initialContent, 'text/html');
                        doc.querySelectorAll('ol > li').forEach(li => li.setAttribute('data-list', 'ordered'));
                        doc.querySelectorAll('ul > li').forEach(li => li.setAttribute('data-list', 'bullet'));
                        this.quill.root.innerHTML = doc.body.innerHTML;
                    }
                },

                syncEditorToInput() {
                    if (this.quill) {
                        const html = this.quill.root.innerHTML;
                        const cleaned = html === '<p><br></p>' ? '' : html;
                        document.getElementById('notes-input').value = cleaned;
                    }
                },

                extractId() {
                    const input = this.youtubeInput.trim();
                    if (!input) { this.youtubeId = ''; return; }
                    let match = input.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
                    if (match) { this.youtubeId = match[1]; return; }
                    match = input.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
                    if (match) { this.youtubeId = match[1]; return; }
                    match = input.match(/\/embed\/([a-zA-Z0-9_-]{11})/);
                    if (match) { this.youtubeId = match[1]; return; }
                    if (/^[a-zA-Z0-9_-]{11}$/.test(input)) { this.youtubeId = input; return; }
                    this.youtubeId = '';
                },

                handleSubmit(e) {
                    if (!this.youtubeId) {
                        e.preventDefault();
                        alert('YouTube URL atau ID tidak valid.');
                        return;
                    }
                    this.syncEditorToInput();
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
