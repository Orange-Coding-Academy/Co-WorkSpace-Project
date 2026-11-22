<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    // Admin authorization
    // private function checkAdmin(): void
    // {
    //     abort_unless(
    //         auth()->check() && auth()->user()->role === 'admin',
    //         403
    //     );
    // }

    // Display all reviews
    public function index(Request $request)
    {
        // $this->checkAdmin();

        $status = $request->query('status', 'pending');

        $query = Review::with([
            'user:id,name,email',
            'workspace:id,name',
        ]);

        if ($status === 'pending') {
            $query->where('is_visible', false);
        } elseif ($status === 'approved') {
            $query->where('is_visible', true);
        }

        $reviews = $query->latest()->paginate(2)->withQueryString();

        $totalReviews = Review::count();

        $pendingReviews = Review::where('is_visible', false)->count();

        $approvedReviews = Review::where('is_visible', true)->count();

        return view('admin.reviews', compact(
            'reviews',
            'status',
            'totalReviews',
            'pendingReviews',
            'approvedReviews'
        ));
    }

    // Approve review
    public function approve($id)
    {
        // $this->checkAdmin();

        $review = Review::findOrFail($id);

        $review->update([
            'is_visible' => true,
        ]);

        return back()->with('success', 'Review approved successfully.');
    }

    // Hide review
    public function hide($id)
    {
        // $this->checkAdmin();

        $review = Review::findOrFail($id);

        $review->update([
            'is_visible' => false,
        ]);

        return back()->with('success', 'Review hidden successfully.');
    }

    // Delete review permanently
    public function destroy($id)
    {
        // $this->checkAdmin();

        $review = Review::findOrFail($id);

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
