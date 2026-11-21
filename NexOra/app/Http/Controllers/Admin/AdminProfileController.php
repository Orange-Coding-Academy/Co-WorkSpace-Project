<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;

        if ($request->hasFile('avatar')) {
            $admin->avatar = $request->file('avatar')
                ->store('avatars', 'public');
        }

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

        $admin->password = Hash::make($request->password);
        $admin->save();

        return back()->with('success', 'Password updated successfully!');
    }
}
