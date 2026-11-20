
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
</head>
<body>

    <h2>Admin Profile</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div style="color: red">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <hr>

    <h3>Personal Information</h3>

    <form action="{{ route('admin.profile.personal') }}" method="POST">

        @csrf
        @method('PATCH')

        <div>
            <label>Name</label>
            <input type="text" name="name"
                value="{{ old('name', $admin->name) }}" required>
        </div>

        <br>

        <div>
            <label>Email</label>
            <input type="email" name="email"
                value="{{ old('email', $admin->email) }}" required>
        </div>

        <br>

        <button type="submit">Update Profile</button>

    </form>

    <hr>

    <h3>Change Password</h3>

    <form action="{{ route('admin.profile.password') }}" method="POST">

        @csrf
        @method('PATCH')

        <div>
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>

        <br>

        <div>
            <label>New Password</label>
            <input type="password" name="password" required>
        </div>

        <br>

        <div>
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <br>

        <button type="submit">Update Password</button>

    </form>

</body>
</html>
