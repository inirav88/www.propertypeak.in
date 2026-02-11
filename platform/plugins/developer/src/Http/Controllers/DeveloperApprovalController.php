<?php

namespace Botble\Developer\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Developer\Models\DeveloperProfileRevision;
use Botble\Developer\Models\DeveloperProjectRevision;
use Botble\Developer\Services\DeveloperApprovalService;
use Illuminate\Http\Request;

class DeveloperApprovalController extends BaseController
{
    public function index()
    {
        abort_unless(auth()->user()->hasPermission('developer.approvals.index'), 403);

        $profileRevisions = DeveloperProfileRevision::query()->with(['profile', 'submitter'])->latest()->paginate(10, ['*'], 'profile_page');
        $projectRevisions = DeveloperProjectRevision::query()->with(['project', 'submitter'])->latest()->paginate(10, ['*'], 'project_page');

        $this->pageTitle('Developer Approval Queue');

        return view('plugins/developer::approvals.index', compact('profileRevisions', 'projectRevisions'));
    }

    public function approveProfile(DeveloperProfileRevision $revision, Request $request, DeveloperApprovalService $service)
    {
        abort_unless(auth()->user()->hasPermission('developer.approvals.moderate'), 403);

        $service->approveProfileRevision($revision, $request->input('note'));

        return $this->httpResponse()->setMessage('Profile revision approved.');
    }

    public function rejectProfile(DeveloperProfileRevision $revision, Request $request, DeveloperApprovalService $service)
    {
        abort_unless(auth()->user()->hasPermission('developer.approvals.moderate'), 403);

        $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $service->rejectProfileRevision($revision, $request->input('reason'));

        return $this->httpResponse()->setMessage('Profile revision rejected.');
    }

    public function approveProject(DeveloperProjectRevision $revision, Request $request, DeveloperApprovalService $service)
    {
        abort_unless(auth()->user()->hasPermission('developer.approvals.moderate'), 403);

        $service->approveProjectRevision($revision, $request->input('note'));

        return $this->httpResponse()->setMessage('Project revision approved.');
    }

    public function rejectProject(DeveloperProjectRevision $revision, Request $request, DeveloperApprovalService $service)
    {
        abort_unless(auth()->user()->hasPermission('developer.approvals.moderate'), 403);

        $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $service->rejectProjectRevision($revision, $request->input('reason'));

        return $this->httpResponse()->setMessage('Project revision rejected.');
    }
}
