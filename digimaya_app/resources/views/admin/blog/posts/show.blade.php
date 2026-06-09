<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('View Post') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Marketing'],
                        ['label' => 'Posts', 'url' => route('admin.blog-posts.index')],
                        ['label' => $post->title]
                    ]" />
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if($post->canEditBy(auth()->user()))
                    <a href="{{ route('admin.blog-posts.edit', $post) }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Edit
                    </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="inline"
                          onsubmit="return confirm('Delete post &quot;{{ $post->title }}&quot;? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ============== MAIN COLUMN ============== --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Title + meta --}}
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
                        <div class="mt-2 text-xs text-gray-500">
                            Permalink: /blog/{{ $post->public_id }}/{{ $post->slug }}
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    @if($post->thumbnail)
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Featured Image</h3>
                            <img src="{{ $post->thumbnail_url }}"
                                 alt="{{ $post->title }}"
                                 class="max-h-96 rounded border border-gray-200">
                            <p class="mt-2 text-xs text-gray-500 break-all">
                                @if($post->thumbnailIsExternal())
                                    External: {{ $post->thumbnail }}
                                @else
                                    Storage: {{ $post->thumbnail }}
                                @endif
                            </p>
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Content</h3>
                        @if($post->content)
                            <x-prose-content class="max-w-none text-sm text-gray-800">{!! $post->content !!}</x-prose-content>
                        @else
                            <p class="text-sm text-gray-400 italic">No content.</p>
                        @endif
                    </div>

                    {{-- YouTube Video --}}
                    @if($post->youtube_video_id)
                        <div class="bg-white shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">YouTube Video</h3>
                            <div class="aspect-video bg-black rounded overflow-hidden">
                                <iframe class="w-full h-full"
                                        src="https://www.youtube.com/embed/{{ $post->youtube_video_id }}"
                                        title="YouTube video"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">ID: {{ $post->youtube_video_id }}</p>
                        </div>
                    @endif
                </div>

                {{-- ============== SIDEBAR ============== --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Status --}}
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700">Status</h3>
                        </div>
                        <div class="p-4 space-y-2 text-sm">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-700',
                                    'scheduled' => 'bg-yellow-100 text-yellow-800',
                                    'published' => 'bg-green-100 text-green-800',
                                ];
                                $color = $statusColors[$post->effective_status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <div>
                                <span class="inline-block px-2 py-0.5 text-xs rounded font-medium {{ $color }}">
                                    {{ $post->status_label }}
                                </span>
                            </div>

                            @if($post->published_at)
                                <div class="text-gray-600">
                                    <span class="text-xs text-gray-500">
                                        @if($post->status === 'scheduled')
                                            Scheduled for:
                                        @else
                                            Published at:
                                        @endif
                                    </span>
                                    <div class="mt-0.5">{{ $post->published_at->format('d M Y, H:i') }}</div>
                                </div>
                            @endif

                            <div class="text-gray-600">
                                <span class="text-xs text-gray-500">Created:</span>
                                <div class="mt-0.5">{{ $post->created_at->format('d M Y, H:i') }}</div>
                            </div>

                            @if($post->updated_at && $post->updated_at->ne($post->created_at))
                                <div class="text-gray-600">
                                    <span class="text-xs text-gray-500">Last updated:</span>
                                    <div class="mt-0.5">{{ $post->updated_at->format('d M Y, H:i') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Author --}}
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700">Author</h3>
                        </div>
                        <div class="p-4 text-sm">
                            @if($post->author)
                                <div class="text-gray-900 font-medium">{{ $post->author->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $post->author->role_label ?? $post->author->role }}</div>
                            @else
                                <span class="text-gray-400 italic">Author deleted</span>
                            @endif
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700">Categories</h3>
                        </div>
                        <div class="p-4 text-sm">
                            @if($post->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach($post->categories as $category)
                                        <span class="inline-block px-2 py-0.5 text-xs bg-gray-100 text-gray-700 rounded">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 italic">No categories.</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
