<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $lead->contact_name }}
                    @php
                        $statusColors = [
                            'new'          => 'bg-blue-100 text-blue-800',
                            'contacted'    => 'bg-yellow-100 text-yellow-800',
                            'screened'     => 'bg-purple-100 text-purple-800',
                            'promoted'     => 'bg-green-100 text-green-800',
                            'disqualified' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="ml-2 inline-flex px-2 py-1 text-xs rounded-full {{ $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ \App\Models\Lead::STATUSES[$lead->status] ?? ucfirst($lead->status) }}
                    </span>
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Leads', 'url' => route('admin.leads.index')], ['label' => $lead->contact_name]]" />
                </div>
            </div>
            <div x-data="{}" class="flex items-center gap-2">
                @if($lead->canPromote())
                    <button type="button"
                        @click="$dispatch('open-modal', 'promote-to-client')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        🚀 Promote to Client
                    </button>
                @endif
                <a href="{{ route('admin.leads.edit', $lead) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" class="inline" onsubmit="return confirm('Delete this lead?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Status banner: Screened (ready for promote OR pending interest) --}}
            @if($lead->status === 'screened')
                @if($lead->canPromote())
                    <div class="mb-4 bg-purple-50 border-l-4 border-purple-400 p-4 rounded">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🚀</span>
                            <div>
                                <p class="font-semibold text-purple-900">Lead siap untuk di-handover ke tim Sales.</p>
                                <p class="text-sm text-purple-700 mt-1">Klik tombol <strong>Promote to Client</strong> di kanan atas untuk mengkonversi Lead ini menjadi Client.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-4 bg-amber-50 border-l-4 border-amber-400 p-4 rounded">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">⚠️</span>
                            <div>
                                <p class="font-semibold text-amber-900">Lead belum siap di-promote.</p>
                                <p class="text-sm text-amber-700 mt-1">Status sudah <strong>Screened</strong>, tapi field <strong>Interested In</strong> masih kosong. Edit Lead ini dulu dan isi minat layanan-nya sebelum bisa di-promote ke Client.</p>
                                <p class="text-sm text-amber-700 mt-1">
                                    <a href="{{ route('admin.leads.edit', $lead) }}" class="font-medium underline hover:text-amber-900">→ Edit Lead untuk isi Interested In</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Status banner: Promoted (read-only mode) --}}
            @if($lead->status === 'promoted')
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">✅</span>
                        <div>
                            <p class="font-semibold text-green-900">Lead sudah di-handover ke tim Sales.</p>
                            <p class="text-sm text-green-700 mt-1">
                                Lead ini menjadi <strong>read-only</strong>. Follow-up berikutnya dilakukan oleh tim Sales di Client side.
                                @if($lead->promoted_at)
                                    <br>Di-promote pada {{ $lead->promoted_at->format('d M Y, H:i') }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Status banner: Disqualified (read-only mode) --}}
            @if($lead->status === 'disqualified')
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">❌</span>
                        <div>
                            <p class="font-semibold text-red-900">Lead di-disqualified.</p>
                            <p class="text-sm text-red-700 mt-1">
                                Lead ini menjadi <strong>read-only</strong>. Lead tidak diproses lebih lanjut.
                                @if($lead->disqualified_at)
                                    <br>Di-disqualified pada {{ $lead->disqualified_at->format('d M Y, H:i') }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT COLUMN: Info read-only (sticky on desktop) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">

                    {{-- Section 1: Contact Info --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Contact Info</h3>

                    <dl class="grid grid-cols-1 gap-4 mb-8">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->contact_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($lead->contact_email)
                                    <a href="mailto:{{ $lead->contact_email }}" class="text-indigo-600 hover:text-indigo-900">{{ $lead->contact_email }}</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">WhatsApp</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($lead->contact_phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->contact_phone) }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-900">{{ $lead->contact_phone }}</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Business Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->business_name ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Website</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($lead->website_url)
                                    <a href="{{ $lead->website_url }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-900">{{ $lead->website_url }}</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    {{-- Section 2: Lead Details --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Lead Details</h3>

                    <dl class="grid grid-cols-1 gap-4 mb-8">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Source</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Lead::SOURCES[$lead->source] ?? $lead->source }}</dd>
                        </div>
                        @if($lead->interested_in)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Interested In</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $lead->interested_in_label }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Monthly Ad Budget</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->monthly_ad_budget ? (\App\Models\Lead::BUDGETS[$lead->monthly_ad_budget] ?? $lead->monthly_ad_budget) : '-' }}</dd>
                        </div>
                        @if($lead->utm_source || $lead->utm_medium || $lead->utm_campaign)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">UTM Tracking</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="text-gray-500">Source:</span> {{ $lead->utm_source ?: '-' }} &nbsp;|&nbsp;
                                    <span class="text-gray-500">Medium:</span> {{ $lead->utm_medium ?: '-' }} &nbsp;|&nbsp;
                                    <span class="text-gray-500">Campaign:</span> {{ $lead->utm_campaign ?: '-' }}
                                </dd>
                            </div>
                        @endif
                        @if($lead->referrer_url)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Referrer URL</dt>
                                <dd class="mt-1 text-sm text-gray-900 break-all">{{ $lead->referrer_url }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Message / Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $lead->message ?: '-' }}</dd>
                        </div>
                        @if($lead->status === 'disqualified' && $lead->disqualification_reason)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Disqualification Reason</dt>
                                <dd class="mt-1 text-sm text-red-700 whitespace-pre-line">{{ $lead->disqualification_reason }}</dd>
                            </div>
                        @endif
                    </dl>

                    {{-- Section 3: Assignment --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Assignment</h3>

                    <dl class="grid grid-cols-1 gap-4 mb-8">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Assigned To</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->assignedUser->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Created By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->creator->name ?? 'System' }}</dd>
                        </div>
                        @if($lead->status === 'promoted' && $lead->promotedClient)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Promoted to Client</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('admin.clients.edit', $lead->promotedClient) }}" class="text-indigo-600 hover:text-indigo-900">{{ $lead->promotedClient->name }}</a>
                                </dd>
                            </div>
                        @endif
                    </dl>

                    {{-- Section 4: Status Timeline --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Status Timeline</h3>

                    <dl class="grid grid-cols-1 gap-4 mb-8">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">First Contacted</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->first_contacted_at?->format('d M Y, H:i') ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Last Contacted</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->last_contacted_at?->format('d M Y, H:i') ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $lead->updated_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @if($lead->promoted_at)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Promoted At</dt>
                                <dd class="mt-1 text-sm text-green-700">{{ $lead->promoted_at->format('d M Y, H:i') }}</dd>
                            </div>
                        @endif
                        @if($lead->disqualified_at)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Disqualified At</dt>
                                <dd class="mt-1 text-sm text-red-700">{{ $lead->disqualified_at->format('d M Y, H:i') }}</dd>
                            </div>
                        @endif
                    </dl>

                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Followups (action area) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">

                    {{-- Section 5: Followups --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Followups</h3>

                    {{-- 5a. Add Followup form (hidden in read-only mode: promoted/disqualified) --}}
                    @if(! in_array($lead->status, ['promoted', 'disqualified']))
                    <div x-data="{ open: false }" class="mb-6">
                        <button type="button" @click="open = !open" class="text-sm text-indigo-600 hover:text-indigo-900 mb-2">
                            <span x-show="!open">+ Add Followup</span>
                            <span x-show="open" x-cloak>− Cancel</span>
                        </button>

                        <div x-show="open" x-cloak class="border border-gray-200 rounded-md p-4 bg-gray-50">
                            <form method="POST" action="{{ route('admin.leads.followups.store', $lead) }}">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <x-input-label for="new_method" value="Method *" />
                                        <select id="new_method" name="method" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm" required>
                                            @foreach (\App\Models\LeadFollowup::METHODS as $key => $label)
                                                <option value="{{ $key }}" {{ old('method', 'whatsapp') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('method')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="new_scheduled_at" value="Scheduled At *" />
                                        <x-text-input id="new_scheduled_at" name="scheduled_at" type="datetime-local" class="mt-1 block w-full text-sm" :value="old('scheduled_at', now()->addDay()->format('Y-m-d\TH:i'))" required />
                                        <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <x-input-label for="new_notes" value="Notes" />
                                    <textarea id="new_notes" name="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2" placeholder="Optional context for this followup...">{{ old('notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                                <div x-data="{}" class="flex items-center gap-2">
                                    <x-primary-button>Save Followup</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    @php
                        $pendingFollowups   = $lead->followups->whereNull('completed_at');
                        $completedFollowups = $lead->followups->whereNotNull('completed_at');
                    @endphp

                    {{-- 5b. Pending Followups --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">
                            Pending ({{ $pendingFollowups->count() }})
                        </h4>

                        @if($pendingFollowups->isEmpty())
                            <div class="text-center py-6 text-gray-500 text-sm bg-gray-50 rounded-md">
                                No pending followups.
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($pendingFollowups as $followup)
                                    <div class="border border-yellow-200 bg-yellow-50/30 rounded-md p-4 text-sm">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <span class="font-medium text-gray-900">{{ $followup->method_label }}</span>
                                                <span class="ml-2 inline-flex px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800">Scheduled</span>
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                {{ $followup->scheduled_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                        @if($followup->notes)
                                            <div class="text-gray-700 whitespace-pre-line mb-2">{{ $followup->notes }}</div>
                                        @endif
                                        <div class="flex items-center justify-between mt-3">
                                            <div class="text-xs text-gray-500">
                                                by {{ $followup->creator->name ?? 'Unknown' }}
                                            </div>
                                            @if(! in_array($lead->status, ['promoted', 'disqualified']))
                                            <div x-data="{}" class="flex items-center gap-2">
                                                <button type="button"
                                                    @click="
                                                        $dispatch('open-modal', 'edit-followup');
                                                        $dispatch('load-followup', {
                                                            id: {{ $followup->id }},
                                                            method: '{{ $followup->method }}',
                                                            scheduled_at: '{{ $followup->scheduled_at->format('Y-m-d\TH:i') }}',
                                                            completed_at: '{{ $followup->completed_at?->format('Y-m-d\TH:i') ?: '' }}',
                                                            next_followup_at: '{{ $followup->next_followup_at?->format('Y-m-d\TH:i') ?: '' }}',
                                                            outcome: '{{ $followup->outcome ?: '' }}',
                                                            notes: @js($followup->notes ?: '')
                                                        });
                                                    "
                                                    class="text-indigo-600 hover:text-indigo-900 text-xs">Edit</button>

                                                <button type="button"
                                                    @click="
                                                        $dispatch('open-modal', 'complete-followup');
                                                        $dispatch('load-complete-followup', {
                                                            id: {{ $followup->id }},
                                                            method: '{{ $followup->method }}',
                                                        });
                                                    "
                                                    class="text-green-600 hover:text-green-900 text-xs">✓ Complete</button>

                                                <form method="POST" action="{{ route('admin.leads.followups.destroy', $followup) }}" class="inline" onsubmit="return confirm('Delete this followup?')">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- 5c. Completed Followups --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">
                            Completed ({{ $completedFollowups->count() }})
                        </h4>

                        @if($completedFollowups->isEmpty())
                            <div class="text-center py-6 text-gray-500 text-sm bg-gray-50 rounded-md">
                                No completed followups yet.
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($completedFollowups as $followup)
                                    <div class="border border-gray-200 rounded-md p-4 text-sm">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <span class="font-medium text-gray-900">{{ $followup->method_label }}</span>
                                                <span class="ml-2 inline-flex px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                                                @if($followup->outcome)
                                                    <span class="ml-1 inline-flex px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700">{{ $followup->outcome_label }}</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $followup->completed_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                        @if($followup->notes)
                                            <div class="text-gray-700 whitespace-pre-line mb-2">{{ $followup->notes }}</div>
                                        @endif
                                        <div class="flex items-center justify-between mt-3">
                                            <div class="text-xs text-gray-500">
                                                by {{ $followup->creator->name ?? 'Unknown' }}
                                                @if($followup->next_followup_at)
                                                    &middot; Next: {{ $followup->next_followup_at->format('d M Y') }}
                                                @endif
                                            </div>
                                            @if(! in_array($lead->status, ['promoted', 'disqualified']))
                                            <div x-data="{}" class="flex items-center gap-2">
                                                <button type="button"
                                                    @click="
                                                        $dispatch('open-modal', 'edit-followup');
                                                        $dispatch('load-followup', {
                                                            id: {{ $followup->id }},
                                                            method: '{{ $followup->method }}',
                                                            scheduled_at: '{{ $followup->scheduled_at->format('Y-m-d\TH:i') }}',
                                                            completed_at: '{{ $followup->completed_at?->format('Y-m-d\TH:i') ?: '' }}',
                                                            next_followup_at: '{{ $followup->next_followup_at?->format('Y-m-d\TH:i') ?: '' }}',
                                                            outcome: '{{ $followup->outcome ?: '' }}',
                                                            notes: @js($followup->notes ?: '')
                                                        });
                                                    "
                                                    class="text-indigo-600 hover:text-indigo-900 text-xs">Edit</button>

                                                <form method="POST" action="{{ route('admin.leads.followups.destroy', $followup) }}" class="inline" onsubmit="return confirm('Delete this followup?')">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Edit Followup Modal (single global modal, reused via Alpine state) --}}
    <div x-data="{
            followupId: null,
            method: 'whatsapp',
            scheduled_at: '',
            completed_at: '',
            next_followup_at: '',
            outcome: '',
            notes: ''
         }"
         x-on:load-followup.window="
            followupId = $event.detail.id;
            method = $event.detail.method;
            scheduled_at = $event.detail.scheduled_at;
            completed_at = $event.detail.completed_at;
            next_followup_at = $event.detail.next_followup_at;
            outcome = $event.detail.outcome;
            notes = $event.detail.notes;
         ">
        <x-modal name="edit-followup" maxWidth="2xl" focusable>
            <div class="p-6">

            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Followup</h3>

            <form method="POST" :action="`{{ url('admin/lead-followups') }}/${followupId}/update`">
                @csrf

                {{-- PENDING MODE: Method + Scheduled At (next_followup_at + outcome dropped) --}}
                <div x-show="!completed_at" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                    <div>
                        <x-input-label for="edit_method" value="Method *" />
                        <select id="edit_method" name="method" x-model="method" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm" required>
                            @foreach (\App\Models\LeadFollowup::METHODS as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_scheduled_at" value="Scheduled *" />
                        <input type="datetime-local" id="edit_scheduled_at" name="scheduled_at" x-model="scheduled_at" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm" required>
                    </div>
                </div>

                {{-- COMPLETED MODE: Method (read-only display), Outcome (editable), Completed At --}}
                <div x-show="completed_at">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input-label for="edit_method_completed" value="Method *" />
                            <select id="edit_method_completed" name="method" x-model="method" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm" required>
                                @foreach (\App\Models\LeadFollowup::METHODS as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="edit_outcome" value="Outcome *" />
                            <select id="edit_outcome" name="outcome" x-model="outcome" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                @foreach (\App\Models\LeadFollowup::OUTCOMES as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-input-label for="edit_completed_at_only" value="Completed At" />
                        <input type="datetime-local" id="edit_completed_at_only" name="completed_at" x-model="completed_at" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                    </div>
                    {{-- Preserve historical values (not editable in completed mode) --}}
                    <input type="hidden" name="scheduled_at" :value="scheduled_at">
                    <input type="hidden" name="next_followup_at" :value="next_followup_at">
                </div>

                <div class="mb-4">
                    <x-input-label for="edit_notes" value="Notes" />
                    <textarea id="edit_notes" name="notes" x-model="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-followup')" class="text-sm text-gray-600 hover:text-gray-900 px-4 py-2">Cancel</button>
                    <x-primary-button>Update Followup</x-primary-button>
                </div>
            </form>
        </div>
        </x-modal>
    </div>

    {{-- Complete Followup Modal (Phase 11.3.3.5: triggers Lead status change based on outcome) --}}
    <div x-data="{
            followupId: null,
            method: 'whatsapp',
            outcome: '',
            notes: ''
         }"
         x-on:load-complete-followup.window="
            followupId = $event.detail.id;
            method = $event.detail.method;
            outcome = '';
            notes = '';
         ">
        <x-modal name="complete-followup" maxWidth="lg" focusable>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mark Follow-up as Complete</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Select outcome to determine next action for this lead.
                </p>

                <form method="POST" :action="`{{ url('admin/lead-followups') }}/${followupId}/complete`">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="complete_outcome" value="Outcome *" />
                        <select id="complete_outcome" name="outcome" x-model="outcome" required class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                            <option value="">-- Select Outcome --</option>
                            @foreach (\App\Models\LeadFollowup::OUTCOMES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="mt-2 text-xs text-gray-500 space-y-1">
                            <div><span class="font-medium text-green-700">Positive</span> → Lead → Screened (siap promote)</div>
                            <div><span class="font-medium text-red-700">Negative</span> → Lead → Disqualified</div>
                            <div><span class="font-medium text-gray-700">No Response</span> → Lead status tetap, lakukan attempt lagi</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="complete_notes" value="Notes" />
                        <textarea id="complete_notes" name="notes" x-model="notes" rows="3" placeholder="Catatan singkat hasil follow-up (opsional)" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t">
                        <button type="button" x-on:click="$dispatch('close-modal', 'complete-followup')" class="text-sm text-gray-600 hover:text-gray-900 px-4 py-2">Cancel</button>
                        <x-primary-button>Mark Complete</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>

    {{-- Promote to Client Modal (Phase 11.3.5: handover from Marketing to Sales) --}}
    @if($lead->status === 'screened')
    <div x-data="{
            leadQuality: 'good',
            handoverNotes: ''
         }">
        <x-modal name="promote-to-client" maxWidth="lg" focusable>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">🚀 Promote Lead to Client</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Lead <strong>{{ $lead->contact_name }}</strong>
                    @if($lead->business_name)
                        ({{ $lead->business_name }})
                    @endif
                    akan di-handover ke tim Sales sebagai Client baru.
                </p>

                <form method="POST" action="{{ route('admin.leads.promote', $lead) }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="promote_lead_quality" value="Lead Quality *" />
                        <select id="promote_lead_quality" name="lead_quality" x-model="leadQuality" required class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                            @foreach (\App\Models\Client::LEAD_QUALITIES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Penilaian kualitas Lead untuk Sales: Good = high intent, Average = medium, Poor = low intent.</p>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="promote_handover_notes" value="Handover Notes for Sales" />
                        <textarea id="promote_handover_notes" name="handover_notes" x-model="handoverNotes" rows="4" placeholder="Catatan untuk tim Sales — context yang perlu mereka tahu sebelum follow up (opsional)" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"></textarea>
                        <p class="mt-1 text-xs text-gray-500">Optional. Handover note akan disimpan di Client.notes.</p>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded mb-4 text-xs text-blue-800">
                        <p class="font-semibold mb-1">Setelah promote:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Client baru di-create dengan stage <strong>Interested</strong></li>
                            <li>Lead status berubah jadi <strong>Promoted</strong> (read-only)</li>
                            <li>Tim Sales akan handle follow-up berikutnya</li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t">
                        <button type="button" x-on:click="$dispatch('close-modal', 'promote-to-client')" class="text-sm text-gray-600 hover:text-gray-900 px-4 py-2">Cancel</button>
                        <x-primary-button class="!bg-green-600 hover:!bg-green-700">Confirm Promote to Client</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
    @endif
</x-app-layout>
