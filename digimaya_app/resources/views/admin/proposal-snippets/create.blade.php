<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Add Snippet') }}</h2>
            <div class="mt-2">
                <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Proposal Snippets', 'url' => route('admin.proposal-snippets.index')], ['label' => 'Add Snippet']]" />
            </div>
        </div>
    </x-slot>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem; }
        .ql-container.ql-snow { border-bottom-left-radius: 0.375rem; border-bottom-right-radius: 0.375rem; }
        .ql-toolbar svg { width: 18px; height: 18px; display: inline-block; }
        /* Samakan gaya isi snippet dengan konten blog (lihat komponen x-prose-content) */
        .ql-editor { min-height: 240px; font-size: 1rem; line-height: 1.75; color: #1f2937; }
        .ql-editor p { margin-bottom: 1.25rem; }
        .ql-editor h2 { font-size: 1.5rem; font-weight: 700; color: #111827; margin: 2rem 0 1.5rem; line-height: 1.25; }
        .ql-editor h3 { font-size: 1.25rem; font-weight: 600; color: #111827; margin: 1.5rem 0 1.25rem; line-height: 1.375; }
        .ql-editor a { color: #165DFF; text-decoration: underline; }
        .ql-editor strong, .ql-editor b { font-weight: 700; color: #111827; }
        .ql-editor em, .ql-editor i { font-style: italic; }
        .ql-editor ul { margin-bottom: 1.25rem; padding-left: 1.5rem; list-style: disc; }
        .ql-editor ol { margin-bottom: 1.25rem; padding-left: 1.5rem; list-style: decimal; }
        .ql-editor li { margin-bottom: 0.5rem; }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.proposal-snippets.store') }}" class="space-y-6"
                          x-data="snippetForm({ initialBody: @js(old('body', '')) })" @submit="syncEditorToInput()">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Body</label>
                            {{-- Hidden textarea menerima HTML Quill saat submit; divalidasi server-side --}}
                            <textarea name="body" id="body-input" class="hidden" maxlength="50000">{{ old('body') }}</textarea>
                            {{-- Mount point Quill --}}
                            <div id="quill-editor-body" class="mt-1"></div>
                            <p class="mt-1 text-xs text-gray-500">Gunakan toolbar untuk format (heading, bold, italic, link, list). Disanitasi saat simpan.</p>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label for="is_active" class="ms-2 text-sm text-gray-700">Active (available to insert into proposals)</label>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.proposal-snippets.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Save Snippet</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        function snippetForm(config) {
            return {
                quill: null,

                init() {
                    this.$nextTick(() => this.initQuill(config.initialBody));
                },

                initQuill(initialContent) {
                    const toolbarOptions = [
                        [{ header: [2, 3, false] }],
                        ['bold', 'italic'],
                        ['link'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['clean'],
                    ];

                    this.quill = new Quill('#quill-editor-body', {
                        theme: 'snow',
                        placeholder: 'Tulis isi snippet di sini...',
                        modules: { toolbar: toolbarOptions },
                    });

                    if (initialContent) {
                        // Re-inject data-list supaya Quill 2.x render OL/UL dengan benar
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
                        // Quill mengembalikan "<p><br></p>" saat kosong — anggap string kosong
                        const cleaned = html === '<p><br></p>' ? '' : html;
                        document.getElementById('body-input').value = cleaned;
                    }
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
