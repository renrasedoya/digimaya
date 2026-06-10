<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Proposal') }}</h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Proposals', 'url' => route('admin.proposals.index')], ['label' => 'Edit']]" />
                </div>
            </div>
            <div>
                @if($proposal->isPublished())
                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                @else
                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Draft</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.proposals.update', $proposal) }}"
                  x-data="proposalBuilder(@js($proposal->content_blocks ?? []), @js($snippets), @js($pricingCounts), @js($referenceData))"
                  @submit="syncBlocks()">
                @csrf
                @method('PUT')

                {{-- Proposal Info --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 space-y-6">
                        <h3 class="text-sm font-semibold text-gray-700">Proposal Info</h3>

                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Client (Prospect) <span class="text-red-500">*</span></label>
                            <select id="client_id" name="client_id" required
                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ (int) old('client_id', $proposal->client_id) === $client->id ? 'selected' : '' }}>{{ $client->business_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Proposal Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $proposal->title) }}" required maxlength="255"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full">
                        </div>
                    </div>
                </div>

                {{-- Content Blocks --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-700">Content Blocks</h3>
                            <span class="text-xs text-gray-400" x-text="blocks.length + ' block(s)'"></span>
                        </div>

                        {{-- Empty state --}}
                        <template x-if="blocks.length === 0">
                            <p class="text-sm text-gray-500 text-center py-8 border border-dashed border-gray-200 rounded-md">
                                Belum ada block. Tambah block di bawah untuk mulai menyusun proposal.
                            </p>
                        </template>

                        {{-- Block list --}}
                        <div class="space-y-3">
                            <template x-for="(block, index) in blocks" :key="block.uid">
                                <div class="border border-gray-200 rounded-md">
                                    {{-- Block header --}}
                                    <div class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-t-md">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-500" x-text="blockLabel(block.type)"></span>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                                    class="px-2 py-1 text-xs text-gray-600 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed">Up</button>
                                            <button type="button" @click="moveDown(index)" :disabled="index === blocks.length - 1"
                                                    class="px-2 py-1 text-xs text-gray-600 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed">Down</button>
                                            <button type="button" @click="removeBlock(index)"
                                                    class="px-2 py-1 text-xs text-red-600 hover:text-red-900">Remove</button>
                                        </div>
                                    </div>

                                    {{-- Custom block --}}
                                    <div class="p-3 space-y-3" x-show="block.type === 'custom'">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Section Title</label>
                                            <input type="text" x-model="block.title" maxlength="255"
                                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Content</label>
                                            <textarea x-model="block.body" rows="6" maxlength="50000"
                                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"></textarea>
                                            <p class="mt-1 text-xs text-gray-400">Basic HTML allowed (sanitized on save).</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Gambar (opsional)</label>
                                            <div class="mt-1 flex items-center gap-3">
                                                <input type="file" accept="image/*" @change="uploadImage(block, $event)"
                                                       class="block text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                                <span x-show="block._uploading" x-cloak class="text-xs text-gray-400">Mengupload...</span>
                                            </div>
                                            <input type="url" x-model="block.image_url" maxlength="1000" placeholder="atau tempel URL https://..."
                                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-2 block w-full text-sm">
                                            <p class="mt-1 text-xs text-gray-400">Upload file dari komputer (maks 4MB), atau tempel URL gambar. Tampil di bawah teks.</p>
                                            <template x-if="block.image_url">
                                                <div class="mt-2">
                                                    <img :src="block.image_url" class="max-h-32 rounded border border-gray-100" alt="preview">
                                                    <button type="button" @click="block.image_url = ''"
                                                            class="block mt-1 text-xs text-red-600 hover:text-red-800">Hapus gambar</button>
                                                </div>
                                            </template>
                                        </div>
                                        <div x-show="block.image_url">
                                            <label class="block text-xs font-medium text-gray-600">Caption Gambar (opsional)</label>
                                            <input type="text" x-model="block.caption" maxlength="255"
                                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                        </div>
                                    </div>

                                    {{-- Snippet block (copy-on-insert: editable like custom) --}}
                                    <div class="p-3 space-y-3" x-show="block.type === 'snippet'">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Insert from Library</label>
                                            <select x-model="block.sourceId" @change="applySnippet(block)"
                                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                                <option value="">-- Select snippet --</option>
                                                <template x-for="s in snippets" :key="s.id">
                                                    <option :value="s.id" x-text="s.title"></option>
                                                </template>
                                            </select>
                                            <p class="mt-1 text-xs text-gray-400">Memilih ulang akan menimpa Title &amp; Content di bawah.</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Section Title</label>
                                            <input type="text" x-model="block.title" maxlength="255"
                                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Content</label>
                                            <textarea x-model="block.body" rows="6" maxlength="50000"
                                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"></textarea>
                                            <p class="mt-1 text-xs text-gray-400">Disalin dari library. Edit bebas, tidak mengubah master snippet.</p>
                                        </div>
                                        <div x-show="block.images && block.images.length">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Gambar dari snippet (<span x-text="(block.images || []).length"></span>)</label>
                                            <div class="flex flex-wrap gap-2">
                                                <template x-for="(url, i) in block.images" :key="url">
                                                    <div class="relative">
                                                        <img :src="url" class="h-20 w-20 object-cover rounded border border-gray-200" alt="preview">
                                                        <button type="button" @click="block.images.splice(i, 1)"
                                                                class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs leading-none">&times;</button>
                                                    </div>
                                                </template>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">Disalin dari snippet. Hapus yang tak perlu (tidak mengubah master snippet).</p>
                                        </div>
                                    </div>

                                    {{-- Pricing block (stores option only; tiers resolved at render) --}}
                                    <div class="p-3 space-y-3" x-show="block.type === 'pricing'">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Heading (optional)</label>
                                            <input type="text" x-model="block.heading" maxlength="255" placeholder="Paket & Investasi"
                                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Tampilkan</label>
                                            <select x-model="block.option"
                                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                                <option value="all">Semua harga</option>
                                                <option value="lower">Level bawah (4-10jt)</option>
                                                <option value="upper">Level atas (11jt+)</option>
                                            </select>
                                            <p class="mt-1 text-xs text-gray-400">
                                                Akan menampilkan
                                                <span class="font-medium" x-text="pricingCount(block.option)"></span>
                                                baris harga dari rate card aktif.
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Reference block (stores source + ids; records resolved at render) --}}
                                    <div class="p-3 space-y-3" x-show="block.type === 'reference'">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Heading (optional)</label>
                                            <input type="text" x-model="block.heading" maxlength="255" placeholder="Klien Kami"
                                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Sumber</label>
                                            <select x-model="block.source" @change="changeSource(block)"
                                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                                <option value="">-- Pilih sumber --</option>
                                                <option value="logo_wall">Logo Wall</option>
                                                <option value="testimonials">Testimonials</option>
                                                <option value="case_studies">Case Studies</option>
                                                <option value="services">Services (Paket Layanan)</option>
                                            </select>
                                        </div>
                                        <div x-show="block.source">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Pilih item</label>
                                            <template x-if="referenceItems(block.source).length === 0">
                                                <p class="text-xs text-gray-400 py-2">Belum ada item aktif di sumber ini.</p>
                                            </template>
                                            <div class="space-y-1 max-h-60 overflow-y-auto border border-gray-100 rounded-md p-2"
                                                 x-show="referenceItems(block.source).length > 0">
                                                <template x-for="item in referenceItems(block.source)" :key="item.id">
                                                    <label class="flex items-center gap-2 text-sm text-gray-700 py-1">
                                                        <input type="checkbox" :value="item.id"
                                                               :checked="block.ids.includes(item.id)"
                                                               @change="toggleRef(block, item.id)"
                                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200">
                                                        <span x-text="item.label"></span>
                                                    </label>
                                                </template>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">
                                                <span class="font-medium" x-text="block.ids.length"></span> item dipilih.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Add block --}}
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-medium text-gray-500 mr-1">Add block:</span>
                                <button type="button" @click="addBlock('custom')"
                                        class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">+ Custom</button>
                                <button type="button" @click="addBlock('snippet')"
                                        class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">+ Snippet</button>
                                <button type="button" @click="addBlock('pricing')"
                                        class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">+ Pricing</button>
                                <button type="button" @click="addBlock('reference')"
                                        class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">+ Reference</button>
                            </div>
                        </div>

                        {{-- Hidden serialized payload --}}
                        <input type="hidden" name="content_blocks" x-ref="payload">
                    </div>
                </div>

                {{-- Publish & Share --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-700">Publish & Share</h3>
                        @if($proposal->isPublished())
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Public Link</label>
                                <div class="flex flex-wrap gap-2" x-data="{ copied: false }">
                                    <input type="text" readonly x-ref="link"
                                           value="{{ route('public.proposal.show', $proposal->public_token) }}"
                                           class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-50 text-gray-600">
                                    <button type="button"
                                            @click="navigator.clipboard.writeText($refs.link.value); copied = true; setTimeout(() => copied = false, 1500)"
                                            class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                        <span x-show="!copied">Copy</span>
                                        <span x-show="copied" x-cloak class="text-green-600">Copied</span>
                                    </button>
                                    <a href="{{ route('public.proposal.show', $proposal->public_token) }}" target="_blank"
                                       class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 whitespace-nowrap">Open</a>
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Published {{ $proposal->published_at?->format('d M Y, H:i') }}. Klik Update Published untuk refresh isi setelah edit block.</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Proposal masih draft. Klik Publish untuk membuat link publik (block tersimpan otomatis saat publish).</p>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <p class="text-xs text-gray-400 text-right mb-2">Tip: klik Save Draft dulu sebelum Preview atau Download PDF supaya perubahan block terbaru ikut tampil.</p>
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.proposals.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Back</a>
                    <a href="{{ route('admin.proposals.preview', $proposal) }}" target="_blank"
                       class="px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Preview</a>
                    <a href="{{ route('admin.proposals.pdf', $proposal) }}" target="_blank"
                       class="px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Download PDF</a>
                    <input type="hidden" name="action" x-ref="actionField" value="save">
                    @if($proposal->isPublished())
                        <button type="submit" @click="$refs.actionField.value = 'unpublish'"
                                class="px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Unpublish</button>
                    @endif
                    <button type="submit" @click="$refs.actionField.value = 'save'"
                            class="px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Save Draft</button>
                    <button type="submit" @click="$refs.actionField.value = 'publish'"
                            class="px-4 py-2 bg-brand border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        {{ $proposal->isPublished() ? 'Update Published' : 'Publish' }}
                    </button>
                </div>
            </form>

            {{-- Kirim ke Klien (form email dipisah dari form utama agar tidak nested) --}}
            @if($proposal->isPublished())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 space-y-3">
                        <h3 class="text-sm font-semibold text-gray-700">Kirim ke Klien</h3>
                        <div class="flex flex-wrap items-center gap-2">
                            @php
                                $waPhone = preg_replace('/[^0-9]/', '', $proposal->client->contact_phone ?? '');
                                if (str_starts_with($waPhone, '0')) { $waPhone = '62' . substr($waPhone, 1); }
                                $waText = rawurlencode('Halo, berikut proposal dari Digimaya untuk ' . ($proposal->client->business_name ?? '') . ': ' . route('public.proposal.show', $proposal->public_token));
                            @endphp
                            @if($waPhone)
                                <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank"
                                   class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 whitespace-nowrap">Kirim via WhatsApp</a>
                            @else
                                <span class="px-3 py-2 text-sm text-gray-400">WhatsApp: nomor klien belum diisi</span>
                            @endif

                            @if(!empty($proposal->client->contact_email))
                                <form method="POST" action="{{ route('admin.proposals.send-email', $proposal) }}"
                                      onsubmit="return confirm('Kirim link proposal ke {{ $proposal->client->contact_email }}?')">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-2 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 whitespace-nowrap">Kirim via Email</button>
                                </form>
                            @else
                                <span class="px-3 py-2 text-sm text-gray-400">Email: alamat klien belum diisi</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">Mengirim tautan publik proposal ke klien. Pastikan isi proposal sudah final dan ter-publish.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        function proposalBuilder(initialBlocks, snippetLibrary, pricingCounts, referenceData) {
            return {
                blocks: Array.isArray(initialBlocks) ? initialBlocks : [],
                snippets: Array.isArray(snippetLibrary) ? snippetLibrary : [],
                pricingCounts: pricingCounts || { all: 0, lower: 0, upper: 0 },
                referenceData: referenceData || { logo_wall: [], testimonials: [], case_studies: [], services: [] },

                blockLabel(type) {
                    const map = { custom: 'Custom', snippet: 'Snippet', reference: 'Reference', pricing: 'Pricing' };
                    return map[type] || type;
                },

                newUid() {
                    return 'b' + Date.now() + Math.random().toString(36).slice(2, 7);
                },

                addBlock(type) {
                    if (type === 'custom') {
                        this.blocks.push({ uid: this.newUid(), type: 'custom', title: '', body: '', image_url: '', caption: '' });
                    } else if (type === 'snippet') {
                        this.blocks.push({ uid: this.newUid(), type: 'snippet', sourceId: '', title: '', body: '', images: [] });
                    } else if (type === 'pricing') {
                        this.blocks.push({ uid: this.newUid(), type: 'pricing', heading: '', option: 'all' });
                    } else if (type === 'reference') {
                        this.blocks.push({ uid: this.newUid(), type: 'reference', heading: '', source: '', ids: [] });
                    }
                },

                referenceItems(source) {
                    return this.referenceData[source] || [];
                },

                toggleRef(block, id) {
                    const i = block.ids.indexOf(id);
                    if (i === -1) {
                        block.ids.push(id);
                    } else {
                        block.ids.splice(i, 1);
                    }
                },

                changeSource(block) {
                    if (block.ids.length > 0 && !confirm('Ganti sumber akan menghapus item yang sudah dipilih. Lanjut?')) {
                        return;
                    }
                    block.ids = [];
                },

                pricingCount(option) {
                    if (option === 'lower') return this.pricingCounts.lower;
                    if (option === 'upper') return this.pricingCounts.upper;
                    return this.pricingCounts.all;
                },

                applySnippet(block) {
                    const found = this.snippets.find(s => String(s.id) === String(block.sourceId));
                    if (found) {
                        block.title = found.title || '';
                        block.body = found.body || '';
                        // Copy-on-insert: salin array URL gambar dari snippet ke block
                        block.images = Array.isArray(found.images) ? [...found.images] : [];
                    }
                },

                async uploadImage(block, event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    block._uploading = true;
                    const data = new FormData();
                    data.append('image', file);
                    data.append('_token', document.querySelector('input[name=_token]').value);
                    try {
                        const res = await fetch('{{ route('admin.proposals.upload-image') }}', {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                            body: data,
                        });
                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            alert(err.message || 'Upload gagal. Pastikan file gambar dan ukuran di bawah 4MB.');
                        } else {
                            const json = await res.json();
                            block.image_url = json.url;
                        }
                    } catch (e) {
                        alert('Upload gagal. Coba lagi.');
                    } finally {
                        block._uploading = false;
                        event.target.value = '';
                    }
                },

                removeBlock(index) {
                    if (confirm('Remove this block?')) {
                        this.blocks.splice(index, 1);
                    }
                },

                moveUp(index) {
                    if (index === 0) return;
                    const b = this.blocks.splice(index, 1)[0];
                    this.blocks.splice(index - 1, 0, b);
                },

                moveDown(index) {
                    if (index === this.blocks.length - 1) return;
                    const b = this.blocks.splice(index, 1)[0];
                    this.blocks.splice(index + 1, 0, b);
                },

                syncBlocks() {
                    this.$refs.payload.value = JSON.stringify(this.blocks);
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
