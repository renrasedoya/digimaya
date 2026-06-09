<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Issue Category') }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Issue Categories', 'url' => route('admin.issue-categories.index')],
                ['label' => 'Add New']
            ]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @include('admin.issue-categories._form', [
                        'category' => $category,
                        'existingSubs' => $existingSubs,
                        'formAction' => route('admin.issue-categories.store'),
                        'formMethod' => 'POST',
                    ])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
