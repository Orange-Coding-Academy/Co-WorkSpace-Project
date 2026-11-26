@extends('layouts.member')

@section('title')
    Booking Checkout
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-3bf1c036.css') }}?v=20261125-ui2">
@endpush

@section('content')
    <div class="nx-page-content bg-light">
        <div class="container nx-co">

            {{-- Title + stepper --}}
            <div class="nx-co-top">
                <div class="nx-co-title">
                    <a href="{{ route('member.bookings.show', $booking->id) }}"
                       class="btn btn-outline-secondary btn-sm mb-2">
                        &larr; Back to Booking
                    </a>
                    <h1>Booking Checkout</h1>
                    <p>Review your booking before proceeding to payment.</p>
                </div>

                <div class="nx-co-steps" aria-label="Checkout progress">
                    <div class="nx-co-step is-done">
                        <span class="nx-co-step-dot">&#10003;</span> Details
                    </div>
                    <span class="nx-co-step-line"></span>
                    <div class="nx-co-step is-active">
                        <span class="nx-co-step-dot">2</span> Payment
                    </div>
                    <span class="nx-co-step-line"></span>
                    <div class="nx-co-step">
                        <span class="nx-co-step-dot">3</span> Confirmation
                    </div>
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

            <form method="POST" action="{{ route('member.stripe.checkout.booking') }}">
                @csrf

                <div class="row g-4 align-items-start">

                    {{-- LEFT: customer + booking details --}}
                    <div class="col-lg-7">
                        <div class="nx-co-card">

                            {{-- Customer --}}
                            <div class="nx-co-section">
                                <div class="nx-co-section-head">
                                    <h2>Customer Information</h2>
                                </div>

                                <div class="nx-co-fields">
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

                            {{-- Booking details --}}
                            <div class="nx-co-section">
                                <div class="nx-co-section-head">
                                    <h2>Booking Details</h2>
                                    <span class="nx-co-ref">Ref #{{ $booking->id }}</span>
                                </div>

                                <div class="nx-co-tiles">

                                    <div class="nx-co-tile is-wide">
                                        <span class="nx-co-tile-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/>
                                                <path d="M16 9h2a2 2 0 0 1 2 2v10"/>
                                                <path d="M2 21h20"/>
                                                <path d="M8 7h4M8 11h4M8 15h4"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <span class="nx-co-tile-label">Room</span>
                                            <span class="nx-co-tile-value">{{ $room->name }}</span>
                                        </div>
                                    </div>

                                    <div class="nx-co-tile">
                                        <span class="nx-co-tile-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <rect x="3" y="4" width="18" height="17" rx="3"/>
                                                <path d="M3 10h18M8 2v4M16 2v4"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <span class="nx-co-tile-label">Date</span>
                                            <span class="nx-co-tile-value">{{ $start->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="nx-co-tile">
                                        <span class="nx-co-tile-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M6 2h12M6 22h12"/>
                                                <path d="M7 2c0 5 5 5 5 10s-5 5-5 10M17 2c0 5-5 5-5 10s5 5 5 10"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <span class="nx-co-tile-label">Duration</span>
                                            <span class="nx-co-tile-value">{{ number_format($hours, 2) }} hours</span>
                                        </div>
                                    </div>

                                    <div class="nx-co-tile">
                                        <span class="nx-co-tile-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <circle cx="12" cy="12" r="9"/>
                                                <path d="M12 7v5l3 2"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <span class="nx-co-tile-label">Start</span>
                                            <span class="nx-co-tile-value">{{ $start->format('h:i A') }}</span>
                                        </div>
                                    </div>

                                    <div class="nx-co-tile">
                                        <span class="nx-co-tile-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <circle cx="12" cy="12" r="9"/>
                                                <path d="M9 12l2 2 4-4"/>
                                            </svg>
                                        </span>
                                        <div>
                                            <span class="nx-co-tile-label">End</span>
                                            <span class="nx-co-tile-value">{{ $end->format('h:i A') }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- RIGHT: order summary + terms + pay --}}
                    <div class="col-lg-5">
                        <div class="nx-co-card nx-co-summary">

                            <div class="nx-co-summary-head">
                                <small>Order Summary</small>
                                <strong>{{ $room->name }}</strong>
                            </div>

                            <div class="nx-co-summary-body">

                                <div class="nx-co-line">
                                    <span>Hourly Price</span>
                                    <strong>${{ number_format($room->hourly_price, 2) }}</strong>
                                </div>

                                <div class="nx-co-line">
                                    <span>Duration</span>
                                    <strong>{{ number_format($hours, 2) }} hours</strong>
                                </div>

                                <div class="nx-co-total">
                                    <span>Total</span>
                                    <strong>${{ number_format($total, 2) }}</strong>
                                </div>

                                {{-- Terms --}}
                                <div class="nx-co-terms">
                                    <input type="checkbox" class="form-check-input" name="terms" value="1"
                                        id="terms" required>
                                    <label for="terms">
                                        I agree to the booking terms and cancellation policy.
                                    </label>
                                </div>

                                {{-- Existing booking ID --}}
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    Proceed to Payment
                                </button>

                                <p class="nx-co-secure">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="4" y="11" width="16" height="10" rx="2"/>
                                        <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                                    </svg>
                                    Secure payment through Stripe
                                </p>

                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection