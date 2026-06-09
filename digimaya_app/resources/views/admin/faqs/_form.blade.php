{{-- Required vars: $faq (Faq instance, can be new), $formAction (URL), $formMethod ('POST' or 'PUT') --}}

<div class="bg-white shadow-sm sm:rounded-lg"
     x-data="faqForm({
        initialAnswer: @js(old('answer', $faq->answer ?? ''))
     })">

    <form action="{{ $formAction }}"
          method="POST"
          @submit="syncEditorToInput()"
          class="p-6 space-y-6">
        @csrf
        @if($formMethod === 'PUT')
            @method('PUT')
        @endif

        {{-- Question --}}
        <div>
            <label for="question" class="block text-sm font-medium text-gray-700">
                Question <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="question"
                   name="question"
                   value="{{ old('question', $faq->question) }}"
                   maxlength="255"
                   required
                   placeholder="e.g. How long does setup take?"
                   class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            @error('question')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Answer (Quill) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Answer <span class="text-red-500">*</span>
            </label>
            <div id="quill-editor" class="bg-white"></div>
            <textarea id="answer-input" name="answer" class="hidden">{{ old('answer', $faq->answer) }}</textarea>
            @error('answer')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-gray-500">Use formatting (bold, italic, lists, links) to make answers easy to scan.</p>
        </div>

        {{-- Order + Active --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
            <div>
                <label for="position_order" class="block text-sm font-medium text-gray-700">Display order</label>
                <input type="number"
                       id="position_order"
                       name="position_order"
                       value="{{ old('position_order', $faq->position_order) }}"
                       min="0"
                       max="9999"
                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <p class="mt-1 text-xs text-gray-500">Lower number = shown first.</p>
            </div>

            <div class="flex items-end pb-2">
                <label class="inline-flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           @checked(old('is_active', $faq->is_active ?? true))
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Active (shown on public pages)</span>
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end space-x-2 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.faqs.index') }}"
               class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                {{ $formMethod === 'PUT' ? 'Update FAQ' : 'Create FAQ' }}
            </button>
        </div>
    </form>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .ql-editor { min-height: 300px; font-size: 14px; }
        .ql-toolbar.ql-snow { border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem; }
        .ql-container.ql-snow { border-bottom-left-radius: 0.375rem; border-bottom-right-radius: 0.375rem; }
        .ql-toolbar svg { width: 18px; height: 18px; display: inline-block; }
        .ql-toolbar button { width: 28px; height: 24px; }
        .ql-editor p { margin-bottom: 0.75em; }
        .ql-editor ol, .ql-editor ul { margin-bottom: 0.75em; }
        .ql-editor li { margin-bottom: 0.25em; }
        /* Override Tailwind preflight reset for list bullets/numbers (anti-pattern from NOTES) */
        .ql-editor ol { list-style: decimal; padding-left: 1.5em; }
        .ql-editor ul { list-style: disc; padding-left: 1.5em; }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        function faqForm(config) {
            return {
                quill: null,

                init() {
                    this.$nextTick(() => {
                        this.initQuill(config.initialAnswer);
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
                        placeholder: 'Write the answer here...',
                        modules: {
                            toolbar: toolbarOptions,
                        },
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
                        document.getElementById('answer-input').value = cleaned;
                    }
                },
            };
        }
    </script>
@endpush