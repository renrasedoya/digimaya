<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Add Pricing Tier') }}</h2>
            <div class="mt-2">
                <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Pricing Tiers', 'url' => route('admin.pricing-tiers.index')], ['label' => 'Add Tier']]" />
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

                    <form method="POST" action="{{ route('admin.pricing-tiers.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700">Monthly Ad Budget (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" id="budget" name="budget" value="{{ old('budget') }}" required min="0" step="1"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            <p class="mt-1 text-xs text-gray-500">Enter raw number, no separator. Example: 4000000</p>
                        </div>

                        <div>
                            <label for="agency_fee" class="block text-sm font-medium text-gray-700">Monthly Agency Fee (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" id="agency_fee" name="agency_fee" value="{{ old('agency_fee') }}" required min="0" step="1"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                        </div>

                        <div>
                            <label for="zone" class="block text-sm font-medium text-gray-700">Zone <span class="text-red-500">*</span></label>
                            <select id="zone" name="zone" required
                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Select zone --</option>
                                <option value="lower" {{ old('zone') === 'lower' ? 'selected' : '' }}>Lower (4-10jt)</option>
                                <option value="upper" {{ old('zone') === 'upper' ? 'selected' : '' }}>Upper (11jt+)</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            <p class="mt-1 text-xs text-gray-500">Lower numbers display first.</p>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label for="is_active" class="ms-2 text-sm text-gray-700">Active (shown in proposal pricing block)</label>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.pricing-tiers.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Save Tier</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
