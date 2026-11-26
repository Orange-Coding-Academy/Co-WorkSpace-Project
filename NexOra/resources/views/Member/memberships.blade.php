
@extends('layouts.member')

@section('title', 'My Memberships')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/member-memberships.css') }}?v=20261125-ui4">
   <link rel="stylesheet" href="{{ asset('css/pages/admin-payments.css') }}?v={{ @filemtime(public_path('css/pages/admin-payments.css')) }}">
@endpush

@section('content')
<div class="nx-page-content">
    <div class="container">

        {{-- Hero --}}
        <section class="mm-hero">
            <div class="mm-hero-content">
                <span class="mm-eyebrow">NEXORA MEMBERSHIP</span>
                <h1>Your Memberships.</h1>
                <p>
                    Manage your workspace subscriptions, keep track of your plans,
                    and discover new ways to work better.
                </p>
            </div>

            <div class="mm-hero-decoration">
                <span>✳</span>
                <span>YOUR SPACE</span>
            </div>
        </section>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mm-alert mm-alert-success">
                <span class="mm-alert-dot"></span>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mm-alert mm-alert-error">
                <span class="mm-alert-dot"></span>
                {{ session('error') }}
            </div>
        @endif

        {{-- Current memberships --}}
        <section class="mm-section">

            <div class="mm-section-heading">
                <div class="mm-heading-left">
                    <div class="mm-heading-mark">✦</div>
                    <div>
                        <h2>My Memberships</h2>
                        <p >Your current and previous subscriptions</p>
                    </div>
                </div>

                <span class="mm-section-count">
                    {{ $memberships->count() }} MEMBERSHIPS
                </span>
            </div>

            @forelse($memberships as $membership)
                <div class="mm-membership-card">
                    <div class="mm-card-top">
                        <div>
                            <span class="mm-card-label">MEMBERSHIP PLAN</span>
                            <h3>{{ $membership->plan?->plan_name ?? 'Unknown Plan' }}</h3>
                        </div>

                        <span class="mm-status {{ strtolower($membership->status) }}">
                            {{ ucfirst($membership->status) }}
                        </span>
                    </div>

                    <div class="mm-card-price">
                        <strong>{{ $membership->plan?->price ?? 'N/A' }}</strong>
                        <span>JOD</span>
                    </div>

                    <div class="mm-info-grid">
                        <div class="mm-info">
                            <span>Workspace</span>
                            <strong>{{ $membership->plan?->workspace?->name ?? 'N/A' }}</strong>
                        </div>

                        <div class="mm-info">
                            <span>Start Date</span>
                            <strong>{{ $membership->start_date?->format('M d, Y') ?? 'N/A' }}</strong>
                        </div>

                        <div class="mm-info">
                            <span>Membership Plan</span>
                            <strong>{{ $membership->plan?->plan_name ?? 'Unknown Plan' }}</strong>
                        </div>

                        <div class="mm-info">
                            <span>End Date</span>
                            <strong>{{ $membership->end_date?->format('M d, Y') ?? 'N/A' }}</strong>
                        </div>
                    </div>

                    @if ($membership->status === 'active')
                        <div class="mm-card-action">
                            <form action="{{ route('member.memberships.cancel', $membership->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to cancel this membership?')">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="mm-btn mm-btn-cancel">
                                    Cancel Membership <span>↗</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="mm-empty">
                    <div class="mm-empty-icon">✦</div>
                    <h3>No memberships yet</h3>
                    <p>
                        You haven't joined a workspace membership.
                        Explore the available plans below to get started.
                    </p>
                </div>
            @endforelse
        </section>

        {{-- Available plans --}}
        <section class="mm-section mm-plans-section">

            <div class="mm-section-heading">
                <div class="mm-heading-left">
                    <div class="mm-heading-mark">◇</div>
                    <div>
                        <h2>Explore Membership Plans</h2>
                        <p>Find the right plan for your workspace needs</p>
                    </div>
                </div>

                <span class="mm-section-count">
                    {{ $availablePlans->count() }} AVAILABLE
                </span>
            </div>

            @if($availablePlans->isNotEmpty())
                <div class="mm-plans-grid">
                    @foreach($availablePlans as $plan)
                        <div class="mm-plan-card">

                            <div class="mm-plan-top">
                                <div class="mm-plan-icon">✧</div>
                                <span class="mm-plan-available">● AVAILABLE</span>
                            </div>

                            <h3>{{ $plan->plan_name }}</h3>

                            <div class="mm-plan-price">
                                <strong>{{ $plan->price }}</strong>
                                <span>JOD</span>
                            </div>

                            <div class="mm-plan-divider"></div>

                            <div class="mm-plan-info">
                                <div>
                                    <span>WORKSPACE</span>
                                    <strong>{{ $plan->workspace?->name ?? 'N/A' }}</strong>
                                </div>

                                <div>
                                    <span>DURATION</span>
                                    <strong>{{ $plan->duration_days }} Days</strong>
                                </div>

                                <div>
                                    <span>ABOUT THIS PLAN</span>
                                    <strong>{{ $plan->description ?? 'No description available.' }}</strong>
                                </div>
                            </div>

                            <form action="{{ route('member.stripe.checkout.membership') }}"
                                  method="POST">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                                <button type="submit" class="mm-btn">
                                    Choose This Plan <span>→</span>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
               @if($availablePlans->hasPages())
    @php
        $availablePlans->withQueryString();

        $currentPage = $availablePlans->currentPage();
        $lastPage = $availablePlans->lastPage();

        $windowStart = max(1, $currentPage - 1);
        $windowEnd = min($lastPage, $currentPage + 1);
    @endphp

    <div class="payments-pagination mm-pagination">
        <div class="pagination-summary">
            Page {{ $currentPage }} of {{ $lastPage }}
        </div>

        <nav aria-label="Memberships pagination">
            <ul class="pager">
                {{-- Previous --}}
                <li>
                    @if($availablePlans->onFirstPage())
                        <span class="pager-link is-disabled" aria-disabled="true">‹</span>
                    @else
                        <a class="pager-link"
                           href="{{ $availablePlans->previousPageUrl() }}"
                           rel="prev"
                           aria-label="Previous page">‹</a>
                    @endif
                </li>

                {{-- First page --}}
                @if($windowStart > 1)
                    <li>
                        <a class="pager-link" href="{{ $availablePlans->url(1) }}">1</a>
                    </li>

                    @if($windowStart > 2)
                        <li>
                            <span class="pager-link is-disabled">…</span>
                        </li>
                    @endif
                @endif

                {{-- Current page window --}}
                @foreach(range($windowStart, $windowEnd) as $page)
                    <li>
                        @if($page === $currentPage)
                            <span class="pager-link is-active" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a class="pager-link" href="{{ $availablePlans->url($page) }}">
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endforeach

                {{-- Last page --}}
                @if($windowEnd < $lastPage)
                    @if($windowEnd < $lastPage - 1)
                        <li>
                            <span class="pager-link is-disabled">…</span>
                        </li>
                    @endif

                    <li>
                        <a class="pager-link" href="{{ $availablePlans->url($lastPage) }}">
                            {{ $lastPage }}
                        </a>
                    </li>
                @endif

                {{-- Next --}}
                <li>
                    @if($availablePlans->hasMorePages())
                        <a class="pager-link"
                           href="{{ $availablePlans->nextPageUrl() }}"
                           rel="next"
                           aria-label="Next page">›</a>
                    @else
                        <span class="pager-link is-disabled" aria-disabled="true">›</span>
                    @endif
                </li>
            </ul>
        </nav>
    </div>
@endif

            @else
                <div class="mm-empty">
                    <div class="mm-empty-icon">◇</div>
                    <h3>No plans available right now</h3>
                    <p>New membership options may become available soon.</p>
                </div>
            @endif
        </section>

        {{-- Back --}}
        <div class="mm-footer">
            <a href="{{ route('member.profile.index') }}" class="mm-btn mm-btn-back">
                ← Back to Profile
            </a>
        </div>

    </div>
</div>
@endsection
