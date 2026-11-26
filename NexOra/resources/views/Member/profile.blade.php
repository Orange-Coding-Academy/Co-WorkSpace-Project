
@extends('layouts.member')

@section('title', 'My Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/member-profile.css') }}?v=20261125-ui3">
@endpush

@section('content')
<div class="nx-page-content">
    <div class="profile-container">

        {{-- Profile Hero --}}
        <section class="pf-hero">
            <div class="pf-hero-main">
                <div class="pf-avatar" data-profile-avatar>
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
                    <p class="email">{{ $member->email }}</p>
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
                            <div class="pf-photo-preview" data-profile-avatar>
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

@push('scripts')
<script src="{{ asset('js/pages/profile-photo-preview.js') }}?v=20261125-ui2"></script>
@endpush
