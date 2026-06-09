<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogoWallItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'name',
        'image',
        'group',
        'is_active',
        'position_order',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($item) {
            if (empty($item->public_id)) {
                $item->public_id = static::generateUniquePublicId();
            }
        });
    }

    protected static function generateUniquePublicId(): string
    {
        do {
            $id = strtoupper(Str::random(3));
        } while (static::where('public_id', $id)->exists());

        return $id;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position_order')->orderBy('id');
    }

    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    // Helpers
    public function imageIsExternal(): bool
    {
        return $this->image && Str::startsWith($this->image, ['http://', 'https://']);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if ($this->imageIsExternal()) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
