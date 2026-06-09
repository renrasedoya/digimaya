@php
    $isEdit = isset($service);
    $titleVal = old('title', $isEdit ? $service->title : '');
    $descVal = old('description', $isEdit ? $service->description : '');
    $iconUrlVal = old('icon_url', $isEdit ? $service->icon_url : '');
    $positionVal = old('position', $isEdit ? $service->position : 0);
    $isActiveVal = old('is_active', $isEdit ? $service->is_active : true);
@endphp

<div class="space-y-6">
    {{-- Title --}}
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" id="title" value="{{ $titleVal }}" required maxlength="255"
               class="w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
        @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
        <textarea name="description" id="description" rows="4" required maxlength="1000"
                  class="w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">{{ $descVal }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Max 1000 karakter.</p>
        @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Icon Image Upload --}}
    <div>
        <label for="icon_image" class="block text-sm font-medium text-gray-700 mb-1">Icon Image (Upload)</label>

        @if($isEdit && $service->icon_image)
            <div class="mb-2 flex items-center gap-3">
                <img src="{{ asset('storage/' . $service->icon_image) }}" alt="Current icon" class="w-16 h-16 object-contain border border-gray-200 rounded">
                <label class="inline-flex items-center text-sm text-red-600">
                    <input type="checkbox" name="remove_icon_image" value="1" class="rounded border-gray-300 text-red-600 mr-2">
                    Hapus icon ini
                </label>
            </div>
        @endif

        <input type="file" name="icon_image" id="icon_image" accept="image/png,image/jpeg,image/svg+xml,image/webp"
               class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
        <p class="text-xs text-gray-500 mt-1">PNG, JPG, SVG, WEBP. Max 2MB.</p>
        @error('icon_image') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Icon URL Fallback --}}
    <div>
        <label for="icon_url" class="block text-sm font-medium text-gray-700 mb-1">Icon URL (Fallback)</label>
        <input type="url" name="icon_url" id="icon_url" value="{{ $iconUrlVal }}" maxlength="500"
               placeholder="https://example.com/icon.svg"
               class="w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
        <p class="text-xs text-gray-500 mt-1">Dipake kalau tidak ada upload image. Upload image akan override URL ini.</p>
        @error('icon_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
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
        <a href="{{ route('admin.public-services.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</div>
