<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const STATUSES = [
        'new'          => 'New',
        'contacted'    => 'Contacted',
        'screened'     => 'Screened',
        'promoted'     => 'Promoted',
        'disqualified' => 'Disqualified',
    ];

    public const SOURCES = [
        'contact_form' => 'Contact Form',
        'whatsapp'     => 'WhatsApp',
        'meta_ads'     => 'Meta Ads',
        'google_ads'   => 'Google Ads',
        'referral'     => 'Referral',
        'manual'       => 'Manual Input',
        'other'        => 'Other',
    ];

    public const BUDGETS = [
        '<5jt'    => 'Rp <5jt',
        '5-10jt'  => 'Rp 5-10jt',
        '10-25jt' => 'Rp 10-25jt',
        '25-50jt' => 'Rp 25-50jt',
        '>50jt'   => 'Rp >50jt',
    ];

    public const INTERESTED_IN_OPTIONS = [
        'agency'      => 'Agency',
        'academy'     => 'Academy',
        'partnership' => 'Partnership',
        'others'      => 'Other',
    ];

    protected $attributes = [
        'status' => 'new',
        'source' => 'contact_form',
    ];

    protected $fillable = [
        'contact_name',
        'contact_email',
        'contact_phone',
        'business_name',
        'website_url',
        'monthly_ad_budget',
        'message',
        'source',
        'interested_in',
        'interested_in_other',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'referrer_url',
        'status',
        'assigned_to',
        'promoted_at',
        'promoted_to_client_id',
        'disqualified_at',
        'disqualification_reason',
        'first_contacted_at',
        'last_contacted_at',
        'created_by',
    ];

    protected $casts = [
        'promoted_at'        => 'datetime',
        'disqualified_at'    => 'datetime',
        'first_contacted_at' => 'datetime',
        'last_contacted_at'  => 'datetime',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'contact_name',
                'contact_email',
                'contact_phone',
                'business_name',
                'monthly_ad_budget',
                'source',
                'interested_in',
                'interested_in_other',
                'status',
                'assigned_to',
                'promoted_to_client_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('lead');
    }

    // Relationships

    public function followups(): HasMany
    {
        return $this->hasMany(LeadFollowup::class)->orderBy('scheduled_at', 'desc');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function promotedClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'promoted_to_client_id');
    }

    // Scopes

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeContacted(Builder $query): Builder
    {
        return $query->where('status', 'contacted');
    }

    public function scopeScreened(Builder $query): Builder
    {
        return $query->where('status', 'screened');
    }

    public function scopePromoted(Builder $query): Builder
    {
        return $query->where('status', 'promoted');
    }

    public function scopeDisqualified(Builder $query): Builder
    {
        return $query->where('status', 'disqualified');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['new', 'contacted', 'screened']);
    }

    public function scopeUnassigned(Builder $query): Builder
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeFromSource(Builder $query, string $source): Builder
    {
        return $query->where('source', $source);
    }

    // Accessors

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getSourceLabelAttribute(): string
    {
        return self::SOURCES[$this->source] ?? $this->source;
    }

    public function getBudgetLabelAttribute(): ?string
    {
        if (! $this->monthly_ad_budget) {
            return null;
        }

        return self::BUDGETS[$this->monthly_ad_budget] ?? $this->monthly_ad_budget;
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
     * Check if lead has specific interest.
     */
    public function hasInterest(string $interest): bool
    {
        return $this->interested_in === $interest;
    }

    /**
     * Check if lead is ready for promotion to client.
     * Requires: interested_in filled, and if "others", interested_in_other must also be filled.
     */
    public function isReadyForPromotion(): bool
    {
        if ($this->interested_in === 'others') {
            return ! empty($this->interested_in_other);
        }

        return ! empty($this->interested_in);
    }

    /**
     * Check if lead can be promoted (status screened AND interest filled).
     */
    public function canPromote(): bool
    {
        return $this->status === 'screened' && $this->isReadyForPromotion();
    }

    public function getIsPromotedAttribute(): bool
    {
        return $this->status === 'promoted';
    }

    public function getIsDisqualifiedAttribute(): bool
    {
        return $this->status === 'disqualified';
    }

    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, ['new', 'contacted', 'screened']);
    }

    // ===== SCOPES =====
    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
    }
}