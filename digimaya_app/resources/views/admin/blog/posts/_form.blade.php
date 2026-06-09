{{-- Shared form partial for create + edit blog post pages --}}
{{-- Required vars: $post (BlogPost or new BlogPost), $categories (Collection of BlogCategory), $formAction (string URL), $formMethod (string 'POST' or 'PUT') --}}

@php
    $isEdit = $post->exists ?? false;
    $selectedCategoryIds = old('categories', $isEdit ? $post->categories->pluck('id')->toArray() : []);
    $publishedAtValue = old('published_at',
        $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : ''
    );
    $metaTitleValue = old('meta_title', $post->meta_title ?? '');
    $metaDescriptionValue = old('meta_description', $post->meta_description ?? '');
@endphp

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { min-height: 300px; font-size: 14px; }
    .ql-toolbar.ql-snow { border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem; }
    .ql-container.ql-snow { border-bottom-left-radius: 0.375rem; border-bottom-right-radius: 0.375rem; }
    .ql-toolbar svg { width: 18px; height: 18px; display: inline-block; }
    .ql-toolbar button { width: 28px; height: 24px; }
    .ql-editor p { margin-bottom: 0.75em; }
    .ql-editor ol, .ql-editor ul { margin-bottom: 0.75em; }
    .ql-editor li { margin-bottom: 0.25em; }
    .ql-editor h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin-top: 1.5em;
        margin-bottom: 1em;
        line-height: 1.3;
    }
    .ql-editor h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-top: 1.25em;
        margin-bottom: 0.875em;
        line-height: 1.4;
    }
</style>
@endpush

