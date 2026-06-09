<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Troubleshooter') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Troubleshooter']]" />
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="troubleshooterCMS()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash message (success) --}}
            <div x-show="flashMessage" x-cloak class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" x-text="flashMessage"></div>

            {{-- Main 2-panel layout --}}
            <div class="grid gap-4" style="grid-template-columns: 420px 1fr">

                {{-- LEFT: Tree explorer --}}
                <div>
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-700">Problem Tree</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">{{ $nodes->count() }} nodes</span>
                                <button type="button"
                                        @click="addRootProblem()"
                                        title="Add root problem"
                                        class="flex items-center justify-center w-6 h-6 rounded-md bg-gray-800 text-white hover:bg-gray-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-3 overflow-y-auto" style="max-height: 600px">
                            @if($rootNodes->isEmpty())
                                <p class="text-sm text-gray-500 text-center py-8">Belum ada node. Klik "Add Root Problem" untuk mulai.</p>
                            @else
                                @foreach($rootNodes as $node)
                                    @include('admin.troubleshooter._tree_node', [
                                        'node' => $node,
                                        'depth' => 0,
                                        'nodesByParent' => $nodesByParent
                                    ])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Edit panel --}}
                <div>
                    <template x-if="!selectedNode">
                        <div class="bg-white rounded-lg shadow border border-gray-200 p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-3 text-sm text-gray-500">Pilih node dari kiri untuk edit detail.</p>
                        </div>
                    </template>

                    <template x-if="selectedNode">
                        <div class="bg-white rounded-lg shadow border border-gray-200">

                            {{-- Card header: title + badges --}}
                            <div class="px-6 pt-6 pb-4">
                                <div class="flex items-start justify-between gap-3">
                                    <h2 class="text-lg font-semibold text-gray-900 leading-snug" x-text="selectedNode.label"></h2>
                                    <div class="flex items-center gap-2 flex-shrink-0 mt-0.5">
                                        <span class="px-2 py-0.5 text-xs font-semibold uppercase rounded"
                                              :class="selectedNode.type === 'leaf' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'"
                                              x-text="selectedNode.type"></span>
                                        <span class="px-2 py-0.5 text-xs font-semibold uppercase rounded"
                                              :class="selectedNode.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                              x-text="selectedNode.is_active ? 'Active' : 'Draft'"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Card body: form fields --}}
                            <div class="p-6">
                                <div class="space-y-4">

                                    {{-- Label --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Label <span class="text-red-500">*</span></label>
                                        <input type="text"
                                               x-model="editForm.label"
                                               maxlength="255"
                                               placeholder="e.g. Iklan tidak tayang"
                                               class="block w-full border border-gray-300 focus:border-brand focus:outline-none rounded-md shadow-sm px-3 py-2 text-sm">
                                    </div>

                                    {{-- Type --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                        <select x-model="editForm.type"
                                                class="block w-full border border-gray-300 focus:border-brand focus:outline-none rounded-md shadow-sm px-3 py-2 text-sm">
                                            <option value="question">Question (has children / drill down)</option>
                                            <option value="leaf" x-show="selectedNode && selectedNode.parent_id !== null">Leaf (terminal, fill answers)</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500" x-show="selectedNode && selectedNode.parent_id !== null">Question: node yang punya children. Leaf: node terminal dengan jawaban final.</p>
                                        <p class="mt-1 text-xs text-gray-500" x-show="selectedNode && selectedNode.parent_id === null" x-cloak>Root node selalu Question (entry point untuk drill-down).</p>
                                    </div>

                                    {{-- Is Active toggle --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <label class="inline-flex items-center gap-3 cursor-pointer">
                                            <button type="button"
                                                    @click="editForm.is_active = !editForm.is_active"
                                                    role="switch"
                                                    :aria-checked="editForm.is_active"
                                                    :class="editForm.is_active ? 'bg-brand' : 'bg-gray-200'"
                                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0">
                                                <span :style="editForm.is_active ? 'transform: translateX(1.5rem)' : 'transform: translateX(0.25rem)'"
                                                      class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform"></span>
                                            </button>
                                            <span class="text-sm text-gray-700" x-text="editForm.is_active ? 'Active (tampil ke user)' : 'Draft (disembunyikan)'"></span>
                                        </label>
                                    </div>

                                    {{-- Answers (only if type=leaf) --}}
                                    <div x-show="editForm.type === 'leaf'" class="pt-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Answers</label>

                                        <template x-if="!editForm.answers || editForm.answers.length === 0">
                                            <p class="text-sm text-gray-500 italic py-3 text-center bg-gray-50 rounded-md">Belum ada jawaban. Klik tombol di bawah untuk menambah.</p>
                                        </template>

                                        <div class="space-y-3">
                                            <template x-for="(answer, idx) in editForm.answers" :key="idx">
                                                <div class="border border-gray-200 rounded-md p-4 bg-white relative">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide" x-text="'Answer ' + (idx + 1)"></span>
                                                        <button type="button"
                                                                @click="removeAnswer(idx)"
                                                                title="Hapus jawaban ini"
                                                                class="text-gray-400 hover:text-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div class="space-y-3">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Cause</label>
                                                            <textarea x-model="editForm.answers[idx].cause"
                                                                      rows="2"
                                                                      maxlength="2000"
                                                                      placeholder="Tulis kemungkinan penyebab..."
                                                                      class="block w-full border border-gray-300 focus:border-brand focus:outline-none rounded-md shadow-sm px-3 py-2 text-sm resize-y"></textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Solution</label>
                                                            <textarea x-model="editForm.answers[idx].solution"
                                                                      rows="2"
                                                                      maxlength="5000"
                                                                      placeholder="Tulis langkah-langkah solusi..."
                                                                      class="block w-full border border-gray-300 focus:border-brand focus:outline-none rounded-md shadow-sm px-3 py-2 text-sm resize-y"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <button type="button"
                                                @click="addAnswer()"
                                                class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-brand border border-brand text-sm rounded-md"
                                                style="transition: all 0.15s"
                                                @mouseenter="$el.style.backgroundColor='#165DFF'; $el.style.color='white'"
                                                @mouseleave="$el.style.backgroundColor=''; $el.style.color=''">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Answer
                                        </button>
                                    </div>

                                    {{-- Videos (only if type=leaf) --}}
                                    <div x-show="editForm.type === 'leaf'" class="pt-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Video Tutorial</label>

                                        <template x-if="!editForm.videos || editForm.videos.length === 0">
                                            <p class="text-sm text-gray-500 italic py-3 text-center bg-gray-50 rounded-md">Belum ada video. Klik tombol di bawah untuk menambah.</p>
                                        </template>

                                        <div class="space-y-3">
                                            <template x-for="(video, idx) in editForm.videos" :key="idx">
                                                <div class="border border-gray-200 rounded-md p-4 bg-white relative">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide" x-text="'Video ' + (idx + 1)"></span>
                                                        <button type="button"
                                                                @click="removeVideo(idx)"
                                                                title="Hapus video ini"
                                                                class="text-gray-400 hover:text-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">YouTube URL or ID</label>
                                                        <input type="text"
                                                               x-model="editForm.videos[idx].input"
                                                               @input="extractVideoId(idx)"
                                                               placeholder="Paste YouTube URL atau 11-char ID"
                                                               class="block w-full border border-gray-300 focus:border-brand focus:outline-none rounded-md shadow-sm px-3 py-2 text-sm">
                                                        <p class="mt-1 text-xs text-gray-500" x-show="editForm.videos[idx].youtube_id" x-cloak>
                                                            Detected ID: <span class="font-mono font-semibold" x-text="editForm.videos[idx].youtube_id"></span>
                                                        </p>
                                                    </div>

                                                    <template x-if="editForm.videos[idx].youtube_id">
                                                        <div class="mt-3">
                                                            <a :href="'https://www.youtube.com/watch?v=' + editForm.videos[idx].youtube_id"
                                                               target="_blank" rel="noopener"
                                                               class="block w-48 aspect-video rounded-md overflow-hidden bg-gray-100 relative group">
                                                                <img :src="'https://img.youtube.com/vi/' + editForm.videos[idx].youtube_id + '/mqdefault.jpg'"
                                                                     alt="Video thumbnail"
                                                                     class="w-full h-full object-cover">
                                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition flex items-center justify-center">
                                                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M8 5v14l11-7z"/>
                                                                    </svg>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <button type="button"
                                                @click="addVideo()"
                                                class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-brand border border-brand text-sm rounded-md"
                                                style="transition: all 0.15s"
                                                @mouseenter="$el.style.backgroundColor='#165DFF'; $el.style.color='white'"
                                                @mouseleave="$el.style.backgroundColor=''; $el.style.color=''">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Video
                                        </button>
                                    </div>

                                </div>
                            </div>

                            {{-- Card footer: action buttons --}}
                            <div class="px-6 py-3 border-t border-gray-200 flex items-center gap-2 bg-gray-50/40">
                                <div class="ml-auto flex items-center gap-3">
                                    <button type="button"
                                            @click="deleteNode(selectedNodeId)"
                                            class="px-3 py-1.5 bg-white text-red-600 border border-red-200 text-sm rounded-md hover:bg-red-50">
                                        Delete
                                    </button>
                                    <button type="button"
                                            @click="saveNode()"
                                            :disabled="saving"
                                            :style="saving ? 'opacity: 0.6; cursor: not-allowed' : ''"
                                            class="px-4 py-1.5 bg-brand text-white text-sm rounded-md hover:bg-brand-700">
                                        <span x-show="!saving">Save</span>
                                        <span x-show="saving" x-cloak>Saving...</span>
                                    </button>
                                    <span x-show="isDirty" x-cloak class="text-xs text-amber-600 font-medium">● Unsaved changes</span>
                                </div>
                            </div>
                        </div>
                    </template>

</div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function troubleshooterCMS() {
            return {
                nodes: @json($nodes),
                selectedNodeId: null,
                expandedIds: [],
                editForm: {
                    label: '',
                    type: 'question',
                    is_active: true,
                    answers: [],
                    videos: [],
                },
                isDirty: false,
                hoveredNodeId: null,
                saving: false,
                addingChildAt: null,
                addChildLabel: '',
                addingChildSubmit: false,
                flashMessage: '',
                flashTimer: null,

                init() {
                    // Pickup flash message after page reload (e.g. after delete)
                    const savedFlash = sessionStorage.getItem('troubleshooterFlash');
                    if (savedFlash) {
                        sessionStorage.removeItem('troubleshooterFlash');
                        this.$nextTick(() => this.flashSuccess(savedFlash));
                    }

                    this.expandedIds = [];

                    this.$watch('selectedNodeId', (newId) => {
                        this.populateEditForm(newId);
                        if (newId) this.expandPathTo(newId);
                    });

                    const pendingSelectId = sessionStorage.getItem('ts_select_after_reload');
                    if (pendingSelectId) {
                        sessionStorage.removeItem('ts_select_after_reload');
                        const id = parseInt(pendingSelectId, 10);
                        if (this.nodes.find(n => n.id === id)) {
                            this.selectedNodeId = id;
                        }
                    }

                    this.$watch('editForm', () => {
                        this.checkDirty();
                    }, { deep: true });

                },

                emptyForm() {
                    return {
                        label: '',
                        type: 'question',
                        is_active: true,
                        answers: [],
                        videos: [],
                    };
                },

                populateEditForm(nodeId) {
                    if (!nodeId) {
                        this.editForm = this.emptyForm();
                        this.isDirty = false;
                        return;
                    }
                    const node = this.nodes.find(n => n.id === nodeId);
                    if (!node) return;

                    this.editForm = {
                        label: node.label || '',
                        type: node.type || 'question',
                        is_active: !!node.is_active,
                        answers: Array.isArray(node.answers) ? node.answers.map(a => ({...a})) : [],
                        videos: Array.isArray(node.videos) ? node.videos.map(v => ({ youtube_id: v.youtube_id || '', input: v.youtube_id || '' })) : [],
                    };
                    this.isDirty = false;
                },

                checkDirty() {
                    if (!this.selectedNode) {
                        this.isDirty = false;
                        return;
                    }
                    const original = this.selectedNode;
                    this.isDirty =
                        this.editForm.label !== (original.label || '') ||
                        this.editForm.type !== (original.type || 'question') ||
                        this.editForm.is_active !== !!original.is_active ||
                        JSON.stringify(this.editForm.answers || []) !== JSON.stringify(original.answers || []) ||
                        JSON.stringify((this.editForm.videos || []).filter(v => v.youtube_id).map(v => ({ youtube_id: v.youtube_id }))) !== JSON.stringify((original.videos || []).map(v => ({ youtube_id: v.youtube_id })));
                },

                addAnswer() {
                    if (!this.editForm.answers) {
                        this.editForm.answers = [];
                    }
                    this.editForm.answers.push({ cause: '', solution: '' });
                },

                removeAnswer(idx) {
                    if (!Array.isArray(this.editForm.answers)) return;
                    if (!confirm('Delete this answer?')) return;
                    this.editForm.answers.splice(idx, 1);
                },

                addVideo() {
                    if (!this.editForm.videos) {
                        this.editForm.videos = [];
                    }
                    this.editForm.videos.push({ youtube_id: '', input: '' });
                },

                removeVideo(idx) {
                    if (!Array.isArray(this.editForm.videos)) return;
                    if (!confirm('Delete this video?')) return;
                    this.editForm.videos.splice(idx, 1);
                },

                extractVideoId(idx) {
                    if (!Array.isArray(this.editForm.videos)) return;
                    const v = this.editForm.videos[idx];
                    if (!v) return;
                    const input = (v.input || '').trim();
                    if (!input) {
                        v.youtube_id = '';
                        return;
                    }

                    // Pattern 1: youtube.com/watch?v=XXX
                    let match = input.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
                    if (match) { v.youtube_id = match[1]; return; }

                    // Pattern 2: youtu.be short URL
                    match = input.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
                    if (match) { v.youtube_id = match[1]; return; }

                    // Pattern 3: youtube.com/embed/XXX
                    match = input.match(/\/embed\/([a-zA-Z0-9_-]{11})/);
                    if (match) { v.youtube_id = match[1]; return; }

                    // Pattern 4: raw 11-char ID
                    if (/^[a-zA-Z0-9_-]{11}$/.test(input)) {
                        v.youtube_id = input;
                        return;
                    }

                    // No valid pattern matched
                    v.youtube_id = '';
                },

                get selectedNode() {
                    return this.selectedNodeId
                        ? this.nodes.find(n => n.id === this.selectedNodeId)
                        : null;
                },

                childrenOf(parentId) {
                    return this.nodes
                        .filter(n => n.parent_id === parentId)
                        .sort((a, b) => a.sort_order - b.sort_order || a.id - b.id);
                },

                hasChildren(id) {
                    return this.nodes.some(n => n.parent_id === id);
                },

                isExpanded(id) {
                    return this.expandedIds.includes(id);
                },

                toggleExpand(id) {
                    if (this.isExpanded(id)) {
                        this.expandedIds = this.expandedIds.filter(x => x !== id);
                    } else {
                        this.expandedIds.push(id);
                    }
                },

                expandPathTo(nodeId) {
                    let current = this.nodes.find(n => n.id === nodeId);
                    while (current && current.parent_id) {
                        if (!this.expandedIds.includes(current.parent_id)) {
                            this.expandedIds.push(current.parent_id);
                        }
                        current = this.nodes.find(n => n.id === current.parent_id);
                    }
                },


                selectNode(id) {
                    if (this.isDirty && !confirm('Ada perubahan yang belum disimpan. Lanjut tanpa save?')) {
                        return;
                    }
                    this.selectedNodeId = id;
                    const hasChildren = this.nodes.some(n => n.parent_id === id);
                    if (hasChildren && !this.isExpanded(id)) {
                        this.expandedIds.push(id);
                    }
                },

                breadcrumbPath(node) {
                    const path = [];
                    let current = node;
                    while (current) {
                        path.unshift(current.label);
                        current = this.nodes.find(n => n.id === current.parent_id);
                    }
                    return path;
                },

                startAddChild(parentId) {
                    if (!parentId) return;
                    this.addChildLabel = '';
                    this.addingChildAt = parentId;
                    if (!this.expandedIds.includes(parentId)) {
                        this.expandedIds.push(parentId);
                    }
                    this.$nextTick(() => {
                        const ref = this.$refs['addChildInput_' + parentId];
                        if (ref) ref.focus();
                    });
                },

                cancelAddChild() {
                    this.addingChildAt = null;
                    this.addChildLabel = '';
                    this.addingChildSubmit = false;
                },

                async submitAddChild() {
                    if (!this.addingChildAt || this.addingChildSubmit) return;
                    const label = this.addChildLabel.trim();
                    if (!label) return;

                    const parentId = this.addingChildAt;
                    this.addingChildSubmit = true;
                    try {
                        const res = await fetch('{{ route("admin.troubleshooter.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                parent_id: parentId,
                                type: 'question',
                                label: label,
                                is_active: true,
                            }),
                        });

                        const data = await res.json();
                        if (!res.ok) {
                            alert('Error: ' + (data.message || 'Gagal create child'));
                            this.addingChildSubmit = false;
                            return;
                        }

                        const newNode = data.node;
                        sessionStorage.setItem('ts_select_after_reload', newNode.id);
                        sessionStorage.setItem('troubleshooterFlash', data.message || 'Child created.');
                        window.location.reload();
                    } catch (err) {
                        alert('Network error: ' + err.message);
                        this.addingChildSubmit = false;
                    }
                },

                parentPath(node) {
                    if (!node) return [];
                    const path = [];
                    let current = this.nodes.find(n => n.id === node.parent_id);
                    while (current) {
                        path.unshift({ id: current.id, label: current.label });
                        current = this.nodes.find(n => n.id === current.parent_id);
                    }
                    return path;
                },

                async addRootProblem() {
                    const label = prompt('Label untuk root problem baru:');
                    if (!label || !label.trim()) return;

                    try {
                        const res = await fetch('{{ route("admin.troubleshooter.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                parent_id: null,
                                type: 'question',
                                label: label.trim(),
                                is_active: true,
                            }),
                        });

                        const data = await res.json();
                        if (!res.ok) {
                            alert('Error: ' + (data.message || 'Gagal create root problem'));
                            return;
                        }

                        sessionStorage.setItem('troubleshooterFlash', data.message || 'Root problem created.');
                        window.location.reload();
                    } catch (err) {
                        alert('Network error: ' + err.message);
                    }
                },

                async saveNode() {
                    if (!this.selectedNodeId || this.saving) return;
                    if (!this.editForm.label.trim()) {
                        alert('Label tidak boleh kosong.');
                        return;
                    }

                    const original = this.selectedNode;
                    if (original && original.type === 'leaf' && this.editForm.type === 'question') {
                        const answerCount = Array.isArray(original.answers) ? original.answers.length : 0;
                        const videoCount = Array.isArray(original.videos) ? original.videos.length : 0;
                        if (answerCount > 0 || videoCount > 0) {
                            const parts = [];
                            if (answerCount > 0) parts.push(answerCount + ' jawaban');
                            if (videoCount > 0) parts.push(videoCount + ' video');
                            const msg = 'Mengubah ke Question akan menghapus ' + parts.join(' dan ') + '. Lanjutkan?';
                            if (!confirm(msg)) {
                                return;
                            }
                        }
                    }

                    this.saving = true;
                    try {
                        const url = `{{ url('admin/troubleshooter') }}/${this.selectedNodeId}`;
                        const res = await fetch(url, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                type: this.editForm.type,
                                label: this.editForm.label.trim(),
                                answers: (this.editForm.answers && this.editForm.answers.length > 0) ? this.editForm.answers : null,
                                videos: (this.editForm.videos && this.editForm.videos.length > 0)
                                    ? this.editForm.videos.filter(v => v.youtube_id).map(v => ({ youtube_id: v.youtube_id }))
                                    : null,
                                is_active: this.editForm.is_active,
                            }),
                        });

                        const data = await res.json();
                        if (!res.ok) {
                            alert('Error: ' + (data.message || 'Gagal save'));
                            return;
                        }

                        const updated = data.node;
                        const idx = this.nodes.findIndex(n => n.id === updated.id);
                        if (idx !== -1) {
                            this.nodes[idx] = {
                                id: updated.id,
                                parent_id: updated.parent_id,
                                type: updated.type,
                                label: updated.label,
                                answers: Array.isArray(updated.answers) ? updated.answers : [],
                                videos: Array.isArray(updated.videos) ? updated.videos : [],
                                sort_order: updated.sort_order,
                                is_active: updated.is_active,
                            };
                        }

                        this.isDirty = false;
                        this.flashSuccess('Saved');
                    } catch (err) {
                        alert('Network error: ' + err.message);
                    } finally {
                        this.saving = false;
                    }
                },

                async deleteNode(nodeId) {
                    const node = this.nodes.find(n => n.id === nodeId);
                    if (!node) return;

                    const descendantCount = this.countDescendants(nodeId);
                    let confirmMsg;
                    if (descendantCount === 0) {
                        confirmMsg = `Hapus "${node.label}"?`;
                    } else {
                        const total = descendantCount + 1;
                        confirmMsg = `"${node.label}" punya ${descendantCount} children.\n\nMenghapus akan menghilangkan ${total} nodes (termasuk descendants).\n\nLanjutkan?`;
                    }

                    if (!confirm(confirmMsg)) return;

                    try {
                        const url = `{{ url('admin/troubleshooter') }}/${nodeId}`;
                        const res = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                        });

                        const data = await res.json();
                        if (!res.ok) {
                            alert('Error: ' + (data.message || 'Gagal delete'));
                            return;
                        }

                        sessionStorage.setItem('troubleshooterFlash', data.message || 'Node deleted.');
                        window.location.reload();
                    } catch (err) {
                        alert('Network error: ' + err.message);
                    }
                },

                countDescendants(nodeId) {
                    const direct = this.nodes.filter(n => n.parent_id === nodeId);
                    let count = direct.length;
                    for (const child of direct) {
                        count += this.countDescendants(child.id);
                    }
                    return count;
                },

                flashSuccess(msg) {
                    this.flashMessage = msg;
                    if (this.flashTimer) clearTimeout(this.flashTimer);
                    this.flashTimer = setTimeout(() => { this.flashMessage = ''; }, 4000);
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
