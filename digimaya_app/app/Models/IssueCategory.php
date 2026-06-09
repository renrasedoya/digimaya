<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    // ===== RELATIONSHIPS =====

    public function subCategories(): HasMany
    {
        return $this->hasMany(IssueSubCategory::class)->orderBy('display_order');
    }

    public function activeSubCategories(): HasMany
    {
        return $this->hasMany(IssueSubCategory::class)
            ->where('is_active', true)
            ->orderBy('display_order');
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
}
