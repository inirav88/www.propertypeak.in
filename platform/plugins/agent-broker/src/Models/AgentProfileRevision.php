<?php

namespace Botble\AgentBroker\Models;

use Botble\Base\Models\BaseModel;
use Botble\RealEstate\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentProfileRevision extends BaseModel
{
    protected $table = 're_agent_profile_revisions';

    protected $fillable = [
        'entity_id',
        'entity_type',
        'payload',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'submitted_by',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'reviewed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(AgentProfile::class, 'entity_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'reviewed_by');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'submitted_by');
    }
}
