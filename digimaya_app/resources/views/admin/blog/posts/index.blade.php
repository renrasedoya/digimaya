<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Blog Posts') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Marketing'],
                        ['label' => 'Posts']
                    ]" />
                </div>
            </div>
            <a href="{{ route('admin.blog-posts.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                + Add New Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Status tabs --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="px-6 py-3 border-b border-gray-200">
                    @php
                        $currentStatus = request('status', 'all');
                        $tabs = [
                            'all' => 'All (' . $statusCounts['all'] . ')',
                            'published' => 'Published (' . $statusCounts['published'] . ')',
                            'scheduled' => 'Scheduled (' . $statusCounts['scheduled'] . ')',
                            'draft' => 'Draft (' . $statusCounts['draft'] . ')',
                        ];
                    @endphp
                    <nav class="flex space-x-6 -mb-3">
                        @foreach($tabs as $key => $label)
                            <a href="{{ route('admin.blog-posts.index', $key === 'all' ? [] : ['status' => $key]) }}"
                               class="py-2 px-1 border-b-2 text-sm font-medium {{ $currentStatus === $key ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-4">
                    <form method="GET" action="{{ route('admin.blog-posts.index') }}" class="flex flex-wrap gap-2 items-end">
                        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                                   class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm w-64">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                            <select name="category" class="border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-sm">
                                <option value="">All categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center">
                            <label class="inline-flex items-center text-sm text-gray-600">
                                <input type="checkbox" name="mine" value="1" @checked(request('mine'))
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 me-2">
                                My posts only
                            </label>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">Filter</button>
                        <a href="{{ route('admin.blog-posts.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Reset</a>
                    </form>
                </div>
            </div>

            {{-- Posts table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if($posts->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-sm">
                                @if(request()->hasAny(['search', 'category', 'mine', 'status']))
                                    No posts match your filters.
                                @else
                                    No blog posts yet.
                                @endif
                            </p>
                            @unless(request()->hasAny(['search', 'category', 'mine', 'status']))
                                <a href="{{ route('admin.blog-posts.create') }}"
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Create your first post
                                </a>
                            @endunless
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($posts as $post)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('admin.blog-posts.show', $post) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                                {{ $post->title }}
                                            </a>
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $post->public_id }} / {{ $post->slug }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            @forelse($post->categories as $cat)
                                                <span class="inline-block px-2 py-0.5 text-xs bg-gray-100 text-gray-700 rounded mr-1">{{ $cat->name }}</span>
                                            @empty
                                                <span class="text-gray-400 text-xs">—</span>
                                            @endforelse
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $post->author?->name ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @php
                                                $statusColors = [
                                                    'draft' => 'bg-gray-100 text-gray-700',
                                                    'scheduled' => 'bg-yellow-100 text-yellow-800',
                                                    'published' => 'bg-green-100 text-green-800',
                                                ];
                                                $color = $statusColors[$post->effective_status] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <span class="inline-block px-2 py-0.5 text-xs rounded font-medium {{ $color }}">
                                                {{ $post->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            @if($post->published_at)
                                                {{ $post->published_at->format('d M Y H:i') }}
                                            @else
                                                <span class="text-gray-400">{{ $post->created_at->format('d M Y') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm space-x-2 whitespace-nowrap">
                                            <a href="{{ route('admin.blog-posts.show', $post) }}" class="text-gray-600 hover:text-gray-900">View</a>
                                            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @if(auth()->user()->isSuperAdmin())
                                                <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Delete post &quot;{{ $post->title }}&quot;? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($posts->hasPages())
                            <div class="mt-6">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
