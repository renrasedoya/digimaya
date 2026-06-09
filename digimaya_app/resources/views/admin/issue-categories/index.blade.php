<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Issue Categories') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Issue Categories']]" />
                </div>
            </div>
            <a href="{{ route('admin.issue-categories.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Category
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <p class="text-sm text-gray-500 mb-6">
                        Master data untuk issue categories yang muncul di Project Reports. Sub-categories tidak bisa di-delete (FK dari project_reports), tapi bisa di-set inactive.
                    </p>

                    @if($categories->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            No categories found. <a href="{{ route('admin.issue-categories.create') }}" class="text-indigo-600 hover:text-indigo-900">Create one</a>.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-20">Order</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sub-categories</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Status</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase w-32">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($categories as $cat)
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-600">{{ $cat->display_order }}</td>
                                            <td class="px-3 py-2 font-medium">
                                                <a href="{{ route('admin.issue-categories.edit', $cat) }}" class="text-indigo-600 hover:text-indigo-900">{{ $cat->name }}</a>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-600">
                                                @php
                                                    $activeSubs = $cat->subCategories->where('is_active', true);
                                                    $inactiveSubs = $cat->subCategories->where('is_active', false);
                                                @endphp
                                                <span class="text-gray-900">{{ $activeSubs->count() }} active</span>
                                                @if($inactiveSubs->count() > 0)
                                                    <span class="text-gray-400 ml-2">, {{ $inactiveSubs->count() }} inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2">
                                                @if($cat->is_active)
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.issue-categories.edit', $cat) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                                <form method="POST" action="{{ route('admin.issue-categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Delete this category? Sub-categories used in reports will block deletion.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-12">{{ $categories->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
