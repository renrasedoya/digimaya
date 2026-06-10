<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalSnippet extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'body',
        'images',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}
