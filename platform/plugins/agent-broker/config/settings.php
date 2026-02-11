<?php

return [
    'approval' => [
        'global_auto_approve' => false,
        'allow_per_profile_override' => true,
    ],
    'lead_routing' => [
        'notify_admin' => true,
        'notify_agent' => true,
        'mode' => 'both',
        'send_confirmation_email' => false,
    ],
    'slug' => [
        'use_root_slug' => true,
        'reserved_keywords' => [
            'admin',
            'account',
            'login',
            'register',
            'api',
            'developers',
            'agents',
        ],
    ],
    'required_fields' => [
        'phone',
        'address',
    ],
];
