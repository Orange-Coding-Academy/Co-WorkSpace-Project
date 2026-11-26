<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $workspaceName }}</title>
</head>
<body style="font-family: Inter, Arial, Helvetica, sans-serif; direction: ltr; padding: 25px; margin: 0; background: #F5F5F5; color: #1D2924; font-size: 15px; line-height: 1.6;">

    <h2 style="margin: 0 0 12px; color: #203D34; font-family: Manrope, Arial, Helvetica, sans-serif; font-size: 22px; font-weight: 800;">Hello!</h2>

    <p style="margin: 0 0 16px; color: #53615A;">
        We have some exciting updates from
        <strong style="color: #315C4D;">{{ $workspaceName }}</strong>.
    </p>

    <div style="line-height: 1.8; background: #FFFFFF; border: 1px solid #E2E8E2; border-radius: 16px; padding: 20px 24px; color: #1D2924;">
        {!! nl2br(e($campaignContent)) !!}
    </div>

    <hr style="border: 0; border-top: 1px solid #E2E8E2; margin: 24px 0;">

    <p style="margin: 0; color: #7F8B84; font-size: 13px;">
        Thank you for being part of NEXORA.
    </p>

</body>
</html>