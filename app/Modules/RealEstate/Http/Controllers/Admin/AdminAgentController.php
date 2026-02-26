<?php

namespace App\Modules\RealEstate\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\RealEstate\Models\Agent;
use App\Modules\RealEstate\Repositories\AgentRepository;
use App\Modules\RealEstate\Notifications\AccountApproved;
use App\Modules\RealEstate\Notifications\AccountRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminAgentController extends Controller
{
    public function __construct(
        private AgentRepository $agentRepository,
    ) {}

    /**
     * Display list of all agents.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Agent::with('user');

        if ($status) {
            $query->whereHas('user', function ($q) use ($status): void {
                $q->where('status', $status);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        }

        $agents = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Agent::count(),
            'pending' => Agent::whereHas('user', fn($q) => $q->where('status', 'pending'))->count(),
            'approved' => Agent::whereHas('user', fn($q) => $q->where('status', 'approved'))->count(),
            'rejected' => Agent::whereHas('user', fn($q) => $q->where('status', 'rejected'))->count(),
        ];

        return view('realestate::admin.agents.index', compact('agents', 'stats', 'status', 'search'));
    }

    /**
     * Display pending agents.
     */
    public function pending(): View
    {
        $agents = Agent::with('user')
            ->whereHas('user', function ($q): void {
                $q->where('status', 'pending');
            })
            ->latest()
            ->paginate(20);

        return view('realestate::admin.agents.pending', compact('agents'));
    }

    /**
     * Show agent details.
     */
    public function show(int $id): View
    {
        $agent = Agent::with(['user', 'properties'])->findOrFail($id);

        return view('realestate::admin.agents.show', compact('agent'));
    }

    /**
     * Approve an agent account.
     */
    public function approve(int $id): RedirectResponse
    {
        $agent = Agent::with('user')->findOrFail($id);
        $user = $agent->user;

        if ($user->isApproved()) {
            return redirect()->back()->with('info', 'Agent is already approved.');
        }

        $user->approve(Auth::id());

        // Send notification
        $user->notify(new AccountApproved('agent'));

        return redirect()->back()->with('success', 'Agent approved successfully.');
    }

    /**
     * Reject an agent account.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $agent = Agent::with('user')->findOrFail($id);
        $user = $agent->user;

        if ($user->isRejected()) {
            return redirect()->back()->with('info', 'Agent is already rejected.');
        }

        $reason = $request->input('reason');
        $user->reject(Auth::id());

        // Send notification
        $user->notify(new AccountRejected('agent', $reason));

        return redirect()->back()->with('success', 'Agent rejected successfully.');
    }

    /**
     * Bulk approve agents.
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No agents selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $agent = Agent::with('user')->find($id);
            if ($agent && !$agent->user->isApproved()) {
                $agent->user->approve(Auth::id());
                $agent->user->notify(new AccountApproved('agent'));
                $count++;
            }
        }

        return redirect()->back()->with('success', $count . ' agent(s) approved successfully.');
    }

    /**
     * Suspend an agent account.
     */
    public function suspend(int $id): RedirectResponse
    {
        $agent = Agent::with('user')->findOrFail($id);
        $user = $agent->user;

        $user->suspend();

        return redirect()->back()->with('success', 'Agent suspended successfully.');
    }

    /**
     * Delete an agent.
     */
    public function destroy(int $id): RedirectResponse
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent deleted successfully.');
    }
}
