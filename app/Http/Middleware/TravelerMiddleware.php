<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TravelerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();  // Auth user

        // If not logged in
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized User'
            ], 401);
        }

        // If user role is not agency
        if ($user->role !== 'traveler') {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only traveler can access this route.'
            ], 403);
        }

        return $next($request);
    }
}
