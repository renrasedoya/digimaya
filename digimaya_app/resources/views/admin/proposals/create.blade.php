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

                    @if($prospectClients->isEmpty() && $activeClients->isEmpty())
                        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                            Belum ada Client berstatus Prospect maupun Active. Promote lead jadi client (Prospect), atau aktifkan client dulu.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.proposals.store') }}" class="space-y-6">
                        @csrf

                        <div x-data="{
                                type: @js(old('recipient_type', 'prospect')),
                                clientId: @js((string) old('client_id', $preselectClientId ?: '')),
                                prospects: @js($prospectClients),
                                actives: @js($activeClients),
                                get list() { return this.type === 'active' ? this.actives : this.prospects; },
                             }" class="space-y-6">

                            {{-- Jenis penerima --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">For <span class="text-red-500">*</span></label>
                                <div class="inline-flex rounded-md border border-gray-300 overflow-hidden">
                                    <label class="px-4 py-2 text-sm cursor-pointer select-none"
                                           :class="type === 'prospect' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'">
                                        <input type="radio" name="recipient_type" value="prospect" x-model="type" @change="clientId = ''" class="sr-only"> Prospect
                                    </label>
                                    <label class="px-4 py-2 text-sm cursor-pointer select-none border-l border-gray-300"
                                           :class="type === 'active' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'">
                                        <input type="radio" name="recipient_type" value="active" x-model="type" @change="clientId = ''" class="sr-only"> Client (Active)
                                    </label>
                                </div>
                            </div>

                            {{-- Client (opsi menyesuaikan jenis penerima) --}}
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700"><span x-text="type === 'active' ? 'Client' : 'Prospect'">Prospect</span> <span class="text-red-500">*</span></label>
                                <select id="client_id" name="client_id" required x-model="clientId"
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    <option value="" x-text="type === 'active' ? '-- Select client --' : '-- Select prospect --'">-- Select prospect --</option>
                                    <template x-for="c in list" :key="c.id">
                                        <option :value="String(c.id)" x-text="c.business_name"></option>
                                    </template>
                                </select>
                                <p class="mt-1 text-xs text-gray-500" x-show="list.length === 0" x-cloak>
                                    Tidak ada client untuk jenis ini. Pilih jenis lain, atau tambah/ubah status client dulu.
                                </p>
                            </div>
                        </div>

                        <div x-data="{
                                title: @js(old('title', '')),
                                titleTouched: {{ old('title') ? 'true' : 'false' }},
                                templateKey: @js(old('template', $templateOptions->first()?->key ?? '')),
                                templates: @js($templateOptions),
                                templateName() {
                                    const t = this.templates.find(t => t.key === this.templateKey);
                                    return t ? t.name : '';
                                },
                                autofillTitle() {
                                    if (!this.titleTouched && this.templateName()) {
                                        this.title = 'e-Proposal ' + this.templateName();
                                    }
                                },
                             }" x-init="autofillTitle()" class="space-y-6">

                            <div>
                                <label for="template" class="block text-sm font-medium text-gray-700">Template <span class="text-red-500">*</span></label>
                                @if($templateOptions->isEmpty())
                                    <div class="mt-1 p-4 bg-yellow-50 border border-yellow-200 rounded-md text-sm text-yellow-800">
                                        Belum ada template. Buat dulu di menu CRM &rarr; Template Proposal.
                                    </div>
                                @else
                                    <select id="template" name="template" required
                                            x-model="templateKey" @change="autofillTitle()"
                                            class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                        @foreach($templateOptions as $tpl)
                                            <option value="{{ $tpl->key }}">{{ $tpl->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Proposal akan langsung terisi section dari template ini (teks, pricing, reference). Kamu tinggal hapus section yang tidak perlu di langkah berikutnya.</p>
                                @endif
                            </div>

                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Proposal Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" required maxlength="255"
                                       x-model="title" @input="titleTouched = true"
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <p class="mt-1 text-xs text-gray-500">Judul internal. Terisi otomatis dari template, bisa kamu ubah.</p>
                            </div>
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

    @push('styles')
    <style>[x-cloak]{display:none !important;}</style>
    @endpush
</x-app-layout>
