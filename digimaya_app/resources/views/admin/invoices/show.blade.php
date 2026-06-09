<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Invoice') }}
                    <span class="text-sm text-gray-500">{{ $invoice->invoice_number }}</span>
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Invoices', 'url' => route('admin.invoices.index')],
                        ['label' => $invoice->invoice_number],
                    ]" />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.invoices.pdf-preview', $invoice) }}" target="_blank"
                   class="px-3 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Preview PDF
                </a>
                <a href="{{ route('admin.invoices.pdf', $invoice) }}"
                   class="px-3 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-700">
                    Download PDF
                </a>
                @if(!$invoice->is_locked)
                    <a href="{{ route('admin.invoices.edit', $invoice) }}"
                       class="px-3 py-2 bg-gray-800 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-gray-700">
                        Edit
                    </a>
                    <button type="button" onclick="document.getElementById('mark-paid-modal').classList.remove('hidden')"
                            class="px-3 py-2 bg-green-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-green-700">
                        Mark as Paid
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-md text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-md text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Status banner --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    @if($invoice->status === 'paid')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Paid</span>
                        @if($invoice->paid_date)
                            <span class="text-xs text-gray-500">on {{ $invoice->paid_date->format('d M Y') }}</span>
                        @endif
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Unpaid</span>
                        @if($invoice->due_date && $invoice->due_date->isPast())
                            <span class="text-xs text-red-600 font-medium">— Overdue ({{ $invoice->due_date->diffForHumans() }})</span>
                        @endif
                    @endif
                </div>
                @if($invoice->income)
                    <a href="{{ route('admin.incomes.edit', $invoice->income) }}" class="text-xs text-indigo-600 hover:text-indigo-700">
                        Linked Income #{{ $invoice->income->id }} →
                    </a>
                @endif
            </div>

            {{-- Main detail grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Left: Invoice info + items --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Bill To section (3-mode aware) --}}
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Bill To</h3>
                        @php
                            $isProjectMode = $invoice->project_id && $invoice->project;
                            $isClientMode = $invoice->client_id && $invoice->client && !$isProjectMode;
                            $isCustomMode = !$isProjectMode && !$isClientMode;

                            if ($isProjectMode || $isClientMode) {
                                $billClient = $invoice->client;
                                $billName = $billClient?->business_name ?? '— (deleted)';
                                $billAddress = $billClient?->address;
                                $billContactName = $billClient?->contact_name;
                                $billContactPhone = $billClient?->contact_phone;
                                $billContactEmail = $billClient?->contact_email;
                            } else {
                                $billName = $invoice->custom_client_name ?: '-';
                                $billAddress = $invoice->custom_client_address;
                                $billContactName = null;
                                $billContactPhone = null;
                                $billContactEmail = $invoice->custom_client_contact;
                            }
                        @endphp

                        <div class="space-y-1">
                            <div class="text-base font-medium text-gray-900">
                                {{ $billName }}
                                @if($isCustomMode)
                                    <span class="ml-2 inline-flex text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200 font-normal">Custom</span>
                                @endif
                            </div>
                            @if($billContactName)
                                <div class="text-sm text-gray-600">{{ $billContactName }}</div>
                            @endif
                            @if($billAddress)
                                <div class="text-sm text-gray-600 whitespace-pre-line">{{ $billAddress }}</div>
                            @endif
                            @if($billContactPhone)
                                <div class="text-sm text-gray-600">{{ $billContactPhone }}</div>
                            @endif
                            @if($billContactEmail)
                                <div class="text-sm text-gray-600">{{ $billContactEmail }}</div>
                            @endif
                        </div>

                        @if($isProjectMode || $invoice->period_start)
                            <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                @if($isProjectMode)
                                    <div>
                                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Project</dt>
                                        <dd class="text-gray-900 mt-1">
                                            <a href="{{ route('admin.projects.show', $invoice->project) }}" class="text-indigo-600 hover:text-indigo-700">
                                                {{ $invoice->project->name }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                                @if($invoice->period_start && $invoice->period_end)
                                    <div>
                                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Period</dt>
                                        <dd class="text-gray-900 mt-1">{{ \App\Services\InvoicePeriodFormatter::format($invoice->period_start, $invoice->period_end) }}</dd>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Invoice meta --}}
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Invoice Details</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-gray-500">Invoice Number</dt>
                                <dd class="text-gray-900">{{ $invoice->invoice_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Issue Date</dt>
                                <dd class="text-gray-900">{{ $invoice->issue_date?->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Due Date</dt>
                                <dd class="text-gray-900">{{ $invoice->due_date?->format('d M Y') }}</dd>
                            </div>
                            @if($invoice->createdBy)
                                <div>
                                    <dt class="text-gray-500">Created By</dt>
                                    <dd class="text-gray-900">{{ $invoice->createdBy->name }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-gray-500">Created At</dt>
                                <dd class="text-gray-900">{{ $invoice->created_at?->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Line items --}}
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="p-6 pb-3">
                            <h3 class="text-base font-semibold text-gray-900">Line Items</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($invoice->items as $item)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                {{ $item->service?->name ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->description ?: '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-700">{{ rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ','), '0'), '.') }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-700">IDR {{ number_format((float) $item->unit_price, 0, '.', ',') }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900 font-medium">IDR {{ number_format((float) $item->line_total, 0, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if($invoice->notes)
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-2">Notes</h3>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Right: Totals + Bank --}}
                <div class="space-y-4">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Totals</h3>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Subtotal</dt>
                                <dd class="">IDR {{ number_format((float) $invoice->subtotal, 0, '.', ',') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Tax ({{ rtrim(rtrim(number_format((float) $invoice->tax_rate, 2, '.', ''), '0'), '.') }}%)</dt>
                                <dd class="">IDR {{ number_format((float) $invoice->tax_amount, 0, '.', ',') }}</dd>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <dt class="font-semibold text-gray-900">Total</dt>
                                <dd class="font-bold text-gray-900">IDR {{ number_format((float) $invoice->total, 0, '.', ',') }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($invoice->bankAccount)
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-3">Payment To</h3>
                            <dl class="space-y-1 text-sm">
                                <div>
                                    <dt class="text-gray-500 text-xs">Bank</dt>
                                    <dd class="text-gray-900 font-medium">{{ $invoice->bankAccount->bank_name }}@if($invoice->bankAccount->label) ({{ $invoice->bankAccount->label }})@endif</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 text-xs">Account Number</dt>
                                    <dd class="text-gray-900">{{ $invoice->bankAccount->account_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 text-xs">Account Holder</dt>
                                    <dd class="text-gray-900">{{ $invoice->bankAccount->account_holder }}</dd>
                                </div>
                            </dl>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Mark as Paid modal --}}
    @if(!$invoice->is_locked)
        <div id="mark-paid-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 onclick="document.getElementById('mark-paid-modal').classList.add('hidden')"></div>

            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
                    <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice) }}" class="p-6">
                        @csrf
                        @method('PATCH')

                        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 mb-4">Mark Invoice as Paid</h3>
                        <p class="text-sm text-gray-600 mb-4">This will lock the invoice and create a linked Income record automatically.</p>

                        <div class="space-y-4">
                            <div>
                                <label for="paid_date" class="block text-sm font-medium text-gray-700">Paid Date <span class="text-red-500">*</span></label>
                                <input type="date" name="paid_date" id="paid_date" required
                                       value="{{ now()->format('Y-m-d') }}"
                                       max="{{ now()->format('Y-m-d') }}"
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            </div>

                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method <span class="text-red-500">*</span></label>
                                <select name="payment_method" id="payment_method" required
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cash">Cash</option>
                                    <option value="qris">QRIS</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div>
                                @php
                                    // Resolve default category from invoice items' service
                                    $invoiceServiceIds = $invoice->items->pluck('service_id')->unique();
                                    $resolvedService = ($invoiceServiceIds->count() === 1 && $invoiceServiceIds->first() !== null)
                                        ? \App\Models\Service::find($invoiceServiceIds->first())
                                        : null;
                                    $defaultCategory = $resolvedService?->category;
                                    $isAutoDetected = !empty($defaultCategory);
                                @endphp
                                <label class="block text-sm font-medium text-gray-700">Source Category <span class="text-red-500">*</span></label>
                                @if($isAutoDetected)
                                    <input type="hidden" name="source_category" value="{{ $defaultCategory }}">
                                    <div class="mt-1 bg-green-50 border border-green-200 rounded-md px-3 py-2 text-sm">
                                        <span class="font-semibold text-green-800">{{ ucfirst($defaultCategory) }}</span>
                                        <span class="text-green-700">— auto-detected from service: <strong>{{ $resolvedService->name }}</strong></span>
                                    </div>
                                @else
                                    <select name="source_category" id="source_category" required
                                            class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                        <option value="">-- Select category --</option>
                                        <option value="agency">Agency</option>
                                        <option value="academy">Academy</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">No service detected. Please select category manually.</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                            <button type="button" onclick="document.getElementById('mark-paid-modal').classList.add('hidden')"
                                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Confirm Paid
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
