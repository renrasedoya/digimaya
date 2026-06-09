<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Lead') }}
        </h2>
        <div class="mt-2">
            <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Leads', 'url' => route('admin.leads.index')], ['label' => 'Add New']]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.leads.store') }}">
                        @csrf

                        {{-- Section 1: Business Info --}}
                        <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Business Info</h3>

                        <div class="mb-4">
                            <x-input-label for="business_name" value="Business Name" />
                            <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full" :value="old('business_name')" />
                            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="website_url" value="Website URL" />
                            <x-text-input id="website_url" name="website_url" type="text" class="mt-1 block w-full" :value="old('website_url')" placeholder="https://example.com" />
                            <x-input-error :messages="$errors->get('website_url')" class="mt-2" />
                        </div>

                        {{-- Section 2: Contact Info --}}
                        <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Contact Info</h3>

                        <div class="mb-4">
                            <x-input-label for="contact_name" value="Contact Name" />
                            <x-text-input id="contact_name" name="contact_name" type="text" class="mt-1 block w-full" :value="old('contact_name')" required autofocus />
                            <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="contact_email" value="Contact Email" />
                            <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" :value="old('contact_email')" />
                            <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="contact_phone" value="WhatsApp" />
                            <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" :value="old('contact_phone')" placeholder="08xxxxxxxxxx" />
                            <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                        </div>

                        {{-- Section 3: CRM --}}
                        <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">CRM</h3>

                        <div class="mb-4">
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full" required>
                                @foreach (\App\Models\Lead::STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'new') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="source" value="Source" />
                            <select id="source" name="source" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Select Source --</option>
                                @foreach (\App\Models\Lead::SOURCES as $key => $label)
                                    <option value="{{ $key }}" {{ old('source', 'manual') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('source')" class="mt-2" />
                        </div>

                        <div x-data="{
                                interest: '{{ old('interested_in', '') }}',
                                otherText: '{{ old('interested_in_other', '') }}',
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
                                    @foreach (\App\Models\Lead::INTERESTED_IN_OPTIONS as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
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
                            <x-input-label for="monthly_ad_budget" value="Monthly Ad Budget" />
                            <select id="monthly_ad_budget" name="monthly_ad_budget" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Select Budget --</option>
                                @foreach (\App\Models\Lead::BUDGETS as $key => $label)
                                    <option value="{{ $key }}" {{ old('monthly_ad_budget') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('monthly_ad_budget')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="assigned_to" value="Assigned To" />
                            <select id="assigned_to" name="assigned_to" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Unassigned --</option>
                                @foreach ($assignableUsers as $user)
                                    <option value="{{ $user->id }}" {{ (int) old('assigned_to', auth()->id()) === $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="message" value="Message / Notes" />
                            <textarea id="message" name="message" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2" placeholder="Initial inquiry message or internal notes...">{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="disqualification_reason" value="Disqualification Reason" />
                            <textarea id="disqualification_reason" name="disqualification_reason" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2" placeholder="Only fill if status is Disqualified">{{ old('disqualification_reason') }}</textarea>
                            <x-input-error :messages="$errors->get('disqualification_reason')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Save</x-primary-button>
                            <a href="{{ route('admin.leads.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>