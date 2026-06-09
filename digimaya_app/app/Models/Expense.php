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

class Expense extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const PAYMENT_METHODS = [
        'bank_transfer' => 'Bank Transfer',
        'cash' => 'Cash',
        'qris' => 'QRIS',
        'credit_card' => 'Credit Card',
        'other' => 'Other',
    ];

    public const RECURRING_TYPES = [
        'one_time' => 'One Time',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_SKIPPED = 'skipped';

    public const STATUSES = [
        'draft' => 'Draft',
        'confirmed' => 'Confirmed',
        'skipped' => 'Skipped',
    ];

    protected $fillable = [
        'expense_category_id',
        'created_by',
        'amount',
        'expense_date',
        'vendor_name',
        'payment_method',
        'recurring_type',
        'status',
        'recurring_parent_id',
        'recurring_until',
        'reference_number',
        'description',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'recurring_until' => 'date',
        'amount' => 'decimal:2',
        'recurring_parent_id' => 'integer',
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
                'expense_category_id',
                'payment_method',
                'expense_date',
                'vendor_name',
                'recurring_type',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('expense');
    }

    protected static function booted(): void
    {
        static::creating(function (Expense $expense) {
            if (empty($expense->created_by) && Auth::check()) {
                $expense->created_by = Auth::id();
            }
        });
    }

    // Relationships

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year);
    }

    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month);
    }

    public function scopeBetweenDates(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    public function scopeRecurring(Builder $query): Builder
    {
        return $query->whereIn('recurring_type', ['monthly', 'yearly']);
    }

    public function scopeOneTime(Builder $query): Builder
    {
        return $query->where('recurring_type', 'one_time');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function recurringParent(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'recurring_parent_id');
    }

    public function recurringChildren()
    {
        return $this->hasMany(Expense::class, 'recurring_parent_id');
    }

    // Accessors

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public function getRecurringTypeLabelAttribute(): string
    {
        return self::RECURRING_TYPES[$this->recurring_type] ?? $this->recurring_type;
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'IDR ' . number_format((float) $this->amount, 0, '.', ',');
    }
}
