<?php

namespace App\Modules\RealEstate\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAgent
{
    /**
     * Handle an incoming request.
     * Combined middleware for Agent access (role + approval check).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')
                ->with('error', 'Please login to access the agent dashboard.');
        }

        // Check role
        if (!$user->isAgent()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access restricted to agents only.',
                ], 403);
            }

            return redirect()->route('home')
                ->with('error', 'Access restricted to agents only.');
        }

        // Check approval status
        if (!$user->isApproved()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your agent account is pending approval.',
                    'status' => $user->status,
                ], 403);
            }

            return redirect()->route('agent-portal.pending')
                ->with('warning', 'Your account is pending approval. Please wait for admin verification.');
        }

        return $next($request);
    }
}
