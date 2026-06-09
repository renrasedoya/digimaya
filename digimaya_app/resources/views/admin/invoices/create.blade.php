<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Invoice') }}</h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Invoices', 'url' => route('admin.invoices.index')],
                        ['label' => 'Create'],
                    ]" />
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.invoices._form', [
                'formAction' => route('admin.invoices.store'),
            ])
        </div>
    </div>
</x-app-layout>
