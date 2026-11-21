<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
class ManagerProfileController extends Controller
{
    public function index()
    {
        $manager = User::where('id', Auth::id())
            ->where('role', 'spaceManager')
            ->firstOrFail();


        return view('Manager.profile', compact('manager'));
    }


    public function updatePersonal(Request $request)
    {
        $manager = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $manager->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->phone = $request->phone;
        if ($request->hasFile('avatar')) {
            $manager->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        $manager->save();
        return redirect()->route('manager.profile.index')
            ->with('success', 'Profile updated successfully!');

    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $manager = auth()->user();

        $manager->password = Hash::make($request->password);
        $manager->save();

        return back()->with('success', 'Password updated successfully!');
    }
}