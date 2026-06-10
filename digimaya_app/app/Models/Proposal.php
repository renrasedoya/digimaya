<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Proposal extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'client_id',
        'title',
        'public_token',
        'status',
        'content_blocks',
        'published_content',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'client_id' => 'integer',       // Bug 4 family: FK must cast integer
        'created_by' => 'integer',
        'content_blocks' => 'array',
        'published_content' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * content_blocks / published_content shape (for Fase 3 builder):
     * [
     *   { "uid": "b1", "type": "reference|snippet|custom|pricing", ...payload }
     * ]
     * - reference: { "source": "logo_wall|testimonials|case_studies", "ids": [...] }
     * - snippet:   { "title": "...", "body": "<html>" }   // copy-on-insert
     * - custom:    { "title": "...", "body": "<html>" }
     * - pricing:   { "option": "all|lower|upper", "heading": "..." }
     */

    protected static function booted(): void
    {
        static::creating(function (Proposal $proposal) {
            if (empty($proposal->public_token)) {
                $proposal->public_token = self::generateUniqueToken();
            }
        });
    }

    public static function generateUniqueToken(): string
    {
        do {
            $token = Str::random(20);
        } while (self::withTrashed()->where('public_token', $token)->exists()); // Bug 1: withTrashed

        return $token;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'published_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('proposal');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed(); // SoftDeletes Manifestation 2
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}
