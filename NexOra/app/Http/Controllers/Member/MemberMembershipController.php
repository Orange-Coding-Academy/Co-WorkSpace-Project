<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Membership;

class MemberMembershipController extends Controller
{
    public function index()
    {
        $member = auth()->user();
//$member = \App\Models\User::findOrFail(1);
        $memberships = Membership::where('user_id', $member->id)
            ->with('plan.workspace')
            ->get();

        return view('Member.memberships', compact('memberships'));
    }
}