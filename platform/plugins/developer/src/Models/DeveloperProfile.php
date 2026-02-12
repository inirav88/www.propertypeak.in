<?php

namespace Botble\Developer\Models;

use Botble\Base\Models\BaseModel;
use Botble\RealEstate\Models\Account;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeveloperProfile extends BaseModel
{
    use SoftDeletes;

    protected $table = 're_developer_profiles';

    protected $fillable = [
        'account_id',
        'slug',
        'company_name',
        'contact_email',
        'contact_phone',
        'website',
        'logo',
        'banner',
        'about',
        'social_links',
        'is_featured',
        'is_verified',
        'is_blocked',
        'requires_approval',
        'auto_approve_changes',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'is_blocked' => 'boolean',
        'requires_approval' => 'boolean',
        'auto_approve_changes' => 'boolean',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saved(function (DeveloperProfile $profile): void {
            SlugHelper::createSlug($profile, $profile->slug);
        });

        static::deleted(function (DeveloperProfile $profile): void {
            Slug::query()
                ->where('reference_type', self::class)
                ->where('reference_id', $profile->getKey())
                ->delete();
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(DeveloperProject::class, 'developer_profile_id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(DeveloperLead::class, 'developer_profile_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(DeveloperProfileRevision::class, 'entity_id');
    }
}
