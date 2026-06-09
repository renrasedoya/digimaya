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

class Income extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const SOURCE_CATEGORIES = [
        'agency' => 'Agency',
        'academy' => 'Academy',
        'other' => 'Other',
    ];

    public const PAYMENT_METHODS = [
        'bank_transfer' => 'Bank Transfer',
        'cash' => 'Cash',
        'qris' => 'QRIS',
        'credit_card' => 'Credit Card',
        'other' => 'Other',
    ];

    protected $fillable = [
        'client_id',
        'service_id',
        'created_by',
        'invoice_id',
        'source_category',
        'amount',
        'received_date',
        'payment_method',
        'reference_number',
        'description',
    ];

    protected $casts = [
        'received_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'description',
                'amount',
                'source_category',
                'payment_method',
                'received_date',
                'client_id',
                'service_id',
                'invoice_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('income');
    }

    protected static function booted(): void
    {
        static::creating(function (Income $income) {
            if (empty($income->created_by) && Auth::check()) {
                $income->created_by = Auth::id();
            }

            // Auto-fill source_category from service if not set
            if (empty($income->source_category) && $income->service_id) {
                $service = Service::find($income->service_id);
                if ($service) {
                    $income->source_category = $service->category;
                }
            }
        });
    }

    // Relationships

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // Scopes

    public function scopeAgency(Builder $query): Builder
    {
        return $query->where('source_category', 'agency');
    }

    public function scopeAcademy(Builder $query): Builder
    {
        return $query->where('source_category', 'academy');
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('received_date', now()->month)
            ->whereYear('received_date', now()->year);
    }

    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('received_date', $year)
            ->whereMonth('received_date', $month);
    }

    public function scopeBetweenDates(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('received_date', [$startDate, $endDate]);
    }

    // Accessors

    public function getSourceCategoryLabelAttribute(): string
    {
        return self::SOURCE_CATEGORIES[$this->source_category] ?? $this->source_category;
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'IDR ' . number_format((float) $this->amount, 0, '.', ',');
    }
}
