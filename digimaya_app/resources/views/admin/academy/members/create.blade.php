<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enroll Member') }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Academy'],
                ['label' => 'Members', 'url' => route('admin.academy.members.index')],
                ['label' => 'Enroll']
            ]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded text-sm">
                        Setelah submit, sistem akan generate setup link + kirim welcome email otomatis ke alamat member. Kamu bisa copy setup link manual dari halaman detail kalau email gagal.
                    </div>

                    <form method="POST" action="{{ route('admin.academy.members.store') }}">
                        @csrf

                        @include('admin.academy.members._form')

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Enroll & Send Welcome Email') }}</x-primary-button>
                            <a href="{{ route('admin.academy.members.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
