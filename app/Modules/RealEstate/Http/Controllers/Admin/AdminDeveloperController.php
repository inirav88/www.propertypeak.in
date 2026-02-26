<?php

namespace App\Modules\RealEstate\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\RealEstate\Models\Developer;
use App\Modules\RealEstate\Repositories\DeveloperRepository;
use App\Modules\RealEstate\Notifications\AccountApproved;
use App\Modules\RealEstate\Notifications\AccountRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminDeveloperController extends Controller
{
    public function __construct(
        private DeveloperRepository $developerRepository,
    ) {}

    /**
     * Display list of all developers.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Developer::with('user');

        if ($status) {
            $query->whereHas('user', function ($q) use ($status): void {
                $q->where('status', $status);
            });
        }

        if ($search) {
            $query->where('company_name', 'like', '%' . $search . '%');
        }

        $developers = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Developer::count(),
            'pending' => Developer::whereHas('user', fn($q) => $q->where('status', 'pending'))->count(),
            'approved' => Developer::whereHas('user', fn($q) => $q->where('status', 'approved'))->count(),
            'rejected' => Developer::whereHas('user', fn($q) => $q->where('status', 'rejected'))->count(),
        ];

        return view('realestate::admin.developers.index', compact('developers', 'stats', 'status', 'search'));
    }

    /**
     * Display pending developers.
     */
    public function pending(): View
    {
        $developers = Developer::with('user')
            ->whereHas('user', function ($q): void {
                $q->where('status', 'pending');
            })
            ->latest()
            ->paginate(20);

        return view('realestate::admin.developers.pending', compact('developers'));
    }

    /**
     * Show developer details.
     */
    public function show(int $id): View
    {
        $developer = Developer::with(['user', 'projects'])->findOrFail($id);

        return view('realestate::admin.developers.show', compact('developer'));
    }

    /**
     * Approve a developer account.
     */
    public function approve(int $id): RedirectResponse
    {
        $developer = Developer::with('user')->findOrFail($id);
        $user = $developer->user;

        if ($user->isApproved()) {
            return redirect()->back()->with('info', 'Developer is already approved.');
        }

        $user->approve(Auth::id());

        // Send notification
        $user->notify(new AccountApproved('developer'));

        return redirect()->back()->with('success', 'Developer approved successfully.');
    }

    /**
     * Reject a developer account.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $developer = Developer::with('user')->findOrFail($id);
        $user = $developer->user;

        if ($user->isRejected()) {
            return redirect()->back()->with('info', 'Developer is already rejected.');
        }

        $reason = $request->input('reason');
        $user->reject(Auth::id());

        // Send notification
        $user->notify(new AccountRejected('developer', $reason));

        return redirect()->back()->with('success', 'Developer rejected successfully.');
    }

    /**
     * Bulk approve developers.
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No developers selected.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $developer = Developer::with('user')->find($id);
            if ($developer && !$developer->user->isApproved()) {
                $developer->user->approve(Auth::id());
                $developer->user->notify(new AccountApproved('developer'));
                $count++;
            }
        }

        return redirect()->back()->with('success', $count . ' developer(s) approved successfully.');
    }

    /**
     * Suspend a developer account.
     */
    public function suspend(int $id): RedirectResponse
    {
        $developer = Developer::with('user')->findOrFail($id);
        $user = $developer->user;

        $user->suspend();

        return redirect()->back()->with('success', 'Developer suspended successfully.');
    }

    /**
     * Delete a developer.
     */
    public function destroy(int $id): RedirectResponse
    {
        $developer = Developer::findOrFail($id);
        $developer->delete();

        return redirect()->route('admin.developers.index')
            ->with('success', 'Developer deleted successfully.');
    }
}
