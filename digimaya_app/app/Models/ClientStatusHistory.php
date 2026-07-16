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

    /**
     * Statuses that represent an active client leaving (churn).
     * NOTE: 'lost' is intentionally excluded — it's a prospect dead-end (conversion loss),
     * not a churn. See Client::TRANSITIONS.
     */
    public const CHURN_STATUSES = ['inactive', 'churned'];

    public function scopeBecameActive(Builder $query): Builder
    {
        return $query->where('status_to', 'active');
    }

    /**
     * A churn/loss event: an ACTIVE client transitioning to inactive or churned.
     * Requiring status_from='active' avoids double-counting an inactive→churned move
     * (the client was already counted as lost when it first went inactive).
     */
    public function scopeBecameInactive(Builder $query): Builder
    {
        return $query->where('status_from', 'active')
                     ->whereIn('status_to', self::CHURN_STATUSES);
    }

    /**
     * Exclude synthetic "Backfill ..." history rows seeded for pre-tracking clients.
     */
    public function scopeExcludingBackfill(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('notes')->orWhere('notes', 'NOT LIKE', 'Backfill%');
        });
    }
}
