<?php

namespace App\Modules\RealEstate\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotDeveloper
{
    /**
     * Handle an incoming request.
     * Combined middleware for Developer access (role + approval check).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')
                ->with('error', 'Please login to access the developer dashboard.');
        }

        // Check role
        if (!$user->isDeveloper()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access restricted to developers only.',
                ], 403);
            }

            return redirect()->route('home')
                ->with('error', 'Access restricted to developers only.');
        }

        // Check approval status
        if (!$user->isApproved()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your developer account is pending approval.',
                    'status' => $user->status,
                ], 403);
            }

            return redirect()->route('developer-portal.pending')
                ->with('warning', 'Your account is pending approval. Please wait for admin verification.');
        }

        return $next($request);
    }
}
