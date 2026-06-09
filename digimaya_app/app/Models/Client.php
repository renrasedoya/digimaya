<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const INDUSTRIES = [
        'Education',
        'F&B',
        'Healthcare',
        'Beauty & Aesthetic',
        'Property',
        'Automotive',
        'Fashion & Apparel',
        'E-commerce',
        'Professional Services',
        'Financial Services',
        'Travel & Hospitality',
        'Manufacturing',
        'Technology / SaaS',
        'Retail',
        'Other',
    ];

    public const SOURCES = [
        'Referral',
        'Google Search',
        'Instagram',
        'TikTok',
        'Facebook / Meta Ads',
        'LinkedIn',
        'Existing Client Upsell',
        'Partner / Affiliate',
        'Event / Workshop',
        'YouTube',
        'Direct',
        'Other',
    ];

    public const STATUSES = [
        'prospect' => 'Prospect',
        'active'   => 'Active',
        'inactive' => 'Inactive',
        'churned'  => 'Churned',
        'lost'     => 'Lost',
    ];

    /**
     * Allowed status transitions: keyed by source status, value = array of allowed targets.
     * NOTE: prospect dead-end → use 'lost' (not 'inactive'/'churned'); keeps conversion-rate
     * vs churn-rate metrics clean. 'lost' can re-engage back to 'prospect'.
     */
    public const STATUS_TRANSITIONS = [
        'prospect' => ['active', 'lost'],
        'active'   => ['inactive', 'churned'],
        'inactive' => ['active', 'churned'],
        'churned'  => ['active'],
        'lost'     => ['prospect'],
    ];

    /**
     * Check if status transition is allowed.
     * $from === null means initial create (no churned on create).
     */
    public static function canTransitionTo(?string $from, string $to): bool
    {
        if ($from === $to) {
            return true;
        }
        if ($from === null) {
            // Create is admin-only: only prospect or active.
            // ('lost'/'inactive'/'churned' are never initial states.)
            return in_array($to, ['prospect', 'active'], true);
        }
        return in_array($to, self::STATUS_TRANSITIONS[$from] ?? [], true);
    }

    /**
     * Get allowed target statuses (includes self for no-change).
     * Used by UI to filter dropdown options.
     */
    public static function getAllowedTargetsFrom(?string $from): array
    {
        if ($from === null) {
            return ['prospect', 'active'];
        }
        return array_values(array_unique(array_merge([$from], self::STATUS_TRANSITIONS[$from] ?? [])));
    }

    public const LEAD_QUALITIES = [
        'poor' => 'Poor',
        'average' => 'Average',
        'good' => 'Good',
    ];

    public const INTERESTED_IN_OPTIONS = [
        'agency'      => 'Agency',
        'academy'     => 'Academy',
        'partnership' => 'Partnership',
        'others'      => 'Other',
    ];

    protected $fillable = [
        'slug',
        'business_name',
        'website_url',
        'industry',
        'status',
        'account_manager_id',
        'client_since',
        'client_until',
        'contact_name',
        'contact_email',
        'contact_phone',
        'address',
        'monthly_retainer',
        'acquisition_cost',
        'source',
        'interested_in',
        'interested_in_other',
        'lead_quality',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'client_since' => 'date',
        'client_until' => 'date',
        'monthly_retainer' => 'decimal:2',
        'acquisition_cost' => 'decimal:2',
        'account_manager_id' => 'integer',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'business_name',
                'status',
                'account_manager_id',
                'lead_quality',
                'industry',
                'source',
                'interested_in',
                'interested_in_other',
                'contact_name',
                'contact_email',
                'contact_phone',
                'address',
                'monthly_retainer',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('client');
    }

    /**
     * Auto-generate slug from business_name on create.
     * Formula: slugify(business_name) + '-' + id
     */
    protected static function booted(): void
    {
        static::creating(function (Client $client) {
            if (empty($client->slug)) {
                $client->slug = 'tmp-' . uniqid();
            }
        });

        static::created(function (Client $client) {
            if (str_starts_with($client->slug, 'tmp-')) {
                $client->slug = Str::slug($client->business_name) . '-' . $client->id;
                $client->saveQuietly();
            }
        });
    }

    // ===== RELATIONSHIPS =====

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accountManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function followups(): HasMany
    {
        return $this->hasMany(ClientFollowup::class)->orderBy('scheduled_at', 'desc');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(ClientStatusHistory::class)->orderBy('changed_at', 'desc');
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ===== ACCESSORS =====
    public function getLeadQualityLabelAttribute(): ?string
    {
        if (! $this->lead_quality) {
            return null;
        }

        return self::LEAD_QUALITIES[$this->lead_quality] ?? $this->lead_quality;
    }

    /**
     * Get human-readable interest label.
     * Returns the custom "others" text if applicable, otherwise standard label.
     */
    public function getInterestedInLabelAttribute(): ?string
    {
        if (! $this->interested_in) {
            return null;
        }

        if ($this->interested_in === 'others' && $this->interested_in_other) {
            return $this->interested_in_other;
        }

        return self::INTERESTED_IN_OPTIONS[$this->interested_in] ?? $this->interested_in;
    }

    /**
     * Check if client has specific interest.
     */
    public function hasInterest(string $interest): bool
    {
        return $this->interested_in === $interest;
    }

    // ===== SCOPES =====

}