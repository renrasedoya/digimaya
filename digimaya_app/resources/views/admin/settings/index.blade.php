<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Settings') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Settings']]" />
                </div>
            </div>
        </div>
    </x-slot>

    @php
        // Determine which tab to activate on page load.
        // Priority: validation error > query param > default 'company'.
        $activeTab = request('tab', 'company');
        if ($errors->any()) {
            $firstErrorKey = $errors->keys()[0];
            if (str_starts_with($firstErrorKey, 'company_'))       $activeTab = 'company';
            elseif (str_starts_with($firstErrorKey, 'bank_'))      $activeTab = 'banking';
            elseif (str_starts_with($firstErrorKey, 'invoice_'))   $activeTab = 'invoice';
            elseif (str_starts_with($firstErrorKey, 'tracking_')) $activeTab = 'tracking';
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash success --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                 x-data="{ activeTab: '{{ $activeTab }}' }">

                <div class="grid grid-cols-1 md:grid-cols-[220px_1fr]">

                    {{-- Sidebar --}}
                    <aside class="border-b md:border-b-0 md:border-r border-gray-200 py-4">

                        <div class="px-4 pb-2 text-xs font-medium uppercase tracking-wider text-gray-400">
                            Active
                        </div>

                        <button type="button"
                                @click="activeTab = 'company'"
                                :class="activeTab === 'company'
                                    ? 'bg-blue-50 border-l-[3px] border-[#165DFF] text-[#0C447C] font-semibold'
                                    : 'border-l-[3px] border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors">
                            Company Info
                        </button>

                        <button type="button"
                                @click="activeTab = 'banking'"
                                :class="activeTab === 'banking'
                                    ? 'bg-blue-50 border-l-[3px] border-[#165DFF] text-[#0C447C] font-semibold'
                                    : 'border-l-[3px] border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors">
                            Banking
                        </button>

                        <button type="button"
                                @click="activeTab = 'invoice'"
                                :class="activeTab === 'invoice'
                                    ? 'bg-blue-50 border-l-[3px] border-[#165DFF] text-[#0C447C] font-semibold'
                                    : 'border-l-[3px] border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors">
                            Invoice
                        </button>

                        <button type="button"
                                @click="activeTab = 'tracking'"
                                :class="activeTab === 'tracking'
                                    ? 'bg-blue-50 border-l-[3px] border-[#165DFF] text-[#0C447C] font-semibold'
                                    : 'border-l-[3px] border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors">
                            Tracking &amp; Custom Code
                        </button>

                        <div class="mt-3 pt-3 border-t border-gray-100 px-4 pb-2 text-xs font-medium uppercase tracking-wider text-gray-400">
                            Coming soon
                        </div>

                        <div class="px-4 py-2 text-sm text-gray-400">Email Templates</div>
                        <div class="px-4 py-2 text-sm text-gray-400">Branding</div>
                        <div class="px-4 py-2 text-sm text-gray-400">System</div>
                        <div class="px-4 py-2 text-sm text-gray-400">Notifications</div>
                    </aside>

                    {{-- Content --}}
                    <div class="p-6">

                        {{-- ========== COMPANY INFO ========== --}}
                        <div x-show="activeTab === 'company'" x-cloak>
                            <div class="mb-6">
                                <h3 class="text-base font-semibold text-gray-900">Company Info</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Information shown on invoice headers and reports.</p>
                            </div>

                            @if($errors->hasAny(['company_name', 'company_address_line_1', 'company_address_line_2', 'company_email', 'company_phone', 'company_npwp']))
                                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                                    <ul class="list-disc list-inside text-sm text-red-700">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.settings.update.company') }}" class="space-y-5">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="company_name" name="company_name"
                                           value="{{ old('company_name', $company['company_name'] ?? '') }}"
                                           required maxlength="255"
                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                </div>

                                <div>
                                    <label for="company_address_line_1" class="block text-sm font-medium text-gray-700">Address Line 1</label>
                                    <input type="text" id="company_address_line_1" name="company_address_line_1"
                                           value="{{ old('company_address_line_1', $company['company_address_line_1'] ?? '') }}"
                                           maxlength="255"
                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                </div>

                                <div>
                                    <label for="company_address_line_2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                                    <input type="text" id="company_address_line_2" name="company_address_line_2"
                                           value="{{ old('company_address_line_2', $company['company_address_line_2'] ?? '') }}"
                                           maxlength="255"
                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    <p class="mt-1 text-xs text-gray-500">Optional.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="company_email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" id="company_email" name="company_email"
                                               value="{{ old('company_email', $company['company_email'] ?? '') }}"
                                               maxlength="255" placeholder="invoice@digimaya.com"
                                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    </div>
                                    <div>
                                        <label for="company_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="text" id="company_phone" name="company_phone"
                                               value="{{ old('company_phone', $company['company_phone'] ?? '') }}"
                                               maxlength="50" placeholder="+62 21 1234 5678"
                                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    </div>
                                </div>

                                <div>
                                    <label for="company_npwp" class="block text-sm font-medium text-gray-700">NPWP</label>
                                    <input type="text" id="company_npwp" name="company_npwp"
                                           value="{{ old('company_npwp', $company['company_npwp'] ?? '') }}"
                                           maxlength="50"
                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                    <p class="mt-1 text-xs text-gray-500">Tax ID, optional. Shown on invoice if filled.</p>
                                </div>

                                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        Save Company Info
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- ========== BANKING ========== --}}
                        <div x-show="activeTab === 'banking'" x-cloak
                             x-data="bankingTab()">

                            <div class="flex items-start justify-between mb-6">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Bank Accounts</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Bank accounts available for invoice footer. You can add multiple.</p>
                                </div>
                                <button type="button"
                                        @click="openCreate()"
                                        class="shrink-0 px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    + Add Bank Account
                                </button>
                            </div>

                            @if($errors->any() && session('_old_input.bank_name'))
                                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                                    <ul class="list-disc list-inside text-sm text-red-700">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($bankAccounts->isEmpty())
                                <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-md">
                                    <p class="text-sm text-gray-500">No bank accounts yet.</p>
                                    <button type="button" @click="openCreate()" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                        Add your first bank account
                                    </button>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($bankAccounts as $account)
                                        <div class="border border-gray-200 rounded-md p-4 bg-white">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="min-w-0">
                                                    <div class="font-semibold text-sm text-gray-900 truncate">
                                                        {{ $account->bank_name }}
                                                        @if($account->label)
                                                            <span class="font-normal text-gray-500">({{ $account->label }})</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($account->is_active)
                                                    <span class="shrink-0 ms-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span class="shrink-0 ms-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-700 mb-1">{{ $account->account_number }}</div>
                                            <div class="text-sm text-gray-500 mb-3">{{ $account->account_holder }}</div>
                                            <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                                                <button type="button"
                                                        @click='openEdit(@json($account))'
                                                        class="text-xs px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('admin.bank-accounts.destroy', $account) }}"
                                                      onsubmit="return confirm('Delete this bank account? This action can be undone by support.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs px-3 py-1.5 border border-red-200 rounded-md text-red-600 hover:bg-red-50">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Modal for create/edit --}}
                            <div x-show="modalOpen" x-cloak
                                 class="fixed inset-0 z-50 overflow-y-auto"
                                 @keydown.escape.window="closeModal()">
                                {{-- Backdrop --}}
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                     @click="closeModal()"></div>

                                {{-- Modal panel --}}
                                <div class="flex items-center justify-center min-h-screen p-4">
                                    <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full"
                                         @click.stop>
                                        <form :action="formAction" method="POST" class="p-6">
                                            @csrf
                                            <input type="hidden" name="_method" :value="mode === 'edit' ? 'PATCH' : 'POST'">

                                            <h3 class="text-lg font-semibold text-gray-900 mb-4"
                                                x-text="mode === 'edit' ? 'Edit Bank Account' : 'Add Bank Account'"></h3>

                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Bank Name <span class="text-red-500">*</span></label>
                                                    <input type="text" name="bank_name" x-model="formData.bank_name"
                                                           required maxlength="100" placeholder="Bank Central Asia"
                                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Account Number <span class="text-red-500">*</span></label>
                                                    <input type="text" name="account_number" x-model="formData.account_number"
                                                           required maxlength="50"
                                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Account Holder Name <span class="text-red-500">*</span></label>
                                                    <input type="text" name="account_holder" x-model="formData.account_holder"
                                                           required maxlength="255"
                                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Label</label>
                                                    <input type="text" name="label" x-model="formData.label"
                                                           maxlength="100" placeholder="e.g. Operational, Tax Reserve"
                                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                                    <p class="mt-1 text-xs text-gray-500">Optional. Helps distinguish multiple accounts at the same bank.</p>
                                                </div>

                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                                        <input type="number" name="sort_order" x-model.number="formData.sort_order"
                                                               min="0" max="9999"
                                                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                                        <p class="mt-1 text-xs text-gray-500">Lower number first.</p>
                                                    </div>
                                                    <div class="flex items-center pt-6">
                                                        <input type="hidden" name="is_active" value="0">
                                                        <input type="checkbox" name="is_active" value="1"
                                                               x-model="formData.is_active"
                                                               id="modal_is_active"
                                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <label for="modal_is_active" class="ms-2 text-sm text-gray-700">Active</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                                                <button type="button" @click="closeModal()" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                                    <span x-text="mode === 'edit' ? 'Save Changes' : 'Add Account'"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @push('scripts')
                        <script>
                            function bankingTab() {
                                return {
                                    modalOpen: false,
                                    mode: 'create',
                                    formData: { bank_name: '', account_number: '', account_holder: '', label: '', is_active: true, sort_order: 0 },
                                    formAction: '',
                                    openCreate() {
                                        this.mode = 'create';
                                        this.formData = { bank_name: '', account_number: '', account_holder: '', label: '', is_active: true, sort_order: 0 };
                                        this.formAction = '{{ route('admin.bank-accounts.store') }}';
                                        this.modalOpen = true;
                                    },
                                    openEdit(account) {
                                        this.mode = 'edit';
                                        this.formData = {
                                            bank_name: account.bank_name || '',
                                            account_number: account.account_number || '',
                                            account_holder: account.account_holder || '',
                                            label: account.label || '',
                                            is_active: !!account.is_active,
                                            sort_order: account.sort_order || 0,
                                        };
                                        this.formAction = `/admin/bank-accounts/${account.id}`;
                                        this.modalOpen = true;
                                    },
                                    closeModal() {
                                        this.modalOpen = false;
                                    },
                                };
                            }
                        </script>
                        @endpush

                        {{-- ========== INVOICE ========== --}}
                        <div x-show="activeTab === 'invoice'" x-cloak>
                            <div class="mb-6">
                                <h3 class="text-base font-semibold text-gray-900">Invoice Settings</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Defaults applied when creating new invoices.</p>
                            </div>

                            @if($errors->hasAny(['invoice_number_prefix', 'invoice_due_offset_days', 'invoice_default_tax_rate', 'invoice_footer_notes']))
                                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                                    <ul class="list-disc list-inside text-sm text-red-700">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.settings.update.invoice') }}" class="space-y-5">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="invoice_number_prefix" class="block text-sm font-medium text-gray-700">Invoice Number Prefix <span class="text-red-500">*</span></label>
                                    <input type="text" id="invoice_number_prefix" name="invoice_number_prefix"
                                           value="{{ old('invoice_number_prefix', $invoice['invoice_number_prefix'] ?? 'DGMY') }}"
                                           required maxlength="20"
                                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full uppercase">
                                    <p class="mt-1 text-xs text-gray-500">Format: PREFIX/YYYY/MM/SEQ. Example: DGMY/2026/05/001. Uppercase letters and digits only.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="invoice_due_offset_days" class="block text-sm font-medium text-gray-700">Default Due Offset (days) <span class="text-red-500">*</span></label>
                                        <input type="number" id="invoice_due_offset_days" name="invoice_due_offset_days"
                                               value="{{ old('invoice_due_offset_days', $invoice['invoice_due_offset_days'] ?? 14) }}"
                                               required min="0" max="365"
                                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                        <p class="mt-1 text-xs text-gray-500">Days from issue date to due date.</p>
                                    </div>

                                    <div>
                                        <label for="invoice_default_tax_rate" class="block text-sm font-medium text-gray-700">Default Tax Rate (%) <span class="text-red-500">*</span></label>
                                        <input type="number" id="invoice_default_tax_rate" name="invoice_default_tax_rate"
                                               value="{{ old('invoice_default_tax_rate', $invoice['invoice_default_tax_rate'] ?? 0) }}"
                                               required min="0" max="100" step="0.01"
                                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                        <p class="mt-1 text-xs text-gray-500">0 = tax off by default. PPN Indonesia = 11.</p>
                                    </div>
                                </div>

                                <div>
                                    <label for="invoice_footer_notes" class="block text-sm font-medium text-gray-700">Footer Notes</label>
                                    <textarea id="invoice_footer_notes" name="invoice_footer_notes" rows="3" maxlength="1000"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">{{ old('invoice_footer_notes', $invoice['invoice_footer_notes'] ?? '') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Shown at the bottom of invoice PDF.</p>
                                </div>

                                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        Save Invoice Settings
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- ========== TRACKING & CUSTOM CODE ========== --}}
                        <div x-show="activeTab === 'tracking'" x-cloak>
                            <div class="mb-6">
                                <h3 class="text-base font-semibold text-gray-900">Tracking &amp; Custom Code</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Paste scripts (Google Tag Manager, GA4, Meta Pixel, site verification, chat widgets) to inject site-wide on all public pages.</p>
                            </div>

                            <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-md text-xs text-amber-800">
                                <strong>Heads up:</strong> code here runs raw on every public page. A broken tag can break the site layout. Paste only trusted snippets and verify after saving.
                            </div>

                            @if($errors->hasAny(['tracking_code_head', 'tracking_code_body_open', 'tracking_code_body_close']))
                                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                                    <ul class="list-disc list-inside text-sm text-red-700">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.settings.update.tracking') }}" class="space-y-5">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label for="tracking_code_head" class="block text-sm font-medium text-gray-700">Header Code &mdash; before <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">&lt;/head&gt;</code></label>
                                    <textarea id="tracking_code_head" name="tracking_code_head" rows="6" spellcheck="false"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono px-3 py-2"
                                              placeholder="&lt;!-- Google Tag Manager / GA4 / verification meta --&gt;">{{ old('tracking_code_head', $tracking['tracking_code_head'] ?? '') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Injected inside <code class="bg-gray-100 px-1 rounded">&lt;head&gt;</code>. Use for GTM head snippet, GA4 (gtag.js), and site verification meta tags.</p>
                                </div>

                                <div>
                                    <label for="tracking_code_body_open" class="block text-sm font-medium text-gray-700">Body Code &mdash; after <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">&lt;body&gt;</code> opens</label>
                                    <textarea id="tracking_code_body_open" name="tracking_code_body_open" rows="5" spellcheck="false"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono px-3 py-2"
                                              placeholder="&lt;!-- Google Tag Manager (noscript) --&gt;">{{ old('tracking_code_body_open', $tracking['tracking_code_body_open'] ?? '') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Injected immediately after the opening <code class="bg-gray-100 px-1 rounded">&lt;body&gt;</code> tag. Use for the GTM <code class="bg-gray-100 px-1 rounded">&lt;noscript&gt;</code> fallback.</p>
                                </div>

                                <div>
                                    <label for="tracking_code_body_close" class="block text-sm font-medium text-gray-700">Footer Code &mdash; before <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">&lt;/body&gt;</code></label>
                                    <textarea id="tracking_code_body_close" name="tracking_code_body_close" rows="5" spellcheck="false"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono px-3 py-2"
                                              placeholder="&lt;!-- Chat widget / deferred scripts --&gt;">{{ old('tracking_code_body_close', $tracking['tracking_code_body_close'] ?? '') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Injected just before the closing <code class="bg-gray-100 px-1 rounded">&lt;/body&gt;</code> tag. Use for chat widgets and non-critical deferred scripts.</p>
                                </div>

                                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        Save Tracking &amp; Custom Code
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
