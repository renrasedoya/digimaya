<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Certificate extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'certificate_number', 'type', 'member_id', 'recipient_name',
        'program_name', 'program_description', 'completion_date',
        'issued_date', 'issued_by', 'pdf_path', 'status',
        'revoked_at', 'revoked_reason', 'revoked_by',
    ];

    protected $casts = [
        'member_id' => 'integer',
        'issued_by' => 'integer',
        'revoked_by' => 'integer',
        'completion_date' => 'date',
        'issued_date' => 'date',
        'revoked_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'certificate_number', 'recipient_name', 'program_name',
                'completion_date', 'status',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('certificate');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function revoker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isRevoked(): bool
    {
        return $this->status === 'revoked';
    }

    public function pdfExists(): bool
    {
        return $this->pdf_path && \Storage::disk('public')->exists($this->pdf_path);
    }

    public function isAcademy(): bool
    {
        return $this->type === 'academy';
    }

    public function isExternal(): bool
    {
        return $this->type === 'external';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRevoked($query)
    {
        return $query->where('status', 'revoked');
    }
}