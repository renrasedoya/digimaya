<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Comparison Row') }}
            </h2>
            <div class="mt-2">
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['label' => 'Components'],
                    ['label' => 'Comparison Table', 'url' => route('admin.comparison-rows.index')],
                    ['label' => $row->aspect]
                ]" />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.comparison-rows.update', $row) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    @include('admin.comparison-rows._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
