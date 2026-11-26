<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
//use App\Models\Booking;
use Illuminate\Http\Request;

class MemberReviewController extends Controller
{
    public function index()
{
    $member = auth()->user();

    // Get member reviews
    $reviews = $member->reviews()
        ->with('workspace')
        ->get();

    // Get workspaces with completed bookings for this member
    $workspaces = \App\Models\Workspace::whereHas('bookings', function ($query) use ($member) {
        $query->where('user_id', $member->id)
              ->where('status', 'completed');
    })->get();

    return view('member.reviews', compact(
        'member',
        'reviews',
        'workspaces'
    ));
}
    public function createreview()
{
    $member = auth()->user();
  //  $member = \App\Models\User::where('role', 'member')->firstOrFail();

    $bookings = $member->bookings()
        ->where('status', 'completed')
        ->with('room.workspace')
        ->get();

    $workspaces = $bookings
        ->map(function ($booking) {
            return $booking->room?->workspace;
        })
        ->filter()
        ->unique('id')
        ->values();

    return view('member.createreview', compact('member', 'workspaces'));
}


    public function store(Request $request)
       {
    $data = $request->validate([
        'workspace_id' => 'required|exists:workspaces,id',
        'rating' => 'required|integer|between:1,5',
        'comment' => 'nullable|string',
    ]);

   // $member = \App\Models\User::where('role', 'member')->firstOrFail();
       $member=auth()->user();
    $allowed = $member->bookings()
        ->where('status', 'completed')
        ->whereHas('room', function ($query) use ($data) {
            $query->where('workspace_id', $data['workspace_id']);
        })
        ->exists();

    if (!$allowed) {
        return back()->withErrors([
            'workspace_id' => 'You cannot review this workspace.'
        ])->withInput();
    }

    $member->reviews()->create($data);

    return redirect()->route('member.reviews.index')
        ->with('success', 'Review added successfully!');
}



public function edit($id)
{
    
   // $member = \App\Models\User::where('role', 'member')->firstOrFail();
       $member=auth()->user();
    // Find only a review owned by this member
    $review = $member->reviews()->findOrFail($id);

    return view('member.editreview', compact('review'));
}

public function update(Request $request, $id)
{
    $data = $request->validate([
        'rating' => 'required|integer|between:1,5',
        'comment' => 'nullable|string',
    ]);

 //   $member = \App\Models\User::where('role', 'member')->firstOrFail();
       $member=auth()->user();
    $review = $member->reviews()->findOrFail($id);

    $review->update([
        'rating' => $data['rating'],
        'comment' => $data['comment'] ?? null,
        'is_visible' => false,
    ]);

    return redirect()
        ->route('member.reviews.index')
        ->with('success', 'Review updated successfully!');
}


public function destroy($id)
{
   // $member = \App\Models\User::where('role', 'member')->firstOrFail();
       $member=auth()->user();
    // Find only a review belonging to this member
    $review = $member->reviews()->findOrFail($id);

    // Delete the review
    $review->delete();

    return redirect()
        ->route('member.reviews.index')
        ->with('success', 'Review deleted successfully!');
}

}
