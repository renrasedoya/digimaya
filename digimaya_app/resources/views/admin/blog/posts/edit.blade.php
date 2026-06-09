<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Post') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Marketing'],
                        ['label' => 'Posts', 'url' => route('admin.blog-posts.index')],
                        ['label' => 'Edit']
                    ]" />
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.blog-posts.show', $post) }}" class="text-sm text-gray-600 hover:text-gray-900">
                    View
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $formAction = route('admin.blog-posts.update', $post);
                $formMethod = 'PUT';
            @endphp

            @include('admin.blog.posts._form', compact('post', 'categories', 'formAction', 'formMethod'))
        </div>
    </div>
</x-app-layout>
