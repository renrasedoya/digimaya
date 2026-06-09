<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    use HasFactory;

    public const ZONE_LOWER = 'lower';
    public const ZONE_UPPER = 'upper';

    protected $fillable = [
        'budget',
        'agency_fee',
        'zone',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'budget' => 'integer',
        'agency_fee' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('budget');
    }

    // Display option for pricing block: 'all' | 'lower' | 'upper'
    public function scopeForDisplay($query, string $option)
    {
        if ($option === self::ZONE_LOWER) {
            return $query->where('zone', self::ZONE_LOWER);
        }
        if ($option === self::ZONE_UPPER) {
            return $query->where('zone', self::ZONE_UPPER);
        }
        return $query; // 'all'
    }
}
