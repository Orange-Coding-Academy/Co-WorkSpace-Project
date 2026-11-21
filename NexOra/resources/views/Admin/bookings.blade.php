
@extends('layouts.dashboard')

@section('dashboard-role', 'admin')
@section('title', 'Bookings')
@section('page-title', 'Bookings')

@push('styles')
    @include('layouts.dashboard.partials.app-compat-styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin-bookings.css') }}">
@endpush

@section('content')

<div class="nx-page-content nx-admin-bookings">
    <div class="nx-bookings-container">

        {{-- Page Header --}}
        <header class="nx-bookings-header">
            <div>
                <span class="nx-bookings-eyebrow">PLATFORM MANAGEMENT</span>
                <h1>All Bookings</h1>
                <p>Manage, monitor and track all workspace bookings across your platform.</p>
            </div>

            <div class="nx-bookings-header__badge">
                <span class="nx-bookings-header__dot"></span>
                Booking Management
            </div>
        </header>

        {{-- Statistics --}}
        <section class="nx-bookings-stats">

            <article class="nx-booking-stat">
                <div class="nx-booking-stat__top">
                    <div class="nx-booking-stat__icon nx-booking-stat__icon--total">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                            <path d="M8 14h3M8 17h6"/>
                        </svg>
                    </div>
                    <span class="nx-booking-stat__caption">ALL BOOKINGS</span>
                </div>

                <div class="nx-booking-stat__bottom">
                    <div>
                        <span class="nx-booking-stat__label">Total Bookings</span>
                        <h3>{{ $bookings->total() }}</h3>
                    </div>
                    <span class="nx-booking-stat__number">01</span>
                </div>
            </article>

            <article class="nx-booking-stat">
                <div class="nx-booking-stat__top">
                    <div class="nx-booking-stat__icon nx-booking-stat__icon--pending">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 2"/>
                        </svg>
                    </div>
                    <span class="nx-booking-stat__caption">AWAITING CONFIRMATION</span>
                </div>

                <div class="nx-booking-stat__bottom">
                    <div>
                        <span class="nx-booking-stat__label">Pending Bookings</span>
                        <h3>{{ $pendingCount ?? 0 }}</h3>
                    </div>
                    <span class="nx-booking-stat__number">02</span>
                </div>
            </article>

            <article class="nx-booking-stat">
                <div class="nx-booking-stat__top">
                    <div class="nx-booking-stat__icon nx-booking-stat__icon--confirmed">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.1V12a10 10 0 1 1-5.9-9.1"/>
                            <path d="m9 11 3 3L22 4"/>
                        </svg>
                    </div>
                    <span class="nx-booking-stat__caption">SUCCESSFULLY CONFIRMED</span>
                </div>

                <div class="nx-booking-stat__bottom">
                    <div>
                        <span class="nx-booking-stat__label">Confirmed Bookings</span>
                        <h3>{{ $confirmedCount ?? 0 }}</h3>
                    </div>
                    <span class="nx-booking-stat__number">03</span>
                </div>
            </article>

            <article class="nx-booking-stat">
                <div class="nx-booking-stat__top">
                    <div class="nx-booking-stat__icon nx-booking-stat__icon--cancelled">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m9 9 6 6M15 9l-6 6"/>
                        </svg>
                    </div>
                    <span class="nx-booking-stat__caption">CANCELLED BOOKINGS</span>
                </div>

                <div class="nx-booking-stat__bottom">
                    <div>
                        <span class="nx-booking-stat__label">Cancelled Bookings</span>
                        <h3>{{ $cancelledCount ?? 0 }}</h3>
                    </div>
                    <span class="nx-booking-stat__number">04</span>
                </div>
            </article>

        </section>

        {{-- Bookings Panel --}}
        <section class="nx-bookings-panel">

            <div class="nx-bookings-panel__header">
                <div>
                    <span class="nx-bookings-panel__eyebrow">RESERVATION OVERVIEW</span>
                    <h2>Booking Records</h2>
                    <p>View booking details, financial information and current statuses.</p>
                </div>

                <span class="nx-bookings-count">
                    {{ $bookings->total() }} Records
                </span>
            </div>

            {{-- Table --}}
            <div class="nx-bookings-table-wrapper">

                <table class="nx-bookings-table">

                    <thead>
                        <tr>
                            <th>MEMBER</th>
                            <th>WORKSPACE</th>
                            <th>ROOM</th>
                            <th>BOOKING DATE</th>
                            <th>TOTAL PRICE</th>
                            <th>PAID AMOUNT</th>
                            <th>BOOKING STATUS</th>
                        
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

                                $bookingStatus = strtolower($booking->status);

                                $statusClass = match($bookingStatus) {
                                    'confirmed' => 'confirmed',
                                    'pending' => 'pending',
                                    'cancelled' => 'cancelled',
                                    default => 'other',
                                };

                                $paymentClass = match($paymentStatus) {
                                    'Paid' => 'paid',
                                    'Partially Paid' => 'partial',
                                    default => 'unpaid',
                                };
                            @endphp

                            <tr>

                                {{-- Member --}}
                                <td>
                                    <div class="nx-booking-member">
                                        

                                        <div class="nx-booking-member__info">
                                            <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                                    
                                        </div>
                                    </div>
                                </td>

                                {{-- Workspace --}}
                                <td>
                                    <div class="nx-booking-location">
                                    

                                        <span>{{ $booking->room->workspace->name ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                {{-- Room --}}
                                <td>
                                    <span class="nx-booking-room">
                                        {{ $booking->room->name ?? 'N/A' }}
                                    </span>
                                </td>

                                {{-- Date and Time --}}
                                <td>
                                    <div class="nx-booking-datetime">
                                        <strong>
                                            {{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y') }}
                                        </strong>

                                        <span>
                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.7"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="9"/>
                                                <path d="M12 7v5l3 2"/>
                                            </svg>

                                            {{ \Carbon\Carbon::parse($booking->start_date_time)->format('g:i A') }}
                                            –
                                            {{ \Carbon\Carbon::parse($booking->end_date_time)->format('g:i A') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Total Price --}}
                                <td>
                                    <span class="nx-booking-money nx-booking-money--total">
                                        {{ number_format($booking->total_price, 0) }}
                                        <small>JOD</small>
                                    </span>
                                </td>

                                {{-- Paid Amount --}}
                                <td>
                                    <span class="nx-booking-money nx-booking-money--paid">
                                        {{ number_format($paidAmount, 0) }}
                                        <small>JOD</small>
                                    </span>
                                </td>

                                {{-- Booking Status --}}
                                <td>
                                    <span class="nx-booking-status nx-booking-status--{{ $statusClass }}">
                                        <span></span>
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                               

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">
                                    <div class="nx-bookings-empty">
                                        <div class="nx-bookings-empty__icon">
                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.6"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="17" rx="2"/>
                                                <path d="M16 2v4M8 2v4M3 10h18"/>
                                                <path d="M9 15h6"/>
                                            </svg>
                                        </div>

                                        <h3>No Bookings Found</h3>
                                        <p>There are currently no bookings available to display.</p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($bookings->hasPages())

                <div class="nx-bookings-pagination">

                    <div class="nx-bookings-pagination__links">
                        {{ $bookings->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            @endif

        </section>

    </div>
</div>

@endsection
