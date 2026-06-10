<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Proposal') }}</h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Proposals', 'url' => route('admin.proposals.index')], ['label' => 'Edit']]" />
                </div>
            </div>
            <div>
                @if($proposal->isPublished())
                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                @else
                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Draft</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.proposals.update', $proposal) }}"
                  x-data="proposalBuilder(@js($proposal->content_blocks ?? []), @js($snippets), @js($pricingCounts), @js($referenceData))"
                  @submit="syncBlocks()">
                @csrf
                @method('PUT')

                {{-- Proposal Info --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 space-y-6">
                        <h3 class="text-sm font-semibold text-gray-700">Proposal Info</h3>

                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Client (Prospect) <span class="text-red-500">*</span></label>
                            <select id="client_id" name="client_id" required
                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ (int) old('client_id', $proposal->client_id) === $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Proposal Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $proposal->title) }}" required maxlength="255"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                        </div>
                    </div>
                </div>

                {{-- Content Blocks (builder) — partial reusable, lihat _builder.blade.php --}}
                @include('admin.proposals._builder')

                {{-- Publish & Share --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-700">Publish & Share</h3>
                        @if($proposal->isPublished())
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Public Link</label>
                                <div class="flex flex-wrap gap-2" x-data="{ copied: false }">
                                    <input type="text" readonly x-ref="link"
                                           value="{{ route('public.proposal.show', $proposal->public_token) }}"
                                           class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-50 text-gray-600">
                                    <button type="button"
                                            @click="navigator.clipboard.writeText($refs.link.value); copied = true; setTimeout(() => copied = false, 1500)"
                                            class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                        <span x-show="!copied">Copy</span>
                                        <span x-show="copied" x-cloak class="text-green-600">Copied</span>
                                    </button>
                                    <a href="{{ route('public.proposal.show', $proposal->public_token) }}" target="_blank"
                                       class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 whitespace-nowrap">Open</a>
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Published {{ $proposal->published_at?->format('d M Y, H:i') }}. Klik Update Published untuk refresh isi setelah edit block.</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Proposal masih draft. Klik Publish untuk membuat link publik (block tersimpan otomatis saat publish).</p>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <p class="text-xs text-gray-400 mb-2">Tip: klik Save Draft dulu sebelum Preview atau Download PDF supaya perubahan block terbaru ikut tampil.</p>
                <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-2 pt-4 border-t border-gray-200">
                    {{-- Aksi sekunder --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('admin.proposals.preview', $proposal) }}" target="_blank"
                           class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Preview</a>
                        <a href="{{ route('admin.proposals.pdf', $proposal) }}" target="_blank"
                           class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Download PDF</a>
                        @if($proposal->isPublished())
                            <button type="submit" @click="$refs.actionField.value = 'unpublish'"
                                    class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Unpublish</button>
                        @endif
                        <button type="submit" @click="$refs.actionField.value = 'save'"
                                class="px-3 py-2 text-sm font-semibold border border-green-600 rounded-md text-green-700 hover:bg-green-50 hover:border-green-700">Save Draft</button>
                    </div>

                    {{-- Aksi utama (menonjol, terpisah di kanan) --}}
                    <div class="flex items-center">
                        <input type="hidden" name="action" x-ref="actionField" value="save">
                        <button type="submit" @click="$refs.actionField.value = 'publish'"
                                class="px-5 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm">
                            {{ $proposal->isPublished() ? 'Update Published' : 'Publish' }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

</x-app-layout>
