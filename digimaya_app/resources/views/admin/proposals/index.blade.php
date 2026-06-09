<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Proposals') }}</h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Proposals']]" />
                </div>
            </div>
            <a href="{{ route('admin.proposals.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + New Proposal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            @php
                                $currentFilter = request('filter', 'all');
                                $tabs = [
                                    'all' => 'All (' . $counts['total'] . ')',
                                    'draft' => 'Draft (' . $counts['draft'] . ')',
                                    'published' => 'Published (' . $counts['published'] . ')',
                                ];
                            @endphp
                            @foreach($tabs as $key => $label)
                                <a href="{{ route('admin.proposals.index', $key === 'all' ? [] : ['filter' => $key]) }}"
                                   class="py-2 px-1 border-b-2 text-sm font-medium {{ $currentFilter === $key ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <form method="GET" action="{{ route('admin.proposals.index') }}" class="mb-6 flex gap-2">
                        @if(request('filter'))<input type="hidden" name="filter" value="{{ request('filter') }}">@endif
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                               class="flex-1 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Search</button>
                    </form>

                    @if($proposals->isEmpty())
                        <p class="text-gray-500 text-center py-8">No proposals found.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Published</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($proposals as $proposal)
                                    <tr>
                                        <td class="px-3 py-2 font-medium">{{ $proposal->title }}</td>
                                        <td class="px-3 py-2 text-gray-600 text-sm">{{ $proposal->client->business_name ?? 'Client removed' }}</td>
                                        <td class="px-3 py-2">
                                            @if($proposal->isPublished())
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Draft</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-gray-500 text-sm">{{ $proposal->published_at?->format('d M Y, H:i') ?? '-' }}</td>
                                        <td class="px-3 py-2 text-right">
                                            <a href="{{ route('admin.proposals.edit', $proposal) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                            <form method="POST" action="{{ route('admin.proposals.destroy', $proposal) }}" class="inline" onsubmit="return confirm('Delete this proposal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-12">{{ $proposals->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
