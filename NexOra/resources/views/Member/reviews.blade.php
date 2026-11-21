

@extends('layouts.dashboard')

@section('dashboard-role', 'member')

@section('title') My Reviews | NexOra @endsection
@section('page-title') My Reviews @endsection

@push('styles')
<style>
/* Member Reviews — page-scoped NexOra styling */
.nx-page-content { color: var(--nx-text, #1D2924); font-family: 'Inter', 'Noto Sans Arabic', sans-serif; }
.nx-page-content *, .nx-page-content *::before, .nx-page-content *::after { box-sizing: border-box; }
.nx-page-content .reviews-container { max-width: 1100px; margin: 24px auto; padding: 0 16px 40px; }
.nx-page-content .page-header { margin-bottom: 26px; }
.nx-page-content .page-title { margin: 0 0 8px; font-family: 'Manrope', sans-serif; font-size: clamp(25px, 3vw, 32px); font-weight: 800; letter-spacing: -.035em; color: #152A23; }
.nx-page-content .subtitle { margin: 0; font-size: 14px; line-height: 1.65; color: #53615A; }
.nx-page-content .reviews-card { padding: 28px; border: 1px solid #E2E8E2; border-radius: 18px; background: #fff; box-shadow: 0 5px 22px rgba(29,41,36,.045); }
.nx-page-content .section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
.nx-page-content .section-title { margin: 0; font-family: 'Manrope', sans-serif; font-size: 20px; font-weight: 750; color: #152A23; }
.nx-page-content .btn, .nx-page-content .delete-cancel, .nx-page-content .delete-confirm { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; padding: 10px 20px; border: 1px solid transparent; border-radius: 999px; font: 700 14px 'Manrope', sans-serif; text-decoration: none; cursor: pointer; transition: background-color .2s, border-color .2s, color .2s, transform .2s; }
.nx-page-content .btn:active, .nx-page-content .delete-confirm:active { transform: scale(.98); }
.nx-page-content .btn-primary { background: #315C4D; border-color: #315C4D; color: #fff; }
.nx-page-content .btn-primary:hover { background: #274B3F; border-color: #274B3F; color: #fff; }
.nx-page-content .btn-outline-primary, .nx-page-content .btn-outline { background: #fff; border-color: #C9D4CC; color: #315C4D; }
.nx-page-content .btn-outline-primary:hover, .nx-page-content .btn-outline:hover { background: #EDF2EC; color: #203D34; }
.nx-page-content .btn-outline-danger, .nx-page-content .btn-danger { background: #fff; border-color: #F0C7C3; color: #A42D25; }
.nx-page-content .btn-outline-danger:hover, .nx-page-content .btn-danger:hover { background: #FDEDEC; color: #912018; }
.nx-page-content .btn-sm { min-height: 38px; padding: 8px 15px; font-size: 13px; }
.nx-page-content .review-item { padding: 24px; margin-bottom: 16px; border: 1px solid #E2E8E2; border-radius: 14px; background: #fff; transition: border-color .2s, box-shadow .2s; }
.nx-page-content .review-item:hover { border-color: #C9D4CC; box-shadow: 0 5px 16px rgba(29,41,36,.045); }
.nx-page-content .review-item:last-child { margin-bottom: 0; }
.nx-page-content .review-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 12px; }
.nx-page-content .workspace-name { margin: 0 0 7px; font-family: 'Manrope', sans-serif; font-size: 18px; font-weight: 750; color: #152A23; overflow-wrap: anywhere; }
.nx-page-content .review-date { font-size: 12px; color: #7F8B84; }
.nx-page-content .rating { color: #C58A62; font-size: 22px; letter-spacing: 2px; white-space: nowrap; line-height: 1.3; }
.nx-page-content .review-comment { margin: 12px 0 20px; color: #53615A; font-size: 14px; line-height: 1.8; overflow-wrap: anywhere; }
.nx-page-content .review-actions { display: flex; flex-wrap: wrap; gap: 10px; }
.nx-page-content .empty-state { padding: 52px 16px; text-align: center; }
.nx-page-content .empty-icon { display: flex; align-items: center; justify-content: center; width: 76px; height: 76px; margin: 0 auto 20px; border-radius: 50%; background: #E7EEE7; color: #315C4D; }
.nx-page-content .empty-icon svg { width: 36px; height: 36px; }
.nx-page-content .empty-state h3 { margin: 0 0 10px; font: 750 20px 'Manrope', sans-serif; color: #152A23; }
.nx-page-content .empty-state p { max-width: 470px; margin: 0 auto 22px; font-size: 14px; line-height: 1.75; color: #53615A; }
/* CSS :target popup remains functional without JavaScript */
.nx-page-content .delete-modal { position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px; background: rgba(21,42,35,.62); }
.nx-page-content .delete-modal:target { display: flex; }
.nx-page-content .delete-modal-box { width: 100%; max-width: 430px; padding: 32px 28px; border: 1px solid #E2E8E2; border-radius: 20px; background: #fff; text-align: center; box-shadow: 0 18px 48px rgba(0,0,0,.18); }
.nx-page-content .delete-icon { display: flex; align-items: center; justify-content: center; width: 64px; height: 64px; margin: 0 auto 18px; border-radius: 50%; background: #FDEDEC; color: #B42318; font-size: 32px; font-weight: 800; }
.nx-page-content .delete-modal-box h2 { margin: 0 0 12px; font: 800 23px 'Manrope', sans-serif; color: #152A23; }
.nx-page-content .delete-modal-box p { margin: 0 0 26px; color: #53615A; font-size: 14px; line-height: 1.75; }
.nx-page-content .delete-actions { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 12px; }
.nx-page-content .delete-cancel { background: #F0F2EF; border-color: #E2E8E2; color: #203D34; }
.nx-page-content .delete-cancel:hover { background: #E3EBE2; }
.nx-page-content .delete-confirm { background: #B42318; border-color: #B42318; color: #fff; }
.nx-page-content .delete-confirm:hover { background: #912018; border-color: #912018; }
.nx-page-content :is(a, button):focus-visible { outline: 3px solid #C58A62; outline-offset: 3px; }
@media (max-width: 650px) { .nx-page-content .reviews-container { margin-top: 16px; padding-inline: 12px; } .nx-page-content .reviews-card { padding: 18px; } .nx-page-content .review-item { padding: 18px; } .nx-page-content .review-top { flex-direction: column; gap: 8px; } .nx-page-content .section-header { align-items: flex-start; } .nx-page-content .delete-modal-box { padding: 26px 18px; } }
@media (prefers-reduced-motion: reduce) { .nx-page-content .btn, .nx-page-content .review-item { transition: none; } }
</style>
@endpush

@section('content')
<div class="nx-page-content">
<div class="reviews-container">

    <div class="page-header">
        <h1 class="page-title">My Reviews</h1>
        <p class="subtitle">
            View and manage the reviews you've shared about workspaces.
        </p>
    </div>

    <div class="reviews-card">

        <div class="section-header">
            <h2 class="section-title">Your Reviews</h2>

        <a href="{{ route('member.reviews.create') }}" class="btn btn-primary">
    + Write a Review
</a>
        </div>

        <!-- Review Card: Example HTML for styling -->
        <!-- Replace this example with your Blade loop later -->

       @foreach($reviews as $review)
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
                           @for($i = 1; $i <= $review->rating; $i++)
                                    ★
                             @endfor
                   </div>
            </div>

            <p class="review-comment">
                 {{ $review->comment }}
            </p>

            <div class="review-actions">

              <a href="{{ route('member.reviews.edit', $review->id) }}"
   class="btn btn-sm btn-outline-primary">
    Edit Review
</a>

                <a href="#delete-review-{{ $review->id }}"
   class="btn btn-sm btn-outline-danger">
    Delete Review
</a>

<div id="delete-review-{{ $review->id }}" class="delete-modal">
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

            <form action="{{ route('member.reviews.destroy', $review->id) }}"
                  method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="delete-confirm">
                    Yes, Delete
                </button>
            </form>

        </div>
    </div>
</div>
            </div>

        </div>
        @endforeach

        <!-- Empty State: Show when member has no reviews -->
        <!-- You'll control its visibility using Blade later -->
          @if ($reviews->isEmpty())
          
          
        <div class="empty-state">

            <div class="empty-icon">
                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.6"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>
                </svg>
            </div>

            <h3>No Reviews Yet</h3>

            <p>
                You haven't written any reviews yet.
                Once you've completed a booking, you can share
                your experience with the workspace.
            </p>

            <a href="#" class="btn btn-primary">
                Explore Workspaces
            </a>

        </div>
        @endif

    </div>

</div>
</div>
@endsection
