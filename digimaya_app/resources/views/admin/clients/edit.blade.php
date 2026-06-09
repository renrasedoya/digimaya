<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Client') }}: {{ $client->business_name }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Clients', 'url' => route('admin.clients.index')], ['label' => 'Edit']]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('admin.clients.update', $client) }}">
                        @csrf
                        @method('PUT')

                        {{-- Section 1: Business Info --}}
                        <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Business Info</h3>

                        <div class="mb-4">
                            <x-input-label for="business_name" value="Business Name" />
                            <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full" :value="old('business_name', $client->business_name)" required autofocus />
                            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="website_url" value="Website URL" />
                            <x-text-input id="website_url" name="website_url" type="text" class="mt-1 block w-full" :value="old('website_url', $client->website_url)" placeholder="https://example.com" />
                            <x-input-error :messages="$errors->get('website_url')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="industry" value="Industry" />
                            @php $currentIndustry = old('industry', $client->industry); @endphp
                            <select id="industry" name="industry" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Select Industry --</option>
                                @if ($currentIndustry && !in_array($currentIndustry, \App\Models\Client::INDUSTRIES))
                                    <option value="{{ $currentIndustry }}" selected>{{ $currentIndustry }} (custom)</option>
                                @endif
                                @foreach (\App\Models\Client::INDUSTRIES as $industry)
                                    <option value="{{ $industry }}" {{ $currentIndustry === $industry ? 'selected' : '' }}>{{ $industry }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                        </div>

                        {{-- Section 2: Contact Info --}}
                        <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Contact Info</h3>

                        <div class="mb-4">
                            <x-input-label for="contact_name" value="Contact Name" />
                            <x-text-input id="contact_name" name="contact_name" type="text" class="mt-1 block w-full" :value="old('contact_name', $client->contact_name)" />
                            <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="contact_email" value="Contact Email" />
                            <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" :value="old('contact_email', $client->contact_email)" />
                            <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="contact_phone" value="WhatsApp" />
                            <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" :value="old('contact_phone', $client->contact_phone)" />
                            <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="address" value="Address" />
                            <textarea id="address" name="address" rows="3" maxlength="1000"
                                      placeholder="Alamat lengkap (opsional)"
                                      class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">{{ old('address', $client->address) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Alamat ini bisa di-tarik otomatis ke invoice saat client di-pilih.</p>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        {{-- Section 3: CRM --}}
                        <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">CRM</h3>

                        <div x-data="{ clientStatus: '{{ old('status', $client->status) }}', allowedTargets: @js(\App\Models\Client::getAllowedTargetsFrom($client->status)) }">
                        <div class="mb-4">
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" x-model="clientStatus" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full" required>
                                <option value="prospect" x-show="allowedTargets.includes('prospect')" {{ old('status', $client->status) === 'prospect' ? 'selected' : '' }}>Prospect</option>
                                <option value="active" x-show="allowedTargets.includes('active')" {{ old('status', $client->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" x-show="allowedTargets.includes('inactive')" {{ old('status', $client->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="churned" x-show="allowedTargets.includes('churned')" {{ old('status', $client->status) === 'churned' ? 'selected' : '' }}>Churned</option>
                                <option value="lost" x-show="allowedTargets.includes('lost')" {{ old('status', $client->status) === 'lost' ? 'selected' : '' }}>Lost</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="account_manager_id" value="Account Manager" />
                            <select id="account_manager_id" name="account_manager_id"
                                :disabled="clientStatus !== 'active'"
                                :class="clientStatus !== 'active' ? 'bg-gray-100 cursor-not-allowed text-gray-400' : ''"
                                class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Belum di-assign --</option>
                                @foreach($accountManagers as $am)
                                    <option value="{{ $am->id }}" {{ old('account_manager_id', $client->account_manager_id) == $am->id ? 'selected' : '' }}>{{ $am->name }}</option>
                                @endforeach
                            </select>
                            <p x-show="clientStatus !== 'active'" x-cloak class="mt-1 text-xs text-amber-600">Account Manager hanya bisa di-assign saat status Active.</p>
                            <p x-show="clientStatus === 'active'" x-cloak class="mt-1 text-xs text-gray-500">Pilih Account Manager yang menangani client ini.</p>
                            <x-input-error :messages="$errors->get('account_manager_id')" class="mt-2" />
                        </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="source" value="Source" />
                            @php $currentSource = old('source', $client->source); @endphp
                            <select id="source" name="source" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Select Source --</option>
                                @if ($currentSource && !in_array($currentSource, \App\Models\Client::SOURCES))
                                    <option value="{{ $currentSource }}" selected>{{ $currentSource }} (custom)</option>
                                @endif
                                @foreach (\App\Models\Client::SOURCES as $source)
                                    <option value="{{ $source }}" {{ $currentSource === $source ? 'selected' : '' }}>{{ $source }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('source')" class="mt-2" />
                        </div>

                        <div x-data="{
                                interest: '{{ old('interested_in', $client->interested_in ?? '') }}',
                                otherText: '{{ old('interested_in_other', $client->interested_in_other ?? '') }}',
                                init() {
                                    this.$watch('interest', (val) => {
                                        if (val !== 'others') this.otherText = '';
                                    });
                                }
                             }">
                            <div class="mb-4">
                                <x-input-label for="interested_in" value="Interested In" />
                                <select id="interested_in" name="interested_in" x-model="interest" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    <option value="">-- Select Interest --</option>
                                    @foreach (\App\Models\Client::INTERESTED_IN_OPTIONS as $key => $label)
                                        <option value="{{ $key }}" {{ old('interested_in', $client->interested_in) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('interested_in')" class="mt-2" />
                            </div>

                            <div class="mb-4" x-show="interest === 'others'" x-cloak x-transition>
                                <x-input-label for="interested_in_other" value="Specify Other Interest" />
                                <x-text-input id="interested_in_other" name="interested_in_other" type="text" x-model="otherText" class="mt-1 block w-full" placeholder="e.g. quick consultation, account audit, etc." maxlength="255" />
                                <x-input-error :messages="$errors->get('interested_in_other')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="client_since" value="Client Since" />
                            <x-text-input id="client_since" name="client_since" type="date" class="mt-1 block w-full" :value="old('client_since', $client->client_since?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('client_since')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="monthly_retainer" value="Monthly Retainer (Rp)" />
                            <x-currency-input name="monthly_retainer" :value="old('monthly_retainer', $client->monthly_retainer)" />
                            <p class="mt-1 text-xs text-gray-500">Estimasi nilai retainer bulanan per client. Untuk tracking MRR keseluruhan.</p>
                            <x-input-error :messages="$errors->get('monthly_retainer')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="acquisition_cost" value="Acquisition Cost (Rp)" />
                            <x-currency-input name="acquisition_cost" :value="old('acquisition_cost', $client->acquisition_cost)" />
                            <p class="mt-1 text-xs text-gray-500">Biaya untuk acquire client ini (iklan, sales effort, dll). Untuk hitung ROI dan CAC per client.</p>
                            <x-input-error :messages="$errors->get('acquisition_cost')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="notes" value="Notes" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">{{ old('notes', $client->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Update</x-primary-button>
                            <a href="{{ route('admin.clients.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>