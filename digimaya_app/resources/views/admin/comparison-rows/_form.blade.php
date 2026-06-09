@php
    $isEdit = isset($row);
    $aspectVal = old('aspect', $isEdit ? $row->aspect : '');
    $valueAVal = old('value_a', $isEdit ? $row->value_a : '');
    $valueBVal = old('value_b', $isEdit ? $row->value_b : '');
    $positionVal = old('position', $isEdit ? $row->position : 0);
    $isActiveVal = old('is_active', $isEdit ? $row->is_active : true);
@endphp

<div class="space-y-6">
    {{-- Aspect --}}
    <div>
        <label for="aspect" class="block text-sm font-medium text-gray-700 mb-1">Aspect / Criteria <span class="text-red-500">*</span></label>
        <input type="text" name="aspect" id="aspect" value="{{ $aspectVal }}" required maxlength="255"
               placeholder="e.g. Account-to-manager ratio"
               class="w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
        @error('aspect') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Value A --}}
    <div>
        <label for="value_a" class="block text-sm font-medium text-gray-700 mb-1">
            Value A (Typical Agency) <span class="text-red-500">*</span>
        </label>
        <textarea name="value_a" id="value_a" rows="2" required maxlength="500"
                  placeholder="Negative point — e.g. 30-50+ accounts per manager"
                  class="w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">{{ $valueAVal }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Max 500 karakter.</p>
        @error('value_a') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Value B --}}
    <div>
        <label for="value_b" class="block text-sm font-medium text-gray-700 mb-1">
            Value B (Digimaya) <span class="text-red-500">*</span>
        </label>
        <textarea name="value_b" id="value_b" rows="2" required maxlength="500"
                  placeholder="Positive point — e.g. Deliberately low ratio, never one of 50"
                  class="w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">{{ $valueBVal }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Max 500 karakter.</p>
        @error('value_b') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Position --}}
    <div>
        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position (Sort Order)</label>
        <input type="number" name="position" id="position" value="{{ $positionVal }}" min="0"
               class="w-32 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
        <p class="text-xs text-gray-500 mt-1">Angka kecil tampil duluan. Default 0.</p>
        @error('position') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Is Active --}}
    <div>
        <label class="inline-flex items-center text-sm text-gray-700">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ $isActiveVal ? 'checked' : '' }}
                   class="rounded border-gray-300 text-indigo-600 mr-2">
            Aktif (tampil di public)
        </label>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">
            {{ $isEdit ? 'Update' : 'Simpan' }}
        </button>
        <a href="{{ route('admin.comparison-rows.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</div>
