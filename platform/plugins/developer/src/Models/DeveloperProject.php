<?php

namespace Botble\Developer\Models;

use Botble\Base\Models\BaseModel;
use Botble\RealEstate\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeveloperProject extends BaseModel
{
    use SoftDeletes;

    protected $table = 're_developer_projects';

    protected $fillable = [
        'developer_profile_id',
        'project_id',
        'name',
        'slug',
        'location',
        'latitude',
        'longitude',
        'rera_number',
        'possession_date',
        'project_status',
        'amenities',
        'gallery',
        'floor_plans',
        'brochure',
        'pricing_details',
        'unit_configurations',
        'highlights',
        'about_developer',
        'is_featured',
        'status',
        'visibility_status',
        'published_at',
        'approval_status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'amenities' => 'array',
        'gallery' => 'array',
        'floor_plans' => 'array',
        'pricing_details' => 'array',
        'unit_configurations' => 'array',
        'highlights' => 'array',
        'is_featured' => 'boolean',
        'possession_date' => 'date',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function developerProfile(): BelongsTo
    {
        return $this->belongsTo(DeveloperProfile::class, 'developer_profile_id');
    }

    public function realEstateProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(DeveloperProjectMedia::class, 'developer_project_id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(DeveloperLead::class, 'developer_project_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(DeveloperProjectRevision::class, 'entity_id');
    }
}
