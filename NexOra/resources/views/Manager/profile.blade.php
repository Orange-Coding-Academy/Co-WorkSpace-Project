{{--
Profile Routes 

use App\Http\Controllers\Manager\ManagerProfileController;

Route::get('/profile', [ManagerProfileController::class, 'index'])->name('profile.index');

Route::put('/profile', [ManagerProfileController::class, 'updatePersonal'])->name('profile.updatePersonal');

Route::put('/profile/password', [ManagerProfileController::class, 'updatePassword'])->name('profile.updatePassword');

--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Profile | NexOra</title>

    <style>
        :root {
            --navy: #12243d;
            --teal: #16858a;
            --background: #f3f6fa;
            --border: #e0e7ef;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--background);
            color: var(--navy);
        }

        .topbar {
            background: white;
            padding: 18px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            color: var(--navy);
        }

        .brand span {
            color: var(--teal);
        }

        .topbar-label {
            font-size: 14px;
            color: var(--muted);
        }

        .container {
            max-width: 1100px;
            margin: 38px auto;
            padding: 0 20px 50px;
        }

        .page-heading {
            margin-bottom: 25px;
        }

        .page-heading h1 {
            font-size: 30px;
            margin: 0 0 8px;
        }

        .page-heading p {
            color: var(--muted);
            margin: 0;
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 5px 22px rgba(18,36,61,.035);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .avatar {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            background: #dceeed;
            color: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-header h2 {
            margin: 0 0 5px;
            font-size: 21px;
        }

        .profile-header p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .profile-divider {
            height: 1px;
            background: var(--border);
            margin: 25px 0;
        }

        .section-title {
            font-size: 20px;
            margin: 0 0 8px;
        }

        .section-description {
            font-size: 14px;
            color: var(--muted);
            margin: 0 0 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        input,
        textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            font: inherit;
            font-size: 14px;
            background: white;
            color: var(--navy);
            outline: none;
            transition: border-color .2s;
        }

        input:focus,
        textarea:focus {
            border-color: var(--teal);
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        input[type="file"] {
            padding: 11px;
            cursor: pointer;
        }

        .hint {
            font-size: 12px;
            color: var(--muted);
        }

        .upload-area {
            border: 2px dashed #b8d5d7;
            border-radius: 12px;
            background: #f8fcfc;
            padding: 25px;
            text-align: center;
        }

        .upload-icon {
            font-size: 30px;
            color: var(--teal);
            margin-bottom: 10px;
        }

        .upload-area p {
            font-size: 14px;
            color: var(--muted);
            margin: 0 0 15px;
        }

        .upload-area input {
            max-width: 360px;
            margin: auto;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 26px;
        }

        .btn {
            background: var(--teal);
            color: white;
            border: none;
            border-radius: 9px;
            padding: 13px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }

        .btn:hover {
            background: #106d72;
        }

        .security-note {
            background: #f1f8f8;
            border-left: 3px solid var(--teal);
            border-radius: 5px;
            padding: 14px;
            margin-top: 22px;
            font-size: 13px;
            color: #46646a;
        }

        .success-message {
            background: #e8f7ef;
            border: 1px solid #a9dfc2;
            color: #236844;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        .error-message {
            color: #d62828;
            font-size: 12px;
        }

        @media (max-width: 700px) {
            .container {
                margin-top: 25px;
            }

            .card {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .page-heading h1 {
                font-size: 25px;
            }

            .topbar {
                padding: 16px 20px;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="brand">Nex<span>Ora</span></div>
    <div class="topbar-label">Manager Dashboard</div>
</header>

<main class="container">

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
            <div class="avatar">
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

</main>

</body>
</html>
