<?php

namespace App\Modules\RealEstate\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Developer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'developers';

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'description',
        'tagline',
        'email',
        'phone',
        'website',
        'office_address',
        'logo',
        'banner_image',
        'social_links',
        'registration_number',
        'rera_id',
        'established_year',
        'total_projects',
        'completed_projects',
        'ongoing_projects',
        'employee_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'social_links' => 'array',
        'established_year' => 'integer',
        'total_projects' => 'integer',
        'completed_projects' => 'integer',
        'ongoing_projects' => 'integer',
        'employee_count' => 'integer',
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

        static::creating(function ($developer): void {
            if (empty($developer->slug)) {
                $developer->slug = Str::slug($developer->company_name);
            }
        });

        static::updating(function ($developer): void {
            if ($developer->isDirty('company_name') && empty($developer->slug)) {
                $developer->slug = Str::slug($developer->company_name);
            }
        });
    }

    /**
     * Get the user that owns this developer profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the projects for this developer.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(DeveloperProject::class, 'developer_id');
    }

    /**
     * Get past/completed projects.
     */
    public function pastProjects(): HasMany
    {
        return $this->projects()->where('status', 'completed');
    }

    /**
     * Get ongoing projects.
     */
    public function ongoingProjects(): HasMany
    {
        return $this->projects()->where('status', 'ongoing');
    }

    /**
     * Get upcoming projects.
     */
    public function upcomingProjects(): HasMany
    {
        return $this->projects()->where('status', 'upcoming');
    }

    /**
     * Get approved projects only.
     */
    public function approvedProjects(): HasMany
    {
        return $this->projects()->where('approval_status', 'approved');
    }

    /**
     * Get properties added by this developer.
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
     * Scope: Only approved developers (based on user status).
     */
    public function scopeApproved($query)
    {
        return $query->whereHas('user', function ($q): void {
            $q->where('status', 'approved');
        });
    }

    /**
     * Scope: Featured developers.
     */
    public function scopeFeatured($query)
    {
        return $query->whereHas('user', function ($q): void {
            $q->where('status', 'approved');
        })->where('is_featured', true);
    }

    /**
     * Get full company name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->company_name;
    }

    /**
     * Get public profile URL.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('developers.show', $this->slug);
    }

    /**
     * Get logo URL or default.
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-developer-logo.png');
    }

    /**
     * Get banner URL or default.
     */
    public function getBannerUrlAttribute(): string
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : asset('images/default-developer-banner.png');
    }

    /**
     * Check if developer is approved.
     */
    public function isApproved(): bool
    {
        return $this->user && $this->user->status === 'approved';
    }

    /**
     * Get upcoming projects count.
     */
    public function getUpcomingProjectsCountAttribute(): int
    {
        if (array_key_exists('projects_count', $this->relations)) {
            return $this->projects_count;
        }
        return $this->projects()->where('status', 'upcoming')->count();
    }

    /**
     * Get approved properties count.
     */
    public function getApprovedPropertiesCountAttribute(): int
    {
        if (array_key_exists('properties_count', $this->relations)) {
            return $this->properties_count;
        }
        return $this->properties()->where('approval_status', 'approved')->count();
    }
}
