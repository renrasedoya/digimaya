<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Blog Categories') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Marketing'],
                        ['label' => 'Categories']
                    ]" />
                </div>
            </div>
            <button type="button"
                    x-data
                    @click="$dispatch('open-category-modal', { mode: 'create' })"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                + Add Category
            </button>
        </div>
    </x-slot>

    <div class="py-12"
         x-data="categoryModal()"
         @open-category-modal.window="open($event.detail)">

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

            {{-- Categories table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if($categories->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-sm">No categories yet.</p>
                            <button type="button"
                                    @click="$dispatch('open-category-modal', { mode: 'create' })"
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Create your first category
                            </button>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($categories as $category)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                            {{ $category->posts_count }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm space-x-2">
                                            <button type="button"
                                                    @click='$dispatch("open-category-modal", {
                                                        mode: "edit",
                                                        id: {{ $category->id }},
                                                        name: @json($category->name)
                                                    })'
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </button>

                                            @if(auth()->user()->role === 'super_admin')
                                                <form action="{{ route('admin.blog-categories.destroy', $category) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Delete category &quot;{{ $category->name }}&quot;? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="ml-2 {{ $category->posts_count > 0 ? 'text-gray-400 cursor-not-allowed' : 'text-red-600 hover:text-red-900' }}"
                                                            @if($category->posts_count > 0) disabled title="Cannot delete: {{ $category->posts_count }} post(s) attached" @endif>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($categories->hasPages())
                            <div class="mt-6">
                                {{ $categories->links() }}
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>

        {{-- Modal: Create / Edit Category --}}
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
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="close()"></div>

            {{-- Modal panel --}}
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full"
                     @click.stop
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                    <form :action="formAction"
                          method="POST"
                          class="p-6">
                        @csrf
                        <template x-if="mode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <h3 class="text-lg font-semibold text-gray-900 mb-4"
                            x-text="mode === 'create' ? 'Add Category' : 'Edit Category'"></h3>

                        {{-- Name --}}
                        <div class="mb-6">
                            <label for="modal_name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                            <input type="text"
                                   id="modal_name"
                                   name="name"
                                   x-model="form.name"
                                   maxlength="100"
                                   required
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            <p class="mt-1 text-xs text-gray-500">Slug will be auto-generated from name. Duplicate names get a suffix (e.g. <code>google-ads-2</code>).</p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end space-x-2">
                            <button type="button"
                                    @click="close()"
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

    @push('scripts')
        <script>
            function categoryModal() {
                return {
                    modalOpen: false,
                    mode: 'create',
                    form: {
                        id: null,
                        name: ''
                    },
                    storeUrl: @json(route('admin.blog-categories.store')),
                    updateUrlBase: @json(url('admin/blog-categories')),

                    get formAction() {
                        return this.mode === 'create'
                            ? this.storeUrl
                            : `${this.updateUrlBase}/${this.form.id}`;
                    },

                    open(payload) {
                        this.mode = payload.mode;
                        if (payload.mode === 'edit') {
                            this.form.id = payload.id;
                            this.form.name = payload.name;
                        } else {
                            this.form.id = null;
                            this.form.name = '';
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