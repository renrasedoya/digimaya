<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicService extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon_image',
        'icon_url',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Get icon source — prioritize uploaded image, fallback to URL.
     */
    public function getIconSrcAttribute(): ?string
    {
        if ($this->icon_image) {
            return asset('storage/' . $this->icon_image);
        }

        return $this->icon_url ?: null;
    }
}
