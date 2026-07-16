@props(['label', 'align' => 'right'])

{{-- Table column header with a small hover/click tooltip explaining the column.
     Relies on Alpine.js (already loaded in the admin layout).
     Tooltip typography is set via inline style on purpose: the project ships a
     pre-compiled Tailwind build that may not contain these utility classes, and
     the header row forces uppercase which must be reset here. --}}
<span class="relative inline-block"
      x-data="{ open: false }"
      @click.outside="open = false"
      @keydown.escape="open = false">
    <button type="button"
            @mouseenter="open = true" @mouseleave="open = false" @click="open = !open"
            class="inline-flex items-center gap-1 cursor-help focus:outline-none"
            style="white-space: nowrap;">
        <span>{{ $label }}</span>
        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-3a1 1 0 102 0 1 1 0 00-2 0zm.25 3a.75.75 0 000 1.5h.25v2.5a.75.75 0 001.5 0v-3a.75.75 0 00-.75-.75H10.25z" clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="open" x-transition.opacity
         class="absolute top-full mt-1 z-30 rounded-md bg-gray-900 text-white shadow-lg {{ $align === 'left' ? 'left-0' : 'right-0' }}"
         style="display: none; width: 16rem; padding: 8px 10px; font-size: 11px; line-height: 1.45; font-weight: 400; text-transform: none; letter-spacing: normal; text-align: left; white-space: normal;">
        {{ $slot }}
    </div>
</span>
