
@extends('layouts.member')

@section('title')
    My Reviews
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/member-reviews.css') }}?v=20261126-ui3">
@endpush

@section('content')

<div class="nx-page-content">
    <div class="reviews-container">

        {{-- Page Header --}}
        <div class="page-header">
            <h1 class="page-title">My Reviews</h1>
            <p class="subtitle">
                View and manage the reviews you've shared about workspaces.
            </p>
        </div>

        <div class="reviews-card">

            {{-- Section Header --}}
            <div class="section-header">
                <h2 class="section-title">Your Reviews</h2>

                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="openReviewModal('createReviewModal')">
                    + Write a Review
                </button>
            </div>

            {{-- Reviews List --}}
            @forelse ($reviews as $review)

                <div class="review-item">

                    <div class="review-top">

                        <div>
                            <h3 class="workspace-name">
                                {{ $review->workspace->name }}
                            </h3>

                            <span class="review-date">
                                {{ $review->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <div class="rating">
                            @for ($i = 1; $i <= $review->rating; $i++)
                                ★
                            @endfor
                        </div>

                    </div>

                    <p class="review-comment">
                        {{ $review->comment }}
                    </p>

                    <div class="review-actions">

                        {{-- Edit Button --}}
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                            onclick="openReviewModal('editReviewModal{{ $review->id }}')">
                            Edit Review
                        </button>

                        {{-- Delete Button --}}
                        <a
                            href="#delete-review-{{ $review->id }}"
                            class="btn btn-sm btn-outline-danger">
                            Delete Review
                        </a>

                    </div>

                </div>

                {{-- Edit Review Modal --}}
                <div
                    id="editReviewModal{{ $review->id }}"
                    class="review-modal">

                    <div class="review-modal-box">

                        <button
                            type="button"
                            class="modal-close"
                            onclick="closeReviewModal('editReviewModal{{ $review->id }}')">
                            &times;
                        </button>

                        <h2>Edit Review</h2>

                        <b>
                            Update your rating and comment.
                        </b>

                        <form
                            action="{{ route('member.reviews.update', $review->id) }}"
                            method="POST">

                            @csrf
                            @method('PUT')

                            {{-- Workspace --}}
                            <div class="form-group">

                                <label class="form-label">
                                    Workspace
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $review->workspace->name }}"
                                    disabled>

                            </div>

                            {{-- Rating --}}
                            <div class="form-group">

                                <label class="form-label">
                                    Your Rating *
                                </label>

                                <select
                                    name="rating"
                                    class="form-control"
                                    required>

                                    <option value="5" {{ $review->rating == 5 ? 'selected' : '' }}>
                                        5 Stars
                                    </option>

                                    <option value="4" {{ $review->rating == 4 ? 'selected' : '' }}>
                                        4 Stars
                                    </option>

                                    <option value="3" {{ $review->rating == 3 ? 'selected' : '' }}>
                                        3 Stars
                                    </option>

                                    <option value="2" {{ $review->rating == 2 ? 'selected' : '' }}>
                                        2 Stars
                                    </option>

                                    <option value="1" {{ $review->rating == 1 ? 'selected' : '' }}>
                                        1 Star
                                    </option>

                                </select>

                            </div>

                            {{-- Comment --}}
                            <div class="form-group">

                                <label class="form-label">
                                    Your Comment (Optional)
                                </label>

                                <textarea
                                    name="comment"
                                    class="form-control"
                                    placeholder="Tell us about your experience...">{{ $review->comment }}</textarea>

                            </div>

                            {{-- Actions --}}
                            <div class="form-actions">

                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    onclick="closeReviewModal('editReviewModal{{ $review->id }}')">
                                    Cancel
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-primary">
                                    Save Changes
                                </button>

                            </div>

                        </form>

                    </div>
                </div>

                {{-- Delete Review Modal --}}
                <div
                    id="delete-review-{{ $review->id }}"
                    class="delete-modal">

                    <div class="delete-modal-box">

                        <div class="delete-icon">!</div>

                        <h2>Delete Review?</h2>

                        <p>
                            Are you sure you want to delete this review?
                            This action cannot be undone.
                        </p>

                        <div class="delete-actions">

                            <a href="#" class="delete-cancel">
                                Cancel
                            </a>

                            <form
                                action="{{ route('member.reviews.destroy', $review->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="delete-confirm">
                                    Yes, Delete
                                </button>

                            </form>

                        </div>

                    </div>
                </div>

            @empty

                {{-- Empty State --}}
                <div class="empty-state">

                    <div class="empty-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                            <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z" />

                        </svg>
                    </div>

                    <h3>No Reviews Yet</h3>

                    <p>
                        You haven't written any reviews yet.
                        Once you've completed a booking, you can share
                        your experience with the workspace.
                    </p>

                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="openReviewModal('createReviewModal')">
                        Write Your First Review
                    </button>

                </div>

            @endforelse

        </div>

    </div>
