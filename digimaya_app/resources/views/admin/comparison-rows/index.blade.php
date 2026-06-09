<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Comparison Table') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Components'],
                        ['label' => 'Comparison Table']
                    ]" />
                </div>
            </div>
            <a href="{{ route('admin.comparison-rows.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                + Add New Row
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <p class="text-sm text-gray-500 mb-6">
                        Comparison rows untuk section <strong>"Typical Agency vs Digimaya"</strong> di home & landing pages.
                        Header kolom (Typical Agency / Digimaya) di-hardcode di view.
                    </p>

                    <div class="border border-gray-200 rounded-md overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr class="text-left text-gray-600">
                                    <th class="px-4 py-3 w-16">Pos</th>
                                    <th class="px-4 py-3 w-48">Aspect</th>
                                    <th class="px-4 py-3">Value A (Typical)</th>
                                    <th class="px-4 py-3">Value B (Digimaya)</th>
                                    <th class="px-4 py-3 w-24">Status</th>
                                    <th class="px-4 py-3 w-32 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($rows as $row)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-600">{{ $row->position }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $row->aspect }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($row->value_a, 60) }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($row->value_b, 60) }}</td>
                                        <td class="px-4 py-3">
                                            @if($row->is_active)
                                                <span class="inline-block px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded">Active</span>
                                            @else
                                                <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('admin.comparison-rows.edit', $row) }}" class="text-indigo-600 hover:text-indigo-800 text-sm mr-3">Edit</a>
                                            <form action="{{ route('admin.comparison-rows.destroy', $row) }}" method="POST" class="inline" onsubmit="return confirm('Delete this row?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 text-sm">
                                            Belum ada comparison row.
                                            <a href="{{ route('admin.comparison-rows.create') }}" class="text-indigo-600 hover:underline">Buat sekarang</a>.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
