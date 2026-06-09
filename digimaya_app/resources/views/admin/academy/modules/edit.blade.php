<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Module') }}: {{ $module->title }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Academy'],
                ['label' => 'Modules', 'url' => route('admin.academy.modules.index')],
                ['label' => $module->title, 'url' => route('admin.academy.modules.show', $module)],
                ['label' => 'Edit']
            ]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @include('admin.academy.modules._form', [
                'module' => $module,
                'formAction' => route('admin.academy.modules.update', $module),
                'formMethod' => 'PUT',
            ])
        </div>
    </div>
</x-app-layout>
