<?php

namespace App\Modules\RealEstate\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApprovedAccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $redirectTo  Custom redirect route name
     */
    public function handle(Request $request, Closure $next, ?string $redirectTo = null): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')
                ->with('error', 'Please login to access this area.');
        }

        // Admins don't need approval
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check account status
        if (!$user->isApproved()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $this->getStatusMessage($user),
                    'status' => $user->status,
                ], 403);
            }

            $redirectRoute = $redirectTo ?? $this->getRedirectRoute($user);

            return redirect()->route($redirectRoute)
                ->with('warning', $this->getStatusMessage($user));
        }

        return $next($request);
    }

    /**
     * Get status-specific message.
     */
    private function getStatusMessage(User $user): string
    {
        return match ($user->status) {
            User::STATUS_PENDING => 'Your account is pending approval. Please wait for admin verification.',
            User::STATUS_REJECTED => 'Your account has been rejected. Please contact support for more information.',
            User::STATUS_SUSPENDED => 'Your account has been suspended. Please contact support.',
            default => 'Your account is not approved.',
        };
    }

    /**
     * Get redirect route based on user role and status.
     */
    private function getRedirectRoute(User $user): string
    {
        if ($user->isDeveloper()) {
            return 'developer.pending';
        }

        if ($user->isAgent()) {
            return 'agent.pending';
        }

        return 'home';
    }
}
