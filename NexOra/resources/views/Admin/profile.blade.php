
@extends('layouts.dashboard')

@section('dashboard-role', 'admin')

@section('title')
    My Profile
@endsection

@section('page-title')
    Admin Profile
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/admin-profile.css') }}?v=20261125-ui1">
<link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-06.css') }}?v=20261125-ui1">
@endpush

@section('content')
<div class="nx-page-content">
    <div class="profile-container">

        <h1 class="page-title">My Profile</h1>
        <p class="subtitle">Manage your personal information and account security.</p>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        {{-- Personal Information --}}
        <div class="profile-card">
            <h2 class="section-title">Personal Information</h2>

            <form action="{{ route('admin.profile.personal') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PATCH')

                <div class="avatar-section">
                    <div class="avatar" data-profile-avatar>
                        @if ($admin->avatar)
                            <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Profile">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
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
                               data-profile-photo-input
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
                        <label for="name">Full Name</label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $admin->name) }}"
                               required>

                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $admin->email) }}"
                               required>

                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $admin->phone) }}">

                        @error('phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>

                    <button type="reset" class="btn btn-outline">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="profile-card">
            <h2 class="section-title">Change Password</h2>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               class="form-control"
                               placeholder="Enter current password"
                               required>

                        @error('current_password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control"
                               placeholder="Enter new password"
                               required>

                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Confirm new password"
                               required>
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

            <p class="subtitle">
                View your account details.
            </p>

            <div class="account-info">
                <span>Admin Since</span>
                <strong>{{ $admin->created_at?->format('M d, Y') }}</strong>
            </div>

            <div class="account-info">
                <span>Account Role</span>
                <strong>{{ ucfirst($admin->role) }}</strong>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/pages/profile-photo-preview.js') }}?v=20261125-ui1"></script>
@endpush
