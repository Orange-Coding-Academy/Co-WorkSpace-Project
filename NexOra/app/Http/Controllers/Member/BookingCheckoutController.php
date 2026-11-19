<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingCheckoutController extends Controller
{
    public function index($id)
    {
        // Retrieve only this member's pending booking
        $booking = Booking::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        // Retrieve the room belonging to this booking
        $room = $booking->room;

        if (!$room) {
            abort(404);
        }

        // Use the existing booking dates and saved total
        $start = $booking->start_date_time;
        $end = $booking->end_date_time;
        $hours = (strtotime($end) - strtotime($start)) / 3600;
        $total = $booking->total_price;

        return view('Member.bookings.checkout', compact(
            'booking',
            'room',
            'start',
            'end',
            'hours',
            'total'
        ));
    }
}
