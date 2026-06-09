@php
    $children = $nodesByParent->get($node->id) ?? collect();
    $hasChildren = $children->count() > 0;
@endphp

<div style="position: relative; padding-left: {{ $depth * 20 }}px">
    @if($depth > 0)
        <div style="position: absolute; left: {{ ($depth - 1) * 20 + 8 }}px; top: 0; bottom: 0; border-left: 1px dashed #d1d5db; pointer-events: none;"></div>
    @endif

    <div class="flex items-center gap-1 py-1.5 px-2 rounded hover:bg-gray-50 cursor-pointer group"
         :class="selectedNodeId === {{ $node->id }} ? 'bg-blue-50' : ''"
         @click="selectNode({{ $node->id }})">

        @if($hasChildren)
            <button type="button"
                    @click.stop="toggleExpand({{ $node->id }})"
                    class="flex-shrink-0 w-4 h-4 flex items-center justify-center text-gray-400 hover:text-gray-700">
                <svg x-show="isExpanded({{ $node->id }})" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                </svg>
                <svg x-show="!isExpanded({{ $node->id }})" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                </svg>
            </button>
        @else
            <span class="flex-shrink-0 w-4 h-4 flex items-center justify-center text-gray-300">
                <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="2"/></svg>
            </span>
        @endif

        <span class="flex-1 text-sm {{ $node->is_active ? 'text-gray-900' : 'text-gray-400 italic' }}">{{ $node->label }}</span>

        @if($node->type === 'leaf')
            <span class="px-1.5 py-0.5 text-xs font-semibold uppercase rounded bg-green-100 text-green-700">Leaf</span>
        @endif
        @if(!$node->is_active)
            <span class="px-1.5 py-0.5 text-xs font-semibold uppercase rounded bg-gray-100 text-gray-500">Draft</span>
        @endif

        {{-- "+" Add child icon (visible saat node selected & type=question) --}}
        <button type="button"
                x-show="selectedNodeId === {{ $node->id }} && '{{ $node->type }}' === 'question'"
                x-cloak
                @click.stop="startAddChild({{ $node->id }})"
                title="Add child"
                class="flex-shrink-0 flex items-center justify-center w-5 h-5 rounded"
                style="transition: all 0.15s"
                @mouseenter="$el.style.color='#165DFF'; $el.style.backgroundColor='#eef4ff'"
                @mouseleave="$el.style.color=''; $el.style.backgroundColor=''">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
    </div>

    {{-- Inline Add Child form (muncul saat addingChildAt === node.id) --}}
    <div x-show="addingChildAt === {{ $node->id }}"
         x-cloak
         style="position: relative; padding-left: {{ ($depth + 1) * 20 }}px">
        <div style="position: absolute; left: {{ $depth * 20 + 8 }}px; top: 0; bottom: 0; border-left: 1px dashed #d1d5db; pointer-events: none;"></div>
        <div class="flex items-center gap-2 py-1.5 px-2">
            <span class="flex-shrink-0 w-4 h-4 flex items-center justify-center text-gray-300">
                <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="2"/></svg>
            </span>
            <input type="text"
                   x-model="addChildLabel"
                   x-ref="addChildInput_{{ $node->id }}"
                   @keydown.enter.prevent="submitAddChild()"
                   @keydown.escape="cancelAddChild()"
                   @click.stop
                   maxlength="255"
                   placeholder="Type label, Enter to add, Esc to cancel"
                   :disabled="addingChildSubmit"
                   class="flex-1 text-sm border border-gray-300 focus:border-brand focus:outline-none rounded px-2 py-1">
            <button type="button"
                    x-show="addingChildSubmit"
                    x-cloak
                    class="text-xs text-gray-500 italic">Adding...</button>
        </div>
    </div>

    @if($hasChildren)
        <div x-show="isExpanded({{ $node->id }})" x-cloak>
            @foreach($children as $child)
                @include('admin.troubleshooter._tree_node', [
                    'node' => $child,
                    'depth' => $depth + 1,
                    'nodesByParent' => $nodesByParent
                ])
            @endforeach
        </div>
    @endif
</div>
