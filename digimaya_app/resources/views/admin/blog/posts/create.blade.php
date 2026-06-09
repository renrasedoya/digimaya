<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('New Post') }}
                </h2>
                <div class="mt-2">
                    <x-breadcrumb :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Marketing'],
                        ['label' => 'Posts', 'url' => route('admin.blog-posts.index')],
                        ['label' => 'New']
                    ]" />
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $post = new \App\Models\BlogPost();
                $formAction = route('admin.blog-posts.store');
                $formMethod = 'POST';
            @endphp

            @include('admin.blog.posts._form', compact('post', 'categories', 'formAction', 'formMethod'))
        </div>
    </div>
</x-app-layout>
