<?php

namespace App\Modules\RealEstate\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\RealEstate\Models\DeveloperProject;
use App\Modules\RealEstate\Models\Property;
use App\Modules\RealEstate\Services\PropertyApprovalService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPropertyApprovalController extends Controller
{
    public function __construct(
        private PropertyApprovalService $propertyApprovalService,
    ) {}

    /**
     * Display pending properties.
     */
    public function pendingProperties(Request $request): View
    {
        $type = $request->get('type'); // sale, rent, pg
        $addedBy = $request->get('added_by'); // developer, agent

        $query = Property::withoutGlobalScope('approved')
            ->with(['user', 'developer', 'agent'])
            ->where('approval_status', 'pending');

        if ($type) {
            $query->where('type', $type);
        }

        if ($addedBy) {
            $query->where('added_by_role', $addedBy);
        }

        $properties = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total_pending' => Property::withoutGlobalScope('approved')->where('approval_status', 'pending')->count(),
            'sale_pending' => Property::withoutGlobalScope('approved')->where('approval_status', 'pending')->where('type', 'sale')->count(),
            'rent_pending' => Property::withoutGlobalScope('approved')->where('approval_status', 'pending')->where('type', 'rent')->count(),
            'pg_pending' => Property::withoutGlobalScope('approved')->where('approval_status', 'pending')->where('type', 'pg')->count(),
        ];

        return view('realestate::admin.properties.pending', compact('properties', 'stats', 'type', 'addedBy'));
    }

    /**
     * Display pending projects.
     */
    public function pendingProjects(Request $request): View
    {
        $status = $request->get('status'); // upcoming, ongoing, completed

        $query = DeveloperProject::with(['developer', 'developer.user'])
            ->where('approval_status', 'pending');

        if ($status) {
            $query->where('status', $status);
        }

        $projects = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total_pending' => DeveloperProject::where('approval_status', 'pending')->count(),
            'upcoming_pending' => DeveloperProject::where('approval_status', 'pending')->where('status', 'upcoming')->count(),
            'ongoing_pending' => DeveloperProject::where('approval_status', 'pending')->where('status', 'ongoing')->count(),
            'completed_pending' => DeveloperProject::where('approval_status', 'pending')->where('status', 'completed')->count(),
        ];

        return view('realestate::admin.projects.pending', compact('projects', 'stats', 'status'));
    }

    /**
     * Approve a property.
     */
    public function approveProperty(int $id): RedirectResponse
    {
        $result = $this->propertyApprovalService->approveProperty($id, auth()->id());

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Reject a property.
     */
    public function rejectProperty(Request $request, int $id): RedirectResponse
    {
        $reason = $request->input('reason');
        $result = $this->propertyApprovalService->rejectProperty($id, auth()->id(), $reason);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Approve a project.
     */
    public function approveProject(int $id): RedirectResponse
    {
        $result = $this->propertyApprovalService->approveProject($id, auth()->id());

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Reject a project.
     */
    public function rejectProject(Request $request, int $id): RedirectResponse
    {
        $reason = $request->input('reason');
        $result = $this->propertyApprovalService->rejectProject($id, auth()->id(), $reason);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Bulk approve properties.
     */
    public function bulkApproveProperties(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No properties selected.');
        }

        $result = $this->propertyApprovalService->bulkApprove($ids, auth()->id());

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Bulk approve projects.
     */
    public function bulkApproveProjects(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No projects selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $result = $this->propertyApprovalService->approveProject($id, auth()->id());
            if ($result['success']) {
                $count++;
            }
        }

        return redirect()->back()->with('success', $count . ' project(s) approved successfully.');
    }

    /**
     * Show property details.
     */
    public function showProperty(int $id): View
    {
        $property = Property::withoutGlobalScope('approved')
            ->with(['user', 'developer', 'agent', 'developerProject'])
            ->findOrFail($id);

        return view('realestate::admin.properties.show', compact('property'));
    }

    /**
     * Show project details.
     */
    public function showProject(int $id): View
    {
        $project = DeveloperProject::with(['developer', 'developer.user', 'properties'])
            ->findOrFail($id);

        return view('realestate::admin.projects.show', compact('project'));
    }

    /**
     * Display all properties with filters.
     */
    public function allProperties(Request $request): View
    {
        $status = $request->get('approval_status');
        $search = $request->get('search');

        $query = Property::withoutGlobalScope('approved')
            ->with(['user', 'developer', 'agent']);

        if ($status) {
            $query->where('approval_status', $status);
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $properties = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Property::withoutGlobalScope('approved')->count(),
            'pending' => Property::withoutGlobalScope('approved')->where('approval_status', 'pending')->count(),
            'approved' => Property::where('approval_status', 'approved')->count(),
            'rejected' => Property::withoutGlobalScope('approved')->where('approval_status', 'rejected')->count(),
        ];

        return view('realestate::admin.properties.index', compact('properties', 'stats', 'status', 'search'));
    }
}
