
@extends('layouts.dashboard')

@section('dashboard-role', 'manager')
@section('title', 'Manager Bookings | NexOra')
@section('page-title', 'Manager Bookings')

@push('styles')
@include('layouts.dashboard.partials.app-compat-styles')

<link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-13.css') }}">
@endpush

@push('page-overrides')
<link rel="stylesheet" href="{{ asset('css/pages/manager-bookings.css') }}?v=20261125-ui3">
@endpush

@section('content')
<div class="nx-bookings-page">
    <div class="bk-container">

        {{-- Page Header --}}
        <header class="bk-header">
            <div class="bk-header-content">
                <div class="bk-eyebrow">MANAGEMENT / BOOKINGS</div>
                <h1>Manager Bookings</h1>
                <p>View and manage bookings across your workspaces.</p>
            </div>

            <div class="bk-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M16 3v4M8 3v4M3 10h18"/>
                    <path d="m9 15 2 2 4-4"/>
                </svg>
            </div>
        </header>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="bk-alert bk-alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <path d="m9 11 3 3L22 4"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bk-alert bk-alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Filters --}}
        <section class="bk-card">
            <div class="bk-section-heading">
                <div class="bk-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 7h16M7 12h10M10 17h4"/>
                        <circle cx="8" cy="7" r="1.5" fill="currentColor"/>
                        <circle cx="15" cy="12" r="1.5" fill="currentColor"/>
                    </svg>
                </div>
                <div>
                    <h2>Filter Bookings</h2>
                    <p >Refine your bookings by status, workspace or date</p>
                </div>
            </div>

            <form action="{{ route('manager.bookings.index') }}"
                  method="GET"
                  id="bookingFiltersForm">

                <div class="bk-filters">

                    <div class="bk-field">
                        <label for="status">Booking Status</label>
                        <select id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <div class="bk-field">
                        <label for="payment_status">Payment Status</label>
                        <select id="payment_status" name="payment_status">
                            <option value="">All Payments</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div class="bk-field">
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

                    <div class="bk-field">
                        <label for="date">Booking Date</label>
                        <input type="date"
                               id="date"
                               name="date"
                               value="{{ request('date') }}">
                    </div>

                </div>

                <div class="bk-filter-footer">
                    <span class="bk-filter-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4M12 8h.01"/>
                        </svg>
                        Filters are applied automatically when changed.
                    </span>

                    <a href="{{ route('manager.bookings.index') }}" class="bk-reset">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                        Reset Filters
                    </a>
                </div>
            </form>
        </section>

        {{-- Bookings Table --}}
        <section class="bk-card">
            <div class="bk-table-heading">
                <div class="bk-table-title">
                    <div class="bk-section-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                            <path d="M7 2v4M17 2v4M3 9h18"/>
                            <path d="M7 13h3M7 17h6"/>
                        </svg>
                    </div>
                    <div>
                        <h2>All Bookings</h2>
                        <p>Review booking details and payment information</p>
                    </div>
                </div>

                <span class="bk-count">
                    {{ $bookings->total() }} Bookings
                </span>
            </div>

            <div class="bk-table-wrap">
                <table class="bk-table">
                    <thead>
                        <tr>
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
                            @php
                                $paymentStatus = $booking->payments
                                    ->sortByDesc('id')
                                    ->first()?->status ?? 'pending';
                            @endphp

                            <tr>
                                {{-- Member --}}
                                <td data-label="Member">
                                    <div class="bk-member">
                                        {{ $booking->user?->name ?? 'N/A' }}
                                    </div>
                                </td>

                                {{-- Workspace / Room --}}
                                <td data-label="Workspace / room">
                                    <div class="bk-workspace">
                                        {{ $booking->room?->workspace?->name ?? 'N/A' }}
                                    </div>
                                    <div class="bk-subtext">
                                        {{ $booking->room?->name ?? 'N/A' }}
                                    </div>
                                </td>

                                {{-- Time Slot --}}
                                <td data-label="Time slot">
                                    <div class="bk-date">
                                        {{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y') }}
                                    </div>
                                    <div class="bk-time">
                                        {{ \Carbon\Carbon::parse($booking->start_date_time)->format('h:i A') }}
                                        –
                                        {{ \Carbon\Carbon::parse($booking->end_date_time)->format('h:i A') }}
                                    </div>
                                </td>

                                {{-- Amount --}}
                                <td data-label="Amount">
                                    <div class="bk-amount">
                                        {{ number_format($booking->total_price, 2) }} JOD
                                    </div>
                                </td>

                                {{-- Booking Status --}}
                                <td data-label="Booking status">
                                    <span class="bk-badge {{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                {{-- Payment Status --}}
                                <td data-label="Payment status">
                                    <span class="bk-badge {{ $paymentStatus }}">
                                        {{ ucfirst($paymentStatus) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td data-label="Actions">
                                    <div class="bk-actions">
                                        <a href="#details-{{ $booking->id }}"
                                           class="bk-action bk-view"
                                           title="View Details"
                                           aria-label="View Details">
                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>

                                        @if(in_array($booking->status, ['pending', 'confirmed']))
                                            <a href="#cancel-{{ $booking->id }}"
                                               class="bk-action bk-cancel"
                                               title="Cancel Booking"
                                               aria-label="Cancel Booking">
                                                <svg viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round">
                                                    <path d="M18 6 6 18M6 6l12 12"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="bk-empty">
                                    <div class="bk-empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.6"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                                            <path d="M7 2v4M17 2v4M3 9h18"/>
                                            <path d="M9 14h6"/>
                                        </svg>
                                    </div>
                                    <strong>No bookings found</strong>
                                    <span>Try changing or resetting your filters.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
@if($bookings->hasPages())
    @php
        $bookings->withQueryString();

        $currentPage = $bookings->currentPage();
        $lastPage = $bookings->lastPage();

        $windowStart = max(1, $currentPage - 1);
        $windowEnd = min($lastPage, $currentPage + 1);
    @endphp

    <div class="bk-pagination">

        <div class="bk-pagination-summary">
            Page {{ $currentPage }} of {{ $lastPage }}
        </div>

        <nav aria-label="Bookings pagination">
            <ul class="bk-pager">

                {{-- Previous --}}
                <li>
                    @if($bookings->onFirstPage())
                        <span class="bk-pager-link is-disabled"
                              aria-disabled="true">‹</span>
                    @else
                        <a class="bk-pager-link"
                           href="{{ $bookings->previousPageUrl() }}"
                           rel="prev"
                           aria-label="Previous page">‹</a>
                    @endif
                </li>

                {{-- First page --}}
                @if($windowStart > 1)
                    <li>
                        <a class="bk-pager-link"
                           href="{{ $bookings->url(1) }}">1</a>
                    </li>

                    @if($windowStart > 2)
                        <li>
                            <span class="bk-pager-link is-disabled">…</span>
                        </li>
                    @endif
                @endif

                {{-- Page numbers --}}
                @foreach(range($windowStart, $windowEnd) as $page)
                    <li>
                        @if($page === $currentPage)
                            <span class="bk-pager-link is-active"
                                  aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a class="bk-pager-link"
                               href="{{ $bookings->url($page) }}">
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endforeach

                {{-- Last page --}}
                @if($windowEnd < $lastPage)
                    @if($windowEnd < $lastPage - 1)
                        <li>
                            <span class="bk-pager-link is-disabled">…</span>
                        </li>
                    @endif

                    <li>
                        <a class="bk-pager-link"
                           href="{{ $bookings->url($lastPage) }}">
                            {{ $lastPage }}
                        </a>
                    </li>
                @endif

                {{-- Next --}}
                <li>
                    @if($bookings->hasMorePages())
                        <a class="bk-pager-link"
                           href="{{ $bookings->nextPageUrl() }}"
                           rel="next"
                           aria-label="Next page">›</a>
                    @else
                        <span class="bk-pager-link is-disabled"
                              aria-disabled="true">›</span>
                    @endif
                </li>

            </ul>
        </nav>
    </div>
@endif
        </section>

        {{-- Details and Cancellation Modals --}}
        @foreach($bookings as $booking)
            @php
                $latestPaymentStatus = $booking->payments
                    ->sortByDesc('id')
                    ->first()?->status ?? 'pending';
            @endphp

            {{-- Booking Details --}}
            <div id="details-{{ $booking->id }}" class="bk-modal">
                <div class="bk-modal-content">
                    <div class="bk-modal-header">
                        <h2>Booking Details</h2>
                        <a href="#" class="bk-close" aria-label="Close">&times;</a>
                    </div>

                    <div class="bk-detail-row">
                        <span>Member</span>
                        <strong>{{ $booking->user?->name ?? 'N/A' }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Workspace</span>
                        <strong>{{ $booking->room?->workspace?->name ?? 'N/A' }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Room</span>
                        <strong>{{ $booking->room?->name ?? 'N/A' }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Start</span>
                        <strong>{{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y, h:i A') }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>End</span>
                        <strong>{{ \Carbon\Carbon::parse($booking->end_date_time)->format('d M Y, h:i A') }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Amount</span>
                        <strong>{{ number_format($booking->total_price, 2) }} JOD</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Booking Status</span>
                        <span class="bk-badge {{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="bk-detail-row">
                        <span>Payment Status</span>
                        <span class="bk-badge {{ $latestPaymentStatus }}">
                            {{ ucfirst($latestPaymentStatus) }}
                        </span>
                    </div>

                    @if($booking->cancellation_reason ?? null)
                        <div class="bk-detail-row">
                            <span>Cancellation Reason</span>
                            <strong>{{ $booking->cancellation_reason }}</strong>
                        </div>
                    @endif

                    <div class="bk-modal-actions">
                        <a href="#" class="bk-modal-btn bk-modal-btn-secondary">Close</a>
                    </div>
                </div>
            </div>

            {{-- Cancel Booking --}}
            @if(in_array($booking->status, ['pending', 'confirmed']))
                <div id="cancel-{{ $booking->id }}" class="bk-modal">
                    <div class="bk-modal-content">
                        <div class="bk-modal-header">
                            <h2>Cancel Booking #BK-{{ $booking->id }}</h2>
                            <a href="#" class="bk-close" aria-label="Close">&times;</a>
                        </div>

                        <div class="bk-cancel-note">
                            Please review this action carefully. Cancelling this booking may affect the member's reservation.
                        </div>

                        <form action="{{ route('manager.bookings.cancel', $booking->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')

                            <p class="bk-cancel-question">
                                Are you sure you want to cancel this booking?
                            </p>

                            <div class="bk-modal-actions">
                                <a href="#" class="bk-modal-btn bk-modal-btn-secondary">
                                    Keep Booking
                                </a>

                                <button type="submit"
                                        class="bk-modal-btn bk-modal-btn-danger">
                                    Confirm Cancellation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bookingFiltersForm');

    if (!form) return;

    const filters = form.querySelectorAll('select, input[type="date"]');

    filters.forEach(function (filter) {
        filter.addEventListener('change', function () {
            form.requestSubmit();
        });
    });
});
</script>
@endsection
