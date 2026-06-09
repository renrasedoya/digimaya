<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Modules') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Academy'],
                        ['label' => 'Modules']
                    ]" />
                </div>
            </div>
            <a href="{{ route('admin.academy.modules.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Module
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

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="GET" action="{{ route('admin.academy.modules.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Statuses</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by module title..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.academy.modules.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Materials</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tier</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($modules as $module)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-500">#{{ $module->display_order }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <a href="{{ route('admin.academy.modules.show', $module) }}" class="hover:underline font-medium">
                                                {{ $module->title }}
                                            </a>
                                            @if($module->description)
                                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($module->description, 80) }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $module->materials_count }}
                                            <span class="text-xs text-gray-500">{{ Str::plural('material', $module->materials_count) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($module->isPaid())
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-800">Paid</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Free</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($module->is_published)
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Draft</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-right whitespace-nowrap">
                                            <a href="{{ route('admin.academy.modules.show', $module) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                            <a href="{{ route('admin.academy.modules.edit', $module) }}" class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                            <form method="POST" action="{{ route('admin.academy.modules.destroy', $module) }}" class="inline" onsubmit="return confirm('Delete module \'{{ $module->title }}\'? Materials and member progress will also be removed.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada module.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $modules->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
