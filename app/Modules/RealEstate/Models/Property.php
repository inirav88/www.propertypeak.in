<?php

namespace App\Modules\RealEstate\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 're_properties';

    protected $fillable = [
        'user_id',
        'added_by_role',
        'developer_project_id',
        'name',
        'slug',
        'type',
        'pg_category',
        'pg_occupancy_type',
        'description',
        'content',
        'location',
        'images',
        'floor_plans',
        'project_id',
        'number_bedroom',
        'number_bathroom',
        'number_floor',
        'square',
        'price',
        'pricing_model',
        'total_beds',
        'available_beds',
        'price_per_bed',
        'price_per_room',
        'security_deposit',
        'maintenance_charges',
        'notice_period_days',
        'food_included',
        'food_type',
        'meals_provided',
        'ac_available',
        'wifi_included',
        'laundry_included',
        'parking_available',
        'gender_preference',
        'preferred_tenants',
        'gate_closing_time',
        'visitors_allowed',
        'smoking_allowed',
        'drinking_allowed',
        'house_rules',
        'nearby_landmarks',
        'furnishing_details',
        'virtual_tour_url',
        'city_id',
        'state_id',
        'country_id',
        'status',
        'approval_status',
        'approved_by',
        'approved_at',
        'is_featured',
        'latitude',
        'longitude',
        'zip_code',
        'views',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_bed' => 'decimal:2',
        'price_per_room' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'maintenance_charges' => 'decimal:2',
        'square' => 'decimal:2',
        'latitude' => 'string',
        'longitude' => 'string',
        'number_bedroom' => 'integer',
        'number_bathroom' => 'integer',
        'number_floor' => 'integer',
        'total_beds' => 'integer',
        'available_beds' => 'integer',
        'notice_period_days' => 'integer',
        'food_included' => 'boolean',
        'ac_available' => 'boolean',
        'wifi_included' => 'boolean',
        'laundry_included' => 'boolean',
        'parking_available' => 'boolean',
        'instant_booking' => 'boolean',
        'verified_pg' => 'boolean',
        'owner_stays' => 'boolean',
        'visitors_allowed' => 'boolean',
        'smoking_allowed' => 'boolean',
        'drinking_allowed' => 'boolean',
        'is_featured' => 'boolean',
        'auto_renew' => 'boolean',
        'never_expired' => 'boolean',
        'images' => 'array',
        'floor_plans' => 'array',
        'furnishing_details' => 'array',
        'pg_occupancy_type' => 'array',
        'food_type' => 'array',
        'meals_provided' => 'array',
        'preferred_tenants' => 'array',
        'approved_at' => 'datetime',
        'expire_date' => 'date',
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

        // Global scope to only show approved properties
        static::addGlobalScope('approved', function ($builder): void {
            $builder->where('approval_status', 'approved');
        });

        static::creating(function ($property): void {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->name);
            }
        });

        static::updating(function ($property): void {
            if ($property->isDirty('name') && empty($property->slug)) {
                $property->slug = Str::slug($property->name);
            }
        });
    }

    /**
     * Get the user who added this property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the developer who added this property.
     */
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'user_id', 'user_id');
    }

    /**
     * Get the agent who added this property.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'user_id', 'user_id');
    }

    /**
     * Get the developer project this property belongs to.
     */
    public function developerProject(): BelongsTo
    {
        return $this->belongsTo(DeveloperProject::class, 'developer_project_id');
    }

    /**
     * Get the admin who approved this property.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Find by slug (removes global scope).
     */
    public function scopeFindBySlug($query, string $slug)
    {
        return $query->withoutGlobalScope('approved')->where('slug', $slug);
    }

    /**
     * Scope: Include all properties without approval filter.
     */
    public function scopeWithAll($query)
    {
        return $query->withoutGlobalScope('approved');
    }

    /**
     * Scope: Only approved properties.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope: Only pending properties.
     */
    public function scopePending($query)
    {
        return $query->withoutGlobalScope('approved')->where('approval_status', 'pending');
    }

    /**
     * Scope: Only rejected properties.
     */
    public function scopeRejected($query)
    {
        return $query->withoutGlobalScope('approved')->where('approval_status', 'rejected');
    }

    /**
     * Scope: Properties for sale.
     */
    public function scopeForSale($query)
    {
        return $query->where('type', 'sale');
    }

    /**
     * Scope: Properties for rent.
     */
    public function scopeForRent($query)
    {
        return $query->where('type', 'rent');
    }

    /**
     * Scope: PG/Hostel properties.
     */
    public function scopePg($query)
    {
        return $query->where('type', 'pg');
    }

    /**
     * Scope: Featured properties.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Filter by location.
     */
    public function scopeInLocation($query, string $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    /**
     * Scope: Filter by city (alias for location).
     * @deprecated Use scopeInLocation instead
     */
    public function scopeInCity($query, string $city)
    {
        return $query->where('location', 'like', '%' . $city . '%');
    }

    /**
     * Scope: Filter by property type (type column in Botble).
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Filter by price range.
     */
    public function scopePriceBetween($query, float $min, float $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope: Filter by bedrooms.
     */
    public function scopeWithBedrooms($query, int $bedrooms)
    {
        return $query->where('number_bedroom', $bedrooms);
    }

    /**
     * Scope: Added by developers.
     */
    public function scopeByDevelopers($query)
    {
        return $query->where('added_by_role', 'developer');
    }

    /**
     * Scope: Added by agents.
     */
    public function scopeByAgents($query)
    {
        return $query->where('added_by_role', 'agent');
    }

    /**
     * Scope: Properties belonging to a specific user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ============================================================================
    // ACCESSORS - Map custom module columns to Botble schema
    // ============================================================================

    /**
     * Get title (alias for name).
     */
    public function getTitleAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    /**
     * Set title (maps to name).
     */
    public function setTitleAttribute($value): void
    {
        $this->attributes['name'] = $value;
    }

    /**
     * Get bedrooms (alias for number_bedroom).
     */
    public function getBedroomsAttribute(): ?int
    {
        return $this->attributes['number_bedroom'] ?? null;
    }

    /**
     * Set bedrooms (maps to number_bedroom).
     */
    public function setBedroomsAttribute($value): void
    {
        $this->attributes['number_bedroom'] = $value;
    }

    /**
     * Get bathrooms (alias for number_bathroom).
     */
    public function getBathroomsAttribute(): ?int
    {
        return $this->attributes['number_bathroom'] ?? null;
    }

    /**
     * Set bathrooms (maps to number_bathroom).
     */
    public function setBathroomsAttribute($value): void
    {
        $this->attributes['number_bathroom'] = $value;
    }

    /**
     * Get carpet_area (alias for square).
     */
    public function getCarpetAreaAttribute(): ?float
    {
        return $this->attributes['square'] ?? null;
    }

    /**
     * Set carpet_area (maps to square).
     */
    public function setCarpetAreaAttribute($value): void
    {
        $this->attributes['square'] = $value;
    }

    /**
     * Get property_type (alias for type).
     */
    public function getPropertyTypeAttribute(): ?string
    {
        return $this->attributes['type'] ?? null;
    }

    /**
     * Set property_type (maps to type).
     */
    public function setPropertyTypeAttribute($value): void
    {
        $this->attributes['type'] = $value;
    }

    /**
     * Get city (extracted from location or via city_id relation).
     */
    public function getCityAttribute(): ?string
    {
        // If location contains comma, extract first part as city
        if (!empty($this->location)) {
            $parts = explode(',', $this->location);
            return trim($parts[0] ?? $this->location);
        }
        return null;
    }

    /**
     * Get state (extracted from location).
     */
    public function getStateAttribute(): ?string
    {
        // If location contains comma, extract second part as state
        if (!empty($this->location) && str_contains($this->location, ',')) {
            $parts = explode(',', $this->location);
            return isset($parts[1]) ? trim($parts[1]) : null;
        }
        return null;
    }

    /**
     * Get address (same as location in Botble schema).
     */
    public function getAddressAttribute(): ?string
    {
        return $this->location;
    }

    /**
     * Get total_floors (alias for number_floor).
     */
    public function getTotalFloorsAttribute(): ?int
    {
        return $this->attributes['number_floor'] ?? null;
    }

    /**
     * Set total_floors (maps to number_floor).
     */
    public function setTotalFloorsAttribute($value): void
    {
        $this->attributes['number_floor'] = $value;
    }

    /**
     * Get built_up_area (alias for square as approximation).
     */
    public function getBuiltUpAreaAttribute(): ?float
    {
        return $this->attributes['square'] ?? null;
    }

    /**
     * Get parking_spaces (alias for parking_available).
     */
    public function getParkingSpacesAttribute(): ?int
    {
        return $this->parking_available ? 1 : 0;
    }

    /**
     * Get furnishing_status (mapped from furnishing_details).
     */
    public function getFurnishingStatusAttribute(): int
    {
        $details = $this->furnishing_details;
        if (is_array($details) && !empty($details)) {
            // Check if furnishing details indicate furnished status
            if (in_array('fully_furnished', $details) || in_array('Fully Furnished', $details)) {
                return 2; // Fully Furnished
            }
            if (in_array('semi_furnished', $details) || in_array('Semi-Furnished', $details)) {
                return 1; // Semi-Furnished
            }
        }
        return 0; // Unfurnished
    }

    /**
     * Get amenities (alias for nearby_landmarks if available).
     */
    public function getAmenitiesAttribute(): ?array
    {
        if (!empty($this->attributes['nearby_landmarks'])) {
            return is_string($this->attributes['nearby_landmarks']) 
                ? explode(',', $this->attributes['nearby_landmarks'])
                : $this->attributes['nearby_landmarks'];
        }
        // Return PG amenities as fallback
        $amenities = [];
        if ($this->ac_available) $amenities[] = 'AC';
        if ($this->wifi_included) $amenities[] = 'WiFi';
        if ($this->laundry_included) $amenities[] = 'Laundry';
        if ($this->parking_available) $amenities[] = 'Parking';
        return $amenities ?: null;
    }

    /**
     * Get nearby_facilities (alias for nearby_landmarks).
     */
    public function getNearbyFacilitiesAttribute(): ?array
    {
        if (!empty($this->attributes['nearby_landmarks'])) {
            return is_string($this->attributes['nearby_landmarks']) 
                ? explode(',', $this->attributes['nearby_landmarks'])
                : $this->attributes['nearby_landmarks'];
        }
        return null;
    }

    /**
     * Get meta_title (alias for name).
     */
    public function getMetaTitleAttribute(): ?string
    {
        return $this->name;
    }

    /**
     * Get meta_description (alias for description).
     */
    public function getMetaDescriptionAttribute(): ?string
    {
        return $this->description;
    }

    // ============================================================================
    // ORIGINAL ACCESSORS
    // ============================================================================

    /**
     * Get public URL.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('properties.show', $this->slug);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price);
    }

    /**
     * Get price in words/compact format.
     */
    public function getCompactPriceAttribute(): string
    {
        $price = (float) $this->price;
        if ($price >= 10000000) {
            return '₹' . round($price / 10000000, 2) . ' Cr';
        } elseif ($price >= 100000) {
            return '₹' . round($price / 100000, 2) . ' L';
        }
        return '₹' . number_format($price);
    }

    /**
     * Get location string.
     */
    public function getFullLocationAttribute(): string
    {
        return $this->location ?? 'Location not specified';
    }

    /**
     * Get short location (alias for location).
     */
    public function getShortLocationAttribute(): string
    {
        return $this->city ?? $this->location ?? 'N/A';
    }

    /**
     * Get main image.
     */
    public function getMainImageAttribute(): ?string
    {
        $images = $this->images;
        if (is_array($images) && count($images) > 0) {
            return $images[0] ?? null;
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
        return asset('images/default-property.jpg');
    }

    /**
     * Get furnishing status text.
     */
    public function getFurnishingStatusTextAttribute(): string
    {
        return match ($this->furnishing_status) {
            1 => 'Semi-Furnished',
            2 => 'Fully Furnished',
            default => 'Unfurnished',
        };
    }

    /**
     * Check if property is approved.
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if property is featured.
     */
    public function isFeatured(): bool
    {
        return (bool) $this->is_featured;
    }

    /**
     * Get added by display name.
     */
    public function getAddedByDisplayAttribute(): string
    {
        if ($this->added_by_role === 'developer') {
            return $this->developer?->company_name ?? 'Developer';
        } elseif ($this->added_by_role === 'agent') {
            return $this->agent?->full_name ?? 'Agent';
        }
        return 'Admin';
    }

    /**
     * Get SEO meta title.
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?? $this->name . ' in ' . ($this->city ?? 'Unknown Location');
    }

    /**
     * Get SEO meta description.
     */
    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?? Str::limit($this->description, 160);
    }

    /**
     * Get location for display.
     */
    public function getLocationAttribute(): ?string
    {
        return $this->attributes['location'] ?? null;
    }

    /**
     * Get content (full description).
     */
    public function getContentAttribute(): ?string
    {
        return $this->attributes['content'] ?? null;
    }
}
