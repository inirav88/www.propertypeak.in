<?php

return [
    [
        'name' => 'Developer',
        'flag' => 'plugins.developer',
    ],
    [
        'name' => 'Profiles',
        'flag' => 'developer.profiles.index',
        'parent_flag' => 'plugins.developer',
    ],
    [
        'name' => 'Create',
        'flag' => 'developer.profiles.create',
        'parent_flag' => 'developer.profiles.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'developer.profiles.edit',
        'parent_flag' => 'developer.profiles.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'developer.profiles.destroy',
        'parent_flag' => 'developer.profiles.index',
    ],
    [
        'name' => 'Projects',
        'flag' => 'developer.projects.index',
        'parent_flag' => 'plugins.developer',
    ],
    [
        'name' => 'Create',
        'flag' => 'developer.projects.create',
        'parent_flag' => 'developer.projects.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'developer.projects.edit',
        'parent_flag' => 'developer.projects.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'developer.projects.destroy',
        'parent_flag' => 'developer.projects.index',
    ],
    [
        'name' => 'Leads',
        'flag' => 'developer.leads.index',
        'parent_flag' => 'plugins.developer',
    ],
    [
        'name' => 'Approval Queue',
        'flag' => 'developer.approvals.index',
        'parent_flag' => 'plugins.developer',
    ],
    [
        'name' => 'Approve / Reject',
        'flag' => 'developer.approvals.moderate',
        'parent_flag' => 'developer.approvals.index',
    ],
    [
        'name' => 'Settings',
        'flag' => 'developer.settings',
        'parent_flag' => 'plugins.developer',
    ],
];
