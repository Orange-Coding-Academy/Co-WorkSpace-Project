<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;


class UserController extends Controller
{
    public function index(Request $request)
   {
    $search = $request->input('search');
    $filter = $request->input('role');
    $status=$request->input('status');

    $sortBy = $request->input('sortBy', 'name');       
    $sortDirection = $request->input('sortDirection', 'asc');

           if (!in_array($sortBy, ['name', 'created_at'])) {
               $sortBy = 'name';
               }

              if (!in_array($sortDirection, ['asc', 'desc'])) {
                        $sortDirection = 'asc';
             }
    $users = User::whereIn('role', ['member', 'spaceManager'])
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })

        ->when($filter, function ($query, $filter) {
            $query->where('role', '=', $filter);
        })

       ->when($status !== null && $status !== '', function ($query) use ($status) {
              $query->where('is_active', '=', $status);
      })

      ->orderBy($sortBy, $sortDirection)
       ->paginate(5)
        ->withQueryString();

    return view('Admin.users',compact('users','search','filter','status','sortBy','sortDirection'));
   }


   public function deactivate(string $id) 

   {
       $user = User::findOrFail($id);
       $user->is_active = 0;
       $user->save();

       return redirect()->route('admin.users.index');



    }

    public function show(string $id){
        $user=User::findOrFail($id);
       // dd($user);
       $totalBookings = $user->bookings()->count();
       $totalMemberships = $user->memberships()->count();
       $totalWorkspaces=$user->managedWorkspaces()->count();
       $recentBookings=$user->bookings()->latest('created_at')->take(5)->get();
       $recentMemberships=$user->memberships()->latest('created_at')->take(5)->get();
       $recentWorkspaces=$user->managedWorkspaces()->latest('created_at')->take(5)->get();

    



      return view('Admin.userDetails', compact('user','totalBookings','totalMemberships','totalWorkspaces','recentBookings','recentMemberships','recentWorkspaces'));
    }
    
}
