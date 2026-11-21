{{--
Manager Bookings Routes
use App\Http\Controllers\BookingController;

Route::get('/bookings', [BookingController::class, 'index'])
    ->name('bookings.index');

Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancelManagerBooking'])
    ->name('bookings.cancel');

--}}

@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Manager Bookings | NexOra @endsection
@section('page-title') Manager Bookings @endsection

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* NexOra Manager Bookings — presentation only */
        .nx-page-content {
            --nx-primary:#315C4D; --nx-dark:#203D34; --nx-hover:#274B3F;
            --nx-text:#1D2924; --nx-muted:#53615A; --nx-bg:#F5F7F3;
            --nx-border:#E2E8E2; --nx-soft:#F8FAF7; --nx-accent:#C58A62;
            font-family:'Inter','Noto Sans Arabic',system-ui,sans-serif;
            color:var(--nx-text); background:var(--nx-bg);
            min-height:100%; padding:1px 0 36px;
        }
        .nx-page-content *, .nx-page-content *::before, .nx-page-content *::after {box-sizing:border-box;}
        .nx-page-content .container {max-width:1250px; width:100%; margin:32px auto 0; padding:0 24px;}
        .nx-page-content .page-header {display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:26px;}
        .nx-page-content h1,.nx-page-content h2 {font-family:'Manrope','Noto Sans Arabic',sans-serif; letter-spacing:-.025em; color:var(--nx-text);}
        .nx-page-content h1 {font-size:clamp(24px,3vw,30px); font-weight:800; margin:0 0 8px;}
        .nx-page-content .subtitle {color:var(--nx-muted); font-size:14px; line-height:1.6; margin:0;}
        .nx-page-content .card {background:#fff; border:1px solid var(--nx-border); border-radius:18px; padding:26px; margin-bottom:24px; box-shadow:0 5px 22px rgba(21,42,35,.045);}
        .nx-page-content .filters {display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:18px;}
        .nx-page-content label {display:block; margin-bottom:9px; color:var(--nx-text); font-size:13px; font-weight:700;}
        .nx-page-content select,.nx-page-content input,.nx-page-content textarea {width:100%; min-width:0; min-height:46px; padding:11px 14px; border:1px solid #C9D4CC; border-radius:11px; background:#fff; color:var(--nx-text); font:inherit; font-size:14px; transition:border-color .2s,box-shadow .2s;}
        .nx-page-content select:hover,.nx-page-content input:hover {border-color:#6F806E;}
        .nx-page-content select:focus,.nx-page-content input:focus,.nx-page-content textarea:focus {outline:0; border-color:var(--nx-primary); box-shadow:0 0 0 3px rgba(49,92,77,.16);}
        .nx-page-content :focus-visible {outline:2px solid var(--nx-accent); outline-offset:2px;}
        .nx-page-content .filter-actions {display:flex; justify-content:flex-end; flex-wrap:wrap; gap:10px; margin-top:22px;}
        .nx-page-content .btn {display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:42px; padding:10px 20px; border:1px solid transparent; border-radius:999px; font:700 13px 'Manrope','Noto Sans Arabic',sans-serif; text-decoration:none; cursor:pointer; white-space:nowrap; transition:background-color .2s,border-color .2s,color .2s,transform .2s;}
        .nx-page-content .btn:hover {transform:translateY(-1px);}
        .nx-page-content .btn:active {transform:translateY(0);}
        .nx-page-content .btn-primary {background:var(--nx-primary); color:#fff; border-color:var(--nx-primary);}
        .nx-page-content .btn-primary:hover {background:var(--nx-hover); color:#fff; border-color:var(--nx-hover);}
        .nx-page-content .btn-secondary {background:#EDF2EC; color:var(--nx-dark); border-color:#DCE6DC;}
        .nx-page-content .btn-secondary:hover {background:#E3EBE2; color:var(--nx-dark);}
        .nx-page-content .btn-outline {background:#fff; border-color:#C9D4CC; color:var(--nx-primary);}
        .nx-page-content .btn-outline:hover {background:#EDF2EC; border-color:var(--nx-primary); color:var(--nx-dark);}
        .nx-page-content .btn-danger {background:#B42318; border-color:#B42318; color:#fff;}
        .nx-page-content .btn-danger:hover {background:#912018; border-color:#912018; color:#fff;}
        .nx-page-content .btn-link {text-align:center;}
        .nx-page-content .table-header {display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px;}
        .nx-page-content .table-header h2 {margin:0; font-size:19px; font-weight:800;}
        .nx-page-content .table-wrapper {width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; border:1px solid var(--nx-border); border-radius:13px;}
        .nx-page-content table {width:100%; border-collapse:collapse; text-align:left; white-space:nowrap;}
        .nx-page-content th {background:var(--nx-soft); color:var(--nx-muted); font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.045em;}
        .nx-page-content th,.nx-page-content td {padding:17px 15px; border-bottom:1px solid #E9EEE9; vertical-align:middle;}
        .nx-page-content td {font-size:13px;}
        .nx-page-content tbody tr:hover {background:#FAFCF9;}
        .nx-page-content tr:last-child td {border-bottom:0;}
        .nx-page-content .member-name {font-weight:700; color:var(--nx-text);}
        .nx-page-content .muted {color:var(--nx-muted); font-size:12px; margin-top:5px;}
        .nx-page-content .badge {display:inline-flex; align-items:center; justify-content:center; padding:7px 12px; border-radius:999px; font-size:11px; font-weight:700; line-height:1.2;}
        .nx-page-content .pending {background:#FFF6E5; color:#754C05;}
        .nx-page-content .confirmed,.nx-page-content .paid {background:#EAF6EF; color:#145C3A;}
        .nx-page-content .cancelled,.nx-page-content .failed {background:#FDEDEC; color:#912018;}
        .nx-page-content .completed {background:#E7EEE7; color:var(--nx-dark);}
        .nx-page-content .expired {background:#F0F2EF; color:var(--nx-muted);}
        .nx-page-content .actions {display:flex; align-items:center; gap:8px;}
        .nx-page-content .actions .btn {min-height:36px; padding:8px 14px; font-size:12px;}
        /* Keep existing CSS :target modal behavior intact. */
        .nx-page-content .modal {display:none; position:fixed; inset:0; background:rgba(21,42,35,.62); align-items:center; justify-content:center; padding:20px; z-index:1050; overflow-y:auto;}
        .nx-page-content .modal:target {display:flex;}
        .nx-page-content .modal-content {background:#fff; border:1px solid var(--nx-border); border-radius:20px; width:100%; max-width:560px; padding:28px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 55px rgba(21,42,35,.2);}
        .nx-page-content .modal-header {display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:20px; padding:0 0 16px; border-bottom:1px solid #E9EEE9;}
        .nx-page-content .modal-header h2 {margin:0; font-size:20px; font-weight:800;}
        .nx-page-content .close {display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:var(--nx-soft); color:var(--nx-muted); text-decoration:none; font-size:25px; line-height:1; flex-shrink:0;}
        .nx-page-content .close:hover {background:#E7EEE7; color:var(--nx-dark);}
        .nx-page-content .detail-row {display:flex; justify-content:space-between; align-items:flex-start; gap:18px; padding:14px 0; border-bottom:1px solid #E9EEE9; font-size:13px;}
        .nx-page-content .detail-row > span:first-child {color:var(--nx-muted); flex:0 0 42%;}
        .nx-page-content .detail-row strong {text-align:right; overflow-wrap:anywhere; white-space:normal; font-weight:700;}
        .nx-page-content .detail-row .badge {margin-left:auto;}
        .nx-page-content .modal-actions {display:flex; justify-content:flex-end; flex-wrap:wrap; gap:10px; margin-top:24px;}
        .nx-page-content .modal-content form p {font-size:14px; line-height:1.6; color:var(--nx-muted);}
        @media(max-width:950px) {.nx-page-content .filters {grid-template-columns:repeat(2,minmax(0,1fr));}}
        @media(max-width:600px) {
            .nx-page-content .container {padding:0 14px; margin-top:22px;}
            .nx-page-content .card {padding:18px; margin-bottom:18px; border-radius:15px;}
            .nx-page-content .filters {grid-template-columns:1fr; gap:14px;}
            .nx-page-content .page-header {align-items:flex-start;}
            .nx-page-content .filter-actions {justify-content:stretch;}
            .nx-page-content .filter-actions .btn {flex:1;}
            .nx-page-content .table-header {flex-wrap:wrap;}
            .nx-page-content .modal {padding:12px;}
            .nx-page-content .modal-content {padding:20px; border-radius:16px;}
            .nx-page-content .detail-row {gap:12px;}
            .nx-page-content .modal-actions .btn {flex:1;}
        }
        @media(prefers-reduced-motion:reduce) {.nx-page-content .btn {transition:none;}}
    </style>
@endpush

@section('content')
<div class="nx-page-content">
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
        <tr><td colspan="8" style="text-align:center;padding:30px;color:#53615A;">No bookings found.</td></tr>
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
</div>
@endsection
