@php
    $statusClass = match ($payment->status) {
        'paid'    => 'status-paid',
        'pending' => 'status-pending',
        default   => 'status-failed',
    };

    $statusLabel = $payment->status === 'paid' ? 'Succeeded' : ucfirst($payment->status);
@endphp

<div class="pd">

    <div class="pd-summary">
        <div>
            <span class="pd-label">Amount</span>
            <div class="pd-amount">
                {{ number_format($payment->amount, 2) }}
                <small>{{ strtoupper($payment->currency ?? 'USD') }}</small>
            </div>
        </div>

        <span class="payment-status {{ $statusClass }}">{{ $statusLabel }}</span>
    </div>

    <h3 class="pd-title">Customer</h3>
    <dl class="pd-grid">
        <div class="pd-row">
            <dt>Name</dt>
            <dd>{{ $payment->user?->name ?? 'Unknown' }}</dd>
        </div>
        <div class="pd-row">
            <dt>Email</dt>
            <dd>{{ $payment->user?->email ?? 'N/A' }}</dd>
        </div>
    </dl>

    <h3 class="pd-title">Transaction information</h3>
    <dl class="pd-grid">
        <div class="pd-row">
            <dt>Payment</dt>
            <dd>#{{ $payment->id }}</dd>
        </div>
        <div class="pd-row">
            <dt>Provider</dt>
            <dd>{{ ucfirst($payment->provider ?? 'N/A') }}</dd>
        </div>
        <div class="pd-row">
            <dt>Date</dt>
            <dd>{{ $payment->created_at?->format('d M Y, H:i') ?? 'N/A' }}</dd>
        </div>
        <div class="pd-row">
            <dt>Stripe Checkout Session</dt>
            <dd>{{ $payment->stripe_checkout_session_id ?? 'N/A' }}</dd>
        </div>
        <div class="pd-row">
            <dt>Stripe Payment Intent</dt>
            <dd>{{ $payment->stripe_payment_intent_id ?? 'N/A' }}</dd>
        </div>
    </dl>

</div>