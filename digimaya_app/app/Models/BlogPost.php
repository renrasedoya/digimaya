<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_PUBLISHED = 'published';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SCHEDULED,
        self::STATUS_PUBLISHED,
    ];

    protected $fillable = [
        'public_id',
        'title',
        'slug',
        'content',
        'youtube_video_id',
        'meta_title',
        'meta_description',
        'status',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'published_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('blog_post');
    }

    /**
     * Auto-generate public_id (3-char alphanumeric) on creating.
     * Auto-generate slug from title on saving.
     */
    protected static function booted(): void
    {
        static::creating(function (BlogPost $post) {
            if (empty($post->public_id)) {
                $post->public_id = static::generateUniquePublicId();
            }
        });

        static::saving(function (BlogPost $post) {
            if (empty($post->slug) && !empty($post->title)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Generate unique 3-char alphanumeric lowercase public_id.
     * Retries on collision (very rare for ~46k space).
     */
    public static function generateUniquePublicId(int $maxAttempts = 10): string
    {
        for ($i = 0; $i < $maxAttempts; $i++) {
            $id = Str::lower(Str::random(3));
            if (preg_match('/^[a-z0-9]{3}$/', $id) && !static::where('public_id', $id)->exists()) {
                return $id;
            }
        }

        throw new \RuntimeException('Failed to generate unique public_id after ' . $maxAttempts . ' attempts.');
    }

    // ============== Scopes ==============

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeByStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    // ============== Accessors ==============

    /**
     * Effective status — promotes scheduled posts to published once their time has passed.
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->status === self::STATUS_SCHEDULED
            && $this->published_at
            && $this->published_at->lessThanOrEqualTo(now())) {
            return self::STATUS_PUBLISHED;
        }

        return $this->status;
    }

    /**
     * Human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->effective_status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_PUBLISHED => 'Published',
            default => ucfirst($this->status),
        };
    }

    /**
     * Permalink (for future public page).
     * Format: /blog/{public_id}/{slug}
     */
    public function getPermalinkAttribute(): string
    {
        return route('public.blog.show', ['public_id' => $this->public_id, 'slug' => $this->slug]);
    }

    /**
     * Thumbnail type — drives how the public/admin UI renders the card.
     * 'video' when a YouTube video is attached, otherwise 'text' (generated text-card).
     */
    public function getThumbnailTypeAttribute(): string
    {
        return !empty($this->youtube_video_id) ? 'video' : 'text';
    }

    /**
     * Resolved thumbnail image URL.
     * Returns a hotlinked YouTube thumbnail when a video is attached (zero storage),
     * or null when the post should fall back to the generated text-card.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!empty($this->youtube_video_id)) {
            return 'https://img.youtube.com/vi/' . $this->youtube_video_id . '/maxresdefault.jpg';
        }

        return null;
    }

    /**
     * Lower-res YouTube thumbnail, used as JS onerror fallback (always exists).
     */
    public function getThumbnailFallbackUrlAttribute(): ?string
    {
        if (!empty($this->youtube_video_id)) {
            return 'https://img.youtube.com/vi/' . $this->youtube_video_id . '/hqdefault.jpg';
        }

        return null;
    }

    /**
     * Text shown on the generated text-card thumbnail (post without video).
     * Prefers meta_title for SEO consistency, falls back to title.
     */
    public function getThumbnailTextAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    /**
     * Deterministic on-brand background color for the text-card thumbnail.
     * Same title always yields the same color (stable across reloads/pages).
     */
    public function getThumbnailColorAttribute(): string
    {
        $palette = [
            '#165DFF', // brand blue
            '#1E40AF', // indigo
            '#0F766E', // teal
            '#334155', // slate
            '#7C3AED', // violet
            '#0E7490', // cyan
        ];

        $index = crc32((string) $this->title) % count($palette);

        return $palette[$index];
    }

    // ============== Permissions ==============

    /**
     * Check if a given user can edit this post.
     * Rules:
     *  - super_admin: can edit any post
     *  - admin/marketing: can only edit posts they created
     *  - others / null: cannot edit
     */
    public function canEditBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin() || $user->isMarketing()) {
            return $this->created_by === $user->id;
        }

        return false;
    }

    // ============== Relationships ==============

    public function categories()
    {
        return $this->belongsToMany(
            BlogCategory::class,
            'blog_post_category',
            'blog_post_id',
            'blog_category_id'
        );
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
