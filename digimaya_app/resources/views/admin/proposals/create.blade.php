<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('New Proposal') }}</h2>
            <div class="mt-2">
                <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Proposals', 'url' => route('admin.proposals.index')], ['label' => 'New Proposal']]" />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    @if($clients->isEmpty())
                        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                            Belum ada Client berstatus Prospect. Proposal cuma bisa dibuat untuk prospect. Promote dulu lead jadi client, atau ubah status client jadi Prospect.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.proposals.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Client (Prospect) <span class="text-red-500">*</span></label>
                            <select id="client_id" name="client_id" required
                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Select prospect --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ (int) old('client_id', $preselectClientId) === $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Proposal Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            <p class="mt-1 text-xs text-gray-500">Internal title. Example: Proposal Google Ads - PT Maju Jaya</p>
                        </div>

                        <div>
                            <label for="template" class="block text-sm font-medium text-gray-700">Template <span class="text-red-500">*</span></label>
                            @if($templateOptions->isEmpty())
                                <div class="mt-1 p-4 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                                    Belum ada template. Buat dulu di menu CRM &rarr; Template Proposal.
                                </div>
                            @else
                                <select id="template" name="template" required
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    @foreach($templateOptions as $tpl)
                                        <option value="{{ $tpl->key }}" {{ old('template', $templateOptions->first()->key) === $tpl->key ? 'selected' : '' }}>{{ $tpl->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Proposal akan langsung terisi section dari template ini (teks, pricing, reference). Kamu tinggal hapus section yang tidak perlu di langkah berikutnya.</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.proposals.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Create & Continue</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
