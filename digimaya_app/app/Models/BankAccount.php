<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'label',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope: only active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: order by sort_order then id (stable tie-breaker).
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Display name combining bank_name and optional label.
     * E.g. "Bank Central Asia (Operational)" or "Bank Central Asia"
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->label
            ? "{$this->bank_name} ({$this->label})"
            : $this->bank_name;
    }

    /**
     * Masked account number for partial-display contexts.
     * E.g. "1234567890" -> "******7890"
     */
    public function getMaskedAccountNumberAttribute(): string
    {
        $num = (string) $this->account_number;
        if (strlen($num) <= 4) {
            return $num;
        }
        return str_repeat('*', strlen($num) - 4) . substr($num, -4);
    }
}
