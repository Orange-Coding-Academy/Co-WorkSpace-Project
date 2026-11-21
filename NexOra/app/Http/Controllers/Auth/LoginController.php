<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // 1. Check if account is active
        if (!$user->is_active) {
            return $this->logoutWithError(
                $request,
                'Your account is inactive.'
            );
        }

        // 2. Redirect according to user role
        switch ($user->role) {

            case 'admin':
                return redirect()->route('admin.AdminDashboard');

            case 'spaceManager':

                // Get manager's workspace
                $workspace = $user->managedWorkspaces()->latest()->first();

                // No workspace found
                if (!$workspace) {
                    return $this->logoutWithError(
                        $request,
                        'No workspace application was found for your account.'
                    );
                }

                // Keep the manager signed in so they can finish setting up the workspace.
                if ($workspace->approval_status === 'pending') {
                    return redirect()->route('manager.workspaces.details', [
                        'workspaceId' => $workspace->id,
                    ])->with('warning', 'Your workspace is waiting for admin approval.');
                }

                // Application is rejected
                if ($workspace->approval_status === 'rejected') {
                    return $this->logoutWithError(
                        $request,
                        'Your workspace application has been rejected. Please contact the administrator.'
                    );
                }

                // Application is approved
                if ($workspace->approval_status === 'approved') {
                  return redirect()->route('manager.dashboard');
                }

                // Unknown approval status
                return $this->logoutWithError(
                    $request,
                    'Your workspace application has an invalid status.'
                );

            case 'member':
                return redirect()->route('member.bookings.index');

            default:
                return $this->logoutWithError(
                    $request,
                    'Your account role is not valid.'
                );
        }
    }

    /**
     * Logout user and return an error message.
     */
    private function logoutWithError(Request $request, string $message)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors([
                'email' => $message,
            ]);
    }
}
