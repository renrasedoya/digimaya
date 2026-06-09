<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Case Study') }}
            </h2>
            <div class="mt-2">
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['label' => 'Components'],
                    ['label' => 'Case Studies', 'url' => route('admin.case-studies.index')],
                    ['label' => 'New']
                ]" />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @include('admin.case-studies._form', [
                'caseStudy'  => $caseStudy,
                'results'    => $results,
                'formAction' => route('admin.case-studies.store'),
                'formMethod' => 'POST',
            ])
        </div>
    </div>
</x-app-layout>
