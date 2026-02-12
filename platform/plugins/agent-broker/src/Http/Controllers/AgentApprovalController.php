<?php

namespace Botble\AgentBroker\Http\Controllers;

use Botble\AgentBroker\Models\AgentProfileRevision;
use Botble\AgentBroker\Services\AgentBrokerApprovalService;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class AgentApprovalController extends BaseController
{
    public function index()
    {
        abort_unless(auth()->user()->hasPermission('agent-broker.approvals.index'), 403);

        $revisions = AgentProfileRevision::query()->with(['profile', 'submitter'])->latest()->paginate(20);

        $this->pageTitle('Agent Approval Queue');

        return view('plugins/agent-broker::approvals.index', compact('revisions'));
    }

    public function approve(AgentProfileRevision $revision, Request $request, AgentBrokerApprovalService $service)
    {
        abort_unless(auth()->user()->hasPermission('agent-broker.approvals.moderate'), 403);

        $service->approveProfileRevision($revision, $request->input('note'));

        return $this->httpResponse()->setMessage('Agent profile revision approved.');
    }

    public function reject(AgentProfileRevision $revision, Request $request, AgentBrokerApprovalService $service)
    {
        abort_unless(auth()->user()->hasPermission('agent-broker.approvals.moderate'), 403);

        $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $service->rejectProfileRevision($revision, $request->input('reason'));

        return $this->httpResponse()->setMessage('Agent profile revision rejected.');
    }
}
