@extends('layouts.dashboard')

@section('dashboard-role', 'member')

@section('title') My Memberships | NexOra @endsection
@section('page-title') My Memberships @endsection

@push('styles')
<style>
/* Member My Memberships — page-specific styling only */
.nx-page-content {
    --mm-primary: #315C4D;
    --mm-primary-dark: #203D34;
    --mm-text: #1D2924;
    --mm-muted: #53615A;
    --mm-border: #E2E8E2;
    --mm-surface: #F8FAF7;
    color: var(--mm-text);
    font-family: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
}
.nx-page-content, .nx-page-content * { box-sizing: border-box; }
.nx-page-content .container {
    width: 100%;
    max-width: 1100px;
margin: 0 auto 48px;    padding: 0 16px;
}
.nx-page-content .page-title,
.nx-page-content .card > h2,
.nx-page-content .membership-header h3 {
    font-family: 'Manrope', 'Noto Sans Arabic', sans-serif;
    color: #152A23;
    letter-spacing: -.025em;
}
.nx-page-content .page-title {
    font-size: clamp(25px, 3vw, 32px);
    font-weight: 800;
    line-height: 1.25;
    margin: 0 0 8px;
}
.nx-page-content .subtitle {
    color: var(--mm-muted);
    font-size: 14px;
    line-height: 1.65;
    margin: 0 0 24px;
}
.nx-page-content .card {
    background: #fff;
    border: 1px solid var(--mm-border);
    border-radius: 18px;
    box-shadow: 0 5px 22px rgba(29, 41, 36, .045);
    padding: 28px;
    margin: 0 0 24px;
    min-width: 0;
}
.nx-page-content .card > h2 {
    font-size: 21px;
    font-weight: 750;
    margin: 0 0 8px;
}
.nx-page-content .card > .subtitle { margin-bottom: 22px; }
.nx-page-content .membership-item {
    background: #fff;
    border: 1px solid var(--mm-border);
    border-radius: 14px;
    padding: 23px;
    margin-bottom: 16px;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.nx-page-content .membership-item:last-child { margin-bottom: 0; }
.nx-page-content .membership-item:hover {
    border-color: #C9D4CC;
    box-shadow: 0 5px 18px rgba(29, 41, 36, .045);
}
.nx-page-content .membership-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding-bottom: 17px;
    margin-bottom: 20px;
    border-bottom: 1px solid #E9EEE9;
}
.nx-page-content .membership-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 750;
    line-height: 1.4;
    overflow-wrap: anywhere;
}
.nx-page-content .membership-status {
    display: inline-flex;
    align-items: center;
    padding: 7px 13px;
    border-radius: 999px;
    background: #E7EEE7;
    border: 1px solid #C9D4CC;
    color: var(--mm-primary-dark);
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}
.nx-page-content .membership-details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px 28px;
}
.nx-page-content .membership-details > div { min-width: 0; }
.nx-page-content .membership-details span {
    display: block;
    color: #7F8B84;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 7px;
}
.nx-page-content .membership-details strong {
    display: block;
    color: var(--mm-text);
    font-size: 14px;
    font-weight: 650;
    line-height: 1.6;
    overflow-wrap: anywhere;
}
.nx-page-content .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    max-width: 100%;
    padding: 11px 21px;
    border: 1px solid var(--mm-primary);
    border-radius: 999px;
    background: var(--mm-primary);
    color: #fff;
    font-family: 'Manrope', 'Noto Sans Arabic', sans-serif;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.3;
    text-decoration: none;
    cursor: pointer;
    transition: background-color .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
}
.nx-page-content .btn:hover {
    background: #274B3F;
    border-color: #274B3F;
    color: #fff;
    text-decoration: none;
}
.nx-page-content .btn:active { transform: scale(.98); }
.nx-page-content .btn:focus-visible {
    outline: 3px solid #C58A62;
    outline-offset: 3px;
}
/* Cancellation is a secondary, cautionary action; checkout stays primary. */
.nx-page-content form[action*="cancel"] .btn {
    background: #fff;
    border-color: #D9B8B4;
    color: #912018;
}
.nx-page-content form[action*="cancel"] .btn:hover {
    background: #FDEDEC;
    border-color: #B42318;
    color: #912018;
}
/* Back navigation should be visually secondary. */
.nx-page-content .container > a.btn {
    background: transparent;
    border-color: #C9D4CC;
    color: var(--mm-primary-dark);
}
.nx-page-content .container > a.btn:hover {
    background: #E7EEE7;
    border-color: var(--mm-primary);
    color: var(--mm-primary-dark);
}
@media (max-width: 650px) {
    .nx-page-content .container { margin-top: 16px; padding: 0 12px; }
    .nx-page-content .card { padding: 18px; border-radius: 15px; }
    .nx-page-content .membership-item { padding: 17px; }
    .nx-page-content .membership-details { grid-template-columns: 1fr; gap: 15px; }
    .nx-page-content .membership-header { align-items: flex-start; }
    .nx-page-content .membership-header h3 { font-size: 16px; }
    .nx-page-content .card > h2 { font-size: 19px; }
}
@media (prefers-reduced-motion: reduce) {
    .nx-page-content .btn, .nx-page-content .membership-item { transition: none; }
}
</style>
@endpush

