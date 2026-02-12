<?php

namespace Botble\AgentBroker\Http\Controllers;

use Botble\AgentBroker\Models\AgentLead;
use Botble\Base\Http\Controllers\BaseController;

class AgentLeadController extends BaseController
{
    public function index()
    {
        abort_unless(auth()->user()->hasPermission('agent-broker.leads.index'), 403);

        $leads = AgentLead::query()->with(['agentProfile', 'property'])->latest()->paginate(20);

        $this->pageTitle('Agent Leads');

        return view('plugins/agent-broker::leads.index', compact('leads'));
    }
}
