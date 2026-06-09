<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('FAQs') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Components'],
                        ['label' => 'FAQs']
                    ]" />
                </div>
            </div>
            <a href="{{ route('admin.faqs.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                + Add New FAQ
            </a>
        </div>
    </x-slot>

    <div class="py-12">
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

            {{-- Status tabs --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="px-6 py-3 border-b border-gray-200">
                    @php
                        $currentStatus = $statusFilter ?: 'all';
                        $tabs = [
                            'all'      => 'All (' . $counts['all'] . ')',
                            'active'   => 'Active (' . $counts['active'] . ')',
                            'inactive' => 'Inactive (' . $counts['inactive'] . ')',
                        ];
                    @endphp
                    <nav class="flex space-x-6 -mb-3">
                        @foreach($tabs as $key => $label)
                            <a href="{{ route('admin.faqs.index', $key === 'all' ? [] : ['status' => $key]) }}"
                               class="py-2 px-1 border-b-2 text-sm font-medium {{ $currentStatus === $key ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            {{-- Search --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-4">
                <div class="p-4">
                    <form method="GET" action="{{ route('admin.faqs.index') }}" class="flex gap-2">
                        @if($statusFilter)
                            <input type="hidden" name="status" value="{{ $statusFilter }}">
                        @endif
                        <input type="text"
                               name="q"
                               value="{{ $search }}"
                               placeholder="Search by question or answer..."
                               class="flex-1 text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
                        <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
                            Search
                        </button>
                        @if($search !== '')
                            <a href="{{ route('admin.faqs.index', $statusFilter ? ['status' => $statusFilter] : []) }}"
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
                    @if($faqs->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-sm">
                                @if($statusFilter || $search !== '')
                                    No FAQs match your filter.
                                @else
                                    No FAQs yet.
                                @endif
                            </p>
                            @if(! $statusFilter && $search === '')
                                <a href="{{ route('admin.faqs.create') }}"
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Create your first FAQ
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question & Answer</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($faqs as $faq)
                                        <tr class="hover:bg-gray-50">
                                            {{-- Question + Answer preview --}}
                                            <td class="px-4 py-3 max-w-2xl">
                                                <div class="text-sm font-medium text-gray-900">{{ $faq->question }}</div>
                                                <div class="text-xs text-gray-500 mt-1 line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($faq->answer), 120) }}
                                                </div>
                                            </td>

                                            {{-- Order --}}
                                            <td class="px-4 py-3 text-center text-sm text-gray-500">
                                                {{ $faq->position_order }}
                                            </td>

                                            {{-- Status (toggle) --}}
                                            <td class="px-4 py-3 text-center">
                                                <form action="{{ route('admin.faqs.update', $faq) }}"
                                                      method="POST"
                                                      class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="toggle_only" value="1">
                                                    <input type="hidden" name="is_active" value="{{ $faq->is_active ? '0' : '1' }}">
                                                    <button type="submit"
                                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $faq->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                                                            title="Click to {{ $faq->is_active ? 'deactivate' : 'activate' }}">
                                                        {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm space-x-2">
                                                <a href="{{ route('admin.faqs.edit', $faq) }}"
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Edit
                                                </a>

                                                <form action="{{ route('admin.faqs.destroy', $faq) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Delete this FAQ? This cannot be undone.');">
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

                        @if($faqs->hasPages())
                            <div class="mt-6">
                                {{ $faqs->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
