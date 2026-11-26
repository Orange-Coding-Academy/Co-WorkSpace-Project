<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('user_id', Auth::id());

        // Tabs
        if ($request->tab === 'upcoming') {
            $query->where('status', 'confirmed')
                  ->where('start_date_time', '>', now());
        }

        elseif ($request->tab === 'pending') {
            $query->where('status', 'pending');
        }

        elseif ($request->tab === 'completed') {
            $query->where('end_date_time', '<=', now())
                  ->where('status', 'confirmed');
        }

        elseif ($request->tab === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('start_date_time', $request->date);
        }

        // Workspace / room filter
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $bookings = $query->latest()->paginate(5)->withQueryString();

        // Load related room and payment
        $bookings->getCollection()->transform(function ($booking) {
            $booking->room = Room::find($booking->room_id);
            $booking->payment = Payment::where('booking_id', $booking->id)
                ->where('user_id', Auth::id())
                ->latest()
                ->first();

            return $booking;
        });

        $rooms = Room::whereIn(
            'id',
            Booking::where('user_id', Auth::id())
                ->select('room_id')
        )->get();

        return view('Member.bookings.index', compact(
            'bookings',
            'rooms'
        ));
    }


    public function show($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->findOrFail($id);

        $room = Room::findOrFail($booking->room_id);

        $payment = Payment::where('booking_id', $booking->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        return view('Member.bookings.show', compact(
            'booking',
            'room',
            'payment'
        ));
    }


    public function cancel($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->findOrFail($id);

        // Only pending bookings can be cancelled here.
        // Paid bookings require a separate refund policy.
        if (
            $booking->status !== 'pending' ||
            Carbon::parse($booking->start_date_time)->isPast()
        ) {
            return back()->with(
                'error',
                'This booking cannot be cancelled.'
            );
        }

        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()
            ->route('member.bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}
