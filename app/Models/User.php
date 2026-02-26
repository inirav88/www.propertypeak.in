<?php

namespace App\Models;

use App\Modules\RealEstate\Models\Agent;
use App\Modules\RealEstate\Models\Developer;
use App\Modules\RealEstate\Models\Property;
use Botble\ACL\Models\User as BaseUser;
use Illuminate\Support\Str;

class User extends BaseUser
{
    /**
     * Role constants.
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_DEVELOPER = 'developer';
    const ROLE_AGENT = 'agent';

    /**
     * Status constants.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SUSPENDED = 'suspended';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'phone',
        'first_name',
        'last_name',
        'password',
        'avatar_id',
        'permissions',
        'last_login',
        'role',
        'status',
        'approved_at',
        'approved_by',
        'slug',
        'activated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($user): void {
            if (empty($user->slug)) {
                $baseSlug = Str::slug($user->name);
                $user->slug = $baseSlug;
            }
            if (empty($user->role)) {
                $user->role = self::ROLE_DEVELOPER;
            }
            if (empty($user->status)) {
                $user->status = self::STATUS_PENDING;
            }
        });
    }

    /**
     * Get the developer profile for this user.
     */
    public function developer()
    {
        return $this->hasOne(Developer::class);
    }

    /**
     * Get the agent profile for this user.
     */
    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    /**
     * Get the properties added by this user.
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get the admin who approved this user.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Users this admin has approved.
     */
    public function approvedUsers()
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->isSuperUser();
    }

    /**
     * Check if user is a developer.
     */
    public function isDeveloper(): bool
    {
        return $this->role === self::ROLE_DEVELOPER;
    }

    /**
     * Check if user is an agent.
     */
    public function isAgent(): bool
    {
        return $this->role === self::ROLE_AGENT;
    }

    /**
     * Check if user account is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if user account is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if user account is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if user account is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    /**
     * Approve the user account.
     */
    public function approve(int $approvedBy): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => $approvedBy,
        ]);
    }

    /**
     * Reject the user account.
     */
    public function reject(int $rejectedBy): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_at' => now(),
            'approved_by' => $rejectedBy,
        ]);
    }

    /**
     * Suspend the user account.
     */
    public function suspend(): void
    {
        $this->update([
            'status' => self::STATUS_SUSPENDED,
        ]);
    }

    /**
     * Get profile based on role.
     */
    public function profile()
    {
        if ($this->isDeveloper()) {
            return $this->developer;
        } elseif ($this->isAgent()) {
            return $this->agent;
        }
        return null;
    }

    /**
     * Scope: Only developers.
     */
    public function scopeDevelopers($query)
    {
        return $query->where('role', self::ROLE_DEVELOPER);
    }

    /**
     * Scope: Only agents.
     */
    public function scopeAgents($query)
    {
        return $query->where('role', self::ROLE_AGENT);
    }

    /**
     * Scope: Only admins.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope: Only approved users.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: Only pending users.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Find by slug.
     */
    public function scopeFindBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
