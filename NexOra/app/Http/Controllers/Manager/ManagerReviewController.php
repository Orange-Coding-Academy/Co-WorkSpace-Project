<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ManagerReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'workspace'])
            ->whereHas('workspace', function ($query) {
                $query->where('manager_id', Auth::id());
            })
            ->latest()
            ->paginate(5);

        return view('Manager.index', compact('reviews'));
    }

    public function show($id)
    {
        $review = Review::with(['user', 'workspace'])
            ->whereHas('workspace', function ($query) {
                $query->where('manager_id', Auth::id());
            })
            ->findOrFail($id);

        return view('Manager.show', compact('review'));
    }
}