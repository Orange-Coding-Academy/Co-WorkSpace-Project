<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberProfileController extends Controller
{
public function index()
{
    //$member = \App\Models\User::findOrFail(1);
             $member = auth()->user();

    return view('Member.profile', compact('member'));
}
    public function updatePersonal(Request $request){
        $member=auth()->user();
        $request->validate([
            'name'=> 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
              'phone'=>'nullable|string|max:20',
            'avatar'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'


        ]);
        $member->name=$request->name;
        $member->email=$request->email;
        $member->phone=$request->phone;
       if ($request->hasFile('avatar'))
         {
             $member->avatar = $request->file('avatar')->store('avatars', 'public');
         }
        $member->save();

        return redirect()
             ->route('member.profile.index')
              ->with('success', 'Profile updated successfully!');


    }


    public function updatePassword(Request $request)
     {
          $member=auth()->user();
          $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',

          ]);
          
          $member->password = Hash::make($request->password);
          $member->save();
          return back()->with('success', 'Password updated successfully!');


     }
}
