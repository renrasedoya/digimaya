<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Project') }}: {{ $project->name }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Projects', 'url' => route('admin.projects.index')],
                ['label' => $project->name, 'url' => route('admin.projects.show', $project)],
                ['label' => 'Edit']
            ]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @include('admin.projects._form', [
                        'project' => $project,
                        'clients' => $clients,
                        'advertisers' => $advertisers,
                        'formAction' => route('admin.projects.update', $project),
                        'formMethod' => 'PUT',
                    ])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>