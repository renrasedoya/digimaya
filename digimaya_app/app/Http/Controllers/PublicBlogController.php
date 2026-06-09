<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

/**
 * Public-facing blog controller.
 *
 * Routes:
 *  - GET /blog                              → index (listing with hero + grid + filters)
 *  - GET /blog/{public_id}/{slug}           → show (detail page)
 *
 * Public access (no auth). Only published posts visible.
 */
class PublicBlogController extends Controller
{
    /**
     * Posts per page in the grid section (after the hero).
     */
    private const PER_PAGE = 9;

    /**
     * Blog listing — hybrid layout (hero + grid) with category filter and search.
     *
     * Behavior:
     *  - No filter active: hero = latest published post, grid = posts 2..N paginated.
     *  - Filter active (category or search): hero hidden, all matches in grid.
     */
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $search       = trim((string) $request->query('q', ''));
        $hasFilter    = ! empty($categorySlug) || $search !== '';

        $query = BlogPost::query()
            ->published()
            ->with(['categories', 'author'])
            ->orderByDesc('published_at');

        // Filter by category slug (SEO-friendly).
        $activeCategory = null;
        if ($categorySlug) {
            $activeCategory = BlogCategory::where('slug', $categorySlug)->first();
            if ($activeCategory) {
                $query->whereHas('categories', function ($q) use ($activeCategory) {
                    $q->where('blog_categories.id', $activeCategory->id);
                });
            } else {
                // Unknown category slug → return empty result set rather than 404.
                $query->whereRaw('1 = 0');
            }
        }

        // Search: title + content (LIKE — content is small enough on a single-tenant blog).
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                  ->orWhere('content', 'like', $like);
            });
        }

        $featured = null;
        if (! $hasFilter) {
            // Hero = latest published post; grid excludes it.
            $featured = (clone $query)->first();
            if ($featured) {
                $query->where('id', '!=', $featured->id);
            }
        }

        $posts = $query->paginate(self::PER_PAGE)->withQueryString();

        $categories = BlogCategory::orderBy('name')->get();

        return view('public.blog.index', [
            'featured'       => $featured,
            'posts'          => $posts,
            'categories'     => $categories,
            'activeCategory' => $activeCategory,
            'search'         => $search,
            'hasFilter'      => $hasFilter,
        ]);
    }

    /**
     * Blog detail page.
     *
     * URL: /blog/{public_id}/{slug}
     *
     * - Validates public_id resolves to a published post.
     * - Redirects (301) to canonical slug if slug in URL is stale.
     * - Sanitizes content via Purifier 'blog' profile.
     * - Loads up to 3 related posts (same category fallback to latest).
     */
    public function show(string $publicId, string $slug)
    {
        $post = BlogPost::with(['categories', 'author'])
            ->where('public_id', $publicId)
            ->firstOrFail();

        // Block non-published posts from public view.
        if ($post->effective_status !== BlogPost::STATUS_PUBLISHED) {
            abort(404);
        }

        // Canonical slug enforcement (301 redirect if URL slug is stale).
        if ($post->slug !== $slug) {
            return redirect()->route('public.blog.show', [
                'public_id' => $post->public_id,
                'slug'      => $post->slug,
            ], 301);
        }

        // Sanitize content (defense in depth — Quill toolbar already restricts).
        $sanitizedContent = clean($post->content ?? '', 'blog');

        // Related posts: same category, exclude current, fallback to latest published.
        $related = $this->resolveRelatedPosts($post, 3);

        // SEO meta auto-fallback (no admin fields yet — see NOTES.md decision).
        $metaDescription = $this->buildMetaDescription($post);

        return view('public.blog.show', [
            'post'             => $post,
            'sanitizedContent' => $sanitizedContent,
            'related'          => $related,
            'metaDescription'  => $metaDescription,
        ]);
    }

    // ============== Private helpers ==============

    /**
     * Resolve related posts: same category first, fallback to latest published.
     */
    private function resolveRelatedPosts(BlogPost $post, int $limit)
    {
        $categoryIds = $post->categories->pluck('id')->all();

        $related = collect();

        if (! empty($categoryIds)) {
            $related = BlogPost::published()
                ->where('id', '!=', $post->id)
                ->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('blog_categories.id', $categoryIds);
                })
                ->with('categories')
                ->orderByDesc('published_at')
                ->limit($limit)
                ->get();
        }

        // Fill gap with latest published if same-category not enough.
        if ($related->count() < $limit) {
            $excludeIds = $related->pluck('id')->push($post->id)->all();
            $fillCount  = $limit - $related->count();

            $fallback = BlogPost::published()
                ->whereNotIn('id', $excludeIds)
                ->with('categories')
                ->orderByDesc('published_at')
                ->limit($fillCount)
                ->get();

            $related = $related->concat($fallback);
        }

        return $related;
    }

    /**
     * Meta description: prefer the admin-entered field, fall back to the
     * first ~160 chars of plain-text content.
     */
    private function buildMetaDescription(BlogPost $post): string
    {
        if (!empty($post->meta_description)) {
            return $post->meta_description;
        }

        $plain = trim(strip_tags($post->content ?? ''));
        $plain = preg_replace('/\s+/', ' ', $plain);

        if (mb_strlen($plain) <= 160) {
            return $plain;
        }

        return rtrim(mb_substr($plain, 0, 157)) . '...';
    }
}
