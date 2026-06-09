<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Balance') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Balance', 'url' => route('admin.balances.index')], ['label' => 'Edit Balance']]" />
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

                    {{-- Audit info --}}
                    <div class="mb-6 p-3 bg-gray-50 border border-gray-200 rounded-md text-xs text-gray-600">
                        Reported by <span class="font-medium text-gray-800">{{ $balance->creator?->name ?: 'Unknown' }}</span>
                        on {{ $balance->created_at->format('d M Y H:i') }}
                        @if($balance->updated_at->ne($balance->created_at))
                            · Last updated {{ $balance->updated_at->format('d M Y H:i') }}
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.balances.update', $balance) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Bank Account --}}
                        <div>
                            <label for="bank_account_id" class="block text-sm font-medium text-gray-700">Bank Account <span class="text-red-500">*</span></label>
                            <select id="bank_account_id" name="bank_account_id" required
                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                <option value="">-- Pilih Rekening --</option>
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_account_id', $balance->bank_account_id) == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }} — {{ $bank->account_number }} ({{ $bank->account_holder }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Period --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700">Month <span class="text-red-500">*</span></label>
                                <select id="month" name="month" required
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ old('month', $balance->month) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Year <span class="text-red-500">*</span></label>
                                <select id="year" name="year" required
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" {{ old('year', $balance->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Closing Balance Amount --}}
                        <div>
                            <label for="balance_amount" class="block text-sm font-medium text-gray-700">Closing Balance <span class="text-red-500">*</span></label>
                            <x-currency-input name="balance_amount" :value="old('balance_amount', $balance->balance_amount)" required />
                            <p class="mt-1 text-xs text-gray-500">Saldo akhir bulan per rekening dan periode yang dipilih.</p>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="3" maxlength="1000"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">{{ old('notes', $balance->notes) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.balances.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Update Balance
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
