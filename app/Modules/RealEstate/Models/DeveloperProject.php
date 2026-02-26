<?php

namespace App\Modules\RealEstate\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DeveloperProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'developer_projects';

    protected $fillable = [
        'developer_id',
        'name',
        'slug',
        'description',
        'short_description',
        'type',
        'status',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'latitude',
        'longitude',
        'rera_number',
        'legal_clearance',
        'launch_date',
        'possession_date',
        'completion_date',
        'unit_configurations',
        'total_units',
        'total_towers',
        'total_floors',
        'total_area',
        'price_starting_from',
        'price_per_sqft',
        'amenities',
        'specifications',
        'nearby_facilities',
        'gallery_images',
        'floor_plans',
        'brochure',
        'video_url',
        'virtual_tour_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'approval_status',
        'approved_at',
        'approved_by',
        'is_featured',
        'featured_until',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'total_units' => 'integer',
        'total_towers' => 'integer',
        'total_floors' => 'integer',
        'total_area' => 'decimal:2',
        'price_starting_from' => 'decimal:2',
        'price_per_sqft' => 'decimal:2',
        'unit_configurations' => 'array',
        'amenities' => 'array',
        'specifications' => 'array',
        'nearby_facilities' => 'array',
        'gallery_images' => 'array',
        'floor_plans' => 'array',
        'is_featured' => 'boolean',
        'launch_date' => 'date',
        'possession_date' => 'date',
        'completion_date' => 'date',
        'approved_at' => 'datetime',
        'featured_until' => 'datetime',
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

        static::creating(function ($project): void {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });

        static::updating(function ($project): void {
            if ($project->isDirty('name') && empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    /**
     * Get the developer that owns this project.
     */
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'developer_id');
    }

    /**
     * Get the admin who approved this project.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get properties in this project.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'developer_project_id');
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
     * Scope: Only approved projects.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope: Only pending projects.
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope: Only rejected projects.
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    /**
     * Scope: Upcoming projects.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    /**
     * Scope: Ongoing projects.
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope: Completed projects.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Featured projects.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
            ->where(function ($q): void {
                $q->whereNull('featured_until')
                    ->orWhere('featured_until', '>', now());
            });
    }

    /**
     * Scope: Filter by city.
     */
    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope: Filter by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get public URL.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('developer-projects.show', [
            'developerSlug' => $this->developer?->slug,
            'projectSlug' => $this->slug,
        ]);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): ?string
    {
        if ($this->price_starting_from) {
            return '₹' . number_format($this->price_starting_from);
        }
        return null;
    }

    /**
     * Get location string.
     */
    public function getLocationAttribute(): string
    {
        $parts = array_filter([$this->address, $this->city, $this->state]);
        return implode(', ', $parts);
    }

    /**
     * Check if project is approved.
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if project is featured.
     */
    public function isFeatured(): bool
    {
        if (!$this->is_featured) {
            return false;
        }
        if ($this->featured_until && $this->featured_until->isPast()) {
            return false;
        }
        return true;
    }

    /**
     * Get main image from gallery.
     */
    public function getMainImageAttribute(): ?string
    {
        $gallery = $this->gallery_images;
        if (is_array($gallery) && count($gallery) > 0) {
            return $gallery[0] ?? null;
        }
        return null;
    }

    /**
     * Get main image URL.
     */
    public function getMainImageUrlAttribute(): string
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }
        return asset('images/default-project.jpg');
    }

    /**
     * Get completion percentage.
     */
    public function getCompletionPercentageAttribute(): int
    {
        return match ($this->status) {
            'completed' => 100,
            'sold_out' => 100,
            'ongoing' => 60,
            'upcoming' => 0,
            default => 0,
        };
    }
}
