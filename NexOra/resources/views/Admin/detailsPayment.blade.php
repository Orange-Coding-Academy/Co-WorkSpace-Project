
<div>
    {{-- Payment Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Payment #{{ $payment->id }}</h5>
            <small class="text-muted">
                {{ $payment->created_at?->format('d M Y, H:i') ?? 'N/A' }}
            </small>
        </div>

        @if($payment->status === 'paid')
            <span class="badge bg-success">Succeeded</span>
        @elseif($payment->status === 'pending')
            <span class="badge bg-warning text-dark">Pending</span>
        @else
            <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
        @endif
    </div>

    {{-- Amount --}}
    <div class="bg-light rounded p-4 text-center mb-4">
        <small class="text-muted">Payment Amount</small>
        <h2 class="fw-bold mt-2">
            {{ number_format($payment->amount, 2) }}
            {{ strtoupper($payment->currency ?? 'USD') }}
        </h2>
    </div>

    {{-- Payment Information --}}
    <h6 class="fw-bold mb-3">Payment Information</h6>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <small class="text-muted">Payment ID</small>
            <div>{{ $payment->id }}</div>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Provider</small>
            <div>{{ ucfirst($payment->provider ?? 'N/A') }}</div>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Currency</small>
            <div>{{ strtoupper($payment->currency ?? 'USD') }}</div>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Paid At</small>
            <div>
                {{ $payment->paid_at?->format('d M Y, H:i') ?? 'Not paid' }}
            </div>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Created At</small>
            <div>
                {{ $payment->created_at?->format('d M Y, H:i') ?? 'N/A' }}
            </div>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Last Updated</small>
            <div>
                {{ $payment->updated_at?->format('d M Y, H:i') ?? 'N/A' }}
            </div>
        </div>
    </div>

    <hr>

    {{-- Customer Information --}}
    <h6 class="fw-bold mb-3">Customer Information</h6>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <small class="text-muted">Customer</small>
            <div>{{ $payment->user?->name ?? 'N/A' }}</div>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Email</small>
            <div>{{ $payment->user?->email ?? 'N/A' }}</div>
        </div>
    </div>

    <hr>

    {{-- Related Information --}}
    <h6 class="fw-bold mb-3">Related Information</h6>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <small class="text-muted">Booking ID</small>
            <div>{{ $payment->booking?->id ?? 'N/A' }}</div>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Membership ID</small>
            <div>{{ $payment->membership?->id ?? 'N/A' }}</div>
        </div>
    </div>

    <hr>

    {{-- Transaction Information --}}
    <h6 class="fw-bold mb-3">Transaction Information</h6>

    <div class="mb-3">
        <small class="text-muted">Stripe Checkout Session</small>
        <div class="text-break">
            {{ $payment->stripe_checkout_session_id ?? 'N/A' }}
        </div>
    </div>

    <div class="mb-3">
        <small class="text-muted">Stripe Payment Intent</small>
        <div class="text-break">
            {{ $payment->stripe_payment_intent_id ?? 'N/A' }}
        </div>
    </div>
</div>
