<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Workspace;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display manager bookings.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:pending,confirmed,cancelled,completed,expired',
            'payment_status' => 'nullable|in:pending,paid,failed',
            'workspace_id' => 'nullable|integer|exists:workspaces,id',
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        // Temporary testing only - restore auth()->id() before delivery
        //$managerId = 2;
       $managerId = auth()->id();

        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');
        $workspaceId = $request->input('workspace_id');
        $date = $request->input('date');

        $bookings = Booking::with([
            'user',
            'room.workspace',
            'payments'
        ])
            ->whereHas('room.workspace', function ($query) use ($managerId) {
                $query->where('manager_id', $managerId);
            })

            // Filter by booking status
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })

            // Filter by latest payment status
            ->when($paymentStatus, function ($query, $paymentStatus) {
                $query->where(function ($q) use ($paymentStatus) {

                    $q->whereHas('payments', function ($paymentQuery) use ($paymentStatus) {
                        $paymentQuery
                            ->where('status', $paymentStatus)
                            ->where('id', function ($subQuery) {
                                $subQuery->selectRaw('MAX(id)')
                                    ->from('payments')
                                    ->whereColumn('booking_id', 'bookings.id');
                            });
                    });

                    // Bookings without payment records count as pending
                    if ($paymentStatus === 'pending') {
                        $q->orWhereDoesntHave('payments');
                    }
                });
            })

            // Filter by workspace
            ->when($workspaceId, function ($query, $workspaceId) {
                $query->whereHas('room', function ($roomQuery) use ($workspaceId) {
                    $roomQuery->where('workspace_id', $workspaceId);
                });
            })

            // Filter by booking date
            ->when($date, function ($query, $date) {
                $query->whereDate('start_date_time', $date);
            })

            ->get();

        $workspaces = Workspace::where('manager_id', $managerId)->get();

return view('Manager.bookings', compact('bookings', 'workspaces'));    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource.
     */
    public function store(StoreBookingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Booking $booking)
    {
        //
    }

    /**
     * Cancel a manager booking.
     */
    public function cancelManagerBooking(Request $request, Booking $booking)
    {
        $managerId = auth()->id();

        abort_unless($managerId, 403);

        // Check booking ownership
        $booking->load('room.workspace');

        abort_unless(
            $booking->room?->workspace?->manager_id == $managerId,
            403
        );

        // Only pending or confirmed bookings can be cancelled
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors([
                'booking' => 'This booking cannot be cancelled.',
            ]);
        }

        // Do not cancel paid bookings until refund policy is integrated
        if ($booking->payments()
            ->where('status', 'paid')
            ->exists()) {
            return back()->withErrors([
                'booking' => 'Paid bookings require payment team review before cancellation.',
            ]);
        }

        // Update booking status only
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()
            ->route('manager.bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}
