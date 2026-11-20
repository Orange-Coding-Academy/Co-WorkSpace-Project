<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipPlan;

class MemberMembershipController extends Controller
{
    public function index()
    {
        $member = auth()->user();
      $memberships = Membership::where('user_id', $member->id)
    ->whereIn('status', ['active', 'cancelled'])
    ->with('plan.workspace')
    ->get();

        $availablePlans = MembershipPlan::where('is_active', true)
            ->with('workspace')
            ->get();

        return view('Member.memberships', compact('memberships', 'availablePlans'));
    }


    public function cancel($id)
{
    $membership = Membership::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    if ($membership->status !== 'active') {
        return redirect()->route('member.memberships.index')
            ->with('error', 'Only active memberships can be cancelled.');
    }

    $membership->status = 'cancelled';
    $membership->save();

    return redirect()->route('member.memberships.index')
        ->with('success', 'Membership cancelled successfully.');
}
}