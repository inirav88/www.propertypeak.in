<?php

namespace App\Modules\RealEstate\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPublicProfileAccess
{
    /**
     * Handle an incoming request.
     * Ensures public profiles are only visible for approved users.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the user from route parameter (either 'developer' or 'agent' slug)
        $slug = $request->route('slug');

        if (!$slug) {
            abort(404);
        }

        // Find user by slug - they must be approved to have a public profile
        $user = User::where('slug', $slug)
            ->where('status', User::STATUS_APPROVED)
            ->first();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile not found or not yet approved.',
                ], 404);
            }

            abort(404, 'Profile not found or not yet approved.');
        }

        // Attach user to request for controller use
        $request->attributes->set('profile_user', $user);

        return $next($request);
    }
}
