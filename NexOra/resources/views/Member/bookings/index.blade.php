@extends('layouts.member')

@section('title')
    My Bookings
@endsection

@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/member-bookings-index.css') }}">
       <link rel="stylesheet" href="{{ asset('css/pages/admin-payments.css') }}?v={{ @filemtime(public_path('css/pages/admin-payments.css')) }}">
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">غ
@endpush

@section('content')
    <div class="nx-page-content bg-light">
        <div class="container py-2">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold ">My Bookings</h3>
                    <p class="textAcc">Manage and track your reservations.</p>
                </div>
                {{-- <a href="{{ route('member.checkout.index') }}" class="btn btn-primary">
            New Booking
        </a> --}}
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4">
                @foreach ([
            'all' => 'All',
            'upcoming' => 'Upcoming',
            'pending' => 'Pending',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
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
                        <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Room</label>
                        <select name="room_id" class="form-select">
                            <option value="">All Rooms</option>

                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button class="btn btn-primary">Filter</button>
                        <a href="{{ route('member.bookings.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                                            <span
                                                class="badge
                                    {{ $booking->status === 'confirmed'
                                        ? 'bg-success'
                                        : ($booking->status === 'pending'
                                            ? 'bg-warning text-dark'
                                            : ($booking->status === 'cancelled'
                                                ? 'bg-danger'
                                                : 'bg-secondary')) }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>

                                      <td>
    <div class="nx-booking-actions">

        {{-- View --}}
        <a href="{{ route('member.bookings.show', $booking->id) }}"
           class="nx-action-icon nx-action-view"
           title="View Booking"
           aria-label="View Booking">
            <i class="bi bi-eye"></i>
        </a>

        {{-- Cancel --}}
        @if ($booking->status === 'pending' &&
            \Carbon\Carbon::parse($booking->start_date_time)->isFuture())

            <form method="POST"
                  action="{{ route('member.bookings.cancel', $booking->id) }}"
                  onsubmit="return confirm('Are you sure you want to cancel this booking?')">

                @csrf
                @method('PATCH')

                <button type="submit"
                        class="nx-action-icon nx-action-cancel"
                        title="Cancel Booking"
                        aria-label="Cancel Booking">
                    <i class="bi bi-x-lg"></i>
                </button>
            </form>

        @endif

        {{-- Proceed to Checkout --}}
        @if ($booking->status === 'pending' &&
            \Carbon\Carbon::parse($booking->start_date_time)->isFuture())

            <a href="{{ route('member.bookings.checkout', $booking->id) }}"
               class="nx-checkout-btn">
                <i class="bi bi-credit-card"></i>
                <span>Proceed to Checkout</span>
                <i class="bi bi-arrow-up-right nx-checkout-arrow"></i>
            </a>

        @endif

    </div>
</td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="7" class="text-center py-5">
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

                    {{-- Pagination - same design as Payments --}}
@if($bookings->hasPages())
    @php
        $bookings->withQueryString();

        $currentPage = $bookings->currentPage();
        $lastPage = $bookings->lastPage();

        $windowStart = max(1, $currentPage - 1);
        $windowEnd = min($lastPage, $currentPage + 1);
    @endphp

    <div class="payments-pagination">
        <div class="pagination-summary">
            Page {{ $currentPage }} of {{ $lastPage }}
        </div>

        <nav aria-label="Bookings pagination">
            <ul class="pager">

                {{-- Previous --}}
                <li>
                    @if($bookings->onFirstPage())
                        <span class="pager-link is-disabled" aria-disabled="true">‹</span>
                    @else
                        <a class="pager-link"
                           href="{{ $bookings->previousPageUrl() }}"
                           rel="prev"
                           aria-label="Previous page">‹</a>
                    @endif
                </li>

                {{-- First page --}}
                @if($windowStart > 1)
                    <li>
                        <a class="pager-link" href="{{ $bookings->url(1) }}">1</a>
                    </li>

                    @if($windowStart > 2)
                        <li>
                            <span class="pager-link is-disabled">…</span>
                        </li>
                    @endif
                @endif

                {{-- Page numbers --}}
                @foreach(range($windowStart, $windowEnd) as $page)
                    <li>
                        @if($page === $currentPage)
                            <span class="pager-link is-active" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a class="pager-link" href="{{ $bookings->url($page) }}">
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endforeach

                {{-- Last page --}}
                @if($windowEnd < $lastPage)
                    @if($windowEnd < $lastPage - 1)
                        <li>
                            <span class="pager-link is-disabled">…</span>
                        </li>
                    @endif

                    <li>
                        <a class="pager-link" href="{{ $bookings->url($lastPage) }}">
                            {{ $lastPage }}
                        </a>
                    </li>
                @endif

                {{-- Next --}}
                <li>
                    @if($bookings->hasMorePages())
                        <a class="pager-link"
                           href="{{ $bookings->nextPageUrl() }}"
                           rel="next"
                           aria-label="Next page">›</a>
                    @else
                        <span class="pager-link is-disabled" aria-disabled="true">›</span>
                    @endif
                </li>

            </ul>
        </nav>
    </div>
@endif

                </div>
            </div>

        </div>
    </div>
@endsection
