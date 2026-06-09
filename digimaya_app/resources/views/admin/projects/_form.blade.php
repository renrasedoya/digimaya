{{-- Required vars: $project, $clients, $advertisers, $formAction, $formMethod ('POST' or 'PUT') --}}
@php
    $isEdit = $project->exists;
    // Build advertiser → parent_am_id map for client-scoped filtering in JS
    $advertiserAmMap = $advertisers->mapWithKeys(fn ($a) => [(int) $a->id => (int) ($a->parent_am_id ?? 0)])->toArray();
    $clientAmMap = $clients->mapWithKeys(fn ($c) => [(int) $c->id => (int) ($c->account_manager_id ?? 0)])->toArray();
@endphp

<form method="POST" action="{{ $formAction }}"
      x-data="{
          clientId: '{{ old('client_id', $project->client_id ?? '') }}',
          advertiserId: '{{ old('advertiser_id', $project->advertiser_id ?? '') }}',
          clientAmMap: @js($clientAmMap),
          advertiserAmMap: @js($advertiserAmMap),
          get filteredAdvertiserIds() {
              const targetAm = this.clientAmMap[this.clientId] ?? 0;
              if (targetAm === 0) {
                  // Client has no AM, allow any advertiser
                  return Object.keys(this.advertiserAmMap).map(id => parseInt(id));
              }
              return Object.keys(this.advertiserAmMap)
                  .filter(id => this.advertiserAmMap[id] === targetAm)
                  .map(id => parseInt(id));
          },
          isAdvertiserVisible(id) {
              return this.filteredAdvertiserIds.includes(parseInt(id));
          },
          onClientChange() {
              const allowed = this.filteredAdvertiserIds;
              if (this.advertiserId && !allowed.includes(parseInt(this.advertiserId))) {
                  this.advertiserId = '';
              }
          }
      }">
    @csrf
    @if($formMethod === 'PUT')
        @method('PUT')
    @endif

    {{-- Section 1: Project Info --}}
    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Project Info</h3>

    <div class="mb-4">
        <x-input-label for="client_id" value="Client *" />
        <select id="client_id" name="client_id" x-model="clientId" @change="onClientChange()" required
                class="block w-full">
            <option value="">-- Search and select client --</option>
            @if($isEdit && $project->client)
                <option value="{{ $project->client->id }}" selected>{{ $project->client->business_name }}</option>
            @endif
        </select>
        <p class="mt-1 text-xs text-gray-500">Search by client business name. Hanya client yang Anda kelola yang akan muncul.</p>
        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
    </div>

    <div class="mb-4">
        <x-input-label for="advertiser_id" value="Advertiser *" />
        <select id="advertiser_id" name="advertiser_id" x-model="advertiserId" required
                class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
            <option value="">-- Select Advertiser --</option>
            @foreach($advertisers as $advertiser)
                <option value="{{ $advertiser->id }}" x-show="isAdvertiserVisible({{ $advertiser->id }})">{{ $advertiser->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">Advertiser harus berada di bawah Account Manager yang sama dengan Client.</p>
        <x-input-error :messages="$errors->get('advertiser_id')" class="mt-2" />
    </div>

    <div class="mb-4">
        <x-input-label for="name" value="Project Name *" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                      :value="old('name', $project->name)" required maxlength="255"
                      placeholder="e.g. Skin Clinic - Google Ads Bandung" />
        <p class="mt-1 text-xs text-gray-500">Default biasanya pakai nama bisnis client + platform.</p>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="mb-6">
        <x-input-label for="account_url" value="Account URL" />
        <x-text-input id="account_url" name="account_url" type="text" class="mt-1 block w-full"
                      :value="old('account_url', $project->account_url)" placeholder="https://ads.google.com/..." maxlength="500" />
        <p class="mt-1 text-xs text-gray-500">Link langsung ke akun ad platform (opsional).</p>
        <x-input-error :messages="$errors->get('account_url')" class="mt-2" />
    </div>

    {{-- Section 2: Status & Timeline --}}
    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Status & Timeline</h3>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div>
            <x-input-label for="status" value="Status *" />
            <select id="status" name="status" required
                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                @foreach(\App\Models\Project::STATUSES as $key => $label)
                    <option value="{{ $key }}" {{ old('status', $project->status ?? 'active') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="started_at" value="Started At" />
            <x-text-input id="started_at" name="started_at" type="date" class="mt-1 block w-full"
                          :value="old('started_at', $project->started_at?->format('Y-m-d'))" />
            <p class="mt-1 text-xs text-gray-500">Tanggal mulai project. Jadi anchor periode billing di invoice - tanggal yang sama dipakai setiap bulan.</p>
            <x-input-error :messages="$errors->get('started_at')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="ended_at" value="Ended At" />
            <x-text-input id="ended_at" name="ended_at" type="date" class="mt-1 block w-full"
                          :value="old('ended_at', $project->ended_at?->format('Y-m-d'))" />
            <x-input-error :messages="$errors->get('ended_at')" class="mt-2" />
        </div>
        <div class="md:col-span-2">
            <x-input-label for="project_value" value="Project Value (opsional)" />
            <x-currency-input name="project_value" :value="old('project_value', $project->project_value)" prefix="Rp" />
            <p class="mt-1 text-xs text-gray-500">Nilai project. Bisa one-time, bulanan, atau annually - tergantung kebutuhan. Bisa di-tarik otomatis ke line item invoice.</p>
            <x-input-error :messages="$errors->get('project_value')" class="mt-2" />
        </div>
    </div>

    {{-- Section 3: Notes --}}
    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Internal Notes</h3>

    <div class="mb-6">
        <textarea id="notes" name="notes" rows="4"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"
                  placeholder="Catatan internal AM (tidak akan terlihat oleh advertiser).">{{ old('notes', $project->notes) }}</textarea>
        <p class="mt-1 text-xs text-gray-500">Brief detail (LP, KPI, audience) tetap di-handle di luar sistem (PDF + WA).</p>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-4 mt-8 pt-6 border-t">
        <x-primary-button>{{ $isEdit ? 'Update' : 'Save' }}</x-primary-button>
        <a href="{{ route('admin.projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</form>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endpush

@push('scripts')
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