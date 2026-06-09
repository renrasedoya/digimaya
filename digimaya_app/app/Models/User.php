<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Available roles.
     */
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MARKETING = 'marketing';
    public const ROLE_ACCOUNT_MANAGER = 'account_manager';
    public const ROLE_ADVERTISER = 'advertiser';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_MARKETING => 'Marketing',
        self::ROLE_ACCOUNT_MANAGER => 'Account Manager',
        self::ROLE_ADVERTISER => 'Advertiser',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'parent_am_id',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'parent_am_id' => 'integer',
    ];

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is admin (admin OR super_admin).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);
    }

    /**
     * Check if user is marketing role.
     */
    public function isMarketing(): bool
    {
        return $this->role === self::ROLE_MARKETING;
    }

    /**
     * Check if user is account manager.
     */
    public function isAccountManager(): bool
    {
        return $this->role === self::ROLE_ACCOUNT_MANAGER;
    }

    /**
     * Check if user is advertiser.
     */
    public function isAdvertiser(): bool
    {
        return $this->role === self::ROLE_ADVERTISER;
    }

    /**
     * Check if user has specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Get human-readable role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? 'Unknown';
    }

    // ===== RELATIONSHIPS =====

    /**
     * Advertiser -> Account Manager (parent).
     * Only set when role = advertiser.
     */
    public function parentAm()
    {
        return $this->belongsTo(User::class, 'parent_am_id');
    }

    /**
     * Account Manager -> Advertisers (children).
     */
    public function advertisers()
    {
        return $this->hasMany(User::class, 'parent_am_id')
            ->where('role', self::ROLE_ADVERTISER);
    }

    /**
     * Advertiser -> Projects assigned to this advertiser.
     */
    public function projectsAsAdvertiser()
    {
        return $this->hasMany(\App\Models\Project::class, 'advertiser_id');
    }

    /**
     * Account Manager -> Clients managed.
     */
    public function managedClients()
    {
        return $this->hasMany(Client::class, 'account_manager_id');
    }

    // ===== SCOPES =====

    /**
     * Scope: filter active users only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: filter by role.
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
