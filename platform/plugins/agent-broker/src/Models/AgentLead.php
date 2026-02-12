<?php

namespace Botble\AgentBroker\Models;

use Botble\Base\Models\BaseModel;
use Botble\RealEstate\Models\Property;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentLead extends BaseModel
{
    use SoftDeletes;

    protected $table = 're_agent_leads';

    protected $fillable = [
        'agent_profile_id',
        'property_id',
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

    public function agentProfile(): BelongsTo
    {
        return $this->belongsTo(AgentProfile::class, 'agent_profile_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