<div x-data="postForm({
        initialStatus: @js(old('status', $post->status ?? 'draft')),
        initialContent: @js(old('content', $post->content ?? '')),
    })">

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $formAction }}" method="POST" @submit="syncEditorToInput()">
        @csrf
        @if($formMethod === 'PUT')
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ============== MAIN COLUMN ============== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Title --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <input type="text"
                           name="title"
                           value="{{ old('title', $post->title ?? '') }}"
                           placeholder="Add title"
                           required
                           maxlength="200"
                           class="w-full text-2xl font-semibold border-0 border-b border-gray-200 focus:border-indigo-500 focus:ring-0 px-0 py-2 placeholder-gray-400">

                    @if($isEdit)
                        <div class="mt-2 text-xs text-gray-500">
                            Permalink: /blog/{{ $post->public_id }}/{{ $post->slug }}
                        </div>
                    @endif
                </div>

                {{-- Content (Quill) --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    {{-- Hidden textarea — receives Quill HTML on submit, validated server-side --}}
                    <textarea name="content" id="content-input" class="hidden" maxlength="65535"></textarea>
                    {{-- Quill editor mount point --}}
                    <div id="quill-editor"></div>
                    <p class="mt-1 text-xs text-gray-500">Use the toolbar for formatting (headings, bold, italic, link, list).</p>
                </div>

                {{-- SEO --}}
                <div class="bg-white shadow-sm sm:rounded-lg"
                     x-data="{
                        metaTitle: @js($metaTitleValue),
                        metaDescription: @js($metaDescriptionValue),
                     }">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700">SEO</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        {{-- Meta Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                            <input type="text"
                                   name="meta_title"
                                   x-model="metaTitle"
                                   maxlength="70"
                                   placeholder="Defaults to post title if empty"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                            <p class="mt-1 text-xs text-gray-500">
                                <span x-text="metaTitle.length"></span>/70 characters. Used for the page title and text thumbnail.
                            </p>
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                            <textarea name="meta_description"
                                      x-model="metaDescription"
                                      maxlength="160"
                                      rows="3"
                                      placeholder="Short summary shown in search results"
                                      class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm"></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                <span x-text="metaDescription.length"></span>/160 characters.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============== SIDEBAR ============== --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Publish box --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700">Publish</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        {{-- Status select --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status"
                                    x-model="status"
                                    class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                                <option value="draft">Draft</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="published">Published</option>
                            </select>
                        </div>

                        {{-- Published at — shown only for scheduled (required) --}}
                        <div x-show="status === 'scheduled'" x-cloak>
                            <label class="block text-sm font-medium text-gray-700">
                                Schedule for <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local"
                                   name="published_at"
                                   value="{{ $publishedAtValue }}"
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                            <p class="mt-1 text-xs text-gray-500" x-show="status === 'scheduled'">
                                Post will be visible from this date.
                            </p>
                        </div>

                        {{-- Submit button --}}
                        <div class="pt-2 border-t border-gray-200 flex items-center justify-between">
                            <a href="{{ route('admin.blog-posts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                                <span x-text="submitLabel"></span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Categories --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700">Categories</h3>
                    </div>
                    <div class="p-4 max-h-64 overflow-y-auto">
                        @if($categories->isEmpty())
                            <p class="text-sm text-gray-500">
                                No categories yet.
                                <a href="{{ route('admin.blog-categories.index') }}" class="text-indigo-600 hover:underline">Create one</a>.
                            </p>
                        @else
                            <div class="space-y-2">
                                @foreach($categories as $category)
                                    <label class="flex items-center text-sm">
                                        <input type="checkbox"
                                               name="categories[]"
                                               value="{{ $category->id }}"
                                               @checked(in_array($category->id, $selectedCategoryIds))
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 me-2">
                                        {{ $category->name }}
                                    </label>
                                @endforeach
                            </div>
                            <p class="mt-3 text-xs text-gray-500">Up to 10 categories.</p>
                        @endif
                    </div>
                </div>

                {{-- YouTube --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700">YouTube Video</h3>
                    </div>
                    <div class="p-4">
                        <input type="text"
                               name="youtube_input"
                               value="{{ old('youtube_input', $post->youtube_video_id ?? '') }}"
                               placeholder="Paste YouTube URL or ID"
                               maxlength="500"
                               class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 mt-1 block w-full text-sm">
                        <p class="mt-1 text-xs text-gray-500">
                            Accepts full YouTube URL (youtu.be/... or youtube.com/watch?v=...) or 11-char video ID.
                        </p>
                        @if($isEdit && $post->youtube_video_id)
                            <p class="mt-2 text-xs text-gray-700">
                                Current: <span class="">{{ $post->youtube_video_id }}</span>
                            </p>
                        @endif
                        <p class="mt-2 text-xs text-gray-500">
                            If a video is set, its cover is used as the post thumbnail. Otherwise a text thumbnail is generated automatically.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    function postForm(config) {
        return {
            status: config.initialStatus,
            quill: null,

            init() {
                // Initialize Quill on next tick (after DOM render)
                this.$nextTick(() => {
                    this.initQuill(config.initialContent);
                });
            },

            initQuill(initialContent) {
                const toolbarOptions = [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic'],
                    ['link'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['clean'],
                ];

                this.quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Write your post content here...',
                    modules: {
                        toolbar: toolbarOptions,
                    },
                });

                // Load initial content (HTML from server)
                if (initialContent) {
                    // Inject data-list attribute supaya Quill 2.x render OL/UL dengan benar
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(initialContent, 'text/html');
                    doc.querySelectorAll('ol > li').forEach(li => li.setAttribute('data-list', 'ordered'));
                    doc.querySelectorAll('ul > li').forEach(li => li.setAttribute('data-list', 'bullet'));
                    this.quill.root.innerHTML = doc.body.innerHTML;
                }
            },

            syncEditorToInput() {
                if (this.quill) {
                    const html = this.quill.root.innerHTML;
                    // Quill returns "<p><br></p>" when empty — treat as empty string
                    const cleaned = html === '<p><br></p>' ? '' : html;
                    document.getElementById('content-input').value = cleaned;
                }
            },

            get submitLabel() {
                return {
                    'draft': 'Save Draft',
                    'scheduled': 'Schedule',
                    'published': 'Publish',
                }[this.status] || 'Save';
            },
        };
    }
</script>
@endpush