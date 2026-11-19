<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Plans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Membership Plans</h3>
            <p class="text-muted mb-0">Manage your workspace membership offers.</p>
        </div>

        <a href="{{ route('manager.membership-plans.create') }}" class="btn btn-primary">
            + Create Plan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Workspace</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Subscribers</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td><strong>{{ $plan->plan_name }}</strong></td>
                                <td>{{ $plan->workspace->name }}</td>
                                <td>{{ number_format($plan->price, 2) }}</td>
                                <td>
                                    {{ ucfirst($plan->duration_type) }}
                                    ({{ $plan->duration_days }} days)
                                </td>
                                <td>{{ $plan->memberships_count }}</td>

                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('manager.membership-plans.show', $plan->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>

                                        <a href="{{ route('manager.membership-plans.edit', $plan->id) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            Edit
                                        </a>

                                        <form action="{{ route('manager.membership-plans.status', $plan->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="is_active"
                                                   value="{{ $plan->is_active ? 0 : 1 }}">

                                            <button type="submit"
                                                    class="btn btn-sm {{ $plan->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    No membership plans found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $plans->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>

</body>
</html>