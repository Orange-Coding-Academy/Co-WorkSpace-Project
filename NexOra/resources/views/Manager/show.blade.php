<div class="modal-header border-0 pb-0">     <div>         <h5 class="modal-title fw-bold">Review Details\</h5>         
    <small class="text-secondary">             Review #{{ $review->id }}         </small>     </div>

<button type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close">
</button>

</div>

<div class="modal-body p-4">

<style>
    .review-label {
        color: #7F8B84;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .review-value {
        font-weight: 600;
        margin-bottom: 20px;
    }

    .review-rating {
        color: #e9a526;
        font-size: 24px;
        font-weight: 700;
    }

    .review-comment {
        background: #F8FAF7;
        border: 1px solid #E9EEE9;
        border-radius: 10px;
        padding: 18px;
        line-height: 1.7;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
        min-height: 80px;
    }
</style>

<h6 class="fw-bold mb-3">Customer Information</h6>

<div class="review-label">Customer</div>
<div class="review-value">
    {{ $review->user->name ?? 'Unknown' }}
</div>

<div class="review-label">Customer ID</div>
<div class="review-value">
    {{ $review->user_id }}
</div>

<div class="review-label">Submitted Date</div>
<div class="review-value">
    {{ $review->created_at->format('d M Y, h:i A') }}
</div>

<hr>

<h6 class="fw-bold mb-3">Customer Feedback</h6>

<div class="review-label">Rating</div>

<div class="review-rating mb-3">
    ★ {{ $review->rating }} / 5
</div>

<div class="review-label">Comment</div>

<div class="review-comment">
    {{ $review->comment ?: 'No comment provided.' }}
</div>

<div class="mt-4">

    <div class="review-label">Visibility</div>

    @if($review->is_visible)
        <span class="badge text-bg-success">Visible</span>
    @else
        <span class="badge text-bg-warning">Hidden</span>
    @endif

</div>

</div>

<div class="modal-footer border-0">     <button type="button"             class="btn btn-secondary"             data-bs-dismiss="modal">         Close     </button> </div>
