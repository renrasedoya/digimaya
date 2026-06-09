<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Income') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Income']]" />
                </div>
            </div>
            <a href="{{ route('admin.incomes.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Income
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary cards for selected month --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">Total ({{ \Carbon\Carbon::create($year, $month)->format('M Y') }})</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">IDR {{ number_format($summary['total'], 0, '.', ',') }}</div>
                    <div class="mt-1 text-xs text-gray-500">{{ $summary['count'] }} entries</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">Agency</div>
                    <div class="mt-1 text-2xl font-bold text-blue-600">IDR {{ number_format($summary['agency'], 0, '.', ',') }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">Academy</div>
                    <div class="mt-1 text-2xl font-bold text-purple-600">IDR {{ number_format($summary['academy'], 0, '.', ',') }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">Other</div>
                    <div class="mt-1 text-2xl font-bold text-gray-600">IDR {{ number_format($summary['other'], 0, '.', ',') }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Filters --}}
                    <form method="GET" action="{{ route('admin.incomes.index') }}" class="mb-6 flex flex-wrap gap-2">
                        <select name="month" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <select name="category" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Categories</option>
                            <option value="agency" {{ request('category') === 'agency' ? 'selected' : '' }}>Agency</option>
                            <option value="academy" {{ request('category') === 'academy' ? 'selected' : '' }}>Academy</option>
                            <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by client name..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.incomes.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    {{-- Table --}}
                    @if($incomes->isEmpty())
                        <p class="text-gray-500 text-center py-8">No income records found for the selected period.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($incomes as $income)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-600 text-sm">{{ $income->received_date->format('d M Y') }}</td>
                                        <td class="px-3 py-2 font-medium">{{ $income->client?->business_name ?: '-' }}</td>
                                        <td class="px-3 py-2 text-gray-600 text-sm">{{ $income->service?->name ?: '-' }}</td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $income->source_category === 'agency' ? 'bg-blue-100 text-blue-800' : ($income->source_category === 'academy' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ $income->source_category_label }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-right font-medium">{{ $income->formatted_amount }}</td>
                                        <td class="px-3 py-2 text-gray-600 text-sm">{{ $income->payment_method_label }}</td>
                                        <td class="px-3 py-2 text-right">
                                            <a href="{{ route('admin.incomes.edit', $income) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                            <form method="POST" action="{{ route('admin.incomes.destroy', $income) }}" class="inline" onsubmit="return confirm('Delete this income record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-12">{{ $incomes->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
