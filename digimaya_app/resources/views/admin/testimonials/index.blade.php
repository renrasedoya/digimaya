<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Testimonials') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Components'],
                        ['label' => 'Testimonials']
                    ]" />
                </div>
            </div>
            <button type="button"
                    x-data
                    @click="$dispatch('open-testimonial-modal', { mode: 'create' })"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                + Add Testimonial
            </button>
        </div>
    </x-slot>

    <div class="py-12"
         x-data="testimonialModal()"
         @open-testimonial-modal.window="open($event.detail)">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
                    <p class="font-semibold mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Filter bar --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                <div class="p-4">
                    <form method="GET" action="{{ route('admin.testimonials.index') }}" class="flex flex-col sm:flex-row gap-3">
                        <div class="sm:w-48">
                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="w-full text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
                                <option value="">All status</option>
                                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active only</option>
                                <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive only</option>
                            </select>
                        </div>
                        <div class="flex-1 flex gap-2">
                            <input type="text"
                                   name="q"
                                   value="{{ $search }}"
                                   placeholder="Search by name, company, or quote..."
                                   class="flex-1 text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
                            <button type="submit"
                                    class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
                                Search
                            </button>
                            @if($statusFilter || $search !== '')
                                <a href="{{ route('admin.testimonials.index') }}"
                                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-50">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if($testimonials->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-sm">
                                @if($statusFilter || $search !== '')
                                    No testimonials match your filter.
                                @else
                                    No testimonials yet.
                                @endif
                            </p>
                            @if(! $statusFilter && $search === '')
                                <button type="button"
                                        @click="$dispatch('open-testimonial-modal', { mode: 'create' })"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Create your first testimonial
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name & Position</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quote</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($testimonials as $t)
                                        <tr class="hover:bg-gray-50">
                                            {{-- Photo --}}
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($t->photo_url)
                                                    <img src="{{ $t->photo_url }}"
                                                         alt="{{ $t->name }}"
                                                         class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-sm font-semibold">
                                                        {{ strtoupper(mb_substr($t->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Name & Position --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="font-medium text-gray-900">{{ $t->name }}</div>
                                                @if($t->position || $t->company)
                                                    <div class="text-xs text-gray-500">
                                                        {{ $t->position }}{{ $t->position && $t->company ? ' @ ' : '' }}{{ $t->company }}
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Quote excerpt --}}
                                            <td class="px-4 py-3 text-sm text-gray-600 max-w-md">
                                                <p class="line-clamp-2">{{ \Illuminate\Support\Str::limit($t->quote, 120) }}</p>
                                            </td>

                                            {{-- Rating --}}
                                            <td class="px-4 py-3 text-center text-sm">
                                                @if($t->rating)
                                                    <span class="text-yellow-500">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            {{ $i <= $t->rating ? '★' : '☆' }}
                                                        @endfor
                                                    </span>
                                                @else
                                                    <span class="text-gray-300 text-xs">—</span>
                                                @endif
                                            </td>

                                            {{-- Position order --}}
                                            <td class="px-4 py-3 text-center text-sm text-gray-500">
                                                {{ $t->position_order }}
                                            </td>

                                            {{-- Status (toggle) --}}
                                            <td class="px-4 py-3 text-center">
                                                <form action="{{ route('admin.testimonials.update', $t) }}"
                                                      method="POST"
                                                      class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="toggle_only" value="1">
                                                    <input type="hidden" name="is_active" value="{{ $t->is_active ? '0' : '1' }}">
                                                    <button type="submit"
                                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $t->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                                                            title="Click to {{ $t->is_active ? 'deactivate' : 'activate' }}">
                                                        {{ $t->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm space-x-2">
                                                <button type="button"
                                                        @click='$dispatch("open-testimonial-modal", {
                                                            mode: "edit",
                                                            id: {{ $t->id }},
                                                            name: @json($t->name),
                                                            position: @json($t->position),
                                                            company: @json($t->company),
                                                            client_id: {{ $t->client_id ?? 'null' }},
                                                            quote: @json($t->quote),
                                                            rating: {{ $t->rating ?? 'null' }},
                                                            photo_url: @json($t->photoIsExternal() ? $t->photo : ''),
                                                            photo_existing: @json($t->photo_url),
                                                            is_active: {{ $t->is_active ? 'true' : 'false' }},
                                                            position_order: {{ $t->position_order }}
                                                        })'
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                    Edit
                                                </button>

                                                <form action="{{ route('admin.testimonials.destroy', $t) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Delete testimonial from &quot;{{ addslashes($t->name) }}&quot;? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ml-2 text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($testimonials->hasPages())
                            <div class="mt-6">
                                {{ $testimonials->links() }}
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>

        {{-- ============== MODAL ============== --}}
        <div x-show="modalOpen"
             x-cloak
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             @keydown.escape.window="close()">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="close()"></div>

            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full"
                     @click.stop
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                    <form :action="formAction" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        <template x-if="mode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <h3 class="text-lg font-semibold text-gray-900 mb-5"
                            x-text="mode === 'create' ? 'Add Testimonial' : 'Edit Testimonial'"></h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Name --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="form.name" required maxlength="255"
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            </div>

                            {{-- Position --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Position</label>
                                <input type="text" name="position" x-model="form.position" maxlength="255"
                                       placeholder="CEO, Marketing Manager..."
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            </div>

                            {{-- Company (Tom Select with AJAX clients + free-text) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Company</label>
                                <select name="company_picker"
                                        x-ref="companyPicker"
                                        placeholder="Select client or type new...">
                                    <template x-if="form.company_initial_value && form.company_initial_text">
                                        <option :value="form.company_initial_value" selected x-text="form.company_initial_text"></option>
                                    </template>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Pick from clients or type any custom name.</p>
                            </div>

                            {{-- Photo (file or URL) --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>

                                {{-- Tabs: file vs url --}}
                                <div class="flex gap-2 mb-2">
                                    <button type="button" @click="photoTab = 'file'"
                                            :class="photoTab === 'file' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700'"
                                            class="px-3 py-1 text-xs font-semibold rounded">Upload File</button>
                                    <button type="button" @click="photoTab = 'url'"
                                            :class="photoTab === 'url' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700'"
                                            class="px-3 py-1 text-xs font-semibold rounded">External URL</button>
                                </div>

                                <div x-show="photoTab === 'file'">
                                    <input type="file" name="photo_file" accept="image/*"
                                           class="block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4 file:rounded-md
                                                  file:border-0 file:text-sm file:font-semibold
                                                  file:bg-gray-100 file:text-gray-700
                                                  hover:file:bg-gray-200">
                                    <p class="mt-1 text-xs text-gray-500">Max 2MB. JPG/PNG/WEBP.</p>
                                </div>

                                <div x-show="photoTab === 'url'">
                                    <input type="url" name="photo_url" x-model="form.photo_url"
                                           placeholder="https://..."
                                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full">
                                </div>

                                {{-- Existing photo preview (edit mode) --}}
                                <template x-if="mode === 'edit' && form.photo_existing">
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500 mb-1">Current photo:</p>
                                        <img :src="form.photo_existing" class="w-16 h-16 rounded-full object-cover">
                                    </div>
                                </template>
                            </div>

                            {{-- Quote --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Quote <span class="text-red-500">*</span></label>
                                <textarea name="quote" x-model="form.quote" required rows="4" maxlength="5000"
                                          placeholder="What did the client say..."
                                          class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full"></textarea>
                            </div>

                            {{-- Rating --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rating</label>
                                <select name="rating" x-model="form.rating"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    <option value="">— No rating —</option>
                                    <option value="5">★★★★★ (5)</option>
                                    <option value="4">★★★★☆ (4)</option>
                                    <option value="3">★★★☆☆ (3)</option>
                                    <option value="2">★★☆☆☆ (2)</option>
                                    <option value="1">★☆☆☆☆ (1)</option>
                                </select>
                            </div>

                            {{-- Position order --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Display order</label>
                                <input type="number" name="position_order" x-model.number="form.position_order"
                                       min="0" max="9999"
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <p class="mt-1 text-xs text-gray-500">Lower = shown first.</p>
                            </div>

                            {{-- Active --}}
                            <div class="sm:col-span-2">
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                           x-model="form.is_active"
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Active (shown on public pages)</span>
                                </label>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end space-x-2 mt-6 pt-4 border-t border-gray-200">
                            <button type="button" @click="close()"
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                                <span x-text="mode === 'create' ? 'Create' : 'Update'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            function testimonialModal() {
                return {
                    modalOpen: false,
                    mode: 'create',
                    photoTab: 'file',
                    companyPickerInstance: null,
                    form: {
                        id: null,
                        name: '',
                        position: '',
                        company_initial_value: '',
                        company_initial_text: '',
                        quote: '',
                        rating: '',
                        photo_url: '',
                        photo_existing: '',
                        is_active: true,
                        position_order: 0
                    },
                    storeUrl: @json(route('admin.testimonials.store')),
                    updateUrlBase: @json(url('admin/testimonials')),
                    clientsSearchUrl: @json(route('admin.clients.search')),

                    get formAction() {
                        return this.mode === 'create'
                            ? this.storeUrl
                            : `${this.updateUrlBase}/${this.form.id}`;
                    },

                    open(payload) {
                        this.mode = payload.mode;
                        this.photoTab = 'file';

                        if (payload.mode === 'edit') {
                            this.form.id = payload.id;
                            this.form.name = payload.name || '';
                            this.form.position = payload.position || '';
                            // Tom Select pre-fill: prefer client_id, else use company text as free-text value
                            if (payload.client_id) {
                                this.form.company_initial_value = String(payload.client_id);
                                this.form.company_initial_text = payload.company || '';
                            } else if (payload.company) {
                                this.form.company_initial_value = payload.company;
                                this.form.company_initial_text = payload.company;
                            } else {
                                this.form.company_initial_value = '';
                                this.form.company_initial_text = '';
                            }
                            this.form.quote = payload.quote || '';
                            this.form.rating = payload.rating || '';
                            this.form.photo_url = payload.photo_url || '';
                            this.form.photo_existing = payload.photo_existing || '';
                            this.form.is_active = !!payload.is_active;
                            this.form.position_order = payload.position_order || 0;

                            if (payload.photo_url) {
                                this.photoTab = 'url';
                            }
                        } else {
                            this.form.id = null;
                            this.form.name = '';
                            this.form.position = '';
                            this.form.company_initial_value = '';
                            this.form.company_initial_text = '';
                            this.form.quote = '';
                            this.form.rating = '';
                            this.form.photo_url = '';
                            this.form.photo_existing = '';
                            this.form.is_active = true;
                            this.form.position_order = 0;
                        }

                        this.modalOpen = true;

                        // Init Tom Select after modal renders (next tick to ensure <option> from x-if is in DOM)
                        this.$nextTick(() => {
                            this.initCompanyPicker();
                        });
                    },

                    initCompanyPicker() {
                        // Destroy previous instance if exists (reuse modal for multiple opens)
                        if (this.companyPickerInstance) {
                            this.companyPickerInstance.destroy();
                            this.companyPickerInstance = null;
                        }

                        const el = this.$refs.companyPicker;
                        if (!el) return;

                        const initialValue = this.form.company_initial_value;
                        const initialText  = this.form.company_initial_text;
                        const searchUrl    = this.clientsSearchUrl;

                        this.companyPickerInstance = new TomSelect(el, {
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
                                fetch(searchUrl + '?q=' + encodeURIComponent(query) + '&limit=20')
                                    .then(r => r.json())
                                    .then(data => callback(data))
                                    .catch(() => callback());
                            },
                            render: {
                                option_create: function (data, escape) {
                                    return '<div class="create">Use new: <strong>' + escape(data.input) + '</strong></div>';
                                },
                            },
                        });

                        // Pre-select for edit mode
                        if (initialValue && initialText) {
                            this.companyPickerInstance.addOption({ value: initialValue, text: initialText });
                            this.companyPickerInstance.setValue(initialValue, true); // silent
                        }
                    },

                    close() {
                        this.modalOpen = false;
                        if (this.companyPickerInstance) {
                            this.companyPickerInstance.destroy();
                            this.companyPickerInstance = null;
                        }
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>