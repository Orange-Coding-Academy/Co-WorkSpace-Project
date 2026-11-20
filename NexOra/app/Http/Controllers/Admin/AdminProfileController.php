<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function index()
    {
        $admin = User::where('id', Auth::id())
            ->where('role', 'admin')
            ->firstOrFail();

        return view('Admin.profile', compact('admin'));
    }

    public function updatePersonal(Request $request)
    {
        $admin = User::where('id', Auth::id())
            ->where('role', 'admin')
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        $admin->save();

        return redirect()
            ->route('admin.profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::where('id', Auth::id())
            ->where('role', 'admin')
            ->firstOrFail();

        // User model automatically hashes the password
        $admin->password = $request->password;

        $admin->save();

        return back()->with('success', 'Password updated successfully!');
    }
}
