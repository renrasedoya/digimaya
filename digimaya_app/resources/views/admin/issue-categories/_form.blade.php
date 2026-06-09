{{-- Required vars: $category, $existingSubs, $formAction, $formMethod ('POST' or 'PUT') --}}
@php
    $isEdit = $category->exists;
    $oldSubs = old('sub_categories');
    if ($oldSubs !== null) {
        // Form re-render after validation error
        $initialSubs = collect($oldSubs)->map(fn ($s) => [
            'id' => $s['id'] ?? null,
            'name' => $s['name'] ?? '',
            'display_order' => $s['display_order'] ?? 0,
            'is_active' => isset($s['is_active']) ? (bool) $s['is_active'] : true,
        ])->values()->all();
    } else {
        $initialSubs = $existingSubs->map(fn ($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'display_order' => $s->display_order,
            'is_active' => $s->is_active,
        ])->values()->all();
    }
@endphp

<form method="POST" action="{{ $formAction }}"
      x-data="{
          subs: @js($initialSubs),
          addSub() {
              const nextOrder = this.subs.length > 0 ? Math.max(...this.subs.map(s => parseInt(s.display_order) || 0)) + 10 : 10;
              this.subs.push({ id: null, name: '', display_order: nextOrder, is_active: true });
          },
          removeSub(index) {
              const item = this.subs[index];
              if (item.id) {
                  // Existing sub: mark inactive instead of remove (preserve FK)
                  item.is_active = false;
              } else {
                  // New unsaved sub: just remove from array
                  this.subs.splice(index, 1);
              }
          },
          restoreSub(index) {
              this.subs[index].is_active = true;
          }
      }">
    @csrf
    @if($formMethod === 'PUT')
        @method('PUT')
    @endif

    {{-- ===== Category Info ===== --}}
    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Category Info</h3>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="sm:col-span-2">
            <x-input-label for="name" value="Category Name *" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $category->name)" required autofocus maxlength="100" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="display_order" value="Display Order" />
            <x-text-input id="display_order" name="display_order" type="number" class="mt-1 block w-full"
                          :value="old('display_order', $category->display_order ?? 0)" min="0" max="9999" />
            <p class="mt-1 text-xs text-gray-500">Lower = appears first.</p>
            <x-input-error :messages="$errors->get('display_order')" class="mt-2" />
        </div>
    </div>

    <div class="mb-6">
        <label class="flex items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
            <span class="ml-2 text-sm text-gray-700">Active</span>
        </label>
        <p class="mt-1 text-xs text-gray-500">Inactive categories tidak muncul di Project Report dropdown.</p>
    </div>

    {{-- ===== Sub-categories ===== --}}
    <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-8">Sub-categories</h3>

    <p class="text-xs text-gray-500 mb-4">
        Sub-categories yang sudah pernah dipakai di Project Report tidak bisa di-delete (FK constraint), tapi bisa di-set inactive (hilang dari dropdown form, tapi historical data tetap utuh).
    </p>

    <div class="space-y-2 mb-4">
        <template x-for="(sub, index) in subs" :key="index">
            <div class="flex items-center gap-2 p-2 border rounded-md"
                 :class="sub.is_active ? 'border-gray-200 bg-white' : 'border-gray-200 bg-gray-50 opacity-60'">
                <input type="hidden" :name="`sub_categories[${index}][id]`" :value="sub.id || ''">
                <input type="hidden" :name="`sub_categories[${index}][is_active]`" :value="sub.is_active ? '1' : '0'">

                <input type="number"
                       :name="`sub_categories[${index}][display_order]`"
                       x-model="sub.display_order"
                       class="w-20 border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                       min="0" max="9999" placeholder="Order">

                <input type="text"
                       :name="`sub_categories[${index}][name]`"
                       x-model="sub.name"
                       class="flex-1 border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                       maxlength="100" placeholder="e.g. Conversion drop">

                <template x-if="sub.is_active">
                    <button type="button" @click="removeSub(index)"
                            class="text-red-600 hover:text-red-900 text-sm px-2"
                            x-text="sub.id ? 'Deactivate' : 'Remove'"></button>
                </template>

                <template x-if="!sub.is_active">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">Inactive</span>
                        <button type="button" @click="restoreSub(index)"
                                class="text-indigo-600 hover:text-indigo-900 text-sm px-2">Restore</button>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <button type="button" @click="addSub()"
            class="text-sm text-indigo-600 hover:text-indigo-900">
        + Add Sub-category
    </button>

    {{-- ===== Submit Buttons ===== --}}
    <div class="flex items-center gap-4 mt-8 pt-6 border-t">
        <x-primary-button>{{ $isEdit ? 'Update' : 'Save' }}</x-primary-button>
        <a href="{{ route('admin.issue-categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</form>
