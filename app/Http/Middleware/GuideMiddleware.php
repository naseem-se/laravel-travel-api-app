<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuideMiddleware
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
        if ($user->role !== 'local_guide') {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only local guide can access this route.'
            ], 403);
        }

        return $next($request);
    }
}
