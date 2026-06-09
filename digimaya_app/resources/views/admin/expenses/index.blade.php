<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Expense') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Expense']]" />
                </div>
            </div>
            <a href="{{ route('admin.expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Expense
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

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Pending Recurring drafts (current month reminder) --}}
            @if($pendingDrafts->isNotEmpty())
                <div class="bg-amber-50 border border-amber-200 rounded-lg mb-6">
                    <div class="px-4 py-3 border-b border-amber-200">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-amber-700"></span>
                            <span class="text-sm font-semibold text-amber-800">
                                Pending Recurring {{ now()->format('F Y') }}
                            </span>
                            <span class="text-xs text-amber-700">({{ $pendingDrafts->count() }} need review)</span>
                        </div>
                        <p class="mt-1 text-xs text-amber-700">
                            Confirm to record, edit if the amount changed, or skip if it does not apply this period.
                        </p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($pendingDrafts as $draft)
                            <div class="px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $draft->category?->name ?: 'Uncategorized' }}
                                        @if($draft->vendor_name)
                                            <span class="text-gray-500 font-normal">· {{ $draft->vendor_name }}</span>
                                        @endif
                                        <span class="ml-1 inline-flex px-2 py-0.5 text-xs rounded-full bg-orange-100 text-orange-800">{{ $draft->recurring_type_label }}</span>
                                    </div>
                                    <div class="text-sm text-gray-700 mt-0.5">IDR {{ number_format($draft->amount, 0, '.', ',') }}</div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <form method="POST" action="{{ route('admin.expenses.confirm-recurring', $draft) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-md text-xs">Confirm</button>
                                    </form>
                                    <a href="{{ route('admin.expenses.edit', $draft) }}" class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-md text-xs hover:bg-gray-50">Edit</a>
                                    <form method="POST" action="{{ route('admin.expenses.skip-recurring', $draft) }}" onsubmit="return confirm('Skip this recurring expense for {{ now()->format('F Y') }}? It will not be counted.')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 border border-gray-300 text-gray-600 rounded-md text-xs hover:bg-gray-50">Skip</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">Total ({{ \Carbon\Carbon::create($year, $month)->format('M Y') }})</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">IDR {{ number_format($summary['total'], 0, '.', ',') }}</div>
                    <div class="mt-1 text-xs text-gray-500">{{ $summary['count'] }} entries</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">Recurring</div>
                    <div class="mt-1 text-2xl font-bold text-orange-600">IDR {{ number_format($summary['recurring'], 0, '.', ',') }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">One-time</div>
                    <div class="mt-1 text-2xl font-bold text-gray-600">IDR {{ number_format($summary['one_time'], 0, '.', ',') }}</div>
                </div>
            </div>

            {{-- Category breakdown card --}}
            @if($categoryBreakdown->isNotEmpty())
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="text-sm font-medium text-gray-700 mb-3">Breakdown by Category</div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($categoryBreakdown as $row)
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">{{ $row->category?->name ?: 'Uncategorized' }}</span>
                                <span class="text-sm font-semibold">IDR {{ number_format($row->total, 0, '.', ',') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Filters --}}
                    <form method="GET" action="{{ route('admin.expenses.index') }}" class="mb-6 flex flex-wrap gap-2">
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
                        <select name="category_id" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="recurring" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All Types</option>
                            <option value="one_time" {{ request('recurring') === 'one_time' ? 'selected' : '' }}>One Time</option>
                            <option value="monthly" {{ request('recurring') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ request('recurring') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vendor or description..."
                               class="flex-1 min-w-[200px] border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Apply</button>
                        <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>

                    {{-- Table --}}
                    @if($expenses->isEmpty())
                        <p class="text-gray-500 text-center py-8">No expense records found for the selected period.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($expenses as $expense)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-600 text-sm">{{ $expense->expense_date->format('F Y') }}</td>
                                        <td class="px-3 py-2 font-medium">{{ $expense->category?->name ?: '-' }}</td>
                                        <td class="px-3 py-2 text-gray-600 text-sm">{{ $expense->vendor_name ?: '-' }}</td>
                                        <td class="px-3 py-2 text-right font-medium">{{ $expense->formatted_amount }}</td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $expense->recurring_type === 'one_time' ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ $expense->recurring_type_label }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-gray-600 text-sm">{{ $expense->payment_method_label }}</td>
                                        <td class="px-3 py-2 text-right">
                                            <a href="{{ route('admin.expenses.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" class="inline" onsubmit="return confirm('Delete this expense record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-12">{{ $expenses->links() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
