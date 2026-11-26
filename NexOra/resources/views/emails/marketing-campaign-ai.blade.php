<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $workspaceName }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f8;font-family:Arial,Helvetica,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f8;padding:32px 12px;">
  <tr>
    <td align="center">
      <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;">

        <tr>
          <td align="center" style="background:#4f46e5;background-image:linear-gradient(135deg,#4f46e5,#7c3aed);padding:36px 24px;">
            <div style="font-size:13px;letter-spacing:3px;color:#c7d2fe;margin-bottom:8px;">NEXORA</div>
            <h1 style="margin:0;font-size:26px;color:#ffffff;">{{ $workspaceName }}</h1>
          </td>
        </tr>

        <tr>
          <td style="padding:32px 36px;font-size:16px;line-height:1.7;color:#374151;">
            {!! \Illuminate\Support\Str::markdown($campaignContent, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
          </td>
        </tr>

        <tr>
          <td align="center" style="padding:0 36px 36px;">
            <a href="{{ config('app.url') }}"
               style="display:inline-block;background:#4f46e5;color:#ffffff;text-decoration:none;font-weight:bold;padding:14px 32px;border-radius:999px;font-size:16px;">
              Book Now
            </a>
          </td>
        </tr>

        <tr>
          <td align="center" style="background:#f9fafb;padding:20px 24px;font-size:12px;color:#9ca3af;">
            You received this email because you previously booked with NEXORA.<br>
            &copy; {{ date('Y') }} NEXORA. All rights reserved.
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>