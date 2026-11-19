<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ManagerReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user')
            ->latest()
            ->paginate(10);

        return view('Manager.index', compact('reviews'));
    }

    public function show($id)
    {
        $review = Review::with('user')
            ->findOrFail($id);

        return view('Manager.show', compact('review'));
    }
}