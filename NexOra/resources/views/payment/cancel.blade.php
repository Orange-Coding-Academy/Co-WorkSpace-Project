<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment canceled</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5 text-center" style="max-width: 500px;">
    <h2 class="text-danger">Payment canceled</h2>
    <p>No money was taken. You can try again.</p>
    <a href="{{ route('stripe.test') }}" class="btn btn-primary">Back</a>
</div>
</body>
</html>