@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Membership Subscribers @endsection
@section('page-title') Membership Subscribers @endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-04.css') }}">
@endpush

@section('content')
<div class="nx-page-content bg-light">
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
</div>
@endsection
