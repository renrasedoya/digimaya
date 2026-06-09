<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Logo Wall') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Components'],
                        ['label' => 'Logo Wall']
                    ]" />
                </div>
            </div>
            <button type="button" x-data
                    @click="$dispatch('open-logo-wall-modal', { mode: 'create' })"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                + Add New Item
            </button>
        </div>
    </x-slot>

    <div class="py-12"
         x-data="logoWallModal()"
         @open-logo-wall-modal.window="open($event.detail)">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

            {{-- Group filter tabs (dynamic from existing groups) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="px-6 py-3 border-b border-gray-200">
                    @php $currentGroup = $groupFilter ?: 'all'; @endphp
                    <nav class="flex space-x-6 -mb-3 overflow-x-auto">
                        <a href="{{ route('admin.logo-wall.index') }}"
                           class="py-2 px-1 border-b-2 text-sm font-medium whitespace-nowrap {{ $currentGroup === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                            All ({{ $totalCount }})
                        </a>
                        @foreach($groupCounts as $group => $count)
                            <a href="{{ route('admin.logo-wall.index', ['group' => $group]) }}"
                               class="py-2 px-1 border-b-2 text-sm font-medium whitespace-nowrap {{ $currentGroup === $group ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                {{ $group }} ({{ $count }})
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            {{-- Search --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                <div class="p-4">
                    <form method="GET" action="{{ route('admin.logo-wall.index') }}" class="flex gap-2">
                        @if($groupFilter)
                            <input type="hidden" name="group" value="{{ $groupFilter }}">
                        @endif
                        <input type="text"
                               name="q"
                               value="{{ $search }}"
                               placeholder="Search by name..."
                               class="flex-1 text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
                        <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
                            Search
                        </button>
                        @if($search !== '')
                            <a href="{{ route('admin.logo-wall.index', $groupFilter ? ['group' => $groupFilter] : []) }}"
                               class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-50">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($items->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-sm">
                                @if($groupFilter || $search !== '')
                                    No items match your filter.
                                @else
                                    No logo wall items yet.
                                @endif
                            </p>
                            @if(! $groupFilter && $search === '')
                                <button type="button" x-data
                                        @click="$dispatch('open-logo-wall-modal', { mode: 'create' })"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Add your first item
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($items as $item)
                                        <tr class="hover:bg-gray-50">
                                            {{-- Logo thumbnail --}}
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($item->image_url)
                                                    <img src="{{ $item->image_url }}"
                                                         alt="{{ $item->name }}"
                                                         class="w-14 h-10 rounded object-contain bg-gray-50 border border-gray-100">
                                                @else
                                                    <div class="w-14 h-10 rounded bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                                        —
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Name --}}
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                {{ $item->name }}
                                            </td>

                                            {{-- Group --}}
                                            <td class="px-4 py-3 text-sm">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700">
                                                    {{ $item->group }}
                                                </span>
                                            </td>

                                            {{-- Order --}}
                                            <td class="px-4 py-3 text-center text-sm text-gray-500">
                                                {{ $item->position_order }}
                                            </td>

                                            {{-- Status (toggle) --}}
                                            <td class="px-4 py-3 text-center">
                                                <form action="{{ route('admin.logo-wall.update', $item) }}"
                                                      method="POST"
                                                      class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="toggle_only" value="1">
                                                    <input type="hidden" name="is_active" value="{{ $item->is_active ? '0' : '1' }}">
                                                    <button type="submit"
                                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                                                            title="Click to {{ $item->is_active ? 'deactivate' : 'activate' }}">
                                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm space-x-2">
                                                <button type="button"
                                                        @click='$dispatch("open-logo-wall-modal", {
                                                            mode: "edit",
                                                            id: {{ $item->id }},
                                                            name: @json($item->name),
                                                            group: @json($item->group),
                                                            image_url: @json($item->imageIsExternal() ? $item->image : ""),
                                                            image_existing: @json($item->image_url),
                                                            is_active: {{ $item->is_active ? "true" : "false" }},
                                                            position_order: {{ $item->position_order }}
                                                        })'
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                    Edit
                                                </button>

                                                <form action="{{ route('admin.logo-wall.destroy', $item) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Delete logo wall item &quot;{{ addslashes($item->name) }}&quot;? This cannot be undone.');">
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

                        @if($items->hasPages())
                            <div class="mt-6">
                                {{ $items->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- ============== MODAL ============== --}}
            <div x-show="modalOpen"
                 x-cloak
                 x-transition.opacity
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 py-8">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="close()"></div>

                    <div class="relative bg-white rounded-lg max-w-lg w-full shadow-xl"
                         @click.stop>
                        <form :action="formAction"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : ''">

                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900"
                                    x-text="mode === 'create' ? 'Add Logo Wall Item' : 'Edit Logo Wall Item'"></h3>
                                <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                            </div>

                            <div class="px-6 py-4 space-y-4">
                                {{-- Name --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" x-model="form.name" required maxlength="255"
                                           placeholder="e.g. PT Maju Jaya, Google Premier Partner 2025"
                                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                </div>

                                {{-- Group --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Group <span class="text-red-500">*</span></label>
                                    <input type="text" name="group" x-model="form.group" required maxlength="50"
                                           list="logo-wall-group-suggestions"
                                           pattern="[a-z0-9_]+"
                                           placeholder="e.g. clients, badges, social_proof"
                                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                    <datalist id="logo-wall-group-suggestions">
                                        @foreach($existingGroups as $g)
                                            <option value="{{ $g }}">
                                        @endforeach
                                    </datalist>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Lowercase letters, numbers, underscores only.
                                        @if(count($existingGroups))
                                            Existing: <span class="">{{ implode(', ', $existingGroups) }}</span>.
                                        @endif
                                    </p>
                                </div>

                                {{-- Image (file or URL) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo Image</label>
                                    <div class="flex gap-2 mb-2">
                                        <button type="button" @click="imageTab = 'file'"
                                                :class="imageTab === 'file' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700'"
                                                class="px-3 py-1 text-xs font-semibold rounded">Upload File</button>
                                        <button type="button" @click="imageTab = 'url'"
                                                :class="imageTab === 'url' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700'"
                                                class="px-3 py-1 text-xs font-semibold rounded">External URL</button>
                                    </div>
                                    <div x-show="imageTab === 'file'">
                                        <input type="file" name="image_file" accept="image/*"
                                               class="block w-full text-sm text-gray-500
                                                      file:mr-4 file:py-2 file:px-4 file:rounded-md
                                                      file:border-0 file:text-sm file:font-semibold
                                                      file:bg-gray-100 file:text-gray-700
                                                      hover:file:bg-gray-200">
                                        <p class="mt-1 text-xs text-gray-500">Max 1MB. PNG/SVG recommended (transparent background).</p>
                                    </div>
                                    <div x-show="imageTab === 'url'">
                                        <input type="url" name="image_url" x-model="form.image_url"
                                               placeholder="https://..."
                                               class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full">
                                    </div>

                                    <template x-if="mode === 'edit' && form.image_existing">
                                        <div class="mt-3">
                                            <p class="text-xs text-gray-500 mb-1">Current logo:</p>
                                            <img :src="form.image_existing" alt="Current" class="h-12 rounded border border-gray-200 bg-gray-50 object-contain px-2">
                                        </div>
                                    </template>
                                </div>

                                {{-- Order + Active --}}
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Display order</label>
                                        <input type="number" name="position_order" x-model="form.position_order" min="0" max="9999"
                                               class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                        <p class="mt-1 text-xs text-gray-500">Lower = first.</p>
                                    </div>
                                    <div class="flex items-end pb-2">
                                        <label class="inline-flex items-center">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active"
                                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700">Active</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-2">
                                <button type="button" @click="close()"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700"
                                        x-text="mode === 'create' ? 'Save Item' : 'Update Item'"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function logoWallModal() {
                return {
                    modalOpen: false,
                    mode: 'create',
                    imageTab: 'file',
                    form: {
                        id: null,
                        name: '',
                        group: '',
                        image_url: '',
                        image_existing: '',
                        is_active: true,
                        position_order: 0
                    },
                    storeUrl: @json(route('admin.logo-wall.store')),
                    updateUrlBase: @json(url('admin/logo-wall')),

                    get formAction() {
                        return this.mode === 'create'
                            ? this.storeUrl
                            : `${this.updateUrlBase}/${this.form.id}`;
                    },

                    open(payload) {
                        this.mode = payload.mode;
                        this.imageTab = 'file';

                        if (payload.mode === 'edit') {
                            this.form.id = payload.id;
                            this.form.name = payload.name || '';
                            this.form.group = payload.group || '';
                            this.form.image_url = payload.image_url || '';
                            this.form.image_existing = payload.image_existing || '';
                            this.form.is_active = !!payload.is_active;
                            this.form.position_order = payload.position_order || 0;

                            if (payload.image_url) {
                                this.imageTab = 'url';
                            }
                        } else {
                            // Pre-fill group from current filter (UX nicety)
                            const urlParams = new URLSearchParams(window.location.search);
                            const currentGroup = urlParams.get('group');

                            this.form.id = null;
                            this.form.name = '';
                            this.form.group = currentGroup && currentGroup !== 'all' ? currentGroup : '';
                            this.form.image_url = '';
                            this.form.image_existing = '';
                            this.form.is_active = true;
                            this.form.position_order = 0;
                        }

                        this.modalOpen = true;
                    },

                    close() {
                        this.modalOpen = false;
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
