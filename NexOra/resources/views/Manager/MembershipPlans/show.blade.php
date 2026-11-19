<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $plan->plan_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

    <a href="{{ route('manager.membership-plans.index') }}" class="text-decoration-none">
        &larr; Back to Membership Plans
    </a>

    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <div>
            <h3 class="fw-bold">{{ $plan->plan_name }}</h3>
            <p class="text-muted mb-0">Membership plan details</p>
        </div>

        <a href="{{ route('manager.membership-plans.edit', $plan->id) }}"
           class="btn btn-primary">Edit Plan</a>
    </div>

    <div class="row g-3">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Price</p>
                    <h3>{{ number_format($plan->price, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Duration</p>
                    <h3>{{ $plan->duration_days }} Days</h3>
                    <small>{{ ucfirst($plan->duration_type) }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Subscribers</p>
                    <h3>{{ $plan->memberships_count }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">Plan Information</h5>

            <p>
                <strong>Workspace:</strong>
                {{ $plan->workspace->name }}
            </p>

            <p>
                <strong>Status:</strong>

                @if($plan->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </p>

            <p><strong>Description:</strong></p>

            <p class="text-muted">
                {{ $plan->description ?: 'No description provided.' }}
            </p>

            <a href="{{ route('manager.membership-plans.subscribers', $plan->id) }}"
               class="btn btn-outline-primary mt-2">
                View Subscribers
            </a>

        </div>
    </div>

</div>

</body>
</html>