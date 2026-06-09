@php
    use App\Models\Invoice;

    // Determine initial state for Alpine — works for both create + edit contexts
    $initialItems = isset($invoice) && $invoice->items->isNotEmpty()
        ? $invoice->items->map(fn ($it) => [
            'service_id'  => $it->service_id !== null ? (string) $it->service_id : '',
            'description' => $it->description,
            'quantity'    => (float) $it->quantity,
            'unit_price'  => (float) $it->unit_price,
        ])->values()->toArray()
        : (old('items') ?: []);

    $initialTaxRate = old('tax_rate', $invoice->tax_rate ?? ($defaults['tax_rate'] ?? 0));
    $initialIssueDate = old('issue_date', isset($invoice) ? $invoice->issue_date->format('Y-m-d') : now()->format('Y-m-d'));
    $initialDueDate = old('due_date', isset($invoice)
        ? $invoice->due_date->format('Y-m-d')
        : now()->addDays($defaults['due_offset_days'] ?? 7)->format('Y-m-d'));

    // 3-mode binding initial state
    $initialMode = old('mode', isset($invoice) ? $invoice->mode : Invoice::MODE_PROJECT);
    $initialClientId = old('client_id', $invoice->client_id ?? null);
    $initialProjectId = old('project_id', $invoice->project_id ?? null);
    $initialCustomName = old('custom_client_name', $invoice->custom_client_name ?? '');
    $initialCustomAddress = old('custom_client_address', $invoice->custom_client_address ?? '');
    $initialCustomContact = old('custom_client_contact', $invoice->custom_client_contact ?? '');
    $initialPeriodStart = old('period_start', isset($invoice) && $invoice->period_start ? $invoice->period_start->format('Y-m-d') : '');
    $initialPeriodEnd = old('period_end', isset($invoice) && $invoice->period_end ? $invoice->period_end->format('Y-m-d') : '');

    // Pre-existing project/client labels for edit mode (so Tom Select can render selected option without first AJAX call)
    $preselectedProject = null;
    $preselectedClient = null;
    if (isset($invoice)) {
        if ($invoice->project) {
            $preselectedProject = [
                'id' => $invoice->project->id,
                'label' => ($invoice->project->client?->business_name ?? '-') . ' - ' . $invoice->project->name,
                'client_id' => $invoice->project->client_id,
                'client_name' => $invoice->project->client?->business_name ?? '-',
                'started_at' => $invoice->project->started_at ? $invoice->project->started_at->format('Y-m-d') : null,
                'anchor_day' => $invoice->project->started_at ? (int) $invoice->project->started_at->day : null,
                'project_value' => $invoice->project->project_value !== null ? (float) $invoice->project->project_value : null,
            ];
        }
        if ($invoice->client) {
            $preselectedClient = [
                'id' => $invoice->client->id,
                'name' => $invoice->client->business_name,
            ];
        }
    }

    $initialBankId = old('bank_account_id', $invoice->bank_account_id ?? null);
    $initialNotes = old('notes', $invoice->notes ?? '');
    $dueOffsetDays = (int) ($defaults['due_offset_days'] ?? 7);
@endphp

