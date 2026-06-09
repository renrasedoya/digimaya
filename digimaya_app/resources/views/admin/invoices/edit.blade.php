<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Invoice') }} <span class="text-sm text-gray-500">{{ $invoice->invoice_number }}</span></h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Invoices', 'url' => route('admin.invoices.index')],
                        ['label' => $invoice->invoice_number],
                    ]" />
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.invoices.show', $invoice) }}"
                   class="px-3 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    View Detail
                </a>
                <a href="{{ route('admin.invoices.pdf', $invoice) }}"
                   class="px-3 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-700">
                    Download PDF
                </a>
            </div>
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

            @php
                $defaults = [
                    'tax_rate' => $invoice->tax_rate,
                    'due_offset_days' => 14,
                ];
            @endphp

            @include('admin.invoices._form', [
                'formAction' => route('admin.invoices.update', $invoice),
                'invoice' => $invoice,
                'defaults' => $defaults,
            ])
        </div>
    </div>

</x-app-layout>
