
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payments | NexOra Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
            color: #212529;
        }

        .stat-card {
            border: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            border-radius: 12px;
        }

        .stat-card h3 {
            font-weight: 700;
        }

        .content-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }

        .table th {
            white-space: nowrap;
            font-size: 13px;
            color: #64748b;
        }

        .table td {
            font-size: 14px;
        }

        .transaction-id {
            max-width: 170px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }
    </style>
</head>

<body>

<div class="container-fluid p-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Payments</h2>
            <p class="text-muted mb-0">
                Monitor and manage all payment transactions.
            </p>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Revenue</p>
                    <h3 class="mb-0">
                        {{ number_format($stats['total_revenue'], 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Successful Payments</p>
                    <h3 class="text-success mb-0">
                        {{ $stats['successful_payments'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Pending Payments</p>
                    <h3 class="text-warning mb-0">
                        {{ $stats['pending_payments'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Failed Payments</p>
                    <h3 class="text-danger mb-0">
                        {{ $stats['failed_payments'] }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Revenue Chart --}}
    <div class="card content-card mb-4">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">Revenue Overview</h5>

            <form method="GET"
                  action="{{ route('admin.payments.index') }}"
                  class="row g-2 mb-4">

                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="provider" value="{{ request('provider') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <input type="date"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <input type="date"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="form-control">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary me-2">Apply</button>
                    <a href="{{ route('admin.payments.index') }}"
                       class="btn btn-outline-secondary">Reset</a>
                </div>

            </form>

            <canvas id="revenueChart" height="90"></canvas>

        </div>
    </div>

    {{-- Payments Table --}}
    <div class="card content-card">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">All Transactions</h5>

                <span class="text-muted">
                    {{ $payments->total() }} payments
                </span>
            </div>

            {{-- Filters --}}
            <form method="GET"
                  action="{{ route('admin.payments.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Customer, email or transaction ID"
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        <option value="paid" @selected(request('status') === 'paid')>
                            Succeeded
                        </option>
                        <option value="pending" @selected(request('status') === 'pending')>
                            Pending
                        </option>
                        <option value="failed" @selected(request('status') === 'failed')>
                            Failed
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Provider</label>
                    <select name="provider" class="form-select">
                        <option value="">All providers</option>
                        <option value="stripe" @selected(request('provider') === 'stripe')>
                            Stripe
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date"
                           name="date_from"
                           class="form-control"
                           value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="date"
                           name="date_to"
                           class="form-control"
                           value="{{ request('date_to') }}">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>

                <div class="col-12">
                    <a href="{{ route('admin.payments.index') }}"
                       class="btn btn-sm btn-outline-secondary">
                        Clear filters
                    </a>
                </div>

            </form>

            {{-- Table --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Provider</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Details</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($payments as $payment)

                            <tr>
                                <td>#{{ $payment->id }}</td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $payment->user?->name ?? 'Unknown' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $payment->user?->email ?? 'N/A' }}
                                    </small>
                                </td>

                                <td class="fw-semibold">
                                    {{ number_format($payment->amount, 2) }}
                                    {{ strtoupper($payment->currency ?? 'USD') }}
                                </td>

                                <td>
                                    {{ ucfirst($payment->provider ?? 'N/A') }}
                                </td>

                                <td>
                                    @if($payment->status === 'paid')
                                        <span class="badge bg-success">Succeeded</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $payment->created_at?->format('d M Y, H:i') ?? 'N/A' }}
                                </td>

                                <td>
                                    <small class="text-muted transaction-id"
                                           title="{{ $payment->stripe_payment_intent_id ?? $payment->stripe_checkout_session_id }}">
                                        {{ $payment->stripe_payment_intent_id
                                            ?? $payment->stripe_checkout_session_id
                                            ?? 'N/A' }}
                                    </small>
                                </td>

                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            onclick="showPayment({{ $payment->id }})">
                                        View
                                    </button>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center text-muted py-5">
                                    No payments found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            {{ $payments->links('pagination::bootstrap-5') }}

        </div>
    </div>

</div>

{{-- Payment Details Modal --}}
<div class="modal fade"
     id="paymentModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Payment Details</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body" id="paymentModalBody">
                Loading...
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Revenue Chart
    const chartData = @json($chart);

    const chartElement = document.getElementById('revenueChart');

    new Chart(chartElement, {
        type: 'line',

        data: {
            labels: chartData.map(item => item.date),

            datasets: [{
                label: 'Revenue',
                data: chartData.map(item => item.amount),
                borderWidth: 2,
                tension: 0.35,
                fill: true
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: true,

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    // Payment Details Modal
    async function showPayment(id) {

        const modalElement = document.getElementById('paymentModal');
        const modalBody = document.getElementById('paymentModalBody');

        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

        modalBody.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p class="text-muted mt-2">
                    Loading payment details...
                </p>
            </div>
        `;

        modal.show();

        try {

            const response = await fetch(`/admin/payments/${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Unable to load payment details.');
            }

            modalBody.innerHTML = await response.text();

        } catch (error) {

            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    ${error.message}
                </div>
            `;
        }
    }
</script>

</body>
</html>