@if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
        <ul class="list-disc list-inside text-sm text-red-700">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div x-data="invoiceForm()">
    <form method="POST" action="{{ $formAction }}" class="space-y-6" @submit="syncBeforeSubmit()">
        @csrf
        @if(isset($invoice))
            @method('PATCH')
        @endif

        {{-- Hidden mode input + client_id (Alpine-synced, with submit handler fallback) --}}
        <input type="hidden" name="mode" :value="mode">
        <input type="hidden" name="client_id" x-ref="clientIdInput">

        {{-- Binding Mode Section --}}
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-2">Binding Mode</h3>
            <p class="text-xs text-gray-500 mb-4">Pilih cara binding invoice ini ke project, client, atau manual entry.</p>

            <div class="flex gap-2 flex-wrap mb-4">
                <button type="button" @click="setMode('project')"
                        :class="mode === 'project' ? 'bg-gray-100 border-gray-400 text-gray-900 font-medium' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50'"
                        class="px-4 py-2 border rounded-md text-sm transition">
                    Project
                </button>
                <button type="button" @click="setMode('client')"
                        :class="mode === 'client' ? 'bg-gray-100 border-gray-400 text-gray-900 font-medium' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50'"
                        class="px-4 py-2 border rounded-md text-sm transition">
                    Client Only
                </button>
                <button type="button" @click="setMode('custom')"
                        :class="mode === 'custom' ? 'bg-gray-100 border-gray-400 text-gray-900 font-medium' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50'"
                        class="px-4 py-2 border rounded-md text-sm transition">
                    Custom
                </button>
            </div>

            {{-- Mode 1: Project --}}
            <div x-show="mode === 'project'" x-cloak class="space-y-4">
                <div>
                    <label for="project_id" class="block text-sm font-medium text-gray-700">Project <span class="text-red-500">*</span></label>
                    <select name="project_id" id="project_id" x-ref="projectSelect"
                            class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                        @if($preselectedProject)
                            <option value="{{ $preselectedProject['id'] }}" selected>{{ $preselectedProject['label'] }}</option>
                        @endif
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Format: [Client] - [Nama project]</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Client (auto)</label>
                    <input type="text" :value="projectClientName" readonly
                           class="border border-gray-300 bg-gray-50 text-gray-600 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                </div>

                {{-- Project Value info card --}}
                <div x-show="projectId && projectValue !== null" x-cloak
                     class="bg-indigo-50 border border-indigo-100 rounded-md p-4 flex items-center justify-between gap-3">
                    <div>
                        <div class="text-xs text-indigo-700 uppercase tracking-wide">Project Value</div>
                        <div class="text-base font-semibold text-indigo-900 mt-1" x-text="'Rp ' + formatNumber(projectValue)"></div>
                    </div>
                    <button type="button" @click="addProjectValueAsLineItem()"
                            class="px-3 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-700">
                        + Add as Line Item
                    </button>
                </div>

                <div x-show="projectId && projectValue === null" x-cloak
                     class="bg-gray-50 border border-gray-200 rounded-md p-4 text-xs text-gray-500">
                    Project ini belum punya project value. Edit project untuk set nilai.
                </div>
            </div>

            {{-- Mode 2: Client Only --}}
            <div x-show="mode === 'client'" x-cloak class="space-y-4">
                <div>
                    <label for="client_only_id" class="block text-sm font-medium text-gray-700">Client <span class="text-red-500">*</span></label>
                    <select id="client_only_id" x-ref="clientSelect"
                            class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                        @if($preselectedClient && $initialMode === 'client')
                            <option value="{{ $preselectedClient['id'] }}" selected>{{ $preselectedClient['name'] }}</option>
                        @endif
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Pilih client terdaftar. Tidak terhubung ke project tertentu.</p>
                </div>
            </div>

            {{-- Mode 3: Custom --}}
            <div x-show="mode === 'custom'" x-cloak class="space-y-4">
                <div>
                    <label for="custom_client_name" class="block text-sm font-medium text-gray-700">Client Name <span class="text-red-500">*</span></label>
                    <input type="text" name="custom_client_name" id="custom_client_name" x-model="customName" maxlength="200"
                           placeholder="Nama client atau perusahaan"
                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                </div>
                <div>
                    <label for="custom_client_address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="custom_client_address" id="custom_client_address" x-model="customAddress" rows="2" maxlength="1000"
                              placeholder="Alamat lengkap (opsional)"
                              class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full"></textarea>
                </div>
                <div>
                    <label for="custom_client_contact" class="block text-sm font-medium text-gray-700">Contact</label>
                    <input type="text" name="custom_client_contact" id="custom_client_contact" x-model="customContact" maxlength="200"
                           placeholder="Telepon atau email (opsional)"
                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                </div>
                <p class="text-xs text-gray-500">Data ini tidak tersimpan ke tabel clients. Hanya tercatat di invoice ini.</p>
            </div>
        </div>

        {{-- Invoice Details + Period --}}
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Invoice Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date <span class="text-red-500">*</span></label>
                    <input type="date" name="issue_date" id="issue_date" required
                           x-model="issueDate"
                           @change="onIssueDateChange()"
                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" id="due_date" required
                           x-model="dueDate"
                           @change="onDueDateChange()"
                           class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                    <p class="mt-1 text-xs text-gray-500" x-show="!dueDateManuallyChanged">
                        Auto-set to {{ $dueOffsetDays }} days after issue date.
                    </p>
                    <p class="mt-1 text-xs text-amber-600" x-show="dueDateManuallyChanged">
                        Manually overridden.
                        <button type="button" @click="resetDueDate()" class="underline hover:text-amber-700">Reset to auto</button>
                    </p>
                </div>

                <div class="md:col-span-2 pt-2 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Billing Period
                        <span x-show="mode === 'project'" class="text-red-500">*</span>
                        <span x-show="mode !== 'project'" class="text-gray-400 text-xs font-normal">(opsional)</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <input type="date" name="period_start" x-model="periodStart" @change="onPeriodChange()"
                                   :required="mode === 'project'"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full">
                        </div>
                        <div>
                            <input type="date" name="period_end" x-model="periodEnd" @change="onPeriodChange()"
                                   :required="mode === 'project'"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full">
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500" x-show="mode === 'project' && !periodManuallyChanged">
                        Auto-prefilled dari anchor date project (periode berikutnya dari today).
                    </p>
                    <p class="mt-2 text-xs text-amber-600" x-show="mode === 'project' && periodManuallyChanged">
                        Manually overridden.
                        <button type="button" @click="resetPeriod()" class="underline hover:text-amber-700">Reset to auto</button>
                    </p>
                    <p class="mt-2 text-xs text-gray-500" x-show="mode !== 'project'">
                        Kosongkan kalau tidak ingin tampilkan periode di PDF.
                    </p>
                </div>
            </div>
        </div>

        {{-- Line items section --}}
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900">Line Items</h3>
                <button type="button" @click="addRow()"
                        class="px-3 py-1.5 bg-gray-100 border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-200">
                    + Add Row
                </button>
            </div>

            <div class="space-y-4">
                {{-- Empty state --}}
                <div x-show="items.length === 0" x-cloak
                     class="border border-dashed border-gray-300 rounded-md p-6 text-center">
                    <p class="text-sm text-gray-500">Belum ada line items.</p>
                    <p class="text-xs text-gray-400 mt-1">Klik <span class="font-medium">+ Add Row</span> atau <span class="font-medium">+ Add as Line Item</span> (kalau Mode Project dengan project value) untuk mulai.</p>
                </div>

                <template x-for="(item, idx) in items" :key="idx">
                    <div class="border border-gray-200 rounded-md p-3 bg-gray-50/50">
                        {{-- Single row: Service | Description | Qty | Unit Price | Remove --}}
                        <div class="grid grid-cols-12 gap-2 items-end">
                            <div class="col-span-12 md:col-span-3">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Service (optional)</label>
                                <select :name="`items[${idx}][service_id]`" x-model="item.service_id"
                                        @change="onServiceChange(idx)"
                                        class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full text-sm">
                                    <option value="">-- Custom (no service) --</option>
                                    @foreach($services as $svc)
                                        <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Description <span class="text-gray-400 font-normal">(opsional)</span></label>
                                <input type="text" :name="`items[${idx}][description]`" x-model="item.description"
                                       maxlength="500" placeholder="Catatan tambahan (opsional)"
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full text-sm">
                            </div>

                            <div class="col-span-4 md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Qty <span class="text-red-500">*</span></label>
                                <input type="number" :name="`items[${idx}][quantity]`" x-model.number="item.quantity"
                                       step="0.01" min="0.01" required
                                       class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 block w-full text-sm text-right">
                            </div>

                            <div class="col-span-8 md:col-span-3">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Unit Price <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-1">
                                    <div class="flex flex-1">
                                        <span class="inline-flex items-center px-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-xs">Rp</span>
                                        <input type="text" inputmode="decimal"
                                               :value="formatCurrency(item.unit_price)"
                                               @input="item.unit_price = parseCurrency($event.target.value); $event.target.value = formatCurrency(item.unit_price)"
                                               @paste.prevent="handlePaste($event, idx)"
                                               required placeholder="0"
                                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-r-md shadow-sm px-2 py-2 block w-full text-sm text-right">
                                    </div>
                                    <button type="button" @click="removeRow(idx)" 
                                            class="text-red-500 hover:text-red-700 text-xl leading-none px-2" title="Remove row">
                                        &times;
                                    </button>
                                </div>
                                <input type="hidden" :name="`items[${idx}][unit_price]`" :value="item.unit_price">
                            </div>
                        </div>

                        {{-- Line total below row --}}
                        <div class="mt-2 flex justify-end items-center text-xs text-gray-500">
                            <span class="me-2">Line total:</span>
                            <span class="font-semibold text-gray-700" x-text="'IDR ' + formatNumber(item.quantity * item.unit_price)"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Totals + extras --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <div>
                    <label for="bank_account_id" class="block text-sm font-medium text-gray-700">Bank Account (for invoice footer)</label>
                    <select name="bank_account_id" id="bank_account_id"
                            class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                        <option value="">-- None --</option>
                        @foreach($bankAccounts as $bank)
                            <option value="{{ $bank->id }}" {{ (string) $initialBankId === (string) $bank->id ? 'selected' : '' }}>
                                {{ $bank->bank_name }}@if($bank->label) ({{ $bank->label }})@endif - {{ $bank->account_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" maxlength="1000"
                              class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">{{ $initialNotes }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Optional per-invoice notes (separate from default footer notes in Settings).</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Totals</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" x-text="'IDR ' + formatNumber(subtotal)"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <label for="tax_rate" class="text-gray-600">Tax rate (%):</label>
                        <input type="number" name="tax_rate" id="tax_rate" x-model.number="taxRate"
                               step="0.01" min="0" max="100" required
                               class="border border-gray-300 rounded-md shadow-sm px-2 py-1 text-sm text-right w-20">
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax amount:</span>
                        <span class="font-medium" x-text="'IDR ' + formatNumber(taxAmount)"></span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <span class="font-semibold text-gray-900">Total:</span>
                        <span class="font-bold text-gray-900" x-text="'IDR ' + formatNumber(total)"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action bar --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ isset($invoice) ? 'Save Changes' : 'Create Invoice' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    function invoiceForm() {
        return {
            items: @json($initialItems),
            services: @json($services).map(s => ({ ...s, id: String(s.id) })),
            taxRate: parseFloat(@json($initialTaxRate)) || 0,

            issueDate: @json($initialIssueDate),
            dueDate: @json($initialDueDate),
            dueDateManuallyChanged: @json(isset($invoice)),
            dueOffsetDays: {{ $dueOffsetDays }},

            mode: @json($initialMode),
            clientId: @json($initialClientId),
            projectId: @json($initialProjectId),
            customName: @json($initialCustomName),
            customAddress: @json($initialCustomAddress),
            customContact: @json($initialCustomContact),

            periodStart: @json($initialPeriodStart),
            periodEnd: @json($initialPeriodEnd),
            periodManuallyChanged: @json(isset($invoice) && ($initialPeriodStart || $initialPeriodEnd)),

            // Preselected metadata for edit mode (project has client + anchor day)
            _preselectedProject: @json($preselectedProject),

            // Project value (auto-pulled from selected project)
            projectValue: null,

            _projectTs: null,
            _clientTs: null,
            _projectClientCache: {}, // cache project_id => {client_name, anchor_day, started_at}

            init() {
                this.$nextTick(() => this.initTomSelects());
            },

            initTomSelects() {
                const self = this;

                this._projectTs = new TomSelect(this.$refs.projectSelect, {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    preload: 'focus',
                    maxItems: 1,
                    placeholder: '-- Pilih project --',
                    load: function (query, callback) {
                        fetch("{{ route('admin.projects.search') }}?q=" + encodeURIComponent(query) + "&limit=20", {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin'
                        })
                        .then(r => r.json())
                        .then(data => {
                            // Cache metadata for each project
                            data.forEach(p => {
                                self._projectClientCache[p.value] = {
                                    client_id: p.client_id,
                                    client_name: p.client_name,
                                    started_at: p.started_at,
                                    anchor_day: p.anchor_day,
                                    project_value: p.project_value,
                                };
                            });
                            callback(data);
                        })
                        .catch(() => callback());
                    },
                    onChange: function (value) {
                        self.projectId = value || null;
                        self.onProjectChange();
                    },
                });

                this._clientTs = new TomSelect(this.$refs.clientSelect, {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: 'text',
                    preload: 'focus',
                    maxItems: 1,
                    placeholder: '-- Pilih client --',
                    load: function (query, callback) {
                        fetch("{{ route('admin.clients.search') }}?q=" + encodeURIComponent(query) + "&limit=20", {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin'
                        })
                        .then(r => r.json())
                        .then(data => callback(data))
                        .catch(() => callback());
                    },
                    onChange: function (value) {
                        if (self.mode === 'client') {
                            self.clientId = value || null;
                        }
                    },
                });

                // Edit mode: seed cache from preselected project so cascade works without AJAX
                if (this._preselectedProject) {
                    this._projectClientCache[String(this._preselectedProject.id)] = {
                        client_id: this._preselectedProject.client_id,
                        client_name: this._preselectedProject.client_name,
                        started_at: this._preselectedProject.started_at,
                        anchor_day: this._preselectedProject.anchor_day,
                        project_value: this._preselectedProject.project_value,
                    };
                }

                // Sync initial state for Mode 1 (project preselected → fill clientId)
                if (this.mode === 'project' && this.projectId) {
                    this.applyProjectSelection(this.projectId, false);
                }
            },

            // Sync Alpine state to hidden DOM inputs before form submit
            syncBeforeSubmit() {
                if (this.$refs.clientIdInput) {
                    if (this.mode === 'project' || this.mode === 'client') {
                        this.$refs.clientIdInput.value = this.clientId || '';
                    } else {
                        this.$refs.clientIdInput.value = '';
                    }
                }
            },

            setMode(newMode) {
                if (this.mode === newMode) return;

                const hasData = (this.mode === 'project' && this.projectId)
                             || (this.mode === 'client' && this.clientId)
                             || (this.mode === 'custom' && (this.customName || this.customAddress || this.customContact));

                if (hasData && !confirm('Switch mode? Current mode data will be lost.')) {
                    return;
                }

                this.mode = newMode;
                this.clearModeData();
            },

            clearModeData() {
                if (this.mode !== 'project' && this._projectTs) {
                    this._projectTs.clear();
                    this.projectId = null;
                }
                if (this.mode !== 'client' && this._clientTs) {
                    this._clientTs.clear();
                }
                this.clientId = null;
                if (this.mode === 'custom') {
                    this.projectId = null;
                }
                if (this.mode !== 'custom') {
                    this.customName = '';
                    this.customAddress = '';
                    this.customContact = '';
                }
                if (this.mode !== 'project') {
                    this.periodStart = '';
                    this.periodEnd = '';
                    this.periodManuallyChanged = false;
                    this.projectValue = null;
                }
            },

            onProjectChange() {
                this.applyProjectSelection(this.projectId, true);
            },

            applyProjectSelection(projectId, forcePeriodReset) {
                if (!projectId) {
                    this.clientId = null;
                    this.projectValue = null;
                    return;
                }
                const meta = this._projectClientCache[String(projectId)];
                if (!meta) return;

                this.clientId = meta.client_id;
                this.projectValue = (meta.project_value !== undefined && meta.project_value !== null)
                    ? parseFloat(meta.project_value)
                    : null;

                if (forcePeriodReset || !this.periodManuallyChanged) {
                    if (meta.anchor_day) {
                        const period = this.computeDefaultPeriod(meta.anchor_day);
                        this.periodStart = period.start;
                        this.periodEnd = period.end;
                        this.periodManuallyChanged = false;
                    }
                }
            },

            addProjectValueAsLineItem() {
                if (this.projectValue === null) return;
                this.items.push({
                    service_id: '',
                    description: '',
                    quantity: 1,
                    unit_price: this.projectValue
                });
            },

            onPeriodChange() {
                this.periodManuallyChanged = true;
            },

            resetPeriod() {
                if (!this.projectId) return;
                const meta = this._projectClientCache[String(this.projectId)];
                if (!meta || !meta.anchor_day) return;
                const period = this.computeDefaultPeriod(meta.anchor_day);
                this.periodStart = period.start;
                this.periodEnd = period.end;
                this.periodManuallyChanged = false;
            },

            computeDefaultPeriod(anchorDay) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                let startYear = today.getFullYear();
                let startMonth = today.getMonth();

                if (anchorDay === 1) {
                    startMonth += 1;
                    if (startMonth > 11) { startMonth = 0; startYear += 1; }
                    const start = new Date(startYear, startMonth, 1);
                    const end = new Date(startYear, startMonth + 1, 0);
                    return { start: this.toDateString(start), end: this.toDateString(end) };
                }

                let candidateStart = new Date(startYear, startMonth, anchorDay);
                if (today >= candidateStart) {
                    startMonth += 1;
                    if (startMonth > 11) { startMonth = 0; startYear += 1; }
                    candidateStart = new Date(startYear, startMonth, anchorDay);
                }

                const candidateEnd = new Date(candidateStart);
                candidateEnd.setMonth(candidateEnd.getMonth() + 1);
                candidateEnd.setDate(candidateEnd.getDate() - 1);

                return { start: this.toDateString(candidateStart), end: this.toDateString(candidateEnd) };
            },

            toDateString(d) {
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            },

            get projectClientName() {
                if (!this.projectId) return '';
                const meta = this._projectClientCache[String(this.projectId)];
                return meta ? meta.client_name : '';
            },

            addRow() {
                this.items.push({ service_id: '', description: '', quantity: 1, unit_price: 0 });
            },
            removeRow(idx) {
                if (this.items.length > 1) this.items.splice(idx, 1);
            },
            onServiceChange(idx) {
                // No auto-fill; service is shown separately in PDF/show page.
                // Description is optional custom note from admin.
            },

            onIssueDateChange() {
                if (this.dueDateManuallyChanged) return;
                this.dueDate = this.addDays(this.issueDate, this.dueOffsetDays);
            },
            onDueDateChange() {
                this.dueDateManuallyChanged = true;
            },
            resetDueDate() {
                this.dueDateManuallyChanged = false;
                this.dueDate = this.addDays(this.issueDate, this.dueOffsetDays);
            },
            addDays(dateStr, days) {
                if (!dateStr) return '';
                const d = new Date(dateStr + 'T00:00:00');
                d.setDate(d.getDate() + parseInt(days, 10));
                return this.toDateString(d);
            },

            formatCurrency(value) {
                if (value === '' || value === null || isNaN(value)) return '';
                return new Intl.NumberFormat('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(value);
            },
            parseCurrency(str) {
                if (!str) return 0;
                const cleaned = String(str).replace(/[^\d.-]/g, '');
                const n = parseFloat(cleaned);
                return isNaN(n) ? 0 : n;
            },
            handlePaste(event, idx) {
                const pasted = (event.clipboardData || window.clipboardData).getData('text');
                this.items[idx].unit_price = this.parseCurrency(pasted);
                event.target.value = this.formatCurrency(this.items[idx].unit_price);
            },
            formatNumber(n) {
                return new Intl.NumberFormat('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(n || 0);
            },

            get subtotal() {
                return this.items.reduce((sum, it) =>
                    sum + (parseFloat(it.quantity) || 0) * (parseFloat(it.unit_price) || 0), 0);
            },
            get taxAmount() {
                return Math.round(this.subtotal * (parseFloat(this.taxRate) || 0) / 100 * 100) / 100;
            },
            get total() {
                return this.subtotal + this.taxAmount;
            },
        };
    }
</script>
<style>[x-cloak] { display: none !important; }</style>
@endpush
