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

class LeadFollowup extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const METHODS = [
        'whatsapp' => 'WhatsApp',
        'call'     => 'Call',
        'email'    => 'Email',
        'meeting'  => 'Meeting',
        'other'    => 'Other',
    ];

    /**
     * Outcome list (Phase 11.3.3.5: reduced from 5 to 3 to align with refined model).
     * Outcome triggers Lead status update via LeadFollowupController::complete().
     *   - positive    → Lead status = 'screened' (ready to promote)
     *   - negative    → Lead status = 'disqualified'
     *   - no_response → Lead status unchanged (AM continues with new attempt)
     */
    public const OUTCOMES = [
        'positive'    => 'Positive',
        'negative'    => 'Negative',
        'no_response' => 'No Response',
    ];

    protected $fillable = [
        'lead_id',
        'scheduled_at',
        'completed_at',
        'next_followup_at',
        'method',
        'notes',
        'outcome',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at'     => 'datetime',
        'completed_at'     => 'datetime',
        'next_followup_at' => 'datetime',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'lead_id',
                'scheduled_at',
                'completed_at',
                'next_followup_at',
                'method',
                'outcome',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('lead_followup');
    }

    protected static function booted(): void
    {
        static::creating(function (LeadFollowup $followup) {
            if (empty($followup->created_by) && Auth::check()) {
                $followup->created_by = Auth::id();
            }
        });
    }

    // Relationships
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class)->withTrashed();
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
