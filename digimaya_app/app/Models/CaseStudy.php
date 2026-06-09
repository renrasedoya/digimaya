<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CaseStudy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'case_studies';

    protected $fillable = [
        'public_id',
        'client_name',
        'title',
        'client_id',
        'industry',
        'thumbnail',
        'problem',
        'solution',
        'is_active',
        'position_order',
        'created_by',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'position_order' => 'integer',
    ];

    // ============== Boot ==============

    protected static function booted(): void
    {
        static::creating(function (CaseStudy $model) {
            if (empty($model->public_id)) {
                $model->public_id = self::generateUniquePublicId();
            }
        });
    }

    protected static function generateUniquePublicId(): string
    {
        $maxAttempts = 10;
        for ($i = 0; $i < $maxAttempts; $i++) {
            $candidate = Str::lower(Str::random(3));
            if (! self::where('public_id', $candidate)->exists()) {
                return $candidate;
            }
        }
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

    public function getThumbnailUrlAttribute(): ?string
    {
        if (empty($this->thumbnail)) {
            return null;
        }
        if ($this->thumbnailIsExternal()) {
            return $this->thumbnail;
        }
        return asset('storage/' . $this->thumbnail);
    }

    // ============== Helpers ==============

    public function thumbnailIsExternal(): bool
    {
        return $this->thumbnail
            && Str::startsWith($this->thumbnail, ['http://', 'https://']);
    }

    // ============== Relationships ==============

    public function results()
    {
        return $this->hasMany(CaseStudyResult::class)->orderBy('position_order')->orderBy('id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
