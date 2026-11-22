@extends('layouts.dashboard')

@section('dashboard-role', 'admin')
@section('title', 'Payments | NexOra Admin')
@section('page-title', 'Payments')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin-payments.css') }}?v={{ @filemtime(public_path('css/pages/admin-payments.css')) }}">
@endpush

@section('content')
<div class="nx-payments">

    {{-- Page heading --}}
    <div class="payments-heading">
        <div>
            <span class="payments-eyebrow">FINANCE</span>
            <h1>Payments</h1>
            <p>Monitor revenue and manage customer transactions.</p>
        </div>
    </div>

    {{-- KPI cards --}}
    <section class="payments-kpis" aria-label="Payment statistics">

        <article class="payment-kpi">
            <div class="kpi-top">
                <span class="kpi-label">Total revenue</span>
                <span class="kpi-icon revenue-icon" aria-hidden="true">↗</span>
            </div>
            <div class="kpi-value">{{ number_format($stats['total_revenue'], 2) }}</div>
            <span class="kpi-caption">From successful payments</span>
        </article>

        <article class="payment-kpi">
            <div class="kpi-top">
                <span class="kpi-label">Successful payments</span>
                <span class="kpi-icon success-icon" aria-hidden="true">✓</span>
            </div>
            <div class="kpi-value">{{ number_format($stats['successful_payments']) }}</div>
            <span class="kpi-caption">Completed transactions</span>
        </article>

        <article class="payment-kpi">
            <div class="kpi-top">
                <span class="kpi-label">Pending payments</span>
                <span class="kpi-icon warning-icon" aria-hidden="true">◷</span>
            </div>
            <div class="kpi-value">{{ number_format($stats['pending_payments']) }}</div>
            <span class="kpi-caption">Awaiting confirmation</span>
        </article>

        <article class="payment-kpi">
            <div class="kpi-top">
                <span class="kpi-label">Failed payments</span>
                <span class="kpi-icon danger-icon" aria-hidden="true">!</span>
            </div>
            <div class="kpi-value">{{ number_format($stats['failed_payments']) }}</div>
            <span class="kpi-caption">Unsuccessful transactions</span>
        </article>

    </section>

    {{-- One filter bar for the whole page --}}
    @php
        $hasFilters = request()->filled('search') || request()->filled('status')
            || request()->filled('provider') || request()->filled('date_from')
            || request()->filled('date_to');
    @endphp

    <form method="GET"
          action="{{ route('admin.payments.index') }}"
          class="payments-filters"
          id="paymentsFilters">

        <div class="filter-field search-field">
            <label for="payment-search">Search</label>
            <input id="payment-search"
                   type="search"
                   name="search"
                   placeholder="Customer, email or transaction ID"
                   value="{{ request('search') }}">
        </div>

        <div class="filter-field">
            <label for="payment-status">Status</label>
            <select id="payment-status" name="status">
                <option value="">All</option>
                <option value="paid" @selected(request('status') === 'paid')>Succeeded</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="failed" @selected(request('status') === 'failed')>Failed</option>
            </select>
        </div>

        <div class="filter-field">
            <label for="payment-provider">Provider</label>
            <select id="payment-provider" name="provider">
                <option value="">All</option>
                <option value="stripe" @selected(request('provider') === 'stripe')>Stripe</option>
            </select>
        </div>

        <div class="filter-field">
            <label for="payment-date-from">From</label>
            <input id="payment-date-from" type="date" name="date_from" value="{{ request('date_from') }}">
        </div>

        <div class="filter-field">
            <label for="payment-date-to">To</label>
            <input id="payment-date-to" type="date" name="date_to" value="{{ request('date_to') }}">
        </div>

        <div class="filter-actions">
            <button type="submit" class="payments-btn payments-btn-primary">Apply</button>

            @if($hasFilters)
                <a href="{{ route('admin.payments.index') }}" class="payments-btn payments-btn-light">Clear</a>
            @endif
        </div>
    </form>

    {{-- Revenue chart --}}
    <section class="payment-panel revenue-panel">
        <div class="panel-heading">
            <div>
                <h2>Revenue overview</h2>
                <p>Successful payment revenue over time.</p>
            </div>
        </div>

        <div class="revenue-chart-wrap">
            <canvas id="revenueChart" aria-label="Revenue over time" role="img"></canvas>
        </div>
    </section>

    {{-- Transactions --}}
    <section class="payment-panel transactions-panel">

        <div class="panel-heading transactions-heading">
            <div>
                <h2>All transactions</h2>
                <p>
                    Showing {{ $payments->firstItem() ?? 0 }}–{{ $payments->lastItem() ?? 0 }}
                    of {{ number_format($payments->total()) }} payments
                </p>
            </div>
        </div>

        {{-- Table --}}
        <div class="payments-table-wrap">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Payment</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Provider</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Transaction ID</th>
                        <th><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($payments as $payment)
                        @php
                            $transactionId = $payment->stripe_payment_intent_id
                                ?? $payment->stripe_checkout_session_id
                                ?? 'N/A';
                        @endphp
                        <tr>
                            <td>
                                <span class="payment-reference">#{{ $payment->id }}</span>
                            </td>

                            <td>
                                <div class="customer-name">{{ $payment->user?->name ?? 'Unknown' }}</div>
                                <div class="customer-email">{{ $payment->user?->email ?? 'N/A' }}</div>
                            </td>

                            <td>
                                <span class="amount-value">{{ number_format($payment->amount, 2) }}</span>
                                <span class="amount-currency">{{ strtoupper($payment->currency ?? 'USD') }}</span>
                            </td>

                            <td>{{ ucfirst($payment->provider ?? 'N/A') }}</td>

                            <td>
                                @if($payment->status === 'paid')
                                    <span class="payment-status status-paid">Succeeded</span>
                                @elseif($payment->status === 'pending')
                                    <span class="payment-status status-pending">Pending</span>
                                @else
                                    <span class="payment-status status-failed">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>

                            <td class="payment-date">
                                {{ $payment->created_at?->format('d M Y, H:i') ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="transaction-id" title="{{ $transactionId }}">{{ $transactionId }}</span>
                            </td>

                            <td>
                                <button type="button"
                                        class="view-payment-btn"
                                        data-payment-id="{{ $payment->id }}">
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="payments-empty">
                                    <strong>No payments found</strong>
                                    <span>Try changing or clearing your filters.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination (self-contained, no Bootstrap needed) --}}
        @if($payments->hasPages())
            @php
                $payments->withQueryString();
                $currentPage = $payments->currentPage();
                $lastPage = $payments->lastPage();
                $windowStart = max(1, $currentPage - 1);
                $windowEnd = min($lastPage, $currentPage + 1);
            @endphp

            <div class="payments-pagination">
                <div class="pagination-summary">Page {{ $currentPage }} of {{ $lastPage }}</div>

                <nav aria-label="Payments pagination">
                    <ul class="pager">
                        <li>
                            @if($payments->onFirstPage())
                                <span class="pager-link is-disabled" aria-disabled="true">‹</span>
                            @else
                                <a class="pager-link" href="{{ $payments->previousPageUrl() }}" rel="prev" aria-label="Previous page">‹</a>
                            @endif
                        </li>

                        @if($windowStart > 1)
                            <li><a class="pager-link" href="{{ $payments->url(1) }}">1</a></li>
                            @if($windowStart > 2)
                                <li><span class="pager-link is-disabled">…</span></li>
                            @endif
                        @endif

                        @foreach(range($windowStart, $windowEnd) as $page)
                            <li>
                                @if($page === $currentPage)
                                    <span class="pager-link is-active" aria-current="page">{{ $page }}</span>
                                @else
                                    <a class="pager-link" href="{{ $payments->url($page) }}">{{ $page }}</a>
                                @endif
                            </li>
                        @endforeach

                        @if($windowEnd < $lastPage)
                            @if($windowEnd < $lastPage - 1)
                                <li><span class="pager-link is-disabled">…</span></li>
                            @endif
                            <li><a class="pager-link" href="{{ $payments->url($lastPage) }}">{{ $lastPage }}</a></li>
                        @endif

                        <li>
                            @if($payments->hasMorePages())
                                <a class="pager-link" href="{{ $payments->nextPageUrl() }}" rel="next" aria-label="Next page">›</a>
                            @else
                                <span class="pager-link is-disabled" aria-disabled="true">›</span>
                            @endif
                        </li>
                    </ul>
                </nav>
            </div>
        @endif

    </section>
</div>

{{-- Payment details side panel (self-contained, no Bootstrap needed) --}}
<div class="pay-modal-overlay" id="paymentOverlay" hidden></div>

<aside class="pay-modal"
       id="paymentPanel"
       role="dialog"
       aria-modal="true"
       aria-labelledby="paymentPanelTitle"
       aria-hidden="true"
       tabindex="-1"
       data-details-url="{{ url('/admin/payments') }}">

    <div class="pay-modal__header">
        <div>
            <h2 class="pay-modal__title" id="paymentPanelTitle">Payment details</h2>
            <p class="pay-modal__subtitle">Transaction information</p>
        </div>

        <button type="button" class="pay-modal__close" id="paymentPanelClose" aria-label="Close">
            &times;
        </button>
    </div>

    <div class="pay-modal__body" id="paymentPanelBody"></div>
</aside>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        window.paymentChartData = @json($chart);
    </script>
    <script src="{{ asset('js/pages/admin-payments.js') }}?v={{ @filemtime(public_path('js/pages/admin-payments.js')) }}"></script>
@endpush