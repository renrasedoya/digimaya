<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Add Income') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Income', 'url' => route('admin.incomes.index')], ['label' => 'Add Income']]" />
                </div>
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
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.incomes.store') }}" class="space-y-6">
                        @csrf

                        {{-- Service & Category --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6"
                             x-data="{
                                category: '{{ old('source_category') }}',
                                hasService: false,
                                syncFromService(selectEl) {
                                    const opt = selectEl.selectedOptions[0];
                                    const cat = opt.dataset.category;
                                    if (cat) {
                                        this.category = cat;
                                        this.hasService = true;
                                    } else {
                                        this.hasService = false;
                                    }
                                }
                             }"
                             x-init="
                                const sv = document.getElementById('service_id');
                                if (sv && sv.value) syncFromService(sv);
                             ">
                            <div>
                                <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
                                <select id="service_id" name="service_id" x-on:change="syncFromService($event.target)"
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    <option value="">-- No specific service --</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-category="{{ $service->category }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} ({{ ucfirst($service->category) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="source_category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                                <select id="source_category" name="source_category" required
                                        x-bind:class="hasService ? 'bg-gray-100 cursor-not-allowed pointer-events-none' : ''"
                                        x-model="category"
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    <option value="">-- Select category --</option>
                                    @foreach(App\Models\Income::SOURCE_CATEGORIES as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500" x-show="!hasService">Choose 'Other' for income not tied to Agency or Academy services.</p>
                                <p class="mt-1 text-xs text-gray-500" x-show="hasService">Auto-detected from selected service. Change service to override.</p>
                            </div>
                        </div>

                        {{-- Client (Tom Select AJAX) --}}
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                            <select id="client_id" name="client_id" placeholder="Search client by name...">
                                <option value=""></option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Optional. Type to search. Leave empty for income not tied to a specific client.</p>
                        </div>

                        {{-- Amount & Date --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount <span class="text-red-500">*</span></label>
                                <x-currency-input name="amount" :value="old('amount')" required />
                            </div>

                            <div>
                                <label for="received_date" class="block text-sm font-medium text-gray-700">Received Date <span class="text-red-500">*</span></label>
                                <input type="date" id="received_date" name="received_date" value="{{ old('received_date', now()->format('Y-m-d')) }}" required
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            </div>
                        </div>

                        {{-- Payment & Reference --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method <span class="text-red-500">*</span></label>
                                <select id="payment_method" name="payment_method" required
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    @foreach(App\Models\Income::PAYMENT_METHODS as $key => $label)
                                        <option value="{{ $key }}" {{ old('payment_method', 'bank_transfer') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                                <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" maxlength="255"
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <p class="mt-1 text-xs text-gray-500">Invoice number, transfer ref, etc.</p>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" maxlength="5000"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">{{ old('description') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.incomes.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Save Income
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#client_id', {
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                preload: false,
                maxItems: 1,
                load: function (query, callback) {
                    if (!query.length) return callback();
                    fetch("{{ route('admin.clients.search') }}?q=" + encodeURIComponent(query) + "&limit=20")
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
