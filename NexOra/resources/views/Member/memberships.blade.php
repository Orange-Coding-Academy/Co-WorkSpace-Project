
@extends('layouts.member')

@section('title', 'My Memberships')

@push('styles')
<style>
    .nx-page-content {
        --mm-forest: #203d34;
        --mm-deep: #142c24;
        --mm-green: #315c4d;
        --mm-orange: #d88d65;
        --mm-cream: #f5f4ee;
        --mm-white: #fff;
        --mm-text: #1d2924;
        --mm-muted: #78847d;
        --mm-border: #e5e9e2;

        color: var(--mm-text);
        font-family: 'Inter', 'Noto Sans Arabic', sans-serif;
        padding: 4px 0 35px;
    }

    .nx-page-content,
    .nx-page-content * {
        box-sizing: border-box;
    }

    .nx-page-content .container {
        width: 100%;
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 22px;
    }

    /* =========================
       HERO
    ========================= */

    .mm-hero {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        min-height: 210px;
        padding: 37px 43px;
        margin-bottom: 30px;
        border-radius: 22px;
        background:
            radial-gradient(circle at 88% 20%, rgba(216,141,101,.2), transparent 25%),
            linear-gradient(115deg, #142c24 0%, #203d34 65%, #315c4d 100%);
        color: #fff;
        box-shadow: 0 14px 35px rgba(20,44,36,.13);
    }

    .mm-hero::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        right: 16%;
        top: -175px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        box-shadow:
            0 0 0 28px rgba(255,255,255,.025),
            0 0 0 58px rgba(255,255,255,.025);
        pointer-events: none;
    }

    .mm-hero::after {
        content: "N";
        position: absolute;
        right: 48px;
        bottom: -85px;
        font-family: 'Manrope', sans-serif;
        font-size: 260px;
        line-height: 1;
        font-weight: 800;
        color: rgba(255,255,255,.045);
        pointer-events: none;
    }

    .mm-hero-content {
        position: relative;
        z-index: 2;
        max-width: 590px;
    }

    .mm-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 15px;
        color: #e8b18f;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2.2px;
        text-transform: uppercase;
    }

    .mm-eyebrow::before {
        content: "";
        width: 24px;
        height: 2px;
        background: var(--mm-orange);
    }

    .mm-hero h1 {
        margin: 0 0 11px;
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: clamp(29px, 4vw, 42px);
        font-weight: 800;
        letter-spacing: -1.5px;
        line-height: 1.15;
    }

    .mm-hero p {
        max-width: 480px;
        margin: 0;
        color: rgba(255,255,255,.68);
        font-size: 14px;
        line-height: 1.8;
    }

    .mm-hero-decoration {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 115px;
        height: 115px;
        flex-shrink: 0;
        border: 1px solid rgba(255,255,255,.22);
        border-radius: 50%;
        background: rgba(255,255,255,.07);
        backdrop-filter: blur(8px);
    }

    .mm-hero-decoration span:first-child {
        color: #e9b18f;
        font-size: 34px;
        line-height: 1;
    }

    .mm-hero-decoration span:last-child {
        margin-top: 8px;
        color: rgba(255,255,255,.75);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.5px;
    }

    /* =========================
       ALERTS
    ========================= */

    .mm-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        margin-bottom: 18px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        animation: mmSlide .35s ease both;
    }

    .mm-alert-success {
        color: #245b40;
        background: #eaf4ed;
        border: 1px solid #d2e8d8;
    }

    .mm-alert-error {
        color: #9b302b;
        background: #fff0ee;
        border: 1px solid #f3d6d2;
    }

    .mm-alert-dot {
        width: 8px;
        height: 8px;
        flex-shrink: 0;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================
       SECTION HEADINGS
    ========================= */

    .mm-section {
        margin-bottom: 35px;
    }

    .mm-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 17px;
    }

    .mm-heading-left {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .mm-heading-mark {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border-radius: 13px;
        color: var(--mm-green);
        background: #e9eee8;
        font-size: 18px;
    }

    .mm-section-heading h2 {
        margin: 0 0 4px;
        color: var(--mm-deep);
        font-family: 'Manrope', sans-serif;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -.6px;
    }

    .mm-section-heading p {
        margin: 0;
        color: var(--mm-muted);
        font-size: 12px;
    }

    .mm-section-count {
        padding: 7px 12px;
        border: 1px solid var(--mm-border);
        border-radius: 30px;
        background: #fff;
        color: var(--mm-muted);
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =========================
       MEMBERSHIP CARDS
    ========================= */

    .mm-membership-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
    }

    .mm-membership-card {
        position: relative;
        overflow: hidden;
        min-width: 0;
        padding: 23px;
        border: 1px solid var(--mm-border);
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(29,41,36,.025);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        animation: mmSlide .45s ease both;
    }

    .mm-membership-card:hover {
        transform: translateY(-4px);
        border-color: #cbd9ce;
        box-shadow: 0 13px 28px rgba(29,41,36,.075);
    }

    .mm-membership-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--mm-green);
        opacity: .8;
    }

    .mm-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding-bottom: 18px;
        border-bottom: 1px solid #edf0eb;
    }

    .mm-card-label {
        display: block;
        margin-bottom: 7px;
        color: #a0aaa3;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .mm-card-top h3 {
        margin: 0;
        color: var(--mm-deep);
        font-family: 'Manrope', sans-serif;
        font-size: 18px;
        font-weight: 800;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .mm-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        flex-shrink: 0;
        padding: 7px 11px;
        border-radius: 30px;
        background: #eaf3eb;
        color: #326449;
        font-size: 10px;
        font-weight: 800;
        text-transform: capitalize;
    }

    .mm-status::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .mm-status.pending {
        background: #fff5e7;
        color: #a36b2c;
    }

    .mm-status.cancelled,
    .mm-status.expired {
        background: #f8ecea;
        color: #a34d45;
    }

    .mm-card-price {
        display: flex;
        align-items: baseline;
        gap: 6px;
        margin: 18px 0;
    }

    .mm-card-price strong {
        color: var(--mm-forest);
        font-family: 'Manrope', sans-serif;
        font-size: 29px;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .mm-card-price span {
        color: var(--mm-muted);
        font-size: 11px;
        font-weight: 600;
    }

    .mm-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px 15px;
        padding-top: 16px;
        border-top: 1px solid #edf0eb;
    }

    .mm-info {
        min-width: 0;
    }

    .mm-info span {
        display: block;
        margin-bottom: 6px;
        color: #929e96;
        font-size: 10px;
        font-weight: 600;
    }

    .mm-info strong {
        display: block;
        color: #283b32;
        font-size: 12px;
        font-weight: 750;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .mm-card-action {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #edf0eb;
    }

    /* =========================
       AVAILABLE PLANS
    ========================= */

    .mm-plans-section {
        padding: 25px;
        border: 1px solid #e9e9df;
        border-radius: 20px;
        background:
            radial-gradient(circle at 100% 0%, rgba(216,141,101,.07), transparent 30%),
            #f0f1e9;
    }

    .mm-plans-section .mm-heading-mark {
        color: #fff;
        background: var(--mm-forest);
    }

    .mm-plans-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
    }

    .mm-plan-card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        padding: 21px;
        border: 1px solid rgba(32,61,52,.11);
        border-radius: 15px;
        background: #fff;
        transition: transform .25s ease, box-shadow .25s ease;
        animation: mmSlide .5s ease both;
    }

    .mm-plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 27px rgba(32,61,52,.09);
    }

    .mm-plan-card:nth-child(2) {
        animation-delay: .07s;
    }

    .mm-plan-card:nth-child(3) {
        animation-delay: .14s;
    }

    .mm-plan-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 17px;
    }

    .mm-plan-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 39px;
        height: 39px;
        border-radius: 12px;
        color: var(--mm-green);
        background: #edf1eb;
        font-size: 17px;
    }

    .mm-plan-available {
        color: #67816f;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .8px;
        text-transform: uppercase;
    }

    .mm-plan-card h3 {
        margin: 0 0 8px;
        color: var(--mm-deep);
        font-family: 'Manrope', sans-serif;
        font-size: 17px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .mm-plan-price {
        display: flex;
        align-items: baseline;
        gap: 5px;
        margin-bottom: 18px;
    }

    .mm-plan-price strong {
        color: var(--mm-forest);
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -1.3px;
    }

    .mm-plan-price span {
        color: var(--mm-muted);
        font-size: 11px;
    }

    .mm-plan-divider {
        height: 1px;
        margin-bottom: 16px;
        background: #edf0eb;
    }

    .mm-plan-info {
        display: flex;
        flex-direction: column;
        gap: 13px;
        flex: 1;
        margin-bottom: 20px;
    }

    .mm-plan-info div {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .mm-plan-info span {
        color: #99a39c;
        font-size: 10px;
        font-weight: 600;
    }

    .mm-plan-info strong {
        color: #35483e;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.65;
        overflow-wrap: anywhere;
    }

    /* =========================
       BUTTONS
    ========================= */

    .mm-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        min-height: 43px;
        padding: 11px 16px;
        border: 1px solid var(--mm-forest);
        border-radius: 9px;
        background: var(--mm-forest);
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .2px;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease, border-color .2s ease;
    }

    .mm-btn:hover {
        background: #315c4d;
        border-color: #315c4d;
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .mm-btn:active {
        transform: scale(.98);
    }

    .mm-btn-cancel {
        width: auto;
        min-height: 37px;
        padding: 9px 15px;
        border-color: #ead5d1;
        background: #fff;
        color: #a3443b;
        font-size: 10px;
    }

    .mm-btn-cancel:hover {
        border-color: #b94b40;
        background: #fff0ee;
        color: #a3443b;
    }

    .mm-btn-back {
        width: auto;
        min-height: 42px;
        padding: 11px 20px;
        border-color: #d8dfd7;
        background: transparent;
        color: var(--mm-forest);
    }

    .mm-btn-back:hover {
        border-color: var(--mm-forest);
        background: var(--mm-forest);
        color: #fff;
    }

    /* =========================
       EMPTY STATES
    ========================= */

    .mm-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 38px 20px;
        border: 1px dashed #d7dfd6;
        border-radius: 14px;
        background: rgba(255,255,255,.65);
        text-align: center;
    }

    .mm-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        margin-bottom: 13px;
        border-radius: 16px;
        background: #e9eee8;
        color: var(--mm-green);
        font-size: 22px;
    }

    .mm-empty h3 {
        margin: 0 0 6px;
        color: var(--mm-deep);
        font-size: 15px;
        font-weight: 800;
    }

    .mm-empty p {
        margin: 0;
        color: var(--mm-muted);
        font-size: 12px;
        line-height: 1.7;
    }

    /* =========================
       FOOTER ACTION
    ========================= */

    .mm-footer {
        display: flex;
        justify-content: flex-start;
        margin-top: 25px;
    }

    @keyframes mmSlide {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 900px) {
        .mm-plans-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .mm-membership-list {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .nx-page-content .container {
            padding: 0 13px;
        }

        .mm-hero {
            min-height: 190px;
            padding: 27px 22px;
            margin-bottom: 24px;
            border-radius: 17px;
        }

        .mm-hero h1 {
            font-size: 30px;
        }

        .mm-hero p {
            font-size: 12px;
        }

        .mm-hero-decoration {
            width: 70px;
            height: 70px;
        }

        .mm-hero-decoration span:first-child {
            font-size: 24px;
        }

        .mm-hero-decoration span:last-child {
            font-size: 7px;
        }

        .mm-hero::after {
            right: 15px;
            font-size: 170px;
        }

        .mm-section {
            margin-bottom: 25px;
        }

        .mm-section-heading {
            align-items: center;
        }

        .mm-section-heading h2 {
            font-size: 17px;
        }

        .mm-heading-mark {
            width: 37px;
            height: 37px;
        }

        .mm-membership-card {
            padding: 18px;
        }

        .mm-plans-section {
            padding: 16px;
            border-radius: 16px;
        }

        .mm-plans-grid {
            grid-template-columns: 1fr;
        }

        .mm-info-grid {
            gap: 14px 10px;
        }

        .mm-card-top h3 {
            font-size: 16px;
        }

        .mm-footer .mm-btn {
            width: 100%;
        }
    }

    @media (max-width: 380px) {
        .mm-hero-decoration {
            display: none;
        }

        .mm-section-count {
            font-size: 9px;
        }

        .mm-card-top {
            flex-direction: column;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .nx-page-content *,
        .nx-page-content *::before,
        .nx-page-content *::after {
            animation: none !important;
            transition: none !important;
        }
    }
</style>
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
                        <p>Your current and previous subscriptions</p>
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
