<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientFollowup extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const METHODS = [
        'whatsapp' => 'WhatsApp',
        'call' => 'Call',
        'email' => 'Email',
        'meeting' => 'Meeting',
        'other' => 'Other',
    ];

    /**
     * Outcome list (Phase 12.2: aligned with LeadFollowup, 3 values).
     *
     * Semantic difference from Lead:
     *   - positive    → Sales interaction succeeded, progress to next stage
     *   - negative    → Deal lost or prospect explicitly declined
     *   - no_response → Couldn't reach prospect, reschedule needed
     *
     * Phase 12.2 scope: outcome recorded for tracking only.
     * Client.stage is NOT auto-updated — Sales updates stage manually via Edit Client.
     * State machine for auto-stage progression deferred to Phase 12.4.
     */
    public const OUTCOMES = [
        'positive' => 'Positive',
        'negative' => 'Negative',
        'no_response' => 'No Response',
    ];

    protected $fillable = [
        'client_id',
        'scheduled_at',
        'completed_at',
        'next_followup_at',
        'method',
        'notes',
        'outcome',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'next_followup_at' => 'datetime',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'client_id',
                'scheduled_at',
                'completed_at',
                'next_followup_at',
                'method',
                'outcome',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('followup');
    }

    protected static function booted(): void
    {
        static::creating(function (ClientFollowup $followup) {
            if (empty($followup->created_by) && Auth::check()) {
                $followup->created_by = Auth::id();
            }
        });
    }

    // Relationships

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('completed_at');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('scheduled_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNull('completed_at')
            ->where('scheduled_at', '<', now());
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereNull('completed_at')
            ->where('scheduled_at', '>=', now());
    }

    // Accessors

    public function getMethodLabelAttribute(): string
    {
        return self::METHODS[$this->method] ?? $this->method;
    }

    public function getOutcomeLabelAttribute(): ?string
    {
        if (! $this->outcome) {
            return null;
        }

        return self::OUTCOMES[$this->outcome] ?? $this->outcome;
    }

    public function getIsCompletedAttribute(): bool
    {
        return ! is_null($this->completed_at);
    }
}