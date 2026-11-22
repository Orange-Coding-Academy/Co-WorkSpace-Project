
@extends('layouts.dashboard')

@section('dashboard-role', 'admin')
@section('title', 'Reviews')
@section('page-title', 'Reviews')

@push('styles')
    @include('layouts.dashboard.partials.app-compat-styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin-reviews.css') }}">
@endpush

@section('content')

<div class="nx-page-content nx-admin-reviews">
    <div class="nx-reviews-container">

        {{-- Page Header --}}
        <header class="nx-reviews-header">
            <div class="nx-reviews-header__content">
                <span class="nx-reviews-eyebrow">CONTENT MANAGEMENT</span>
                <h1>Reviews & Feedback</h1>
                <p>
                    Manage member feedback, review submissions and control
                    which reviews are published on your platform.
                </p>
            </div>

            <div class="nx-reviews-header__badge">
                <span class="nx-reviews-header__dot"></span>
                Review Management
            </div>
        </header>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="nx-review-alert" role="alert">
                <span class="nx-review-alert__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="m5 12 4 4L19 6"/>
                    </svg>
                </span>

                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Statistics --}}
        <section class="nx-reviews-stats">

            <article class="nx-review-stat">
                <div class="nx-review-stat__top">
                    <div class="nx-review-stat__icon nx-review-stat__icon--total">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8A8.5 8.5 0 0 1 8.7 3.9a8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z"/>
                        </svg>
                    </div>
                    <span class="nx-review-stat__caption">ALL SUBMISSIONS</span>
                </div>

                <div class="nx-review-stat__bottom">
                    <div>
                        <span class="nx-review-stat__label">Total Reviews</span>
                        <h3>{{ $totalReviews }}</h3>
                    </div>
                    <span class="nx-review-stat__symbol">01</span>
                </div>
            </article>

            <article class="nx-review-stat">
                <div class="nx-review-stat__top">
                    <div class="nx-review-stat__icon nx-review-stat__icon--pending">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round"
                             stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 2"/>
                        </svg>
                    </div>
                    <span class="nx-review-stat__caption">AWAITING REVIEW</span>
                </div>

                <div class="nx-review-stat__bottom">
                    <div>
                        <span class="nx-review-stat__label">Pending Reviews</span>
                        <h3>{{ $pendingReviews }}</h3>
                    </div>
                    <span class="nx-review-stat__symbol">02</span>
                </div>
            </article>

            <article class="nx-review-stat">
                <div class="nx-review-stat__top">
                    <div class="nx-review-stat__icon nx-review-stat__icon--approved">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M22 11.1V12a10 10 0 1 1-5.9-9.1"/>
                            <path d="m9 11 3 3L22 4"/>
                        </svg>
                    </div>
                    <span class="nx-review-stat__caption">PUBLISHED</span>
                </div>

                <div class="nx-review-stat__bottom">
                    <div>
                        <span class="nx-review-stat__label">Approved Reviews</span>
                        <h3>{{ $approvedReviews }}</h3>
                    </div>
                    <span class="nx-review-stat__symbol">03</span>
                </div>
            </article>

        </section>

        {{-- Review Management Panel --}}
        <section class="nx-reviews-panel">

            <div class="nx-reviews-panel__header">
                <div>
                    <span class="nx-reviews-panel__eyebrow">FEEDBACK CENTER</span>
                    <h2>Member Feedback</h2>
                    <p>Review and manage all submitted member feedback.</p>
                </div>

                <span class="nx-reviews-count">
                    {{ $reviews->total() }} Reviews
                </span>
            </div>

            {{-- Filters --}}
            <div class="nx-review-filters">

                <div class="nx-review-filters__tabs">

                    <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
                       class="nx-review-filter {{ $status === 'pending' ? 'is-active' : '' }}"
                       @if($status === 'pending') aria-current="page" @endif>
                        <span class="nx-review-filter__dot nx-review-filter__dot--pending"></span>
                        Pending
                        <span class="nx-review-filter__count">{{ $pendingReviews }}</span>
                    </a>

                    <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
                       class="nx-review-filter {{ $status === 'approved' ? 'is-active' : '' }}"
                       @if($status === 'approved') aria-current="page" @endif>
                        <span class="nx-review-filter__dot nx-review-filter__dot--approved"></span>
                        Approved
                        <span class="nx-review-filter__count">{{ $approvedReviews }}</span>
                    </a>

                    <a href="{{ route('admin.reviews.index', ['status' => 'all']) }}"
                       class="nx-review-filter {{ $status === 'all' ? 'is-active' : '' }}"
                       @if($status === 'all') aria-current="page" @endif>
                        All Reviews
                    </a>

                </div>

                <span class="nx-review-filter-note">
                    Showing {{ ucfirst($status) }} feedback
                </span>

            </div>

            {{-- Reviews List --}}
            <div class="nx-reviews-list">

                @forelse($reviews as $review)

                    <article class="nx-review-card">

                        {{-- Card Top --}}
                        <div class="nx-review-card__top">

                            <div class="nx-review-member">

                                <div class="nx-review-avatar">
                                    {{ strtoupper(substr($review->user->name ?? 'D', 0, 1)) }}
                                </div>

                                <div class="nx-review-member__info">
                                    <h3>{{ $review->user->name ?? 'Deleted Member' }}</h3>

                                    @if($review->user?->email)
                                        <span>{{ $review->user->email }}</span>
                                    @else
                                        <span>Member account unavailable</span>
                                    @endif
                                </div>

                            </div>

                            <div class="nx-review-card__meta">

                                <span class="nx-review-status {{ $review->is_visible ? 'is-approved' : 'is-pending' }}">
                                    <span></span>
                                    {{ $review->is_visible ? 'Approved' : 'Pending' }}
                                </span>

                                <time datetime="{{ $review->created_at->toDateString() }}">
                                    {{ $review->created_at->format('d M Y') }}
                                </time>

                            </div>

                        </div>

                        {{-- Workspace --}}
                        <div class="nx-review-workspace">
                            <span class="nx-review-workspace__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.8" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                                    <path d="M3 10h18M9 20V10"/>
                                </svg>
                            </span>

                            <div>
                                <span>WORKSPACE</span>
                                <strong>{{ $review->workspace->name ?? 'Deleted Workspace' }}</strong>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div class="nx-review-rating">

                            <div class="nx-review-stars" aria-label="{{ $review->rating }} out of 5 stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'is-filled' : '' }}">★</span>
                                @endfor
                            </div>

                            <span class="nx-review-rating__value">
                                {{ number_format((float) $review->rating, 1) }}
                                <small>/ 5.0</small>
                            </span>

                        </div>

                        {{-- Comment --}}
                        <div class="nx-review-comment">
                            <span class="nx-review-comment__label">MEMBER FEEDBACK</span>

                            <p>{{ $review->comment ?: 'No written feedback was provided by this member.' }}</p>
                        </div>

                        {{-- Actions --}}
                        <div class="nx-review-card__footer">

                            <span class="nx-review-card__id">
                                REVIEW #{{ $review->id }}
                            </span>

                            <div class="nx-review-actions">

                                @if(!$review->is_visible)

                                    <form method="POST"
                                          action="{{ route('admin.reviews.approve', $review->id) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="nx-review-action nx-review-action--approve"
                                                title="Approve Review"
                                                aria-label="Approve Review">

                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m5 12 4 4L19 6"/>
                                            </svg>

                                            <span>Approve</span>
                                        </button>
                                    </form>

                                @else

                                    <form method="POST"
                                          action="{{ route('admin.reviews.hide', $review->id) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="nx-review-action nx-review-action--hide"
                                                title="Hide Review"
                                                aria-label="Hide Review">

                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 3l18 18"/>
                                                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                                <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c5 0 9 7 9 7a15.5 15.5 0 0 1-3 3.5"/>
                                                <path d="M6.2 6.2C4.2 7.5 3 10 3 12c0 0 4 7 9 7 1.1 0 2.1-.3 3-.7"/>
                                            </svg>

                                            <span>Hide</span>
                                        </button>
                                    </form>

                                @endif

                                <form method="POST"
                                      action="{{ route('admin.reviews.destroy', $review->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this review?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="nx-review-action nx-review-action--delete"
                                            title="Delete Review"
                                            aria-label="Delete Review">

                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.8"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/>
                                            <path d="M8 6V4h8v2"/>
                                            <path d="m19 6-1 14H6L5 6"/>
                                            <path d="M10 11v5M14 11v5"/>
                                        </svg>

                                        <span>Delete</span>
                                    </button>

                                </form>

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="nx-review-empty">

                        <div class="nx-review-empty__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8A8.5 8.5 0 0 1 8.7 3.9a8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z"/>
                            </svg>
                        </div>

                        <h3>No Reviews Found</h3>

                        <p>
                            There are no reviews in this category at the moment.
                            New member feedback will appear here.
                        </p>

                    </div>

                @endforelse

            </div>

            {{-- Pagination --}}
            @if($reviews->hasPages())

               
                    <div class="nx-reviews-pagination__links">
                        {{ $reviews->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            @endif

        </section>

    </div>
</div>

@endsection
