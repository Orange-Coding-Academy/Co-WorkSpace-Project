<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment canceled</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/layouts-app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}?v=20261125-green3">
</head>
<body class="bg-light">
<div class="container py-5 text-center" style="max-width: 500px;">
    <h2 class="text-danger">Payment canceled</h2>
    <p>No money was taken. You can try again.</p>
    <a href="{{ route('member.bookings.index') }}" class="btn btn-primary">Back</a>
</div>
</body>
</html>