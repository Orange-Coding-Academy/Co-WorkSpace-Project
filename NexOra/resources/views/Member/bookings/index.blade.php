
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">My Bookings</h3>
            <p class="text-muted">Manage and track your reservations.</p>
        </div>
        {{-- <a href="{{ route('member.checkout.index') }}" class="btn btn-primary">
            New Booking
        </a> --}}
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
        @foreach([
            'all' => 'All',
            'upcoming' => 'Upcoming',
            'pending' => 'Pending',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ] as $key => $label)

            <li class="nav-item">
                <a class="nav-link {{ request('tab', 'all') === $key ? 'active' : '' }}"
                   href="{{ route('member.bookings.index', ['tab' => $key]) }}">
                    {{ $label }}
                </a>
            </li>

        @endforeach
    </ul>

    <!-- Filters -->
    <form method="GET" class="card card-body mb-4">
        <input type="hidden" name="tab" value="{{ request('tab', 'all') }}">

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date"
                       value="{{ request('date') }}"
                       class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Room</label>
                <select name="room_id" class="form-select">
                    <option value="">All Rooms</option>

                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}"
                            {{ request('room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-primary">Filter</button>
                <a href="{{ route('member.bookings.index') }}"
                   class="btn btn-outline-secondary">Reset</a>
            </div>

        </div>
    </form>

    <!-- Bookings -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Room</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($bookings as $booking)

                        <tr>
                            <td>#{{ $booking->id }}</td>

                            <td>{{ $booking->room?->name ?? 'Room unavailable' }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($booking->start_date_time)->format('h:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($booking->end_date_time)->format('h:i A') }}
                            </td>

                            <td>
                                ${{ number_format($booking->total_price, 2) }}
                            </td>

                            <td>
                                {{ ucfirst($booking->payment?->status ?? 'Unpaid') }}
                            </td>

                            <td>
                                <span class="badge
                                    {{ $booking->status === 'confirmed' ? 'bg-success' :
                                      ($booking->status === 'pending' ? 'bg-warning text-dark' :
                                      ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-secondary')) }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex gap-2">

                                    <a href="{{ route('member.bookings.show', $booking->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>

                                    @if($booking->status === 'pending' &&
                                        \Carbon\Carbon::parse($booking->start_date_time)->isFuture())

                                        <form method="POST"
                                              action="{{ route('member.bookings.cancel', $booking->id) }}"
                                              onsubmit="return confirm('Are you sure you want to cancel this booking?')">

                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-sm btn-outline-danger">
                                                Cancel
                                            </button>
                                        </form>

                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <h5>No bookings found</h5>
                                <p class="text-muted">
                                    You don't have any bookings in this category.
                                </p>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>
            </div>

            {{ $bookings->links() }}

        </div>
    </div>

</div>

</body>
</html>
