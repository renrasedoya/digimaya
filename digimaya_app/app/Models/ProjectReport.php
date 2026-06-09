<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectReport extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    public const HEALTH_HEALTHY = 'healthy';
    public const HEALTH_NEEDS_ATTENTION = 'needs_attention';
    public const HEALTH_CRITICAL = 'critical';

    public const HEALTHS = [
        self::HEALTH_HEALTHY => 'Healthy',
        self::HEALTH_NEEDS_ATTENTION => 'Needs Attention',
        self::HEALTH_CRITICAL => 'Critical',
    ];

    public const STATUS_OPEN = 'open';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';

    public const STATUSES = [
        self::STATUS_OPEN => 'Open',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_RESOLVED => 'Resolved',
    ];

    protected $fillable = [
        'project_id',
        'submitted_by',
        'period_start',
        'period_end',
        'summary',
        'health',
        'issue_category_id',
        'issue_sub_category_id',
        'status',
        'reviewed_by',
        'reviewed_at',
        'acknowledged_at',
        'am_feedback',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'submitted_by' => 'integer',
        'issue_category_id' => 'integer',
        'issue_sub_category_id' => 'integer',
        'reviewed_by' => 'integer',
        'period_start' => 'date',
        'period_end' => 'date',
        'reviewed_at' => 'datetime',
        'acknowledged_at' => 'datetime',
    ];

    // ===== ACTIVITY LOG =====

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'project_id',
                'submitted_by',
                'period_start',
                'period_end',
                'summary',
                'health',
                'issue_category_id',
                'issue_sub_category_id',
                'status',
                'reviewed_by',
                'reviewed_at',
                'acknowledged_at',
                'am_feedback',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('project_report');
    }

    // ===== RELATIONSHIPS =====

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function issueCategory(): BelongsTo
    {
        return $this->belongsTo(IssueCategory::class);
    }

    public function issueSubCategory(): BelongsTo
    {
        return $this->belongsTo(IssueSubCategory::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ===== SCOPES =====

    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeForAdvertiser($query, int $advertiserId)
    {
        return $query->where('submitted_by', $advertiserId);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByHealth($query, string $health)
    {
        return $query->where('health', $health);
    }

    public function scopeUnreviewed($query)
    {
        return $query->whereNull('reviewed_at');
    }

    public function scopeReviewed($query)
    {
        return $query->whereNotNull('reviewed_at');
    }

    public function scopeAcknowledged($query)
    {
        return $query->whereNotNull('acknowledged_at');
    }

    public function scopePendingAcknowledgment($query)
    {
        return $query->whereNotNull('reviewed_at')->whereNull('acknowledged_at');
    }

    // ===== HELPERS =====

    public function isReviewed(): bool
    {
        return $this->reviewed_at !== null;
    }

    public function isAcknowledged(): bool
    {
        return $this->acknowledged_at !== null;
    }

    public function isPendingAcknowledgment(): bool
    {
        return $this->reviewed_at !== null && $this->acknowledged_at === null;
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function isHealthy(): bool
    {
        return $this->health === self::HEALTH_HEALTHY;
    }

    /**
     * Advertiser can edit own report only if not yet resolved.
     * AM/admin can always edit feedback (handled separately).
     */
    public function canBeEditedBy(User $user): bool
    {
        // Resolved reports are locked from advertiser edits
        if ($this->isResolved()) {
            return in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true);
        }

        // Super admin / admin can always edit
        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            return true;
        }

        // Advertiser can edit own non-resolved report
        if ($user->isAdvertiser() && $this->submitted_by === $user->id) {
            return true;
        }

        // AM can edit reports of their managed projects (mainly for feedback)
        if ($user->isAccountManager()) {
            $projectAmId = $this->project->client->account_manager_id ?? null;
            return $projectAmId === $user->id;
        }

        return false;
    }

    public function getHealthLabelAttribute(): string
    {
        return self::HEALTHS[$this->health] ?? ucfirst($this->health);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getPeriodLabelAttribute(): string
    {
        if ($this->period_start->equalTo($this->period_end)) {
            return $this->period_start->format('d M Y');
        }
        return $this->period_start->format('d M') . ' - ' . $this->period_end->format('d M Y');
    }
}
