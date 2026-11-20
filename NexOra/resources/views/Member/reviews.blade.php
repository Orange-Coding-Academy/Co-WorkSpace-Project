{{ 
     /* 
     use App\Http\Controllers\Member\MemberReviewController;

 Route::get('/member/reviews',[MemberReviewController::class,'index'])
        ->name('member.reviews.index');

 Route::get('/member/reviews/create', [MemberReviewController::class, 'createreview'])
    ->name('member.reviews.create');
 
 Route::post('/member/reviews', [MemberReviewController::class, 'store'])
    ->name('member.reviews.store');

 Route::get('/member/reviews/{id}/edit', [MemberReviewController::class, 'edit'])
    ->name('member.reviews.edit');

Route::put('/member/reviews/{id}', [MemberReviewController::class, 'update'])
    ->name('member.reviews.update');

Route::delete('/member/reviews/{id}', [MemberReviewController::class, 'destroy'])
    ->name('member.reviews.destroy'); */}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews | NexOra</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #1c293b;
            font-family: Arial, sans-serif;
        }

        .reviews-container {
            max-width: 1050px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 29px;
            margin: 0 0 10px;
        }

        .subtitle {
            color: #788596;
            font-size: 14px;
            margin: 0;
        }

        .reviews-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.04);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 20px;
            margin: 0;
            color: #183047;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            border: none;
            padding: 11px 18px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-primary {
            background: #119e95;
            color: #fff;
        }

        .btn-primary:hover {
            background: #087e77;
        }

        .btn-outline {
            background: #fff;
            border: 1px solid #dce3eb;
            color: #334155;
        }

        .btn-outline:hover {
            background: #f4f7fb;
        }

        .btn-danger {
            background: #fff1f1;
            color: #d94b4b;
        }

        .btn-danger:hover {
            background: #ffe3e3;
        }

        .review-item {
            border: 1px solid #e7edf2;
            border-radius: 13px;
            padding: 22px;
            margin-bottom: 18px;
        }

        .review-item:last-child {
            margin-bottom: 0;
        }

        .review-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 14px;
        }

        .workspace-name {
            font-size: 18px;
            font-weight: bold;
            color: #183047;
            margin: 0 0 8px;
        }

        .review-date {
            font-size: 12px;
            color: #8b98a8;
        }

        .rating {
            color: #f4b740;
            font-size: 22px;
            letter-spacing: 2px;
            white-space: nowrap;
        }

        .review-comment {
            color: #586779;
            font-size: 14px;
            line-height: 1.8;
            margin: 15px 0 20px;
        }

        .review-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
        }

        .empty-icon {
            width: 76px;
            height: 76px;
            background: #e4f5f3;
            color: #119e95;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .empty-icon svg {
            width: 38px;
            height: 38px;
        }

        .empty-state h3 {
            color: #183047;
            font-size: 19px;
            margin: 0 0 10px;
        }

        .empty-state p {
            color: #8995a3;
            font-size: 14px;
            line-height: 1.7;
            margin: 0 0 22px;
        }
        /* Delete Review Popup */

.delete-modal {
    position: fixed;
    inset: 0;
    background: rgba(20, 35, 45, 0.55);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
}

.delete-modal:target {
    display: flex;
}

.delete-modal-box {
    background: #ffffff;
    width: 100%;
    max-width: 420px;
    padding: 35px 30px;
    border-radius: 18px;
    text-align: center;
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
}

.delete-icon {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background: #fff0f0;
    color: #dc3545;
    font-size: 34px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.delete-modal-box h2 {
    font-size: 23px;
    color: #243746;
    margin-bottom: 12px;
}

.delete-modal-box p {
    color: #7a8994;
    font-size: 14px;
    line-height: 1.7;
    margin-bottom: 28px;
}

.delete-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.delete-cancel,
.delete-confirm {
    padding: 12px 22px;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    font-family: inherit;
}

.delete-cancel {
    background: #f1f5f6;
    color: #52636e;
    border: none;
}

.delete-confirm {
    background: #dc3545;
    color: white;
    border: none;
}

.delete-confirm:hover {
    background: #bb2534;
}

.delete-cancel:hover {
    background: #e5edef;
}
        @media (max-width: 650px) {
            .reviews-card {
                padding: 20px;
            }

            .section-header,
            .review-top {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

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

</body>
</html>
