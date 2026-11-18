<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $workspaceName }}</title>
</head>
<body style="font-family: Arial, sans-serif; direction: ltr; padding: 25px;">

    <h2>Hello!</h2>

    <p>
        We have some exciting updates from
        <strong>{{ $workspaceName }}</strong>.
    </p>

    <div style="line-height: 1.8;">
        {!! nl2br(e($campaignContent)) !!}
    </div>

    <hr>

    <p>
        Thank you for being part of NEXORA.
    </p>

</body>
</html>