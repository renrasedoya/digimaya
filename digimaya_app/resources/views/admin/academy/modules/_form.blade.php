{{-- Required vars: $module (Module instance, can be new), $formAction (URL), $formMethod ('POST' or 'PUT') --}}

<div class="bg-white shadow-sm sm:rounded-lg">
    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        @if($formMethod === 'PUT')
            @method('PUT')
        @endif

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">
                Title <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title', $module->title ?? '') }}"
                   maxlength="255"
                   required
                   placeholder="e.g. Google Ads Fundamentals"
                   class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @if(!isset($module) || !$module->exists)
                <p class="mt-1 text-xs text-gray-500">Slug akan auto-generate dari title.</p>
            @endif
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description"
                      name="description"
                      rows="4"
                      placeholder="Deskripsi singkat module untuk preview di list."
                      class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">{{ old('description', $module->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Cover Image (upload OR external URL) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>

            @if(isset($module) && $module->exists && $module->cover_image)
                <div class="mb-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                    <p class="text-xs text-gray-600 mb-2">Current cover image:</p>
                    <div class="flex items-center gap-3">
                        <img src="{{ $module->cover_image_url }}" alt="Current cover" class="w-24 h-24 object-cover rounded-md border border-gray-200">
                        <div class="text-xs text-gray-500 break-all">
                            {{ $module->coverImageIsExternal() ? 'External: ' . $module->cover_image : 'Uploaded: ' . $module->cover_image }}
                        </div>
                    </div>
                    <label class="inline-flex items-center mt-3 text-xs text-red-600">
                        <input type="checkbox" name="remove_cover_image" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2">Remove current cover image</span>
                    </label>
                </div>
            @endif

            <div class="space-y-2">
                <div>
                    <label for="cover_image_file" class="block text-xs text-gray-600 mb-1">Upload File <span class="text-gray-400">(max 1MB, JPG/PNG/WebP)</span></label>
                    <input type="file"
                           id="cover_image_file"
                           name="cover_image_file"
                           accept="image/*"
                           class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    @error('cover_image_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2 my-2">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="text-xs text-gray-400">OR</span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                <div>
                    <label for="cover_image_url" class="block text-xs text-gray-600 mb-1">External URL</label>
                    <input type="text"
                           id="cover_image_url"
                           name="cover_image_url"
                           value="{{ old('cover_image_url', isset($module) && $module->coverImageIsExternal() ? $module->cover_image : '') }}"
                           placeholder="https://example.com/cover.jpg"
                           class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                    @error('cover_image_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="mt-2 text-xs text-gray-500">Cover image akan tampil di card module di dashboard member. Kalau kosong, akan ditampilkan circle dengan inisial title.</p>
        </div>

        {{-- Order + Tier + Published --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-4 border-t border-gray-200">
            <div>
                <label for="display_order" class="block text-sm font-medium text-gray-700">Display order</label>
                <input type="number"
                       id="display_order"
                       name="display_order"
                       value="{{ old('display_order', $module->display_order ?? 0) }}"
                       min="0"
                       max="9999"
                       class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                <p class="mt-1 text-xs text-gray-500">Lower = shown first.</p>
            </div>

            <div>
                <label for="tier" class="block text-sm font-medium text-gray-700">
                    Tier <span class="text-red-500">*</span>
                </label>
                <select id="tier"
                        name="tier"
                        required
                        class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                    @php $selectedTier = old('tier', $module->tier ?? \App\Models\Module::TIER_FREE); @endphp
                    <option value="{{ \App\Models\Module::TIER_FREE }}" {{ $selectedTier === \App\Models\Module::TIER_FREE ? 'selected' : '' }}>Free</option>
                    <option value="{{ \App\Models\Module::TIER_PAID }}" {{ $selectedTier === \App\Models\Module::TIER_PAID ? 'selected' : '' }}>Paid</option>
                </select>
                @error('tier')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Free = open to all. Paid = paid members only.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <div class="mt-1 flex items-center h-[38px]">
                    <label class="inline-flex items-center">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox"
                               name="is_published"
                               value="1"
                               @checked(old('is_published', $module->is_published ?? false))
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 whitespace-nowrap">Published (visible to members)</span>
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Uncheck = draft, hidden dari members.</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end space-x-2 pt-6 border-t border-gray-200">
            <a href="{{ isset($module) && $module->exists ? route('admin.academy.modules.show', $module) : route('admin.academy.modules.index') }}"
               class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                {{ $formMethod === 'PUT' ? 'Update Module' : 'Create Module' }}
            </button>
        </div>
    </form>
</div>
