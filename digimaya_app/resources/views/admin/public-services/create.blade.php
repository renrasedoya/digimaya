<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Service') }}
            </h2>
            <div class="mt-2">
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['label' => 'Components'],
                    ['label' => 'Services', 'url' => route('admin.public-services.index')],
                    ['label' => 'New']
                ]" />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.public-services.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @include('admin.public-services._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
