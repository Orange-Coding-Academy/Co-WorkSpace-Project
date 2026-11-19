{{--
Manager Bookings Routes
use App\Http\Controllers\BookingController;

Route::get('/bookings', [BookingController::class, 'index'])
    ->name('bookings.index');

Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancelManagerBooking'])
    ->name('bookings.cancel');

--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Bookings | NexOra</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1e293b;
        }

        .container {
            max-width: 1250px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        h1 { margin: 0 0 8px; font-size: 28px; }
        .subtitle { color: #64748b; margin: 0; }

        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 18px rgba(15,23,42,.04);
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        select, input, textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
            background: white;
        }

        .filter-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-primary { background: #2563eb; color: white; }
        .btn-secondary { background: #eef2f7; color: #334155; }
        .btn-outline {
            background: white;
            border: 1px solid #cbd5e1;
            color: #2563eb;
        }
        .btn-danger { background: #dc2626; color: white; }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-header h2 { margin: 0; font-size: 19px; }
        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        th, td {
            padding: 15px 12px;
            border-bottom: 1px solid #edf2f7;
        }

        td { font-size: 13px; }
        tr:last-child td { border-bottom: none; }

        .member-name { font-weight: 600; }
        .muted { color: #64748b; font-size: 12px; margin-top: 4px; }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .pending { background: #fef3c7; color: #92400e; }
        .confirmed, .paid { background: #dcfce7; color: #166534; }
        .cancelled, .failed { background: #fee2e2; color: #991b1b; }
        .completed { background: #dbeafe; color: #1d4ed8; }
        .expired { background: #e2e8f0; color: #475569; }

        .actions { display: flex; gap: 8px; }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.55);
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 100;
        }

        .modal:target { display: flex; }

        .modal-content {
            background: white;
            border-radius: 14px;
            width: 100%;
            max-width: 520px;
            padding: 26px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 { margin: 0; font-size: 21px; }

        .close {
            text-decoration: none;
            font-size: 25px;
            color: #64748b;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 13px 0;
            border-bottom: 1px solid #edf2f7;
            gap: 16px;
        }

        .detail-row span:first-child { color: #64748b; }
        .detail-row strong { text-align: right; overflow-wrap: anywhere; }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .btn-link {
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        @media (max-width: 850px) {
            .filters { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 520px) {
            .filters { grid-template-columns: 1fr; }
            .page-header { align-items: flex-start; }
            .container { padding: 0 12px; margin-top: 24px; }
            .card { padding: 16px; }
        }
    </style>
</head>
<body>

<div class="container">
    <header class="page-header">
        <div>
            <h1>Manager Bookings</h1>
            <p class="subtitle">View and manage bookings across your workspaces.</p>
        </div>
    </header>
@if(session('success'))
    <div class="card" style="color: green;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="color: red;">
        {{ $errors->first() }}
    </div>
@endif
    <!-- Filters -->
    <section class="card">
<form action="{{ route('manager.bookings.index') }}" method="GET">
            <div class="filters">
                  <div>
    <label for="status">Booking Status</label>

    <select id="status" name="status">
        <option value="">All Statuses</option>

        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
            Pending
        </option>

        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
            Confirmed
        </option>

        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
            Cancelled
        </option>

        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
            Completed
        </option>

        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
            Expired
        </option>
    </select>
</div>

               <div>
    <label for="payment_status">Payment Status</label>

    <select id="payment_status" name="payment_status">
        <option value="">All Payments</option>

        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
            Pending
        </option>

        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
            Paid
        </option>

        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
            Failed
        </option>
    </select>
</div>

         <div>
    <label for="workspace_id">Workspace</label>

    <select id="workspace_id" name="workspace_id">
        <option value="">All Workspaces</option>

        @foreach($workspaces as $workspace)
            <option value="{{ $workspace->id }}"
                {{ request('workspace_id') == $workspace->id ? 'selected' : '' }}>
                {{ $workspace->name }}
            </option>
        @endforeach

    </select>
</div>
                <div>
                    <label for="date">Booking Date</label>
                    <input type="date" id="date" name="date" value="{{ request('date') }}">
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ route('manager.bookings.index') }}" class="btn btn-secondary btn-link">Reset</a>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>
    </section>

    <!-- Bookings Table -->
    <section class="card">
        <div class="table-header">
            <h2>All Bookings</h2>
<span class="muted">{{ $bookings->count() }} bookings</span>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Member</th>
                        <th>Workspace / Room</th>
                        <th>Time Slot</th>
                        <th>Amount</th>
                        <th>Booking Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
               
<tbody>
    @forelse($bookings as $booking)
        <tr>
            <!-- Booking Reference -->
            <td>
                <strong>#BK-{{ $booking->id }}</strong>
            </td>

            <!-- Member -->
            <td>
                <div class="member-name">
                    {{ $booking->user?->name ?? 'N/A' }}
                </div>
                <div class="muted">
                    {{ $booking->user?->email ?? 'N/A' }}
                </div>
            </td>

            <!-- Workspace / Room -->
            <td>
                <div>{{ $booking->room?->workspace?->name ?? 'N/A' }}</div>
                <div class="muted">{{ $booking->room?->name ?? 'N/A' }}</div>
            </td>

            <!-- Time Slot -->
            <td>
                <div>
                    {{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y') }}
                </div>
                <div class="muted">
                    {{ \Carbon\Carbon::parse($booking->start_date_time)->format('h:i A') }}
                    -
                    {{ \Carbon\Carbon::parse($booking->end_date_time)->format('h:i A') }}
                </div>
            </td>

            <!-- Amount -->
            <td>
                {{ number_format($booking->total_price, 2) }} JOD
            </td>

            <!-- Booking Status -->
            <td>
                <span class="badge {{ $booking->status }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </td>

            <!-- Payment Status -->
            @php
                $paymentStatus = $booking->payments->sortByDesc('id')->first()?->status ?? 'pending';
            @endphp

            <td>
                <span class="badge {{ $paymentStatus }}">
                    {{ ucfirst($paymentStatus) }}
                </span>
            </td>

            <!-- Actions -->
            <td>
                <div class="actions">
                    <a href="#details-{{ $booking->id }}"
                       class="btn btn-outline btn-link">
                        View
                    </a>

                    @if(in_array($booking->status, ['pending', 'confirmed']))
                        <a href="#cancel-{{ $booking->id }}"
                           class="btn btn-danger btn-link">
                            Cancel
                        </a>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="8" style="text-align:center;padding:30px;color:#64748b;">No bookings found.</td></tr>
    @endforelse
</tbody>


               
            </table>
        </div>
    </section>
</div>

<!-- Details and cancellation modals for each booking -->
@foreach($bookings as $booking)
    @php
        $latestPaymentStatus = $booking->payments->sortByDesc('id')->first()?->status ?? 'pending';
    @endphp

    <div id="details-{{ $booking->id }}" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Booking Details</h2>
                <a href="#" class="close" aria-label="Close">&times;</a>
            </div>
            <div class="detail-row"><span>Booking Reference</span><strong>#BK-{{ $booking->id }}</strong></div>
            <div class="detail-row"><span>Member</span><strong>{{ $booking->user?->name ?? 'N/A' }}</strong></div>
            <div class="detail-row"><span>Email</span><strong>{{ $booking->user?->email ?? 'N/A' }}</strong></div>
            <div class="detail-row"><span>Workspace</span><strong>{{ $booking->room?->workspace?->name ?? 'N/A' }}</strong></div>
            <div class="detail-row"><span>Room</span><strong>{{ $booking->room?->name ?? 'N/A' }}</strong></div>
            <div class="detail-row"><span>Start</span><strong>{{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y, h:i A') }}</strong></div>
            <div class="detail-row"><span>End</span><strong>{{ \Carbon\Carbon::parse($booking->end_date_time)->format('d M Y, h:i A') }}</strong></div>
            <div class="detail-row"><span>Amount</span><strong>{{ number_format($booking->total_price, 2) }} JOD</strong></div>
            <div class="detail-row"><span>Booking Status</span><span class="badge {{ $booking->status }}">{{ ucfirst($booking->status) }}</span></div>
            <div class="detail-row"><span>Payment Status</span><span class="badge {{ $latestPaymentStatus }}">{{ ucfirst($latestPaymentStatus) }}</span></div>
            @if($booking->cancellation_reason ?? null)
                <div class="detail-row"><span>Cancellation Reason</span><strong>{{ $booking->cancellation_reason }}</strong></div>
            @endif
            <div class="modal-actions"><a href="#" class="btn btn-secondary btn-link">Close</a></div>
        </div>
    </div>

    @if(in_array($booking->status, ['pending', 'confirmed']))
        <div id="cancel-{{ $booking->id }}" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Cancel Booking #BK-{{ $booking->id }}</h2>
                    <a href="#" class="close" aria-label="Close">&times;</a>
                </div>
                <p class="subtitle">Please provide a reason for cancelling this booking.</p>
                {{-- This form requires the manager.bookings.cancel route and controller cancellation method. --}}
               <form action="{{ route('manager.bookings.cancel', $booking->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <p>Are you sure you want to cancel this booking?</p>

    <div class="modal-actions">
        <a href="#" class="btn btn-secondary btn-link">
            Keep Booking
        </a>

        <button type="submit" class="btn btn-danger">
            Confirm Cancellation
        </button>
    </div>
</form>
            </div>
        </div>
    @endif
@endforeach

</body>
</html>
