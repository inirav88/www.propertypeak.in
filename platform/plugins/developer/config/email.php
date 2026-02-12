<?php

return [
    'name' => 'plugins/developer::settings.email.title',
    'description' => 'plugins/developer::settings.email.description',
    'templates' => [
        'developer-lead-notification' => [
            'title' => 'New developer lead',
            'description' => 'Send to admin and developer when a new developer lead is submitted.',
            'subject' => 'New developer lead from {{ lead_name }}',
            'can_off' => true,
            'variables' => [
                'lead_name' => 'Lead name',
                'lead_email' => 'Lead email',
                'lead_phone' => 'Lead phone',
                'lead_message' => 'Lead message',
                'developer_name' => 'Developer name',
                'developer_profile_url' => 'Developer profile URL',
            ],
        ],

        'developer-lead-confirmation' => [
            'title' => 'Developer lead confirmation',
            'description' => 'Send to lead submitter as a confirmation receipt.',
            'subject' => 'We received your enquiry for {{ developer_name }}',
            'can_off' => true,
            'variables' => [
                'lead_name' => 'Lead name',
                'lead_email' => 'Lead email',
                'lead_phone' => 'Lead phone',
                'lead_message' => 'Lead message',
                'developer_name' => 'Developer name',
                'developer_profile_url' => 'Developer profile URL',
            ],
        ],
        'developer-profile-change-submitted' => [
            'title' => 'Developer profile change submitted',
            'description' => 'Send to admin when developer submits profile changes for approval.',
            'subject' => 'Developer profile change pending approval',
            'can_off' => true,
            'variables' => [
                'developer_name' => 'Developer name',
                'developer_email' => 'Developer email',
                'revision_url' => 'Revision URL',
            ],
        ],
        'developer-profile-approved' => [
            'title' => 'Developer profile approved',
            'description' => 'Send to developer when profile revision is approved.',
            'subject' => 'Your developer profile update has been approved',
            'can_off' => true,
            'variables' => [
                'developer_name' => 'Developer name',
                'approval_note' => 'Approval note',
            ],
        ],
        'developer-profile-rejected' => [
            'title' => 'Developer profile rejected',
            'description' => 'Send to developer when profile revision is rejected.',
            'subject' => 'Your developer profile update has been rejected',
            'can_off' => true,
            'variables' => [
                'developer_name' => 'Developer name',
                'rejection_reason' => 'Rejection reason',
            ],
        ],
    ],
];
