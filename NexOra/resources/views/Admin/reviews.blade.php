
@extends('layouts.dashboard')

@section('dashboard-role', 'admin')
@section('title', 'Reviews')
@section('page-title', 'Reviews')

@push('styles')
@include('layouts.dashboard.partials.app-compat-styles')
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@section('content')
<div class="nx-page-content py-4">

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Reviews & Feedback</h2>
            <p class="text-muted mb-0">
                Review and manage member feedback before publishing.
            </p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <h6 class="text-muted">Total Reviews</h6>
                <h3 class="fw-bold">{{ $totalReviews }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <h6 class="text-muted">Pending Reviews</h6>
                <h3 class="fw-bold text-warning">{{ $pendingReviews }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <h6 class="text-muted">Approved Reviews</h6>
                <h3 class="fw-bold text-success">{{ $approvedReviews }}</h3>
            </div>
        </div>

    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap gap-2">

            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
               class="btn {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                Pending
            </a>

            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
               class="btn {{ $status === 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">
                Approved
            </a>

            <a href="{{ route('admin.reviews.index', ['status' => 'all']) }}"
               class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                All Reviews
            </a>

        </div>
    </div>

    {{-- Reviews List --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Member Feedback</h5>
        </div>

        <div class="card-body">

            @forelse($reviews as $review)

                <div class="border rounded-3 p-3 mb-3">

                    <div class="d-flex justify-content-between flex-wrap">

                        <div>
                            <h6 class="fw-bold mb-1">
                                {{ $review->user->name ?? 'Deleted Member' }}
                            </h6>

                            <small class="text-muted">
                                {{ $review->user->email ?? '' }}
                            </small>

                            <div class="mt-2">
                                <strong>
                                    {{ $review->workspace->name ?? 'Deleted Workspace' }}
                                </strong>
                            </div>
                        </div>

                        <div class="text-end">

                            <div class="text-warning fs-5">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </div>

                            <small class="text-muted">
                                {{ $review->created_at->format('d M Y') }}
                            </small>

                            <div class="mt-2">
                                @if($review->is_visible)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending / Hidden</span>
                                @endif
                            </div>

                        </div>

                    </div>

                    <hr>

                    <p class="mb-3">
                        {{ $review->comment ?: 'No written feedback.' }}
                    </p>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 flex-wrap">

                        @if(!$review->is_visible)

                            <form method="POST"
                                  action="{{ route('admin.reviews.approve', $review->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle"></i>
                                    Approve
                                </button>
                            </form>

                        @else

                            <form method="POST"
                                  action="{{ route('admin.reviews.hide', $review->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="bi bi-eye-slash"></i>
                                    Hide
                                </button>
                            </form>

                        @endif

                        <form method="POST"
                              action="{{ route('admin.reviews.destroy', $review->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete this review?')">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text fs-1 text-muted"></i>
                    <h5 class="mt-3">No Reviews Found</h5>
                    <p class="text-muted">
                        There are no reviews to display at the moment.
                    </p>
                </div>

            @endforelse

            <div class="mt-3">
                {{ $reviews->links() }}
            </div>

        </div>
    </div>

</div>

</div>
@endsection
