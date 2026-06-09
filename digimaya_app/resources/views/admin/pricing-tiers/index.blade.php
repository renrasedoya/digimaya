<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Pricing Tiers') }}</h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Pricing Tiers']]" />
                </div>
            </div>
            <a href="{{ route('admin.pricing-tiers.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Tier
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
                                    'lower' => 'Lower 4-10jt (' . $counts['lower'] . ')',
                                    'upper' => 'Upper 11jt+ (' . $counts['upper'] . ')',
                                    'active' => 'Active (' . $counts['active'] . ')',
                                    'inactive' => 'Inactive (' . $counts['inactive'] . ')',
                                ];
                            @endphp
                            @foreach($tabs as $key => $label)
                                <a href="{{ route('admin.pricing-tiers.index', $key === 'all' ? [] : ['filter' => $key]) }}"
                                   class="py-2 px-1 border-b-2 text-sm font-medium {{ $currentFilter === $key ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    @if($tiers->isEmpty())
                        <p class="text-gray-500 text-center py-8">No pricing tiers found.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Budget</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Agency Fee</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Zone</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sort</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tiers as $tier)
                                    <tr>
                                        <td class="px-3 py-2 font-medium">Rp {{ number_format($tier->budget, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-gray-700">Rp {{ number_format($tier->agency_fee, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $tier->zone === 'lower' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ $tier->zone === 'lower' ? 'Lower' : 'Upper' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">
                                            @if($tier->is_active)
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-gray-600 text-sm">{{ $tier->sort_order }}</td>
                                        <td class="px-3 py-2 text-right">
                                            <a href="{{ route('admin.pricing-tiers.edit', $tier) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                            <form method="POST" action="{{ route('admin.pricing-tiers.destroy', $tier) }}" class="inline" onsubmit="return confirm('Delete this pricing tier?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-12">{{ $tiers->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
