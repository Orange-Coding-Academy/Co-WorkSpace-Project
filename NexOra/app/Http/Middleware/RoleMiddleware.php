<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $userRole = $request->attributes->get('auth_role');

        if (!$userRole || !is_string($userRole)) {
            return response()->json([
                'error' => 'Unauthenticated.',
            ], 401);
        }

        if ($userRole !== $role) {
            return response()->json([
                'error' => 'You are not authorized to access this resource.',
            ], 403);
        }

        return $next($request);
    }
}
