<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const STATUS_UNPAID = 'unpaid';
    public const STATUS_PAID   = 'paid';

    public const STATUSES = [
        self::STATUS_UNPAID => 'Unpaid',
        self::STATUS_PAID   => 'Paid',
    ];

    public const MODE_PROJECT = 'project';
    public const MODE_CLIENT  = 'client';
    public const MODE_CUSTOM  = 'custom';

    protected $fillable = [
        'invoice_number',
        'client_id',
        'project_id',
        'custom_client_name',
        'custom_client_address',
        'custom_client_contact',
        'period_start',
        'period_end',
        'issue_date',
        'due_date',
        'status',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'notes',
        'bank_account_id',
        'paid_date',
        'created_by',
    ];

    protected $casts = [
        'client_id'       => 'integer',
        'project_id'      => 'integer',
        'bank_account_id' => 'integer',
        'created_by'      => 'integer',
        'issue_date'      => 'date',
        'due_date'        => 'date',
        'paid_date'       => 'date',
        'period_start'    => 'date',
        'period_end'      => 'date',
        'subtotal'        => 'decimal:2',
        'tax_rate'        => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'total'           => 'decimal:2',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'invoice_number',
                'client_id',
                'project_id',
                'custom_client_name',
                'period_start',
                'period_end',
                'status',
                'total',
                'paid_date',
                'due_date',
                'issue_date',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('invoice');
    }

    // ===== Relationships =====

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function income(): HasOne
    {
        return $this->hasOne(Income::class);
    }

    // ===== Scopes =====

    public function scopeUnpaid($query)
    {
        return $query->where('status', self::STATUS_UNPAID);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeByStatus($query, ?string $status)
    {
        if (in_array($status, array_keys(self::STATUSES), true)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('issue_date', 'desc')->orderBy('id', 'desc');
    }

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('issue_date', $year)->whereMonth('issue_date', $month);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_UNPAID)->whereDate('due_date', '<', now()->toDateString());
    }

    // ===== Accessors =====

    /**
     * True when the invoice is locked from edits (status = paid).
     */
    public function getIsLockedAttribute(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Human-readable status label (e.g. "Paid", "Unpaid").
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    /**
     * Binding mode: project, client, or custom.
     */
    public function getModeAttribute(): string
    {
        if ($this->project_id) {
            return self::MODE_PROJECT;
        }
        if ($this->client_id) {
            return self::MODE_CLIENT;
        }
        return self::MODE_CUSTOM;
    }

    /**
     * Resolved client display name (handles all 3 modes).
     */
    public function getClientDisplayNameAttribute(): string
    {
        if ($this->client_id && $this->client) {
            return $this->client->business_name;
        }
        return $this->custom_client_name ?? '-';
    }

    /**
     * True if invoice has a billing period set.
     */
    public function getHasPeriodAttribute(): bool
    {
        return $this->period_start && $this->period_end;
    }
}