@section('content')
<div class="nx-page-content">
<div class="container">

    <h1 class="page-title">My Memberships</h1>
    <p class="subtitle">
        View your workspace memberships and subscription details.
    </p>

    @if(session('success'))
        <p style="color: #274B3F;">
            {{ session('success') }}
        </p>
    @endif

    @if(session('error'))
        <p style="color: #dc3545;">
            {{ session('error') }}
        </p>
    @endif

    <div class="card">

        @forelse($memberships as $membership)

            <div class="membership-item">

                <div class="membership-header">
                    <h3>
                        {{ $membership->plan?->plan_name ?? 'Unknown Plan' }}
                    </h3>

                    <span class="membership-status">
                        {{ ucfirst($membership->status) }}
                    </span>
                </div>

                <div class="membership-details">

                    <div>
                        <span>Workspace</span>
                        <strong>
                            {{ $membership->plan?->workspace?->name ?? 'N/A' }}
                        </strong>
                    </div>

                    <div>
                        <span>Price</span>
                        <strong>
                            {{ $membership->plan?->price ?? 'N/A' }} JOD
                        </strong>
                    </div>

                    <div>
                        <span>Start Date</span>
                        <strong>
                            {{ $membership->start_date?->format('M d, Y') ?? 'N/A' }}
                        </strong>
                    </div>

                    <div>
                        <span>End Date</span>
                        <strong>
                            {{ $membership->end_date?->format('M d, Y') ?? 'N/A' }}
                        </strong>
                    </div>

                </div>

                {{-- Cancel Membership Button --}}
                @if($membership->status === 'active')

                    <form action="{{ route('member.memberships.cancel', $membership->id) }}"
                          method="POST"
                          style="margin-top: 20px;">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="btn"
                                onclick="return confirm('Are you sure you want to cancel this membership?')">
                            Cancel Membership
                        </button>

                    </form>

                @endif

            </div>

        @empty

            <p class="subtitle">
                You don't have any memberships yet.
            </p>

        @endforelse

    </div>

    <div class="card">

        <h2>Available Membership Plans</h2>

        <p class="subtitle">
            Explore available workspace membership plans.
        </p>

        @forelse($availablePlans as $plan)

            <div class="membership-item">

                <div class="membership-header">
                    <h3>{{ $plan->plan_name }}</h3>

                    <span class="membership-status">
                        Available
                    </span>
                </div>

                <div class="membership-details">

                    <div>
                        <span>Workspace</span>
                        <strong>
                            {{ $plan->workspace?->name ?? 'N/A' }}
                        </strong>
                    </div>

                    <div>
                        <span>Price</span>
                        <strong>{{ $plan->price }} JOD</strong>
                    </div>

                    <div>
                        <span>Duration</span>
                        <strong>{{ $plan->duration_days }} Days</strong>
                    </div>

                    <div>
                        <span>Description</span>
                        <strong>
                            {{ $plan->description ?? 'No description' }}
                        </strong>
                    </div>

                </div>

                {{-- Subscribe Button --}}
                <form action="{{ route('member.stripe.checkout.membership') }}"
                      method="POST"
                      style="margin-top: 20px;">

                    @csrf

                    <input type="hidden"
                           name="plan_id"
                           value="{{ $plan->id }}">

                    <button type="submit" class="btn">
                        Subscribe
                    </button>

                </form>

            </div>

        @empty

            <p class="subtitle">
                No membership plans are available right now.
            </p>

        @endforelse

    </div>

    <a href="{{ route('member.profile.index') }}" class="btn">
        Back to Profile
    </a>

</div>
</div>
@endsection
