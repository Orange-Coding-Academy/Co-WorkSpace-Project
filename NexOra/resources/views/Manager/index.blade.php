@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Manager Reviews @endsection
@section('page-title') Manager Reviews @endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-15.css') }}?v=20261124-ui1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-04.css') }}">
@endpush

@section('content')
<div class="nx-page-content">
<div class="page">

    <div class="mb-4">
        <h2 class="fw-bold">Customer Reviews</h2>
        <p class="accent">
            View feedback submitted by customers.
        </p>
    </div>

    <div class="card">
        <small class="total">Total Reviews</small>
        <h2 class="fw-bold mb-0">{{ $reviews->total() }}</h2>
    </div>

    <div class="card-box overflow-hidden">

        <div class="p-4 border-bottom">
            <h5 class="all">All Reviews</h5>
        </div>

        @if($reviews->count())

            <div class="table-responsive">
                <table class="table table-hover mb-0">

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Visibility</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($reviews as $review)

                            <tr>
                                <td class="fw-semibold">
                                    {{ $review->user->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    <span class="rating">
                                        ★ {{ $review->rating }}/5
                                    </span>
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit($review->comment ?? 'No comment', 45) }}
                                </td>

                                <td>
                                    @if($review->is_visible)
                                        <span class="badge badge-visible">
                                            Visible
                                        </span>
                                    @else
                                        <span class="badge badge-hidden">
                                            Hidden
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $review->created_at->format('d M Y') }}
                                </td>

                                <td>
                                    <button
                                        type="button"
                                        class="view-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#reviewModal"
                                        data-url="{{ route('manager.reviews.show', $review->id) }}">

                                        View

                                    </button>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

            <div class="p-3">
                {{ $reviews->links() }}
            </div>

        @else

            <div class="text-center p-5">
                <h5>No reviews found</h5>
                <p class="text-secondary mb-0">
                    There are currently no reviews.
                </p>
            </div>

        @endif

    </div>

</div>


{{-- Review Details Modal --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content" id="reviewModalContent">

            <div class="modal-body text-center p-5">
                Loading review...
            </div>

        </div>

    </div>

</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const reviewModal = document.getElementById('reviewModal');

    reviewModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;
        const url = button.getAttribute('data-url');
        const content = document.getElementById('reviewModalContent');

        content.innerHTML = `
            <div class="modal-body text-center p-5">
                Loading review...
            </div>
        `;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Could not load review.');
                }

                return response.text();
            })
            .then(html => {
                content.innerHTML = html;
            })
            .catch(() => {
                content.innerHTML = `
                    <div class="modal-body text-center p-5 text-danger">
                        Failed to load review details.
                    </div>
                `;
            });

    });
</script>
@endpush
