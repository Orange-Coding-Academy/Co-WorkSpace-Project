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
        $timezone = config('app.timezone', 'Asia/Amman');

        $now = Carbon::now($timezone);

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

        $dayStart = Carbon::parse($date, $timezone)->startOfDay();
        $dayEnd = $dayStart->copy()->addDay();

        $bookings = Booking::where('room_id', $room->id)
            ->where('start_date_time', '<', $dayEnd)
            ->where('end_date_time', '>', $dayStart)
            ->where(function ($query) use ($now) {
                $query->where('status', 'confirmed')
                    ->orWhere(function ($q) use ($now) {
                        $q->where('status', 'pending')
                          ->where('payment_expires_at', '>', $now);
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

            // Check whether another booking overlaps this slot
            $isBooked = $bookings->contains(function ($booking) use ($start, $end, $timezone) {

                $bookingStart = Carbon::parse(
                    $booking->start_date_time,
                    $timezone
                );

                $bookingEnd = Carbon::parse(
                    $booking->end_date_time,
                    $timezone
                );

                return $bookingStart->lt($end)
                    && $bookingEnd->gt($start);
            });

            // A slot that has already started cannot be booked
            $isPast = $start->lessThanOrEqualTo($now);

            $available = !$isBooked && !$isPast;

            $slots[] = [
                'start' => $start->format('H:i'),
                'end' => $end->format('H:i'),
                'available' => $available,
                'status' => $isBooked
                    ? 'booked'
                    : ($isPast ? 'past' : 'available'),
            ];
        }

        return response()->json([
            'date' => $date,
            'current_time' => $now->format('Y-m-d H:i:s'),
            'timezone' => $timezone,
            'slots' => $slots,
        ]);
    }
}