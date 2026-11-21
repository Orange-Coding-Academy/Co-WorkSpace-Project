
@extends('layouts.dashboard')

@section('dashboard-role', 'member')

@section('title') My Profile | NexOra @endsection
@section('page-title') My Profile @endsection

@push('styles')
<style>
    /* Member Profile — page-only styles; shared dashboard remains untouched. */
    .nx-page-content,
    .nx-page-content * { box-sizing: border-box; }

    .nx-page-content {
        --profile-primary: var(--nx-primary, #315C4D);
        --profile-deep: var(--nx-deep, #152A23);
        --profile-muted: var(--nx-text-secondary, #53615A);
        --profile-border: var(--nx-border, #E2E8E2);
        --profile-surface: var(--nx-card, #FFFFFF);
        color: var(--nx-text, #1D2924);
        font-family: var(--nx-font-body, 'Inter', sans-serif);
        min-width: 0;
    }

    .nx-page-content .profile-container {
        width: 100%;
        max-width: 1080px;
       margin: 0 auto 48px;
        padding: 0 12px;
    }

    .nx-page-content .page-title {
        margin: 0 0 8px;
        color: var(--profile-deep);
        font-family: var(--nx-font-heading, 'Manrope', sans-serif);
        font-size: clamp(26px, 3vw, 34px);
        font-weight: 800;
        letter-spacing: -.035em;
        line-height: 1.2;
    }

    .nx-page-content .subtitle {
        margin: 0 0 24px;
        color: var(--profile-muted);
        font-size: 14px;
        line-height: 1.65;
    }

    .nx-page-content .profile-card {
        margin-bottom: 20px;
        padding: clamp(22px, 3vw, 32px);
        border: 1px solid var(--profile-border);
        border-radius: 18px;
        background: var(--profile-surface);
        box-shadow: 0 3px 16px rgba(21, 42, 35, .035);
    }

    .nx-page-content .section-title {
        margin: 0 0 22px;
        color: var(--profile-deep);
        font-family: var(--nx-font-heading, 'Manrope', sans-serif);
        font-size: 19px;
        font-weight: 750;
        letter-spacing: -.025em;
        line-height: 1.4;
    }

    .nx-page-content .avatar-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 28px;
        padding: 18px 20px;
        border: 1px solid var(--profile-border);
        border-radius: 14px;
        background: var(--nx-surface, #F8FAF7);
    }

    .nx-page-content .avatar {
        display: grid;
        place-items: center;
        flex: 0 0 88px;
        width: 88px;
        height: 88px;
        overflow: hidden;
        border: 3px solid #FFFFFF;
        border-radius: 50%;
        background: #E7EEE7;
        color: var(--profile-primary);
        box-shadow: 0 2px 10px rgba(21, 42, 35, .09);
    }

    .nx-page-content .avatar img { display: block; width: 100%; height: 100%; object-fit: cover; }
    .nx-page-content .avatar svg { width: 40px; height: 40px; }
    .nx-page-content .avatar-info { min-width: 0; }
    .nx-page-content .avatar-info h3 {
        margin: 0 0 4px;
        color: var(--profile-deep);
        font-size: 15px;
        font-weight: 700;
    }
    .nx-page-content .avatar-info p {
        margin: 0 0 12px;
        color: var(--profile-muted);
        font-size: 12px;
        line-height: 1.5;
    }

    .nx-page-content .avatar-input {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        opacity: 0;
    }
    .nx-page-content .upload-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 16px;
        border: 1px solid var(--nx-border-strong, #C9D4CC);
        border-radius: 999px;
        background: #FFFFFF;
        color: var(--profile-primary);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }
    .nx-page-content .upload-btn:hover { background: #E7EEE7; border-color: var(--profile-primary); }
    .nx-page-content .avatar-input:focus-visible + .upload-btn {
        outline: 2px solid var(--nx-accent, #C58A62);
        outline-offset: 3px;
    }

    .nx-page-content .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px 22px;
    }
    .nx-page-content .form-group { min-width: 0; }
    .nx-page-content .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--profile-deep);
        font-size: 13px;
        font-weight: 650;
    }
    .nx-page-content .form-control {
        display: block;
        width: 100%;
        min-height: 46px;
        padding: 11px 14px;
        border: 1px solid var(--nx-border-strong, #C9D4CC);
        border-radius: 11px;
        background: #FFFFFF;
        color: var(--nx-text, #1D2924);
        font: inherit;
        font-size: 14px;
        line-height: 1.4;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .nx-page-content .form-control::placeholder { color: var(--nx-text-muted, #7F8B84); }
    .nx-page-content .form-control:hover:not(:disabled) { border-color: var(--nx-secondary, #6F806E); }
    .nx-page-content .form-control:focus {
        outline: none;
        border-color: var(--profile-primary);
        box-shadow: 0 0 0 3px rgba(49, 92, 77, .13);
    }
    .nx-page-content .form-control:disabled { background: #F0F2EF; color: var(--profile-muted); }

    .nx-page-content .actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 26px;
    }
    .nx-page-content .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 20px;
        border: 1px solid transparent;
        border-radius: 999px;
        font-family: var(--nx-font-heading, 'Manrope', sans-serif);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.3;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease, color .2s ease;
    }
    .nx-page-content .btn-primary {
        border-color: var(--profile-primary);
        background: var(--profile-primary);
        color: #FFFFFF;
    }
    .nx-page-content .btn-primary:hover {
        border-color: var(--nx-primary-hover, #274B3F);
        background: var(--nx-primary-hover, #274B3F);
        color: #FFFFFF;
    }
    .nx-page-content .btn-outline {
        border-color: var(--nx-border-strong, #C9D4CC);
        background: #FFFFFF;
        color: var(--profile-deep);
    }
    .nx-page-content .btn-outline:hover { background: #F0F4EF; border-color: var(--profile-primary); }
    .nx-page-content .btn:focus-visible { outline: 2px solid var(--nx-accent, #C58A62); outline-offset: 3px; }

    .nx-page-content .account-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 18px;
        border-top: 1px solid var(--profile-border);
        color: var(--profile-muted);
        font-size: 14px;
    }
    .nx-page-content .account-info strong { color: var(--profile-deep); font-weight: 700; }
    .nx-page-content .success-message {
        margin-bottom: 20px;
        padding: 14px 16px;
        border: 1px solid #BCE2C9;
        border-radius: 12px;
        background: #EAF6EF;
        color: #145C3A;
        font-size: 14px;
    }
    .nx-page-content .error-message { margin-top: 6px; color: #912018; font-size: 12px; line-height: 1.5; }

    /* Retain styling for any membership details shown by this page. */
    .nx-page-content .membership-item {
        margin-bottom: 16px;
        padding: 20px;
        border: 1px solid var(--profile-border);
        border-radius: 14px;
    }
    .nx-page-content .membership-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .nx-page-content .membership-header h3 { margin: 0; color: var(--profile-deep); font-size: 17px; }
    .nx-page-content .membership-status {
        padding: 6px 12px;
        border-radius: 999px;
        background: #E7EEE7;
        color: var(--profile-primary);
        font-size: 12px;
        font-weight: 700;
    }
    .nx-page-content .membership-details { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .nx-page-content .membership-details span { display: block; margin-bottom: 6px; color: var(--profile-muted); font-size: 12px; }
    .nx-page-content .membership-details strong { color: var(--profile-deep); font-size: 14px; }
    .nx-page-content .no-memberships { color: var(--profile-muted); font-size: 14px; }

    @media (max-width: 650px) {
        .nx-page-content .profile-container { margin-top: 8px; padding: 0; }
        .nx-page-content .profile-card { padding: 20px 18px; border-radius: 15px; }
        .nx-page-content .form-grid, .nx-page-content .membership-details { grid-template-columns: 1fr; gap: 17px; }
        .nx-page-content .avatar-section { align-items: flex-start; padding: 16px; gap: 14px; }
        .nx-page-content .avatar { flex-basis: 72px; width: 72px; height: 72px; }
        .nx-page-content .avatar svg { width: 34px; height: 34px; }
        .nx-page-content .actions .btn { flex: 1 1 145px; }
    }
    @media (max-width: 390px) {
        .nx-page-content .avatar-section { flex-direction: column; }
        .nx-page-content .actions .btn { width: 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .nx-page-content .btn, .nx-page-content .form-control, .nx-page-content .upload-btn { transition: none; }
    }
</style>
@endpush

@section('content')
<div class="nx-page-content">
<div class="profile-container">

    <h1 class="page-title">My Profile</h1>
    <p class="subtitle">Manage your personal information and account security.</p>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    {{-- Personal Information --}}
    <div class="profile-card">

        <h2 class="section-title">Personal Information</h2>

        <form action="{{ route('member.profile.updatePersonal') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="avatar-section">

                <div class="avatar">
                    @if($member->avatar)
                        <img src="{{ asset('storage/' . $member->avatar) }}" alt="Profile">
                    @else
                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.5"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21a8 8 0 0 1 16 0"></path>
                        </svg>
                    @endif
                </div>

                <div class="avatar-info">
                    <h3>Profile Photo</h3>
                    <p>JPG, PNG or WEBP. Maximum 2MB.</p>

                    <input type="file"
                           id="avatar-upload"
                           name="avatar"
                           accept=".jpg,.jpeg,.png,.webp"
                           class="avatar-input">

                    <label for="avatar-upload" class="upload-btn">
                        Choose Photo
                    </label>

                    @error('avatar')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $member->name) }}">

                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $member->email) }}">

                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="{{ old('phone', $member->phone) }}">

                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="reset" class="btn btn-outline">Cancel</button>
            </div>

        </form>
    </div>

    {{-- Change Password --}}
    <div class="profile-card">

        <h2 class="section-title">Change Password</h2>

        <form action="{{ route('member.profile.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password"
                           name="current_password"
                           class="form-control"
                           placeholder="Enter current password">

                    @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Enter new password">

                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           placeholder="Confirm new password">
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Update Password
                </button>
            </div>

        </form>
    </div>

    {{-- Account Information --}}
    <div class="profile-card">

        <h2 class="section-title">Account Information</h2>
         <p class="subtitle">View your account details and membership information.</p>
        <div class="account-info">
            <span>Member Since</span>
            <strong>{{ $member->created_at?->format('M d, Y') }}</strong>
        </div>

    </div>
{{-- My Memberships --}}
<div class="profile-card">

    <h2 class="section-title">My Memberships</h2>

    <p class="subtitle">
        View and manage your workspace memberships.
    </p>

   <a href="{{ route('member.memberships.index') }}"
   class="btn btn-primary"
   style="display: inline-block; text-decoration: none;">
    View My Memberships
</a>

</div>

</div>
</div>
@endsection
