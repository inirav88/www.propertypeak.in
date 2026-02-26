<?php

namespace App\Modules\RealEstate\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Agent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agents';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'slug',
        'bio',
        'tagline',
        'designation',
        'license_number',
        'rera_id',
        'email',
        'phone',
        'whatsapp',
        'website',
        'office_address',
        'avatar',
        'banner_image',
        'social_links',
        'experience_years',
        'specializations',
        'languages_spoken',
        'total_sales',
        'active_listings',
        'service_areas',
        'working_hours',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'social_links' => 'array',
        'specializations' => 'array',
        'languages_spoken' => 'array',
        'service_areas' => 'array',
        'working_hours' => 'array',
        'experience_years' => 'integer',
        'total_sales' => 'integer',
        'active_listings' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($agent): void {
            if (empty($agent->slug)) {
                $baseSlug = Str::slug($agent->first_name . ' ' . $agent->last_name);
                $agent->slug = $baseSlug;
            }
        });

        static::updating(function ($agent): void {
            if (($agent->isDirty('first_name') || $agent->isDirty('last_name')) && empty($agent->slug)) {
                $baseSlug = Str::slug($agent->first_name . ' ' . $agent->last_name);
                $agent->slug = $baseSlug;
            }
        });
    }

    /**
     * Get the user that owns this agent profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get properties added by this agent.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'user_id', 'user_id');
    }

    /**
     * Get approved properties only.
     */
    public function approvedProperties(): HasMany
    {
        return $this->properties()->where('approval_status', 'approved');
    }

    /**
     * Scope: Find by slug.
     */
    public function scopeFindBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Scope: Only approved agents (based on user status).
     */
    public function scopeApproved($query)
    {
        return $query->whereHas('user', function ($q): void {
            $q->where('status', 'approved');
        });
    }

    /**
     * Scope: Featured agents.
     */
    public function scopeFeatured($query)
    {
        return $query->whereHas('user', function ($q): void {
            $q->where('status', 'approved');
        });
    }

    /**
     * Get full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get full name with designation.
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->full_name;
        if ($this->designation) {
            $name .= ' - ' . $this->designation;
        }
        return $name;
    }

    /**
     * Get public profile URL.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('agents.show', $this->slug);
    }

    /**
     * Get avatar URL or default.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.png');
    }

    /**
     * Get banner URL or default.
     */
    public function getBannerUrlAttribute(): string
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : asset('images/default-agent-banner.png');
    }

    /**
     * Check if agent is approved.
     */
    public function isApproved(): bool
    {
        return $this->user && $this->user->status === 'approved';
    }

    /**
     * Get formatted experience.
     */
    public function getExperienceTextAttribute(): string
    {
        if ($this->experience_years === 0) {
            return 'Less than 1 year';
        }
        return $this->experience_years . ' ' . Str::plural('year', $this->experience_years) . ' experience';
    }

    /**
     * Set the full name (accessor for first + last).
     */
    public function setFullNameAttribute(string $name): void
    {
        $parts = explode(' ', $name, 2);
        $this->first_name = $parts[0] ?? '';
        $this->last_name = $parts[1] ?? '';
    }
}
