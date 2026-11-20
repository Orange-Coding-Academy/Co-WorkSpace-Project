<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomAvailabilityController extends Controller
{
    public function index(Request $request, Room $room)
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $room = Room::whereKey($room->id)
            ->where('is_active', true)
            ->whereHas('workspace', function ($q) {
                $q->where('is_active', true)
                  ->where('approval_status', 'approved');
            })
            ->firstOrFail();

        $date = $validated['date'];

        $dayStart = Carbon::parse($date)->startOfDay();
        $dayEnd = $dayStart->copy()->addDay();

        $bookings = Booking::where('room_id', $room->id)
            ->where('start_date_time', '<', $dayEnd)
            ->where('end_date_time', '>', $dayStart)
            ->where(function ($query) {
                $query->where('status', 'confirmed')
                    ->orWhere(function ($q) {
                        $q->where('status', 'pending')
                          ->where('payment_expires_at', '>', now());
                    });
            })
            ->get([
                'start_date_time',
                'end_date_time',
                'status'
            ]);

        $slots = [];

        for ($hour = 0; $hour < 24; $hour++) {

            $start = $dayStart->copy()->addHours($hour);
            $end = $start->copy()->addHour();

            $isBooked = $bookings->contains(function ($booking) use ($start, $end) {

                return $booking->start_date_time < $end
                    && $booking->end_date_time > $start;
            });

            $isPast = $start->lessThanOrEqualTo(now());

            $slots[] = [
                'start' => $start->format('H:i'),
                'end' => $end->format('H:i'),
                'available' => !$isBooked && !$isPast,
                'status' => $isBooked
                    ? 'booked'
                    : ($isPast ? 'past' : 'available'),
            ];
        }

        return response()->json([
            'date' => $date,
            'slots' => $slots,
        ]);
    }
}
