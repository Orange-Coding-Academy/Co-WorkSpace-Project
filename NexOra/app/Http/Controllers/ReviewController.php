<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
 
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        //  abort_unless(auth()->check(), 403);

        // abort_unless(auth()->user()->role === 'member', 403);

        $validated = $request->validate([
            'workspace_id' => ['required', 'exists:workspaces,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'workspace_id' => $validated['workspace_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'is_visible' => false,
        ]);

        return back()->with(
            'success',
            'Your review has been submitted and is waiting for admin approval.'
        );
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
