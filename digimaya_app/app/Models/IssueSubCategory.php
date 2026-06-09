<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_category_id',
        'name',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'issue_category_id' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    // ===== RELATIONSHIPS =====

    public function category(): BelongsTo
    {
        return $this->belongsTo(IssueCategory::class, 'issue_category_id');
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
