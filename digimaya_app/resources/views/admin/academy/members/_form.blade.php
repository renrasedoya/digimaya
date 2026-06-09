{{-- Reusable form fields for create + edit. Expects $member (nullable for create). --}}

<div class="mb-4">
    <x-input-label for="name" value="Nama" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                  :value="old('name', $member->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                  :value="old('email', $member->email ?? '')" required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
    @if(!isset($member))
        <p class="mt-1 text-xs text-gray-500">Welcome email akan dikirim ke alamat ini dengan setup link.</p>
    @endif
</div>

<div class="mb-4">
    <x-input-label for="notes" value="Internal Notes (optional)" />
    <textarea id="notes" name="notes" rows="3"
              class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm"
              placeholder="Catatan internal admin (tidak dilihat member).">{{ old('notes', $member->notes ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="tier" value="Tier" />
    <select id="tier" name="tier" required
            class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
        @php $selectedTier = old('tier', $member->tier ?? \App\Models\Member::TIER_FREE); @endphp
        <option value="{{ \App\Models\Member::TIER_FREE }}" {{ $selectedTier === \App\Models\Member::TIER_FREE ? 'selected' : '' }}>Free</option>
        <option value="{{ \App\Models\Member::TIER_PAID }}" {{ $selectedTier === \App\Models\Member::TIER_PAID ? 'selected' : '' }}>Paid</option>
    </select>
    <x-input-error :messages="$errors->get('tier')" class="mt-2" />
    <p class="mt-1 text-xs text-gray-500">Free = trial access (module Free only). Paid = full access.</p>
</div>

<div class="mb-6">
    <label class="inline-flex items-center">
        <input type="checkbox" name="is_active" value="1"
               {{ old('is_active', isset($member) ? $member->is_active : 1) ? 'checked' : '' }}
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
        <span class="ml-2 text-sm text-gray-600">Active (member bisa login)</span>
    </label>
</div>
