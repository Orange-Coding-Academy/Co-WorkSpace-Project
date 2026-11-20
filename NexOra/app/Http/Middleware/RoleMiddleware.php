<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check account status
        if (!$user->is_active) {
            return $this->logoutWithError(
                $request,
                'Your account is inactive.'
            );
        }

        // Check role
        if ($user->role !== $role) {
            abort(403, 'You are not authorized to access this page.');
        }

        // Check manager workspace approval
        if ($role === 'spaceManager') {

            $workspace = $user->managedWorkspaces()
                ->latest()
                ->first();

            if (!$workspace) {
                return $this->logoutWithError(
                    $request,
                    'No workspace was found for your account.'
                );
            }

            if ($workspace->approval_status !== 'approved') {
                $message = $workspace->approval_status === 'pending'
                    ? 'Your workspace is waiting for admin approval.'
                    : 'Your workspace application is not approved.';

                return $this->logoutWithError($request, $message);
            }
        }

        return $next($request);
    }

    private function logoutWithError(
        Request $request,
        string $message
    ): Response {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors([
                'email' => $message,
            ]);
    }
}