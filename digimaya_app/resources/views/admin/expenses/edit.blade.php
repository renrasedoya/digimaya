<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Expense') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Expense', 'url' => route('admin.expenses.index')], ['label' => 'Edit Expense']]" />
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

                    <form method="POST" action="{{ route('admin.expenses.update', $expense) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="expense_category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                            <select id="expense_category_id" name="expense_category_id" required
                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Select category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('expense_category_id', $expense->expense_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount <span class="text-red-500">*</span></label>
                                <x-currency-input name="amount" :value="old('amount', $expense->amount)" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expense Period <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-2 mt-1">
                                    <select id="expense_month" name="expense_month" required
                                            class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full">
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ (int) old('expense_month', $expense->expense_date->month) === $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                        @endforeach
                                    </select>
                                    <select id="expense_year" name="expense_year" required
                                            class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full">
                                        @for($y = now()->year + 1; $y >= 2026; $y--)
                                            <option value="{{ $y }}" {{ (int) old('expense_year', $expense->expense_date->year) === $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="vendor_name" class="block text-sm font-medium text-gray-700">Vendor</label>
                                <input type="text" id="vendor_name" name="vendor_name" value="{{ old('vendor_name', $expense->vendor_name) }}" maxlength="255"
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            </div>

                            <div>
                                <label for="recurring_type" class="block text-sm font-medium text-gray-700">Recurring Type <span class="text-red-500">*</span></label>
                                <select id="recurring_type" name="recurring_type" required
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    @foreach(App\Models\Expense::RECURRING_TYPES as $key => $label)
                                        <option value="{{ $key }}" {{ old('recurring_type', $expense->recurring_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method <span class="text-red-500">*</span></label>
                                <select id="payment_method" name="payment_method" required
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    @foreach(App\Models\Expense::PAYMENT_METHODS as $key => $label)
                                        <option value="{{ $key }}" {{ old('payment_method', $expense->payment_method) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                                <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number', $expense->reference_number) }}" maxlength="255"
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" maxlength="5000"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">{{ old('description', $expense->description) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Save Changes
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
