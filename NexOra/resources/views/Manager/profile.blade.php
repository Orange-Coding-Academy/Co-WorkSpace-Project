{{--
Profile Routes 

use App\Http\Controllers\Manager\ManagerProfileController;

Route::get('/profile', [ManagerProfileController::class, 'index'])->name('profile.index');

Route::put('/profile', [ManagerProfileController::class, 'updatePersonal'])->name('profile.updatePersonal');

Route::put('/profile/password', [ManagerProfileController::class, 'updatePassword'])->name('profile.updatePassword');

--}}

@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Manager Profile | NexOra @endsection
@section('page-title') Manager Profile @endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-18.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-04.css') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-19.css') }}">
@endpush

@section('content')
<div class="nx-page-content">
<div data-nx-page-main class="container">

    <div class="page-heading">
        <h1>My Profile</h1>
        <p>Manage your personal and business information.</p>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <!-- PERSONAL PROFILE -->
    <section class="card">

        <div class="profile-header">
            <div class="avatar" data-profile-avatar>
                @if($manager->avatar)
                    <img src="{{ asset('storage/' . $manager->avatar) }}"
                         alt="Profile Photo"
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr($manager->name, 0, 1)) }}
                @endif
            </div>

            <div>
                <h2>{{ $manager->name }}</h2>
                <p>Space Manager</p>
            </div>
        </div>

        <div class="profile-divider"></div>

        <h2 class="section-title">Personal Information</h2>
        <p class="section-description">
            Update your personal contact details.
        </p>

        <form action="{{ route('manager.profile.updatePersonal') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label for="name">Full Name</label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $manager->name) }}">

                    @error('name')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>

                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $manager->email) }}">

                    @error('email')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>

                    <input type="tel"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $manager->phone) }}">

                    @error('phone')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="avatar">Profile Photo</label>

                    <input type="file"
                           id="avatar"
                           name="avatar"
                           data-profile-photo-input
                           accept="image/png,image/jpeg,image/webp">

                    <span class="hint">JPG, PNG or WEBP.</span>

                    @error('avatar')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="btn">
                    Save Personal Information
                </button>
            </div>

        </form>
    </section>

   
    <!-- CHANGE PASSWORD -->
    <section class="card">

        <h2 class="section-title">Change Password</h2>

        <p class="section-description">
            Keep your account secure by updating your password.
        </p>

        <form action="{{ route('manager.profile.updatePassword') }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group full">
                    <label for="current_password">
                        Current Password
                    </label>

                    <input type="password"
                           id="current_password"
                           name="current_password"
                           placeholder="Enter current password">

                    @error('current_password')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">
                        New Password
                    </label>

                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Enter new password">

                    @error('password')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Confirm New Password
                    </label>

                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Confirm new password">

                    @error('password_confirmation')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            <div class="security-note">
                For security, your current password is required
                before changing to a new password.
            </div>

            <div class="actions">
                <button type="submit" class="btn">
                    Update Password
                </button>
            </div>

        </form>
    </section>

</div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/pages/profile-photo-preview.js') }}?v=20261125-ui1"></script>
@endpush
