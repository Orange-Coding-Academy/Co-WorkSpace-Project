{{ 


        
/*use App\Http\Controllers\Member\MemberMembershipController;
use App\Http\Controllers\Member\MemberProfileController; */


   /*Route::get('/member/profile', [MemberProfileController::class, 'index'])
        ->name('member.profile.index');


Route::put('/member/profile', [MemberProfileController::class, 'updatePersonal'])
    ->name('member.profile.updatePersonal');

Route::put('/member/profile/password', [MemberProfileController::class, 'updatePassword'])
   ->name('member.profile.updatePassword');

   Route::get('/member/memberships', [MemberMembershipController::class, 'index'])
    ->name('member.memberships.index');
 
Route::patch('/member/memberships/{id}/cancel',
    [MemberMembershipController::class, 'cancel']
)->name('member.memberships.cancel');*/
 }}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | NexOra</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #1c293b;
            font-family: Arial, sans-serif;
        }

        .profile-container {
            max-width: 1000px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 29px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #788596;
            margin-bottom: 30px;
        }

        .profile-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 5px 25px rgba(0,0,0,.04);
        }

        .section-title {
            font-size: 20px;
            margin: 0 0 24px;
            color: #183047;
        }

        .avatar-section {
            display: flex;
            align-items: center;
            gap: 22px;
            margin-bottom: 30px;
        }

        .avatar {
            width: 90px;
            height: 90px;
            min-width: 90px;
            border-radius: 50%;
            background: #e0f4f2;
            color: #138f88;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar svg {
            width: 46px;
            height: 46px;
        }

        .avatar-info h3 {
            margin: 0 0 8px;
        }

        .avatar-info p {
            color: #8995a3;
            font-size: 13px;
            margin: 0 0 12px;
        }

        .upload-btn {
            display: inline-block;
            background: #119e95;
            color: white;
            padding: 11px 20px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .upload-btn:hover {
            background: #087e77;
        }

        .avatar-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden;
        }

        .avatar-input:focus-visible + .upload-btn {
            outline: 2px solid #183047;
            outline-offset: 3px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 9px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 13px;
            border: 1px solid #dce3eb;
            border-radius: 9px;
            font-size: 14px;
            background: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #14a69d;
        }

        .form-control:disabled {
            background: #f7f9fc;
            color: #637184;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        .btn {
            border: none;
            padding: 12px 23px;
            border-radius: 9px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #119e95;
            color: white;
        }

        .btn-outline {
            background: white;
            border: 1px solid #dce3eb;
            color: #334155;
        }

        .btn-primary:hover {
            background: #087e77;
        }

        .account-info {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #edf0f4;
            padding-top: 20px;
            color: #667588;
            font-size: 14px;
        }

        .success-message {
            background: #e5f8ef;
            color: #176b46;
            padding: 13px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 6px;
        }
        .membership-item {
    border: 1px solid #edf0f4;
    border-radius: 12px;
    padding: 22px;
    margin-bottom: 16px;
}

.membership-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.membership-header h3 {
    margin: 0;
    color: #183047;
    font-size: 18px;
}

.membership-status {
    background: #e0f4f2;
    color: #138f88;
    padding: 7px 13px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.membership-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.membership-details span {
    display: block;
    color: #788596;
    font-size: 13px;
    margin-bottom: 7px;
}

.membership-details strong {
    color: #1c293b;
    font-size: 14px;
}

.no-memberships {
    color: #788596;
    font-size: 14px;
}

@media (max-width: 650px) {
    .membership-details {
        grid-template-columns: 1fr;
    }
}

        @media (max-width: 650px) {
            .form-grid { grid-template-columns: 1fr; }
            .profile-card { padding: 20px; }
            .avatar-section { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>

<body>

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

</body>
</html>
