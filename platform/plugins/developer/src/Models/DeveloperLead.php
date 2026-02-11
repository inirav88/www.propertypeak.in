<?php

namespace Botble\Developer\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeveloperLead extends BaseModel
{
    use SoftDeletes;

    protected $table = 're_developer_leads';

    protected $fillable = [
        'developer_profile_id',
        'developer_project_id',
        'name',
        'email',
        'phone',
        'message',
        'source',
        'status',
        'metadata',
        'contacted_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'contacted_at' => 'datetime',
    ];

    public function developerProfile(): BelongsTo
    {
        return $this->belongsTo(DeveloperProfile::class, 'developer_profile_id');
    }

    public function developerProject(): BelongsTo
    {
        return $this->belongsTo(DeveloperProject::class, 'developer_project_id');
    }
}
