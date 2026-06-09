<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_COMPLETED = 'completed';

    public const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_PAUSED => 'Paused',
        self::STATUS_COMPLETED => 'Completed',
    ];

    protected $fillable = [
        'client_id',
        'advertiser_id',
        'name',
        'account_url',
        'status',
        'project_value',
        'started_at',
        'ended_at',
        'notes',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'advertiser_id' => 'integer',
        'project_value' => 'decimal:2',
        'started_at' => 'date',
        'ended_at' => 'date',
    ];

    // ===== ACTIVITY LOG =====

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'client_id',
                'advertiser_id',
                'name',
                'account_url',
                'status',
                'project_value',
                'started_at',
                'ended_at',
                'notes',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('project');
    }

    // ===== RELATIONSHIPS =====

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }

    public function reports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProjectReport::class)->orderBy('created_at', 'desc');
    }

    /**
     * Resolve account manager via client.
     * Project gak punya direct AM FK; AM-nya derive dari client.account_manager_id.
     */
    public function accountManager()
    {
        return $this->client?->accountManager;
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePaused($query)
    {
        return $query->where('status', self::STATUS_PAUSED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope: projects where the given user is the advertiser.
     */
    public function scopeForAdvertiser($query, int $advertiserId)
    {
        return $query->where('advertiser_id', $advertiserId);
    }

    /**
     * Scope: projects whose client is managed by the given AM user.
     */
    public function scopeForAccountManager($query, int $amId)
    {
        return $query->whereHas('client', function ($q) use ($amId) {
            $q->where('account_manager_id', $amId);
        });
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    // ===== HELPERS =====

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPaused(): bool
    {
        return $this->status === self::STATUS_PAUSED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
