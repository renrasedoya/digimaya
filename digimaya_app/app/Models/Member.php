<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // ====================
    // Tier constants
    // ====================
    public const TIER_FREE = 'free';
    public const TIER_PAID = 'paid';
    public const TIERS = [self::TIER_FREE, self::TIER_PAID];

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'enrolled_by',
        'notes',
        'setup_token',
        'setup_token_expires_at',
        'last_login_at',
        'tier',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'setup_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'enrolled_by' => 'integer',
        'last_login_at' => 'datetime',
        'setup_token_expires_at' => 'datetime',
        'password' => 'hashed',
        'tier' => 'string',
    ];

    // ====================
    // Relations
    // ====================

    public function enroller()
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    public function progress()
    {
        return $this->hasMany(MemberProgress::class);
    }

    public function completedMaterials()
    {
        return $this->belongsToMany(Material::class, 'member_progress')
            ->withTimestamps();
    }

    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }

    public function certificateRequests()
    {
        return $this->hasMany(\App\Models\CertificateRequest::class);
    }


    // ====================
    // Helpers
    // ====================

    public function hasCompletedMaterial(int $materialId): bool
    {
        return $this->progress()->where('material_id', $materialId)->exists();
    }

    public function isSetupTokenValid(): bool
    {
        return $this->setup_token
            && $this->setup_token_expires_at
            && $this->setup_token_expires_at->isFuture();
    }

    public function generateSetupToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->setup_token = $token;
        $this->setup_token_expires_at = now()->addHours(24);
        $this->save();
        return $token;
    }

    public function clearSetupToken(): void
    {
        $this->setup_token = null;
        $this->setup_token_expires_at = null;
        $this->save();
    }

    // ====================
    // Tier helpers
    // ====================

    public function isPaid(): bool
    {
        return $this->tier === self::TIER_PAID;
    }

    public function isFree(): bool
    {
        return $this->tier === self::TIER_FREE;
    }

    /**
     * Gate: can this member access the given module?
     * - Free module: open to all
     * - Paid module: only paid members
     */
    public function canAccessModule(Module $module): bool
    {
        return $module->isFree() || $this->isPaid();
    }

    // ====================
    // Scopes
    // ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFree($query)
    {
        return $query->where('tier', self::TIER_FREE);
    }

    public function scopePaid($query)
    {
        return $query->where('tier', self::TIER_PAID);
    }
}
