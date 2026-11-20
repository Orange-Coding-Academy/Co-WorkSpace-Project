
@extends('layouts.dashboard')

@section('dashboard-role', 'admin')
@section('title', 'Bookings')
@section('page-title', 'Bookings')

@push('styles')
@include('layouts.dashboard.partials.app-compat-styles')
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@section('content')
<div class="nx-page-content py-4">
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">All Bookings</h2>
            <p class="text-muted mb-0">Manage and monitor all platform bookings</p>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <p class="text-muted mb-1">Total Bookings</p>
                <h3 class="fw-bold">{{ $bookings->total() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <p class="text-muted mb-1">Pending Bookings</p>
                <h3 class="fw-bold text-warning">{{ $pendingCount ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <p class="text-muted mb-1">Confirmed Bookings</p>
                <h3 class="fw-bold text-success">{{ $confirmedCount ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <p class="text-muted mb-1">Cancelled Bookings</p>
                <h3 class="fw-bold text-danger">{{ $cancelledCount ?? 0 }}</h3>
            </div>
        </div>
    </div>

    
    {{-- Bookings Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Member</th>
                            <th>Workspace</th>
                            <th>Room</th>
                            <th>Booking Date</th>
                            <th>Total Price</th>
                            <th>Paid Amount</th>
                            <th>Booking Status</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                            @php
                                $paidAmount = $booking->payments
                                    ->whereNotNull('paid_at')
                                    ->sum('amount');

                                $paymentStatus = $paidAmount >= $booking->total_price
                                    ? 'Paid'
                                    : ($paidAmount > 0 ? 'Partially Paid' : 'Unpaid');
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $booking->user->name ?? 'N/A' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $booking->room->workspace->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $booking->room->name ?? 'N/A' }}
                                </td>

                                <td>
                                    <div>{{ $booking->start_date_time }}</div>
                                    <small class="text-muted">
                                        To: {{ $booking->end_date_time }}
                                    </small>
                                </td>

                                <td class="fw-semibold">
                                    {{ number_format($booking->total_price, 2) }} JOD
                                </td>

                                <td class="text-success fw-semibold">
                                    {{ number_format($paidAmount, 2) }} JOD
                                </td>

                                <td>
                                    @php
                                        $statusClass = match(strtolower($booking->status)) {
                                            'confirmed' => 'success',
                                            'pending' => 'warning',
                                            'cancelled' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $paymentStatus === 'Paid' ? 'success' : ($paymentStatus === 'Partially Paid' ? 'warning' : 'secondary') }}">
                                        {{ $paymentStatus }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    No bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>

</div>
</div>
@endsection