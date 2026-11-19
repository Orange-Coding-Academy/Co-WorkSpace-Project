
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <a href="{{ route('member.bookings.index') }}"
       class="btn btn-outline-secondary mb-4">
        Back to My Bookings
    </a>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between">
                <h4 class="fw-bold">Booking #{{ $booking->id }}</h4>

                <span class="badge bg-primary">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <hr>

            <h5>Room Information</h5>

            <p><strong>Room:</strong> {{ $room->name }}</p>
            <p><strong>Hourly Price:</strong> {{ number_format($room->hourly_price, 2) }}</p>

            <hr>

            <h5>Reservation Information</h5>

            <p>
                <strong>Start:</strong>
                {{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y h:i A') }}
            </p>

            <p>
                <strong>End:</strong>
                {{ \Carbon\Carbon::parse($booking->end_date_time)->format('d M Y h:i A') }}
            </p>

            <p>
                <strong>Total:</strong>
                {{ number_format($booking->total_price, 2) }}
            </p>

            <hr>

            <h5>Payment Information</h5>

            @if($payment)
                <p><strong>Payment ID:</strong> #{{ $payment->id }}</p>
                <p><strong>Amount:</strong> {{ number_format($payment->amount, 2) }} {{ $payment->currency }}</p>
                <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
            @else
                <p class="text-muted">No payment record found.</p>
            @endif


            <hr>

@if($booking->status === 'pending' && !$payment)

    <div class="alert alert-warning">
        Your booking is awaiting payment.
    </div>

    <a href="{{ route('member.bookings.checkout', $booking->id) }}"
   class="btn btn-primary w-100">
    Pay Now
</a>

@endif
        </div>
    </div>

</div>

</body>
</html>
