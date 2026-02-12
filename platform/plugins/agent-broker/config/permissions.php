<?php

return [
    [
        'name' => 'Agent Broker',
        'flag' => 'plugins.agent-broker',
    ],
    [
        'name' => 'Profiles',
        'flag' => 'agent-broker.profiles.index',
        'parent_flag' => 'plugins.agent-broker',
    ],
    [
        'name' => 'Create',
        'flag' => 'agent-broker.profiles.create',
        'parent_flag' => 'agent-broker.profiles.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'agent-broker.profiles.edit',
        'parent_flag' => 'agent-broker.profiles.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'agent-broker.profiles.destroy',
        'parent_flag' => 'agent-broker.profiles.index',
    ],
    [
        'name' => 'Leads',
        'flag' => 'agent-broker.leads.index',
        'parent_flag' => 'plugins.agent-broker',
    ],
    [
        'name' => 'Approval Queue',
        'flag' => 'agent-broker.approvals.index',
        'parent_flag' => 'plugins.agent-broker',
    ],
    [
        'name' => 'Approve / Reject',
        'flag' => 'agent-broker.approvals.moderate',
        'parent_flag' => 'agent-broker.approvals.index',
    ],
    [
        'name' => 'Settings',
        'flag' => 'agent-broker.settings',
        'parent_flag' => 'plugins.agent-broker',
    ],
];
