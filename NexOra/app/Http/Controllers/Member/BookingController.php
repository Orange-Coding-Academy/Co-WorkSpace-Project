<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'start_date_time' => ['required', 'date', 'after:now'],
            'duration' => ['required', 'integer', 'min:1', 'max:168'],
        ]);

        $start = Carbon::parse($validated['start_date_time']);
        $duration = (int) $validated['duration'];
        $end = $start->copy()->addHours($duration);

        // Check room and workspace status
        $roomIsPublic = Room::query()
            ->whereKey($room->id)
            ->where('is_active', true)
            ->whereHas('workspace', function ($query) {
                $query->where('is_active', true)
                      ->where('approval_status', 'approved');
            })
            ->exists();

        abort_unless($roomIsPublic, 404);

        // Save booking intent for guests
        if (!Auth::check()) {
            session([
                'booking_intent' => [
                    'room_id' => $room->id,
                    'start_date_time' => $start->toDateTimeString(),
                    'end_date_time' => $end->toDateTimeString(),
                    'duration' => $duration,
                ],
            ]);

            return redirect()->route('login');
        }

        // Check availability
        $hasConflict = Booking::where('room_id', $room->id)
            ->where('start_date_time', '<', $end)
            ->where('end_date_time', '>', $start)
            ->where(function ($query) {
                $query->where('status', 'confirmed')
                    ->orWhere(function ($q) {
                        $q->where('status', 'pending')
                          ->where('payment_expires_at', '>', now());
                    });
            })
            ->exists();

        if ($hasConflict) {
            return back()->withErrors([
                'booking' => 'This time is no longer available. Please choose another time.',
            ]);
        }

        // Calculate booking total
        $total = $room->hourly_price * $duration;

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'start_date_time' => $start,
            'end_date_time' => $end,
            'total_price' => $total,
            'status' => 'pending',
            'payment_expires_at' => now()->addHours(8),
        ]);

        // Redirect to existing checkout page
        return redirect()
            ->route('member.bookings.checkout', $booking->id)
            ->with(
                'success',
                'Your booking request is pending. It will be confirmed once payment is completed.'
            );
    }
}