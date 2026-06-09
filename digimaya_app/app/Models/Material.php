<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'youtube_id',
        'notes',
        'display_order',
        'is_published',
    ];

    protected $casts = [
        'module_id' => 'integer',
        'display_order' => 'integer',
        'is_published' => 'boolean',
    ];

    // ====================
    // Relations
    // ====================

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function progress()
    {
        return $this->hasMany(MemberProgress::class);
    }

    // ====================
    // Scopes
    // ====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // ====================
    // Helpers (URL builders)
    // ====================

    public function getEmbedUrlAttribute(): string
    {
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }

    public function getThumbnailUrlAttribute(): string
    {
        return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
    }
}
