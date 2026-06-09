<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Module extends Model
{
    use HasFactory;

    // ====================
    // Tier constants
    // ====================
    public const TIER_FREE = 'free';
    public const TIER_PAID = 'paid';
    public const TIERS = [self::TIER_FREE, self::TIER_PAID];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'display_order',
        'is_published',
        'tier',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_published' => 'boolean',
        'tier' => 'string',
    ];

    // ====================
    // Cover image helpers (match LogoWallItem pattern)
    // ====================

    public function coverImageIsExternal(): bool
    {
        return $this->cover_image && str_starts_with($this->cover_image, 'http');
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }
        return $this->coverImageIsExternal()
            ? $this->cover_image
            : asset('storage/' . $this->cover_image);
    }

    // ====================
    // Boot — auto slug
    // ====================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($module) {
            if (empty($module->slug) && !empty($module->title)) {
                $module->slug = static::uniqueSlug($module->title, $module->id);
            }
        });
    }

    public static function uniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }

    // ====================
    // Route binding by slug
    // ====================

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // ====================
    // Relations
    // ====================

    public function materials()
    {
        return $this->hasMany(Material::class)->orderBy('display_order');
    }

    public function publishedMaterials()
    {
        return $this->hasMany(Material::class)
            ->where('is_published', true)
            ->orderBy('display_order');
    }

    // ====================
    // Scopes
    // ====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('id');
    }

    public function scopeFree($query)
    {
        return $query->where('tier', self::TIER_FREE);
    }

    public function scopePaid($query)
    {
        return $query->where('tier', self::TIER_PAID);
    }

    // ====================
    // Tier helpers
    // ====================

    public function isPaid(): bool
    {
        return $this->tier === self::TIER_PAID;
    }

    public function isFree(): bool
    {
        return $this->tier === self::TIER_FREE;
    }
}
