<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    /**
     * Display paginated list of blog posts.
     */
    public function index(Request $request): View
    {
        $query = BlogPost::query()->with('author:id,name,role', 'categories:id,name');

        if ($status = $request->input('status')) {
            if (in_array($status, BlogPost::STATUSES, true)) {
                $query->byStatus($status);
            }
        }

        if ($categoryId = $request->input('category')) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('blog_categories.id', $categoryId);
            });
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($request->boolean('mine')) {
            $query->where('created_by', auth()->id());
        }

        $posts = $query
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $categories = BlogCategory::orderBy('name')->get(['id', 'name']);

        $statusCounts = [
            'all' => BlogPost::count(),
            'draft' => BlogPost::draft()->count(),
            'scheduled' => BlogPost::scheduled()->count(),
            'published' => BlogPost::published()->count(),
        ];

        return view('admin.blog.posts.index', compact('posts', 'categories', 'statusCounts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        $categories = BlogCategory::orderBy('name')->get(['id', 'name']);

        return view('admin.blog.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        $youtubeId = !empty($validated['youtube_input'])
            ? $this->extractYoutubeId($validated['youtube_input'])
            : null;

        $publishedAt = $this->resolvePublishedAt(
            $validated['status'],
            $validated['published_at'] ?? null
        );

        $post = BlogPost::create([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'youtube_video_id' => $youtubeId,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
            'created_by' => auth()->id(),
        ]);

        if (!empty($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        }

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified post (read-only).
     */
    public function show(BlogPost $blogPost): View
    {
        $blogPost->load('author:id,name,role', 'categories:id,name');

        return view('admin.blog.posts.show', ['post' => $blogPost]);
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(BlogPost $blogPost): View
    {
        $this->authorizeEdit($blogPost);

        $categories = BlogCategory::orderBy('name')->get(['id', 'name']);
        $blogPost->load('categories:id');

        return view('admin.blog.posts.edit', [
            'post' => $blogPost,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $this->authorizeEdit($blogPost);

        $validated = $request->validate($this->validationRules());

        $youtubeId = !empty($validated['youtube_input'])
            ? $this->extractYoutubeId($validated['youtube_input'])
            : null;

        $publishedAt = $this->resolvePublishedAt(
            $validated['status'],
            $validated['published_at'] ?? null
        );

        $blogPost->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'youtube_video_id' => $youtubeId,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
        ]);

        $blogPost->categories()->sync($validated['categories'] ?? []);

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Soft-delete the specified post.
     */
    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->categories()->detach();
        $blogPost->delete();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    // ==================== Private helpers ====================

    private function authorizeEdit(BlogPost $post): void
    {
        if (!$post->canEditBy(auth()->user())) {
            abort(403, 'You do not have permission to edit this post.');
        }
    }

    private function validationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'content' => ['nullable', 'string', 'max:65535'],
            'categories' => ['nullable', 'array', 'max:10'],
            'categories.*' => ['integer', 'exists:blog_categories,id'],
            'youtube_input' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'status' => ['required', Rule::in(BlogPost::STATUSES)],
            'published_at' => ['nullable', 'date', 'required_if:status,scheduled'],
        ];
    }

    private function extractYoutubeId(string $input): ?string
    {
        $input = trim($input);

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }

        $patterns = [
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    private function resolvePublishedAt(string $status, ?string $userInput): ?string
    {
        return match ($status) {
            BlogPost::STATUS_DRAFT => null,
            BlogPost::STATUS_SCHEDULED => $userInput,
            BlogPost::STATUS_PUBLISHED => $userInput ?: now()->toDateTimeString(),
            default => null,
        };
    }
}