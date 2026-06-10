<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Template') }}: {{ $proposalTemplate->name }}</h2>
            <div class="mt-2">
                <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Template Proposal', 'url' => route('admin.proposal-templates.index')], ['label' => $proposalTemplate->name]]" />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.proposal-templates.update', $proposalTemplate) }}"
                  x-data="proposalBuilder(@js($proposalTemplate->content_blocks ?? []), @js($snippets), @js($pricingCounts), @js($referenceData))"
                  @submit="syncBlocks()">
                @csrf
                @method('PUT')

                {{-- Template Info (nama saja; tanpa client/title/publish) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Template</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $proposalTemplate->name) }}" maxlength="255"
                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                        <p class="mt-1 text-xs text-gray-500">Key: <span class="font-mono">{{ $proposalTemplate->key }}</span> (tidak diubah). Block di bawah disalin ke proposal baru saat "Buat Proposal".</p>
                    </div>
                </div>

                {{-- Builder (partial yang sama dengan editor proposal) --}}
                @include('admin.proposals._builder')

                {{-- Actions: hanya Simpan (tanpa Preview/PDF/Publish) --}}
                <div class="flex flex-wrap items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.proposal-templates.index') }}" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900">Kembali</a>
                    <button type="submit"
                            class="px-5 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm">Simpan Template</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
