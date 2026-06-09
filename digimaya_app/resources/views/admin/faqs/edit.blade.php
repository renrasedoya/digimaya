<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit FAQ') }}
            </h2>
            <div class="mt-2">
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['label' => 'Components'],
                    ['label' => 'FAQs', 'url' => route('admin.faqs.index')],
                    ['label' => 'Edit']
                ]" />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @include('admin.faqs._form', [
                'faq' => $faq,
                'formAction' => route('admin.faqs.update', $faq),
                'formMethod' => 'PUT',
            ])
        </div>
    </div>
</x-app-layout>
