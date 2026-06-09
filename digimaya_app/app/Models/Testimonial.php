<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'name',
        'position',
        'company',
        'client_id',
        'photo',
        'quote',
        'rating',
        'is_active',
        'position_order',
        'created_by',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'rating'         => 'integer',
        'position_order' => 'integer',
    ];

    // ============== Boot ==============

    protected static function booted(): void
    {
        static::creating(function (Testimonial $model) {
            if (empty($model->public_id)) {
                $model->public_id = self::generateUniquePublicId();
            }
        });
    }

    /**
     * Generate 3-character alphanumeric public_id, retrying on collision.
     */
    protected static function generateUniquePublicId(): string
    {
        $maxAttempts = 10;
        for ($i = 0; $i < $maxAttempts; $i++) {
            $candidate = Str::lower(Str::random(3));
            if (! self::where('public_id', $candidate)->exists()) {
                return $candidate;
            }
        }
        // Fallback to longer ID if we somehow collide 10 times
        return Str::lower(Str::random(6));
    }

    // ============== Scopes ==============

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position_order')->orderBy('id');
    }

    // ============== Accessors ==============

    /**
     * Resolved photo URL — handles both internal storage paths and external URLs.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (empty($this->photo)) {
            return null;
        }
        if ($this->photoIsExternal()) {
            return $this->photo;
        }
        return asset('storage/' . $this->photo);
    }

    // ============== Helpers ==============

    public function photoIsExternal(): bool
    {
        return $this->photo
            && Str::startsWith($this->photo, ['http://', 'https://']);
    }

    // ============== Relationships ==============

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}