</div>


{{-- Create Review Modal --}}
<div id="createReviewModal" class="review-modal">

    <div class="review-modal-box">

        <button
            type="button"
            class="modal-close"
            onclick="closeReviewModal('createReviewModal')">
            &times;
        </button>

        <h2>Write a Review</h2>

        <b>
            Share your experience and help others discover
            the perfect workspace.
        </b>

        <form
            action="{{ route('member.reviews.store') }}"
            method="POST">

            @csrf

            {{-- Workspace --}}
            <div class="form-group">

                <label for="workspace_id" class="form-label">
                    Select Workspace *
                </label>

                <select
                    name="workspace_id"
                    id="workspace_id"
                    class="form-control"
                    required>

                    <option value="">Choose a workspace</option>

                    @foreach ($workspaces as $workspace)

                        <option value="{{ $workspace->id }}">
                            {{ $workspace->name }}
                        </option>

                    @endforeach

                </select>

                <span class="form-hint">
                    Only workspaces with completed bookings can be reviewed.
                </span>

            </div>

            {{-- Rating --}}
            <div class="form-group">

                <label class="form-label">
                    Your Rating *
                </label>

                <select 
                    name="rating"
                    class="form-control"
                    required>

                    <option value="">Choose your rating</option>
                    <option value="5">★★★★★ - 5 Stars</option>
                    <option value="4">★★★★ - 4 Stars</option>
                    <option value="3">★★★ - 3 Stars</option>
                    <option value="2">★★ - 2 Stars</option>
                    <option value="1">★ - 1 Star</option>

                </select>

                <span class="form-hint">
                    Select a rating from 1 to 5 stars.
                </span>

            </div>

            {{-- Comment --}}
            <div class="form-group">

                <label for="create_comment" class="form-label">
                    Your Comment (Optional)
                </label>

                <textarea
                    name="comment"
                    id="create_comment"
                    class="form-control"
                    placeholder="Tell us what you liked about this workspace..."></textarea>

            </div>

            {{-- Actions --}}
            <div class="form-actions">

                <button
                    type="button"
                    class="btn btn-secondary"
                    onclick="closeReviewModal('createReviewModal')">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-primary">
                    Submit Review
                </button>

            </div>

        </form>

    </div>
</div>


{{-- Modal JavaScript --}}
<script>
    function openReviewModal(id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.classList.add('active');
        }
    }

    function closeReviewModal(id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.classList.remove('active');
        }
    }

    // Close modal when clicking outside the modal box
    document.querySelectorAll('.review-modal').forEach(function(modal) {

        modal.addEventListener('click', function(event) {

            if (event.target === modal) {
                modal.classList.remove('active');
            }

        });

    });

    // Close modal using Escape key
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            document.querySelectorAll('.review-modal.active').forEach(function(modal) {
                modal.classList.remove('active');
            });

        }

    });
</script>

@endsection
