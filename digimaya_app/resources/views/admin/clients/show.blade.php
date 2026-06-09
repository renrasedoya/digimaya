<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $client->business_name }}
                    @php
                        $statusColors = [
                            'prospect' => 'bg-blue-100 text-blue-800',
                            'active'   => 'bg-green-100 text-green-800',
                            'inactive' => 'bg-gray-100 text-gray-800',
                            'churned'  => 'bg-red-100 text-red-800',
                            'lost'     => 'bg-orange-100 text-orange-800',
                        ];
                    @endphp
                    <span class="ml-2 inline-flex px-2 py-1 text-xs rounded-full {{ $statusColors[$client->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ \App\Models\Client::STATUSES[$client->status] ?? ucfirst($client->status) }}
                    </span>
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Clients', 'url' => route('admin.clients.index')], ['label' => $client->business_name]]" />
                </div>
            </div>
            <div x-data="{}" class="flex items-center gap-2">
                <a href="{{ route('admin.clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" class="inline" onsubmit="return confirm('Delete this client?')">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT COLUMN: Info read-only --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">

                    {{-- Section 1: Contact Info --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Contact Info</h3>

                    <dl class="grid grid-cols-1 gap-4 mb-8">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Contact Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->contact_name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($client->contact_email)
                                    <a href="mailto:{{ $client->contact_email }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $client->contact_email }}
                                    </a>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Phone / WhatsApp</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($client->contact_phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->contact_phone) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $client->contact_phone }}
                                    </a>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                    </dl>

                    {{-- Section 2: Business Info --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Business Info</h3>

                    <dl class="grid grid-cols-1 gap-4 mb-8">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Business Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->business_name }}</dd>
                        </div>
                        @if($client->website_url)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Website</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ $client->website_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 break-all">
                                        {{ $client->website_url }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                        @if($client->industry)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Industry</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->industry }}</dd>
                            </div>
                        @endif
                        @if($client->source)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Source</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->source }}</dd>
                            </div>
                        @endif
                        @if($client->interested_in)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Interested In</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->interested_in_label }}</dd>
                            </div>
                        @endif
                        @if($client->lead_quality)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Lead Quality</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Client::LEAD_QUALITIES[$client->lead_quality] ?? ucfirst($client->lead_quality) }}</dd>
                            </div>
                        @endif
                    </dl>

                    {{-- Section 3: Account Info --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Account Info</h3>

                    <dl class="grid grid-cols-1 gap-4 mb-8">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($client->status) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Account Manager</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($client->accountManager)
                                    {{ $client->accountManager->name }}
                                @else
                                    <span class="text-gray-400 italic">Belum di-assign</span>
                                @endif
                            </dd>
                        </div>
                        @if($client->monthly_retainer)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Monthly Retainer</dt>
                                <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($client->monthly_retainer, 0, ',', '.') }}</dd>
                            </div>
                        @endif
                        @if($client->acquisition_cost)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Acquisition Cost</dt>
                                <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($client->acquisition_cost, 0, ',', '.') }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Created By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->creator->name ?? 'System' }}</dd>
                        </div>
                    </dl>

                    {{-- Section 4: Lifecycle --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Lifecycle</h3>

                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @if($client->client_since)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Client Since</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->client_since->format('d M Y') }}</dd>
                            </div>
                        @endif
                        @if($client->client_until)
                            <div>
                                <dt class="text-xs uppercase text-gray-500">Client Until</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $client->client_until->format('d M Y') }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $client->updated_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>

                    @if($client->notes)
                        <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Notes</h3>
                        <div class="text-sm text-gray-900 whitespace-pre-line">{{ $client->notes }}</div>
                    @endif

                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Followups (Phase 12.2) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">

                    {{-- Section 5: Followups --}}
                    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Followups</h3>

                    {{-- 5a. Add Followup form --}}
                    @if($client->status === 'lost')
                        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-md text-sm text-amber-800">
                            This client is marked <strong>Lost</strong>. Re-engage it to Prospect (via Edit) to resume follow-ups.
                        </div>
                    @else
                    <div x-data="{ open: false }" class="mb-6">
                        <button type="button" @click="open = !open" class="text-sm text-indigo-600 hover:text-indigo-900 mb-2">
                            <span x-show="!open">+ Add Followup</span>
                            <span x-show="open" x-cloak>− Cancel</span>
                        </button>

                        <div x-show="open" x-cloak class="border border-gray-200 rounded-md p-4 bg-gray-50">
                            <form method="POST" action="{{ route('admin.clients.followups.store', $client) }}">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <x-input-label for="new_method" value="Method *" />
                                        <select id="new_method" name="method" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm" required>
                                            @foreach (\App\Models\ClientFollowup::METHODS as $key => $label)
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
                        $pendingFollowups   = $client->followups->whereNull('completed_at');
                        $completedFollowups = $client->followups->whereNotNull('completed_at')->sortByDesc('completed_at');
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
                                            <div x-data="{}" class="flex items-center gap-2">
                                                <button type="button"
                                                    @click="
                                                        $dispatch('open-modal', 'edit-followup');
                                                        $dispatch('load-followup', {
                                                            id: {{ $followup->id }},
                                                            method: '{{ $followup->method }}',
                                                            scheduled_at: '{{ $followup->scheduled_at->format('Y-m-d\TH:i') }}',
                                                            completed_at: '{{ $followup->completed_at?->format('Y-m-d\TH:i') ?: '' }}',
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

                                                <form method="POST" action="{{ route('admin.clients.followups.destroy', $followup) }}" class="inline" onsubmit="return confirm('Delete this followup?')">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                                </form>
                                            </div>
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
                                            </div>
                                            <div x-data="{}" class="flex items-center gap-2">
                                                <button type="button"
                                                    @click="
                                                        $dispatch('open-modal', 'edit-followup');
                                                        $dispatch('load-followup', {
                                                            id: {{ $followup->id }},
                                                            method: '{{ $followup->method }}',
                                                            scheduled_at: '{{ $followup->scheduled_at->format('Y-m-d\TH:i') }}',
                                                            completed_at: '{{ $followup->completed_at?->format('Y-m-d\TH:i') ?: '' }}',
                                                            outcome: '{{ $followup->outcome ?: '' }}',
                                                            notes: @js($followup->notes ?: '')
                                                        });
                                                    "
                                                    class="text-indigo-600 hover:text-indigo-900 text-xs">Edit</button>

                                                <form method="POST" action="{{ route('admin.clients.followups.destroy', $followup) }}" class="inline" onsubmit="return confirm('Delete this followup?')">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                                </form>
                                            </div>
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

    {{-- ============================================================== --}}
    {{-- Edit Followup Modal (single global, reused via Alpine state)   --}}
    {{-- ============================================================== --}}
    <div x-data="{
            followupId: null,
            method: 'whatsapp',
            scheduled_at: '',
            completed_at: '',
            outcome: '',
            notes: ''
         }"
         x-on:load-followup.window="
            followupId = $event.detail.id;
            method = $event.detail.method;
            scheduled_at = $event.detail.scheduled_at;
            completed_at = $event.detail.completed_at;
            outcome = $event.detail.outcome;
            notes = $event.detail.notes;
         ">
        <x-modal name="edit-followup" maxWidth="2xl" focusable>
            <div class="p-6">

            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Followup</h3>

            <form method="POST" :action="`{{ url('admin/client-followups') }}/${followupId}/update`">
                @csrf

                {{-- PENDING MODE: Method + Scheduled At --}}
                <div x-show="!completed_at" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                    <div>
                        <x-input-label for="edit_method" value="Method *" />
                        <select id="edit_method" name="method" x-model="method" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm" required>
                            @foreach (\App\Models\ClientFollowup::METHODS as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_scheduled_at" value="Scheduled *" />
                        <input type="datetime-local" id="edit_scheduled_at" name="scheduled_at" x-model="scheduled_at" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm" required>
                    </div>
                </div>

                {{-- COMPLETED MODE: Method + Outcome + Completed At --}}
                <div x-show="completed_at">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input-label for="edit_method_completed" value="Method *" />
                            <select id="edit_method_completed" name="method" x-model="method" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm" required>
                                @foreach (\App\Models\ClientFollowup::METHODS as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="edit_outcome" value="Outcome" />
                            <select id="edit_outcome" name="outcome" x-model="outcome" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                <option value="">— Select outcome —</option>
                                @foreach (\App\Models\ClientFollowup::OUTCOMES as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-input-label for="edit_completed_at_only" value="Completed At" />
                        <input type="datetime-local" id="edit_completed_at_only" name="completed_at" x-model="completed_at" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                    </div>
                    {{-- Preserve historical scheduled_at (not editable in completed mode) --}}
                    <input type="hidden" name="scheduled_at" :value="scheduled_at">
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

    {{-- ============================================================== --}}
    {{-- Complete Followup Modal (single global, reused via Alpine state) --}}
    {{-- ============================================================== --}}
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
                    Catat hasil follow-up untuk tracking progress.
                </p>

                <form method="POST" :action="`{{ url('admin/client-followups') }}/${followupId}/complete`">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="complete_outcome" value="Outcome *" />
                        <select id="complete_outcome" name="outcome" x-model="outcome" required class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                            <option value="">-- Select Outcome --</option>
                            @foreach (\App\Models\ClientFollowup::OUTCOMES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="mt-2 text-xs text-gray-500 space-y-1">
                            <div><span class="font-medium text-green-700">Positive</span> → Interaksi sukses, ada kemajuan positif</div>
                            <div><span class="font-medium text-red-700">Negative</span> → Deal lost atau prospect explicitly menolak</div>
                            <div><span class="font-medium text-gray-700">No Response</span> → Tidak berhasil reach, perlu reschedule</div>
                            <div class="mt-2 pt-2 border-t border-gray-200 text-gray-400 italic">Catatan: status Client tetap di-update manual via Edit Client.</div>
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
</x-app-layout>