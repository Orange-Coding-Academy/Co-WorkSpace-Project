<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/layouts-app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}?v=20261125-green3">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 600px;">
    <h3 class="mb-4">Stripe payment test</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Room booking --}}
    <form action="{{ route('stripe.checkout.booking') }}" method="POST" class="card card-body mb-4">
        @csrf
        <h5>Book a room</h5>

        <label class="form-label mt-2">Room</label>
        <select name="room_id" class="form-select" required>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->name }} - ${{ $room->hourly_price }}/hour</option>
            @endforeach
        </select>

        <label class="form-label mt-3">Start</label>
        <input type="datetime-local" name="start_at" class="form-control" value="{{ old('start_at') }}" required>

        <label class="form-label mt-3">End</label>
        <input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at') }}" required>

        <button type="submit" class="btn btn-primary mt-3">Pay for booking</button>
    </form>

    {{-- Membership --}}
    <form action="{{ route('stripe.checkout.membership') }}" method="POST" class="card card-body">
        @csrf
        <h5>Buy a membership</h5>

        <label class="form-label mt-2">Plan</label>
        <select name="plan_id" class="form-select" required>
            @foreach ($plans as $plan)
            <option value="{{ $plan->id }}">{{ $plan->plan_name }} - ${{ $plan->price }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-success mt-3">Pay for membership</button>
    </form>

    <p class="text-muted mt-4 small">
        Test card: 4242 4242 4242 4242, any future date, any CVC.
    </p>
</div>
</body>
</html>