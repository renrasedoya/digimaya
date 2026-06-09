<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Certificate') }}
            </h2>
            <div class="mt-2">
                <x-breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['label' => 'Academy'],
                    ['label' => 'Certificates', 'url' => route('admin.academy.certificates.index')],
                    ['label' => 'New']
                ]" />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($fromRequest)
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md text-sm text-blue-800">
                    Approving request <strong>#{{ $fromRequest->id }}</strong> from <strong>{{ $fromRequest->member->name }}</strong>.
                    Mode is locked to Academy.
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg" x-data="certificateForm()">
                <form method="POST" action="{{ route('admin.academy.certificates.store') }}" class="p-6 space-y-6">
                    @csrf

                    @if($fromRequest)
                        <input type="hidden" name="from_request" value="{{ $fromRequest->id }}">
                    @endif

                    {{-- Mode toggle --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Type</label>
                        <div class="flex gap-2">
                            <label class="flex-1">
                                <input type="radio" name="type" value="academy" x-model="mode" class="sr-only peer"
                                       @if($fromRequest) disabled @endif>
                                <div class="border-2 rounded-md px-4 py-3 cursor-pointer transition"
                                     :class="mode === 'academy' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                    <div class="font-medium text-sm text-gray-900">Academy</div>
                                    <div class="text-xs text-gray-500 mt-1">For Digimaya Academy members</div>
                                </div>
                            </label>
                            <label class="flex-1">
                                <input type="radio" name="type" value="external" x-model="mode" class="sr-only peer"
                                       @if($fromRequest) disabled @endif>
                                <div class="border-2 rounded-md px-4 py-3 cursor-pointer transition"
                                     :class="mode === 'external' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'">
                                    <div class="font-medium text-sm text-gray-900">External</div>
                                    <div class="text-xs text-gray-500 mt-1">For workshops, corporate training, etc.</div>
                                </div>
                            </label>
                        </div>
                        @if($fromRequest)
                            <input type="hidden" name="type" value="academy">
                        @endif
                    </div>

                    {{-- Academy mode: Member dropdown (AJAX) --}}
                    <div x-show="mode === 'academy'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Member <span class="text-red-500">*</span>
                        </label>
                        <select name="member_id" x-ref="memberSelect" class="w-full"></select>
                        <p class="text-xs text-gray-500 mt-1">Type to search active Academy members.</p>
                    </div>

                    {{-- External mode: Custom recipient --}}
                    <div x-show="mode === 'external'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Recipient Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="custom_recipient_name"
                               value="{{ old('custom_recipient_name') }}"
                               placeholder="e.g. John Doe"
                               maxlength="255"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">For workshop attendees, corporate training participants, etc.</p>
                    </div>

                    {{-- Program Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Program Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="program_name"
                               :value="programName"
                               @input="programName = $event.target.value"
                               maxlength="255"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1" x-show="mode === 'academy'">Default Academy program name (editable if needed).</p>
                        <p class="text-xs text-gray-500 mt-1" x-show="mode === 'external'" x-cloak>e.g. "Google Ads Workshop Bandung", "Corporate Training PT XYZ"</p>
                    </div>

                    {{-- Program Description (optional) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Program Description <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea name="program_description" rows="2" maxlength="2000"
                                  placeholder="Brief description printed below program name (optional)"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">{{ old('program_description') }}</textarea>
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Completion Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="completion_date"
                                   value="{{ old('completion_date', $today) }}"
                                   max="{{ $today }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Issued Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="issued_date"
                                   value="{{ old('issued_date', $today) }}"
                                   max="{{ $today }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.academy.certificates.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Issue Certificate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        function certificateForm() {
            return {
                mode: 'academy',
                programName: @json(old('program_name', $academyProgramName)),
                academyDefault: @json($academyProgramName),
                _preselectedMember: @json($prefilledMember),
                _memberTs: null,

                init() {
                    this.$nextTick(() => this.initMemberSelect());

                    this.$watch('mode', (newMode) => {
                        // Reset program name when toggling: academy default vs blank for external
                        if (newMode === 'academy' && this.programName === '') {
                            this.programName = this.academyDefault;
                        } else if (newMode === 'external' && this.programName === this.academyDefault) {
                            this.programName = '';
                        }
                    });
                },

                initMemberSelect() {
                    if (!this.$refs.memberSelect || this._memberTs) return;

                    const self = this;
                    const preselected = this._preselectedMember;

                    this._memberTs = new TomSelect(this.$refs.memberSelect, {
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        preload: 'focus',
                        maxItems: 1,
                        placeholder: '-- Select member --',
                        options: preselected ? [preselected] : [],
                        items: preselected ? [preselected.value] : [],
                        load: function (query, callback) {
                            fetch("{{ route('admin.academy.members.search') }}?q=" + encodeURIComponent(query) + "&limit=20", {
                                headers: { 'Accept': 'application/json' },
                                credentials: 'same-origin'
                            })
                            .then(r => r.json())
                            .then(data => callback(data))
                            .catch(() => callback());
                        },
                    });
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
