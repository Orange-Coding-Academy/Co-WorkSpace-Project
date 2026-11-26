@extends('layouts.member')

@section('title')
    Booking Details
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-bbb0b257.css') }}?v=20261125-ui2">
@endpush

@section('content')
    @php
        $start = \Carbon\Carbon::parse($booking->start_date_time);
        $end = \Carbon\Carbon::parse($booking->end_date_time);
        $hours = $start->diffInMinutes($end) / 60;

        $statusClass = function ($status) {
            return match (strtolower((string) $status)) {
                'confirmed', 'completed', 'paid', 'succeeded' => 'bg-success',
                'pending', 'processing' => 'bg-warning',
                'cancelled', 'canceled', 'failed', 'expired', 'refunded' => 'bg-danger',
                default => 'bg-primary',
            };
        };

        // Show the payment form while the booking is pending and not paid yet.
        $paidStatuses = ['paid', 'succeeded', 'completed', 'confirmed'];
        $needsPayment = strtolower((string) $booking->status) === 'pending'
            && (!$payment || !in_array(strtolower((string) $payment->status), $paidStatuses));
    @endphp

    <div class="nx-page-content bg-light">
        <div class="container nx-bd">

            {{-- Title + status --}}
            <div class="nx-bd-top">
                <div class="nx-bd-title">
                    <a href="{{ route('member.bookings.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                        <span class="nx-inline-arrow" aria-hidden="true">←</span>
                        <span>Back to My Bookings</span>
                    </a>
                    <h1>Booking #{{ $booking->id }}</h1>
                    <p>{{ $room->name }} &middot; {{ $start->format('d M Y') }}</p>
                </div>

                <div class="nx-bd-status">
                    Status
                    <span class="badge {{ $statusClass($booking->status) }}">{{ ucfirst($booking->status) }}</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4 align-items-start">

                {{-- LEFT: customer + room + reservation details --}}
                <div class="col-lg-7">
                    <div class="nx-bd-card">

                        {{-- Customer --}}
                        <div class="nx-bd-section">
                            <div class="nx-bd-section-head">
                                <h2>Customer Information</h2>
                                <span class="nx-bd-ref">Ref #{{ $booking->id }}</span>
                            </div>

                            <div class="nx-bd-fields">
                                <div>
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>
                                <div>
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Room --}}
                        <div class="nx-bd-section">
                            <div class="nx-bd-section-head">
                                <h2>Room Information</h2>
                            </div>

                            <div class="nx-bd-tiles">
                                <div class="nx-bd-tile">
                                    <span class="nx-bd-tile-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                            <path d="M16 9h2a2 2 0 0 1 2 2v10" />
                                            <path d="M2 21h20" />
                                            <path d="M8 7h4M8 11h4M8 15h4" />
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="nx-bd-tile-label">Room</span>
                                        <span class="nx-bd-tile-value">{{ $room->name }}</span>
                                    </div>
                                </div>

                                <div class="nx-bd-tile">
                                    <span class="nx-bd-tile-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M14.5 9.2c-.5-.8-1.4-1.2-2.5-1.2-1.4 0-2.5.8-2.5 1.9 0 2.7 5.2 1.3 5.2 4 0 1.1-1.1 1.9-2.7 1.9-1.2 0-2.2-.5-2.7-1.4M12 6.5V8m0 8v1.5" />
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="nx-bd-tile-label">Hourly Price</span>
                                        <span class="nx-bd-tile-value">{{ number_format($room->hourly_price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Reservation --}}
                        <div class="nx-bd-section">
                            <div class="nx-bd-section-head">
                                <h2>Reservation Information</h2>
                            </div>

                            <div class="nx-bd-tiles">
                                <div class="nx-bd-tile">
                                    <span class="nx-bd-tile-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="3" y="4" width="18" height="17" rx="3" />
                                            <path d="M3 10h18M8 2v4M16 2v4" />
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="nx-bd-tile-label">Date</span>
                                        <span class="nx-bd-tile-value">{{ $start->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="nx-bd-tile">
                                    <span class="nx-bd-tile-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M6 2h12M6 22h12" />
                                            <path d="M7 2c0 5 5 5 5 10s-5 5-5 10M17 2c0 5-5 5-5 10s5 5 5 10" />
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="nx-bd-tile-label">Duration</span>
                                        <span class="nx-bd-tile-value">{{ number_format($hours, 2) }} hours</span>
                                    </div>
                                </div>

                                <div class="nx-bd-tile">
                                    <span class="nx-bd-tile-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M12 7v5l3 2" />
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="nx-bd-tile-label">Start</span>
                                        <span class="nx-bd-tile-value">{{ $start->format('h:i A') }}</span>
                                        <span class="nx-bd-tile-sub">{{ $start->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="nx-bd-tile">
                                    <span class="nx-bd-tile-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M9 12l2 2 4-4" />
                                        </svg>
                                    </span>
                                    <div>
                                        <span class="nx-bd-tile-label">End</span>
                                        <span class="nx-bd-tile-value">{{ $end->format('h:i A') }}</span>
                                        <span class="nx-bd-tile-sub">{{ $end->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- RIGHT: total + payment + pay form --}}
                <div class="col-lg-5">
                    <div class="nx-bd-card nx-bd-summary">

                        <div class="nx-bd-summary-head">
                            <div>
                                <small>Total</small>
                                <strong>${{ number_format($booking->total_price, 2) }}</strong>
                            </div>
                        </div>

                        <div class="nx-bd-summary-body">

                            <div class="nx-bd-line">
                                <span>Hourly Price</span>
                                <strong>${{ number_format($room->hourly_price, 2) }}</strong>
                            </div>

                            <div class="nx-bd-line">
                                <span>Duration</span>
                                <strong>{{ number_format($hours, 2) }} hours</strong>
                            </div>

                            <hr class="nx-bd-divider">

                            <p class="nx-bd-subtitle">Payment Information</p>

                            @if ($payment)
                                <div class="nx-bd-line">
                                    <span>Payment ID</span>
                                    <strong>#{{ $payment->id }}</strong>
                                </div>

                                <div class="nx-bd-line">
                                    <span>Amount</span>
                                    <strong>{{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</strong>
                                </div>

                                <div class="nx-bd-line">
                                    <span>Status</span>
                                    <span class="badge {{ $statusClass($payment->status) }}">{{ ucfirst($payment->status) }}</span>
                                </div>
                            @else
                                <p class="nx-bd-empty">No payment record found.</p>
                            @endif

                            {{-- Payment form: same fields the old checkout page sent to Stripe --}}
                            @if ($needsPayment)
                                <form method="POST" action="{{ route('member.stripe.checkout.booking') }}" class="nx-bd-pay">
                                    @csrf

                                    <div class="alert alert-warning">
                                        {{ $payment ? 'Your payment is not completed yet.' : 'Your booking is awaiting payment.' }}
                                    </div>

                                    <div class="nx-bd-terms">
                                        <input type="checkbox" class="form-check-input" name="terms" value="1"
                                            id="terms" required>
                                        <label for="terms">
                                            I agree to the booking terms and cancellation policy.
                                        </label>
                                    </div>

                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        Proceed to Payment
                                    </button>

                                    <p class="nx-bd-secure">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="4" y="11" width="16" height="10" rx="2" />
                                            <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                                        </svg>
                                        Secure payment through Stripe
                                    </p>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection