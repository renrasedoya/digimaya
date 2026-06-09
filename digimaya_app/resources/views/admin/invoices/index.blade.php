<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Invoices') }}</h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Invoices']]" />
                </div>
            </div>
            <a href="{{ route('admin.invoices.create') }}" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Add Invoice
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

            {{-- Filter bar --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-4 p-4">
                <form method="GET" action="{{ route('admin.invoices.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label for="month" class="block text-xs font-medium text-gray-500 mb-1">Month</label>
                        <select name="month" id="month" class="border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="year" class="block text-xs font-medium text-gray-500 mb-1">Year</label>
                        <select name="year" id="year" class="border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select name="status" id="status" class="border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm">
                            <option value="">All</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $currentStatus === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="q" class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                        <input type="text" name="q" id="q" value="{{ $currentSearch }}" placeholder="Invoice number, client, project..."
                               class="border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm w-full">
                    </div>
                    <div class="flex items-end gap-2">
                        <label class="flex items-center gap-1 text-xs text-gray-700 mb-2">
                            <input type="checkbox" name="overdue" value="1" {{ $overdue ? 'checked' : '' }} class="rounded border-gray-300">
                            Overdue only
                        </label>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs uppercase tracking-widest rounded-md hover:bg-gray-700">Filter</button>
                        <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 text-xs text-gray-600 hover:text-gray-900">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                @if($invoices->isEmpty())
                    <div class="text-center py-16">
                        <p class="text-sm text-gray-500">No invoices found.</p>
                        @if(!$currentStatus && !$currentSearch)
                            <a href="{{ route('admin.invoices.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                Create your first invoice
                            </a>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.invoices.show', $invoice) }}'">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $invoice->invoice_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            @if($invoice->client_id && $invoice->client)
                                                {{ $invoice->client->business_name }}
                                            @elseif($invoice->custom_client_name)
                                                <span class="inline-flex items-center gap-1">
                                                    {{ $invoice->custom_client_name }}
                                                    <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200 font-normal">Custom</span>
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $invoice->project?->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $invoice->issue_date?->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $invoice->due_date?->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900 font-medium">IDR {{ number_format((float) $invoice->total, 0, '.', ',') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($invoice->status === 'paid')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Unpaid</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right" onclick="event.stopPropagation();">
                                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-gray-600 hover:text-gray-900">View</a>
                                            @if(!$invoice->is_locked)
                                                <span class="text-gray-300 mx-1">|</span>
                                                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="text-indigo-600 hover:text-indigo-700">Edit</a>
                                                <span class="text-gray-300 mx-1">|</span>
                                                <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" class="inline"
                                                      onsubmit="return confirm('Delete invoice {{ $invoice->invoice_number }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                                                </form>
                                            @else
                                                <span class="text-gray-300 mx-1">|</span>
                                                <span class="text-gray-400 text-xs">Locked</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
