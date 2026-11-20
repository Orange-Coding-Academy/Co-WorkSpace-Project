
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <a href="{{ route('member.bookings.show', $booking->id) }}"
       class="btn btn-outline-secondary mb-4">
        Back to Booking
    </a>

    <h3 class="fw-bold mb-2">Booking Checkout</h3>
    <p class="text-muted mb-4">
        Review your booking before proceeding to payment.
    </p>

    <!-- Progress -->
    <div class="d-flex justify-content-between mb-4">
        <span class="text-success fw-bold">1. Details</span>
        <span class="text-primary fw-bold">2. Payment</span>
        <span class="text-muted">3. Confirmation</span>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('member.stripe.checkout.booking') }}">
        @csrf

        <div class="row g-4">

            <!-- Booking details -->
            <div class="col-lg-7">

                <!-- Customer Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">Customer Information</h5>

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ auth()->user()->name }}"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   class="form-control"
                                   value="{{ auth()->user()->email }}"
                                   readonly>
                        </div>

                    </div>
                </div>

                <!-- Booking Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">Booking Details</h5>

                        <p>
                            <strong>Booking Reference:</strong>
                            #{{ $booking->id }}
                        </p>

                        <p>
                            <strong>Room:</strong>
                            {{ $room->name }}
                        </p>

                        <p>
                            <strong>Date:</strong>
                            {{ $start->format('d M Y') }}
                        </p>

                        <p>
                            <strong>Start:</strong>
                            {{ $start->format('h:i A') }}
                        </p>

                        <p>
                            <strong>End:</strong>
                            {{ $end->format('h:i A') }}
                        </p>

                        <p>
                            <strong>Duration:</strong>
                            {{ number_format($hours, 2) }} hours
                        </p>

                    </div>
                </div>

                <!-- Terms -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h5 class="fw-bold">Terms and Conditions</h5>

                        <div class="form-check mt-3">

                            <input type="checkbox"
                                   class="form-check-input"
                                   name="terms"
                                   value="1"
                                   id="terms"
                                   required>

                            <label for="terms" class="form-check-label">
                                I agree to the booking terms and cancellation policy.
                            </label>

                        </div>

                    </div>
                </div>

            </div>

            <!-- Price summary -->
            <div class="col-lg-5">

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">Order Summary</h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Room</span>
                            <strong>{{ $room->name }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Hourly Price</span>
                            <strong>
                                ${{ number_format($room->hourly_price, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Duration</span>
                            <strong>
                                {{ number_format($hours, 2) }} hours
                            </strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <h5>Total</h5>
                            <h5 class="text-primary">
                                ${{ number_format($total, 2) }}
                            </h5>
                        </div>

                        <!-- Existing booking ID -->
                        <input type="hidden"
                               name="booking_id"
                               value="{{ $booking->id }}">

                        <button type="submit"
                                class="btn btn-primary w-100 py-3">
                            Proceed to Payment
                        </button>

                        <p class="text-muted text-center small mt-3 mb-0">
                            You will be redirected to Stripe for secure payment.
                        </p>

                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

</body>
</html>
