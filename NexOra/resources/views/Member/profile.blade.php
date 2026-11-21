
@extends('layouts.member')

@section('title', 'My Profile')

@push('styles')
<style>
    .nx-page-content {
        --pf-forest: #203d34;
        --pf-deep: #142c24;
        --pf-green: #315c4d;
        --pf-orange: #d88d65;
        --pf-cream: #f5f4ee;
        --pf-text: #1d2924;
        --pf-muted: #78847d;
        --pf-border: #e5e9e2;
        color: var(--pf-text);
        font-family: 'Inter', 'Noto Sans Arabic', sans-serif;
        min-width: 0;
        padding-bottom: 35px;
    }

    .nx-page-content,
    .nx-page-content * {
        box-sizing: border-box;
    }

    .nx-page-content .profile-container {
        width: 100%;
        max-width: 1160px;
        margin: 8px auto 35px;
        padding: 0 18px;
    }

    /* HERO */

    .pf-hero {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        padding: 31px 38px;
        margin-bottom: 23px;
        border-radius: 20px;
        background:
            radial-gradient(circle at 90% 15%, rgba(216,141,101,.19), transparent 25%),
            linear-gradient(115deg, #142c24, #203d34 70%, #315c4d);
        box-shadow: 0 12px 30px rgba(20,44,36,.12);
        color: #fff;
    }

    .pf-hero::after {
        content: "N";
        position: absolute;
        right: 40px;
        bottom: -100px;
        color: rgba(255,255,255,.045);
        font-family: 'Manrope', sans-serif;
        font-size: 280px;
        font-weight: 800;
        line-height: 1;
        pointer-events: none;
    }

    .pf-hero-main {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 22px;
        min-width: 0;
    }

    .pf-avatar {
        position: relative;
        display: grid;
        place-items: center;
        flex: 0 0 94px;
        width: 94px;
        height: 94px;
        overflow: hidden;
        border: 3px solid rgba(255,255,255,.7);
        border-radius: 50%;
        background: #e8eee7;
        color: var(--pf-green);
        box-shadow: 0 6px 20px rgba(0,0,0,.15);
    }

    .pf-avatar img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    .pf-avatar svg {
        width: 43px;
        height: 43px;
    }

    .pf-hero-info {
        min-width: 0;
    }

    .pf-eyebrow {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 9px;
        color: #e8b18f;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .pf-eyebrow::before {
        content: "";
        width: 22px;
        height: 2px;
        background: var(--pf-orange);
    }

    .pf-hero-info h1 {
        margin: 0 0 6px;
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: clamp(25px, 3vw, 35px);
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .pf-hero-info p {
        margin: 0;
        color: rgba(255,255,255,.7);
        font-size: 13px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .pf-member-since {
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        padding: 15px 19px;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 13px;
        background: rgba(255,255,255,.07);
        backdrop-filter: blur(8px);
    }

    .pf-member-since span {
        display: block;
        margin-bottom: 6px;
        color: rgba(255,255,255,.6);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    .pf-member-since strong {
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        font-weight: 750;
        white-space: nowrap;
    }

    /* ALERTS */

    .pf-alert {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 13px 16px;
        margin-bottom: 17px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 600;
        animation: pfEnter .35s ease both;
    }

    .pf-alert-success {
        color: #245b40;
        border: 1px solid #d2e8d8;
        background: #eaf4ed;
    }

    .pf-alert-error {
        color: #9b302b;
        border: 1px solid #f3d6d2;
        background: #fff0ee;
    }

    .pf-alert-dot {
        width: 8px;
        height: 8px;
        flex-shrink: 0;
        border-radius: 50%;
        background: currentColor;
    }

    /* MAIN LAYOUT */

    .pf-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(275px, .8fr);
        align-items: start;
        gap: 19px;
    }

    .pf-main-column,
    .pf-side-column {
        display: grid;
        gap: 19px;
        min-width: 0;
    }

    .pf-panel {
        min-width: 0;
        padding: 25px;
        border: 1px solid var(--pf-border);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(29,41,36,.035);
        animation: pfEnter .45s ease both;
    }

    .pf-panel-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 19px;
        margin-bottom: 20px;
        border-bottom: 1px solid #edf0eb;
    }

    .pf-heading-icon {
        display: grid;
        place-items: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #eaf0e9;
        color: var(--pf-green);
        font-size: 18px;
    }

    .pf-panel-heading h2 {
        margin: 0 0 4px;
        color: var(--pf-deep);
        font-family: 'Manrope', sans-serif;
        font-size: 17px;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .pf-panel-heading p {
        margin: 0;
        color: var(--pf-muted);
        font-size: 11px;
        line-height: 1.5;
    }

    /* PHOTO */

    .pf-photo-row {
        display: flex;
        align-items: center;
        gap: 17px;
        padding: 15px;
        margin-bottom: 23px;
        border: 1px solid #e8ede7;
        border-radius: 13px;
        background: #f8faf7;
    }

    .pf-photo-preview {
        display: grid;
        place-items: center;
        flex: 0 0 65px;
        width: 65px;
        height: 65px;
        overflow: hidden;
        border-radius: 15px;
        background: #e6eee6;
        color: var(--pf-green);
    }

    .pf-photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pf-photo-preview svg {
        width: 32px;
        height: 32px;
    }

    .pf-photo-info {
        min-width: 0;
    }

    .pf-photo-info strong {
        display: block;
        margin-bottom: 5px;
        color: var(--pf-deep);
        font-size: 13px;
        font-weight: 750;
    }

    .pf-photo-info p {
        margin: 0 0 9px;
        color: var(--pf-muted);
        font-size: 10px;
        line-height: 1.5;
    }

    .pf-file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        opacity: 0;
    }

    .pf-upload-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 6px 12px;
        border: 1px solid #cad7cc;
        border-radius: 7px;
        background: #fff;
        color: var(--pf-green);
        font-size: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: .2s ease;
    }

    .pf-upload-btn:hover {
        border-color: var(--pf-green);
        background: #edf3ed;
    }

    .pf-file-input:focus-visible + .pf-upload-btn {
        outline: 2px solid var(--pf-orange);
        outline-offset: 3px;
    }

    /* FORM */

    .pf-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 16px;
    }

    .pf-field {
        min-width: 0;
    }

    .pf-field label {
        display: block;
        margin-bottom: 8px;
        color: #35483e;
        font-size: 11px;
        font-weight: 750;
    }

    .pf-field input {
        display: block;
        width: 100%;
        min-height: 44px;
        padding: 10px 13px;
        border: 1px solid #dfe5de;
        border-radius: 9px;
        outline: none;
        background: #fff;
        color: var(--pf-text);
        font-family: inherit;
        font-size: 12px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .pf-field input::placeholder {
        color: #a0aaa3;
    }

    .pf-field input:hover {
        border-color: #aebeb0;
    }

    .pf-field input:focus {
        border-color: var(--pf-green);
        box-shadow: 0 0 0 3px rgba(49,92,77,.11);
    }

    .pf-error {
        display: block;
        margin-top: 6px;
        color: #a3443b;
        font-size: 10px;
        line-height: 1.5;
    }

    /* ACTIONS */

    .pf-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid #edf0eb;
    }

    .pf-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 41px;
        padding: 10px 17px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
    }

    .pf-btn:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }

    .pf-btn-primary {
        border-color: var(--pf-forest);
        background: var(--pf-forest);
        color: #fff;
    }

    .pf-btn-primary:hover {
        border-color: var(--pf-green);
        background: var(--pf-green);
        color: #fff;
    }

    .pf-btn-secondary {
        border-color: #dce3db;
        background: #fff;
        color: #526158;
    }

    .pf-btn-secondary:hover {
        border-color: #aebeb0;
        background: #f4f7f2;
        color: var(--pf-deep);
    }

    /* PASSWORD */

    .pf-password-panel {
        background:
            radial-gradient(circle at 100% 0%, rgba(216,141,101,.06), transparent 28%),
            #fff;
    }

    .pf-security-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 13px;
        margin-bottom: 20px;
        border-radius: 10px;
        background: #f5f6f0;
        color: #637267;
        font-size: 10px;
        line-height: 1.65;
    }

    .pf-security-note span {
        color: var(--pf-orange);
        font-size: 15px;
    }

    /* SIDE INFORMATION */

    .pf-side-panel {
        padding: 23px;
        border: 1px solid var(--pf-border);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(29,41,36,.035);
    }

    .pf-side-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: var(--pf-deep);
        font-family: 'Manrope', sans-serif;
        font-size: 15px;
        font-weight: 800;
    }

    .pf-side-title span {
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #eaf0e9;
        color: var(--pf-green);
        font-size: 16px;
    }

    .pf-account-detail {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid #edf0eb;
    }

    .pf-account-detail:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .pf-account-detail span {
        color: var(--pf-muted);
        font-size: 10px;
        font-weight: 600;
    }

    .pf-account-detail strong {
        max-width: 60%;
        color: var(--pf-deep);
        font-size: 11px;
        font-weight: 750;
        text-align: right;
        overflow-wrap: anywhere;
    }

    /* MEMBERSHIP LINK */

    .pf-membership-card {
        position: relative;
        overflow: hidden;
        padding: 22px;
        border: 0;
        border-radius: 15px;
        background:
            radial-gradient(circle at 100% 0%, rgba(216,141,101,.22), transparent 35%),
            linear-gradient(135deg, #142c24, #315c4d);
        color: #fff;
    }

    .pf-membership-card::after {
        content: "✳";
        position: absolute;
        right: 12px;
        bottom: -38px;
        color: rgba(255,255,255,.07);
        font-size: 130px;
        line-height: 1;
    }

    .pf-membership-card > * {
        position: relative;
        z-index: 1;
    }

    .pf-membership-icon {
        display: grid;
        place-items: center;
        width: 40px;
        height: 40px;
        margin-bottom: 16px;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 12px;
        background: rgba(255,255,255,.08);
        color: #edb38f;
        font-size: 20px;
    }

    .pf-membership-card h3 {
        margin: 0 0 7px;
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: 17px;
        font-weight: 800;
    }

    .pf-membership-card p {
        margin: 0 0 19px;
        color: rgba(255,255,255,.7);
        font-size: 11px;
        line-height: 1.7;
    }

    .pf-membership-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 38px;
        padding: 9px 14px;
        border-radius: 8px;
        background: #fff;
        color: var(--pf-forest);
        font-size: 10px;
        font-weight: 800;
        text-decoration: none;
        transition: .2s ease;
    }

    .pf-membership-link:hover {
        background: #f1e7de;
        color: var(--pf-forest);
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* RESPONSIVE */

    @keyframes pfEnter {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 900px) {
        .pf-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .pf-side-column {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: stretch;
        }
    }

    @media (max-width: 600px) {
        .nx-page-content .profile-container {
            padding: 0 12px;
            margin-top: 5px;
        }

        .pf-hero {
            padding: 23px 19px;
            border-radius: 16px;
        }

        .pf-hero-main {
            gap: 14px;
        }

        .pf-avatar {
            flex-basis: 69px;
            width: 69px;
            height: 69px;
        }

        .pf-avatar svg {
            width: 32px;
            height: 32px;
        }

        .pf-hero-info h1 {
            font-size: 24px;
        }

        .pf-hero-info p {
            font-size: 11px;
        }

        .pf-member-since {
            display: none;
        }

        .pf-panel,
        .pf-side-panel {
            padding: 19px 16px;
            border-radius: 14px;
        }

        .pf-form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .pf-side-column {
            grid-template-columns: 1fr;
        }

        .pf-actions .pf-btn {
            flex: 1 1 130px;
        }
    }

    @media (max-width: 390px) {
        .pf-hero-main {
            align-items: flex-start;
        }

        .pf-avatar {
            flex-basis: 58px;
            width: 58px;
            height: 58px;
        }

        .pf-hero-info h1 {
            font-size: 21px;
        }

        .pf-photo-row {
            align-items: flex-start;
        }

        .pf-actions .pf-btn {
            width: 100%;
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
    <div class="profile-container">

        {{-- Profile Hero --}}
        <section class="pf-hero">
            <div class="pf-hero-main">
                <div class="pf-avatar">
                    @if ($member->avatar)
                        <img src="{{ asset('storage/' . $member->avatar) }}" alt="Profile photo">
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21a8 8 0 0 1 16 0"></path>
                        </svg>
                    @endif
                </div>

                <div class="pf-hero-info">
                    <span class="pf-eyebrow">NEXORA ACCOUNT</span>
                    <h1>{{ $member->name }}</h1>
                    <p>{{ $member->email }}</p>
                </div>
            </div>

            <div class="pf-member-since">
                <span>MEMBER SINCE</span>
                <strong>{{ $member->created_at?->format('M d, Y') ?? 'N/A' }}</strong>
            </div>
        </section>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="pf-alert pf-alert-success">
                <span class="pf-alert-dot"></span>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="pf-alert pf-alert-error">
                <span class="pf-alert-dot"></span>
                {{ session('error') }}
            </div>
        @endif

        <div class="pf-layout">

            {{-- Main Column --}}
            <div class="pf-main-column">

                {{-- Personal Information --}}
                <section class="pf-panel">
                    <div class="pf-panel-heading">
                        <div class="pf-heading-icon">✧</div>
                        <div>
                            <h2>Personal Information</h2>
                            <p>Update your personal details and profile photo</p>
                        </div>
                    </div>

                    <form action="{{ route('member.profile.updatePersonal') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="pf-photo-row">
                            <div class="pf-photo-preview">
                                @if ($member->avatar)
                                    <img src="{{ asset('storage/' . $member->avatar) }}" alt="Profile">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="8" r="4"></circle>
                                        <path d="M4 21a8 8 0 0 1 16 0"></path>
                                    </svg>
                                @endif
                            </div>

                            <div class="pf-photo-info">
                                <strong>Profile Photo</strong>
                                <p>JPG, PNG or WEBP · Maximum 2MB</p>

                                <input type="file" id="avatar-upload" name="avatar"
                                       accept=".jpg,.jpeg,.png,.webp" class="pf-file-input">

                                <label for="avatar-upload" class="pf-upload-btn">
                                    Choose Photo
                                </label>

                                @error('avatar')
                                    <span class="pf-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pf-form-grid">
                            <div class="pf-field">
                                <label for="profile-name">Full Name</label>
                                <input id="profile-name" type="text" name="name"
                                       value="{{ old('name', $member->name) }}"
                                       placeholder="Your full name">

                                @error('name')
                                    <span class="pf-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pf-field">
                                <label for="profile-email">Email Address</label>
                                <input id="profile-email" type="email" name="email"
                                       value="{{ old('email', $member->email) }}"
                                       placeholder="Your email address">

                                @error('email')
                                    <span class="pf-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pf-field">
                                <label for="profile-phone">Phone Number</label>
                                <input id="profile-phone" type="text" name="phone"
                                       value="{{ old('phone', $member->phone) }}"
                                       placeholder="Your phone number">

                                @error('phone')
                                    <span class="pf-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pf-actions">
                            <button type="submit" class="pf-btn pf-btn-primary">
                                Save Changes <span>→</span>
                            </button>

                            <button type="reset" class="pf-btn pf-btn-secondary">
                                Reset Fields
                            </button>
                        </div>
                    </form>
                </section>

                {{-- Change Password --}}
                <section class="pf-panel pf-password-panel">
                    <div class="pf-panel-heading">
                        <div class="pf-heading-icon">⌑</div>
                        <div>
                            <h2>Security & Password</h2>
                            <p>Keep your account safe with a strong password</p>
                        </div>
                    </div>

                    <div class="pf-security-note">
                        <span>✦</span>
                        Use a unique password that you don't use on other websites.
                    </div>

                    <form action="{{ route('member.profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="pf-form-grid">
                            <div class="pf-field">
                                <label for="current-password">Current Password</label>
                                <input id="current-password" type="password"
                                       name="current_password"
                                       placeholder="Enter current password"
                                       autocomplete="current-password">

                                @error('current_password')
                                    <span class="pf-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pf-field">
                                <label for="new-password">New Password</label>
                                <input id="new-password" type="password" name="password"
                                       placeholder="Enter new password"
                                       autocomplete="new-password">

                                @error('password')
                                    <span class="pf-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pf-field">
                                <label for="confirm-password">Confirm New Password</label>
                                <input id="confirm-password" type="password"
                                       name="password_confirmation"
                                       placeholder="Confirm new password"
                                       autocomplete="new-password">
                            </div>
                        </div>

                        <div class="pf-actions">
                            <button type="submit" class="pf-btn pf-btn-primary">
                                Update Password <span>→</span>
                            </button>
                        </div>
                    </form>
                </section>

            </div>

            {{-- Side Column --}}
            <aside class="pf-side-column">

                <section class="pf-side-panel">
                    <h2 class="pf-side-title">
                        <span>◈</span>
                        Account Overview
                    </h2>

                    <div class="pf-account-detail">
                        <span>Full Name</span>
                        <strong>{{ $member->name }}</strong>
                    </div>

                    <div class="pf-account-detail">
                        <span>Email Address</span>
                        <strong>{{ $member->email }}</strong>
                    </div>

                    <div class="pf-account-detail">
                        <span>Phone Number</span>
                        <strong>{{ $member->phone ?: 'Not provided' }}</strong>
                    </div>

                    <div class="pf-account-detail">
                        <span>Member Since</span>
                        <strong>{{ $member->created_at?->format('M d, Y') ?? 'N/A' }}</strong>
                    </div>
                </section>

                <section class="pf-membership-card">
                    <div class="pf-membership-icon">✧</div>
                    <h3>Your Memberships</h3>
                    <p>
                        Discover your workspace subscriptions, manage your plans,
                        and explore more membership opportunities.
                    </p>

                    <a href="{{ route('member.memberships.index') }}"
                       class="pf-membership-link">
                        View Memberships <span>→</span>
                    </a>
                </section>

            </aside>
        </div>

    </div>
</div>
@endsection
