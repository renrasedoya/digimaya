<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ClientStatusHistory extends Model
{
    use HasFactory;

    /**
     * Table name (non-default plural — singular intentional for "history" entity).
     */
    protected $table = 'client_status_history';

    protected $fillable = [
        'client_id',
        'status_from',
        'status_to',
        'changed_at',
        'notes',
        'changed_by',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Auto-set `changed_by` from authenticated user when available.
     * Skipped silently in non-auth context (artisan command, scheduler).
     */
    protected static function booted(): void
    {
        static::creating(function (ClientStatusHistory $history) {
            if (empty($history->changed_by) && Auth::check()) {
                $history->changed_by = Auth::id();
            }
            if (empty($history->changed_at)) {
                $history->changed_at = now();
            }
        });
    }

    // Relationships

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Scopes — useful for dashboard queries

    public function scopeBecameActive(Builder $query): Builder
    {
        return $query->where('status_to', 'active');
    }

    public function scopeBecameInactive(Builder $query): Builder
    {
        return $query->where('status_to', 'inactive');
    }
}
