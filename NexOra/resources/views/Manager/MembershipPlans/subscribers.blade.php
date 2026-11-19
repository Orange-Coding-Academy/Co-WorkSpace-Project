<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Subscribers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

    <a href="{{ route('manager.membership-plans.show', $plan->id) }}"
       class="text-decoration-none">
        &larr; Back to Plan
    </a>

    <div class="mt-3 mb-4">
        <h3 class="fw-bold">Subscribers</h3>
        <p class="text-muted mb-0">
            Members subscribed to {{ $plan->plan_name }}.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Email</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($subscribers as $membership)
                            <tr>
                                <td>{{ $membership->user->name }}</td>
                                <td>{{ $membership->user->email }}</td>
                                <td>{{ $membership->start_date }}</td>
                                <td>{{ $membership->end_date }}</td>

                                <td>
                                    @php
                                        $statusColors = [
                                            'active' => 'success',
                                            'pending' => 'warning',
                                            'expired' => 'secondary',
                                            'cancelled' => 'danger',
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $statusColors[$membership->status] ?? 'secondary' }}">
                                        {{ ucfirst($membership->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No subscribers found for this plan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{ $subscribers->links('pagination::bootstrap-5') }}

        </div>
    </div>

</div>

</body>
</html>