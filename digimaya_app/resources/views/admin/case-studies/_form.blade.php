{{-- Required vars: $caseStudy, $results, $formAction, $formMethod ('POST' or 'PUT') --}}

@php
    $isEdit = $caseStudy->exists;
    $existingThumbnailUrl = $caseStudy->thumbnail_url;
    $thumbnailIsExternal  = $isEdit ? $caseStudy->thumbnailIsExternal() : false;
    $resultsArray = $results->map(fn ($r) => ['value' => $r->value, 'label' => $r->label])->values()->all();
    // Tom Select pre-fill: prefer client_id (numeric), else use client_name as free-text value
    if ($isEdit && $caseStudy->client_id) {
        $clientPickerValue = (string) $caseStudy->client_id;
        $clientPickerText  = $caseStudy->client_name;
    } elseif ($isEdit && $caseStudy->client_name) {
        $clientPickerValue = $caseStudy->client_name;
        $clientPickerText  = $caseStudy->client_name;
    } else {
        $clientPickerValue = '';
        $clientPickerText  = '';
    }
@endphp

<div class="bg-white shadow-sm sm:rounded-lg"
     x-data="caseStudyForm({
         initialProblem: @js(old('problem', $caseStudy->problem ?? '')),
         initialSolution: @js(old('solution', $caseStudy->solution ?? '')),
         initialResults: @js(old('results', $resultsArray)),
         clientPickerValue: @js(old('client_picker', $clientPickerValue)),
         clientPickerText: @js($clientPickerText),
         clientPickerLocked: @js((bool) ($caseStudy->client_id ?? false)),
         existingThumbnailUrl: @js($existingThumbnailUrl),
         existingThumbnailIsExternal: @js($thumbnailIsExternal),
     })">

    @if($errors->any())
        <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $formAction }}"
          method="POST"
          enctype="multipart/form-data"
          @submit="syncEditorsToInputs()"
          class="p-6 space-y-6">
        @csrf
        @if($formMethod === 'PUT')
            @method('PUT')
        @endif

        {{-- ===== Client (Tom Select) + Industry (auto-fill, conditionally locked) ===== --}}
        {{-- ===== Title ===== --}}
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">
                Title <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title', $isEdit ? $caseStudy->title : '') }}"
                   maxlength="255"
                   required
                   placeholder="e.g. Scaling Campaigns Drive 165% Increase in Sales"
                   class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <p class="mt-1 text-xs text-gray-500">Headline impactful untuk card di home & halaman detail.</p>
            @error('title')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="client_picker" class="block text-sm font-medium text-gray-700">
                    Client <span class="text-red-500">*</span>
                </label>
                <select name="client_picker"
                        id="client_picker"
                        x-ref="clientPicker"
                        placeholder="Select client or type new..."
                        required>
                </select>
                <p class="mt-1 text-xs text-gray-500">Pick from clients or type any custom name.</p>
            </div>

            <div>
                <label for="industry" class="block text-sm font-medium text-gray-700">Industry</label>
                <input type="text"
                       id="industry"
                       name="industry"
                       x-model="industry"
                       :readonly="industryLocked"
                       :class="industryLocked ? 'bg-gray-50 cursor-not-allowed' : ''"
                       maxlength="255"
                       placeholder="e.g. Otomotif, F&B, Healthcare"
                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <p class="mt-1 text-xs text-gray-500" x-show="industryLocked">Auto-filled from selected client.</p>
                <p class="mt-1 text-xs text-gray-500" x-show="!industryLocked">Editable for custom (non-client) entries.</p>
            </div>
        </div>

        {{-- ===== Thumbnail ===== --}}
        <div class="pt-4 border-t border-gray-200">
            <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>

            <div class="flex gap-2 mb-2">
                <button type="button" @click="thumbnailTab = 'file'"
                        :class="thumbnailTab === 'file' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-3 py-1 text-xs font-semibold rounded">Upload File</button>
                <button type="button" @click="thumbnailTab = 'url'"
                        :class="thumbnailTab === 'url' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-3 py-1 text-xs font-semibold rounded">External URL</button>
            </div>

            <div x-show="thumbnailTab === 'file'">
                <input type="file" name="thumbnail_file" accept="image/*"
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4 file:rounded-md
                              file:border-0 file:text-sm file:font-semibold
                              file:bg-gray-100 file:text-gray-700
                              hover:file:bg-gray-200">
                <p class="mt-1 text-xs text-gray-500">Max 2MB. JPG/PNG/WEBP.</p>
            </div>

            <div x-show="thumbnailTab === 'url'">
                <input type="text" name="thumbnail_url" x-model="thumbnailUrlInput"
                       placeholder="https://..." value="{{ old('thumbnail_url', $thumbnailIsExternal ? $caseStudy->thumbnail : '') }}"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full">
            </div>

            @if($existingThumbnailUrl)
                <div class="mt-3">
                    <p class="text-xs text-gray-500 mb-1">Current thumbnail:</p>
                    <img src="{{ $existingThumbnailUrl }}" alt="Current" class="h-24 rounded border border-gray-200">
                </div>
            @endif
        </div>

        {{-- ===== Problem (Quill) ===== --}}
        <div class="pt-4 border-t border-gray-200">
            <label class="block text-sm font-medium text-gray-700 mb-1">Problem</label>
            <div id="problem-editor" class="bg-white"></div>
            <textarea id="problem-input" name="problem" class="hidden">{{ old('problem', $caseStudy->problem) }}</textarea>
            <p class="mt-2 text-xs text-gray-500">Describe the challenge or pain point the client faced.</p>
        </div>

        {{-- ===== Solution (Quill) ===== --}}
        <div class="pt-4 border-t border-gray-200">
            <label class="block text-sm font-medium text-gray-700 mb-1">Solution</label>
            <div id="solution-editor" class="bg-white"></div>
            <textarea id="solution-input" name="solution" class="hidden">{{ old('solution', $caseStudy->solution) }}</textarea>
            <p class="mt-2 text-xs text-gray-500">Describe the strategy or approach you implemented.</p>
        </div>

        {{-- ===== Results (Repeater) ===== --}}
        <div class="pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <label class="block text-sm font-medium text-gray-700">Results</label>
                <button type="button"
                        @click="addResult()"
                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    + Add result
                </button>
            </div>

            <div class="space-y-2">
                <template x-for="(row, idx) in results" :key="idx">
                    <div class="flex items-start gap-2">
                        <span class="text-xs text-gray-400 mt-2.5 w-5 text-right" x-text="(idx + 1) + '.'"></span>
                        <div class="flex-1 grid grid-cols-2 gap-2">
                            <input type="text"
                                   :name="`results[${idx}][value]`"
                                   x-model="row.value"
                                   placeholder="Value (e.g. 300%)"
                                   maxlength="100"
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm w-full">
                            <input type="text"
                                   :name="`results[${idx}][label]`"
                                   x-model="row.label"
                                   placeholder="Label (e.g. ROAS Increase)"
                                   maxlength="100"
                                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm w-full">
                        </div>
                        <div class="flex gap-1 mt-1">
                            <button type="button" @click="moveResult(idx, -1)" :disabled="idx === 0"
                                    :class="idx === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                                    class="p-1.5 text-gray-500 rounded" title="Move up">
                                ↑
                            </button>
                            <button type="button" @click="moveResult(idx, 1)" :disabled="idx === results.length - 1"
                                    :class="idx === results.length - 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                                    class="p-1.5 text-gray-500 rounded" title="Move down">
                                ↓
                            </button>
                            <button type="button" @click="removeResult(idx)"
                                    class="p-1.5 text-red-500 hover:bg-red-50 rounded" title="Remove">
                                ×
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="results.length === 0">
                    <p class="text-sm text-gray-400 italic py-2">No results added. Click "+ Add result" to start.</p>
                </template>
            </div>
            <p class="mt-3 text-xs text-gray-500">Add metric cards for the case study (e.g. "300%" / "ROAS Increase"). Max 20.</p>
        </div>

        {{-- ===== Order + Active ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
            <div>
                <label for="position_order" class="block text-sm font-medium text-gray-700">Display order</label>
                <input type="number"
                       id="position_order"
                       name="position_order"
                       value="{{ old('position_order', $caseStudy->position_order) }}"
                       min="0" max="9999"
                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <p class="mt-1 text-xs text-gray-500">Lower number = shown first.</p>
            </div>

            <div class="flex items-end pb-2">
                <label class="inline-flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           @checked(old('is_active', $caseStudy->is_active ?? true))
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Active (shown on public pages)</span>
                </label>
            </div>
        </div>

        {{-- ===== Actions ===== --}}
        <div class="flex justify-end space-x-2 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.case-studies.index') }}"
               class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                {{ $formMethod === 'PUT' ? 'Update Case Study' : 'Create Case Study' }}
            </button>
        </div>
    </form>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        function caseStudyForm(config) {
            return {
                problemQuill: null,
                solutionQuill: null,
                clientPicker: null,
                results: Array.isArray(config.initialResults) ? [...config.initialResults] : [],
                industry: @js(old('industry', $caseStudy->industry ?? '')),
                industryLocked: !!config.clientPickerLocked,
                thumbnailTab: 'file',
                thumbnailUrlInput: @js(old('thumbnail_url', $thumbnailIsExternal ? $caseStudy->thumbnail : '')),
                clientsSearchUrl: @json(route('admin.clients.search')),

                init() {
                    this.$nextTick(() => {
                        this.initQuills(config.initialProblem, config.initialSolution);
                        this.initClientPicker(config.clientPickerValue, config.clientPickerText);
                    });
                },

                initQuills(initialProblem, initialSolution) {
                    const toolbarOptions = [
                        ['bold', 'italic'],
                        ['link'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['clean'],
                    ];

                    this.problemQuill = new Quill('#problem-editor', {
                        theme: 'snow',
                        placeholder: 'Describe the problem...',
                        modules: { toolbar: toolbarOptions },
                    });
                    // Set content via root.innerHTML (bypasses dangerouslyPasteHTML null-selection bug)
                    if (initialProblem && typeof initialProblem === 'string') {
                        // Inject data-list attribute supaya Quill 2.x render OL/UL dengan benar
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(initialProblem, 'text/html');
                        doc.querySelectorAll('ol > li').forEach(li => li.setAttribute('data-list', 'ordered'));
                        doc.querySelectorAll('ul > li').forEach(li => li.setAttribute('data-list', 'bullet'));
                        this.problemQuill.root.innerHTML = doc.body.innerHTML;
                    }

                    this.solutionQuill = new Quill('#solution-editor', {
                        theme: 'snow',
                        placeholder: 'Describe the solution...',
                        modules: { toolbar: toolbarOptions },
                    });
                    if (initialSolution && typeof initialSolution === 'string') {
                        // Inject data-list attribute supaya Quill 2.x render OL/UL dengan benar
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(initialSolution, 'text/html');
                        doc.querySelectorAll('ol > li').forEach(li => li.setAttribute('data-list', 'ordered'));
                        doc.querySelectorAll('ul > li').forEach(li => li.setAttribute('data-list', 'bullet'));
                        this.solutionQuill.root.innerHTML = doc.body.innerHTML;
                    }
                },

                initClientPicker(initialValue, initialText) {
                    const self = this;
                    const el = this.$refs.clientPicker;
                    if (!el) return;

                    this.clientPicker = new TomSelect(el, {
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        preload: false,
                        maxItems: 1,
                        create: true,
                        createOnBlur: true,
                        persist: false,
                        load: function (query, callback) {
                            if (!query.length) return callback();
                            fetch(self.clientsSearchUrl + '?q=' + encodeURIComponent(query) + '&limit=20')
                                .then(r => r.json())
                                .then(data => callback(data))
                                .catch(() => callback());
                        },
                        render: {
                            option_create: function (data, escape) {
                                return '<div class="create">Use new: <strong>' + escape(data.input) + '</strong></div>';
                            },
                        },
                        onChange: function (value) {
                            self.handleClientChange(value);
                        },
                        onItemAdd: function (value, item) {
                            // Triggered when a new free-text item is created
                            self.handleClientChange(value);
                        },
                    });

                    // Pre-fill for edit mode
                    if (initialValue && initialText) {
                        const valueStr = String(initialValue);
                        const optionData = { value: valueStr, text: initialText };
                        // For existing client (numeric ID), include industry from saved data
                        if (/^\d+$/.test(valueStr)) {
                            optionData.industry = self.industry;
                        }
                        this.clientPicker.addOption(optionData);
                        this.clientPicker.setValue(valueStr, true); // silent — don't trigger onChange (preserve saved industry)
                    }
                },

                handleClientChange(value) {
                    if (!value) {
                        // Cleared
                        this.industryLocked = false;
                        // Don't clear industry text — user might want to keep it
                        return;
                    }

                    if (/^\d+$/.test(value)) {
                        // Numeric → existing client. Fetch industry from search endpoint by ID.
                        // We don't have a direct "fetch by ID" endpoint, but we can extract from picker's current option.
                        const option = this.clientPicker.options[value];
                        if (option && typeof option.industry !== 'undefined') {
                            this.industry = option.industry || '';
                            this.industryLocked = true;
                            return;
                        }

                        // Fallback: AJAX fetch by querying with empty (will hit by ID match somehow)
                        // — Skip; the option is already in the dropdown from search, so industry should be there.
                        this.industryLocked = true;
                    } else {
                        // Free-text → unlocked
                        this.industryLocked = false;
                    }
                },

                // ===== Results repeater =====
                addResult() {
                    if (this.results.length >= 20) {
                        alert('Maximum 20 results.');
                        return;
                    }
                    this.results.push({ value: '', label: '' });
                },

                removeResult(idx) {
                    this.results.splice(idx, 1);
                },

                moveResult(idx, direction) {
                    const newIdx = idx + direction;
                    if (newIdx < 0 || newIdx >= this.results.length) return;
                    const tmp = this.results[idx];
                    this.results[idx] = this.results[newIdx];
                    this.results[newIdx] = tmp;
                },

                // ===== Submit sync =====
                syncEditorsToInputs() {
                    if (this.problemQuill) {
                        const html = this.problemQuill.root.innerHTML;
                        document.getElementById('problem-input').value = (html === '<p><br></p>') ? '' : html;
                    }
                    if (this.solutionQuill) {
                        const html = this.solutionQuill.root.innerHTML;
                        document.getElementById('solution-input').value = (html === '<p><br></p>') ? '' : html;
                    }
                },
            };
        }
    </script>
@endpush