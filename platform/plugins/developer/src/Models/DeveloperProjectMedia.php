<?php

namespace Botble\Developer\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeveloperProjectMedia extends BaseModel
{
    use SoftDeletes;

    protected $table = 're_developer_project_media';

    protected $fillable = [
        'developer_project_id',
        'type',
        'file',
        'title',
        'description',
        'sort_order',
    ];

    public function developerProject(): BelongsTo
    {
        return $this->belongsTo(DeveloperProject::class, 'developer_project_id');
    }
}
