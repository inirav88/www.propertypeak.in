<?php

return [
    'name' => 'plugins/agent-broker::settings.email.title',
    'description' => 'plugins/agent-broker::settings.email.description',
    'templates' => [
        'agent-lead-notification' => [
            'title' => 'New agent lead',
            'description' => 'Send to admin and agent when a new agent lead is submitted.',
            'subject' => 'New agent lead from {{ lead_name }}',
            'can_off' => true,
            'variables' => [
                'lead_name' => 'Lead name',
                'lead_email' => 'Lead email',
                'lead_phone' => 'Lead phone',
                'lead_message' => 'Lead message',
                'agent_name' => 'Agent name',
                'agent_profile_url' => 'Agent profile URL',
            ],
        ],

        'agent-lead-confirmation' => [
            'title' => 'Agent lead confirmation',
            'description' => 'Send to lead submitter as a confirmation receipt.',
            'subject' => 'We received your enquiry for {{ agent_name }}',
            'can_off' => true,
            'variables' => [
                'lead_name' => 'Lead name',
                'lead_email' => 'Lead email',
                'lead_phone' => 'Lead phone',
                'lead_message' => 'Lead message',
                'agent_name' => 'Agent name',
                'agent_profile_url' => 'Agent profile URL',
            ],
        ],
        'agent-profile-change-submitted' => [
            'title' => 'Agent profile change submitted',
            'description' => 'Send to admin when agent submits profile changes for approval.',
            'subject' => 'Agent profile change pending approval',
            'can_off' => true,
            'variables' => [
                'agent_name' => 'Agent name',
                'agent_email' => 'Agent email',
                'revision_url' => 'Revision URL',
            ],
        ],
        'agent-profile-approved' => [
            'title' => 'Agent profile approved',
            'description' => 'Send to agent when profile revision is approved.',
            'subject' => 'Your agent profile update has been approved',
            'can_off' => true,
            'variables' => [
                'agent_name' => 'Agent name',
                'approval_note' => 'Approval note',
            ],
        ],
        'agent-profile-rejected' => [
            'title' => 'Agent profile rejected',
            'description' => 'Send to agent when profile revision is rejected.',
            'subject' => 'Your agent profile update has been rejected',
            'can_off' => true,
            'variables' => [
                'agent_name' => 'Agent name',
                'rejection_reason' => 'Rejection reason',
            ],
        ],
    ],
];
