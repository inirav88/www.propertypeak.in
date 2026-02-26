<?php

namespace App\Modules\RealEstate\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|array  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')
                ->with('error', 'Please login to access this area.');
        }

        // Support both single role and multiple roles
        $allowedRoles = is_array($roles) ? $roles : [$roles];

        // Flatten array in case roles were passed as string "admin|developer"
        $flattenedRoles = [];
        foreach ($allowedRoles as $role) {
            if (str_contains($role, '|')) {
                $flattenedRoles = array_merge($flattenedRoles, explode('|', $role));
            } else {
                $flattenedRoles[] = $role;
            }
        }

        if (!in_array($user->role, $flattenedRoles, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You do not have permission to access this resource.',
                ], 403);
            }

            // Redirect based on user role
            return $this->redirectBasedOnRole($user)
                ->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }

    /**
     * Redirect user to their appropriate dashboard based on role.
     */
    private function redirectBasedOnRole(User $user): \Illuminate\Http\RedirectResponse
    {
        return match ($user->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_DEVELOPER => redirect()->route('developer-portal.dashboard'),
            User::ROLE_AGENT => redirect()->route('agent-portal.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
