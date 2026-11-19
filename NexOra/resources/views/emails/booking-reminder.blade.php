@php
    $start = \Carbon\Carbon::parse($booking->start_date_time);
    $end   = \Carbon\Carbon::parse($booking->end_date_time);
    $room  = $booking->room?->name ?? 'Your room';
    $workspace = $booking->room?->workspace?->name;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking reminder</title>
</head>
<body style="margin:0; padding:0; background:#f4f5f7; font-family:Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f7; padding:24px 0;">
    <tr>
        <td align="center">
            <table width="560" cellpadding="0" cellspacing="0" style="max-width:560px; width:100%; background:#ffffff; border-radius:12px; overflow:hidden;">

                <tr>
                    <td style="background:#1f2a44; padding:24px; text-align:center;">
                        <span style="color:#ffffff; font-size:22px; font-weight:bold; letter-spacing:1px;">NexOra</span>
                    </td>
                </tr>

                <tr>
                    <td style="padding:32px 32px 8px;">
                        <h2 style="margin:0 0 12px; color:#1f2a44; font-size:22px;">
                            Your booking is coming up
                        </h2>
                        <p style="margin:0; color:#555555; font-size:15px; line-height:1.6;">
                            Hi {{ $booking->user->name }}, this is a friendly reminder about your reservation in the next 24 hours.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:20px 32px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb; border-radius:10px; border:1px solid #e6e8ee;">
                            <tr>
                                <td style="padding:16px 20px; border-bottom:1px solid #e6e8ee;">
                                    <div style="color:#8a90a0; font-size:12px; text-transform:uppercase;">Room</div>
                                    <div style="color:#1f2a44; font-size:16px; font-weight:bold;">
                                        {{ $room }}@if($workspace) &middot; {{ $workspace }}@endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:16px 20px; border-bottom:1px solid #e6e8ee;">
                                    <div style="color:#8a90a0; font-size:12px; text-transform:uppercase;">Date</div>
                                    <div style="color:#1f2a44; font-size:16px; font-weight:bold;">
                                        {{ $start->format('l, d M Y') }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:16px 20px;">
                                    <div style="color:#8a90a0; font-size:12px; text-transform:uppercase;">Time</div>
                                    <div style="color:#1f2a44; font-size:16px; font-weight:bold;">
                                        {{ $start->format('h:i A') }} &ndash; {{ $end->format('h:i A') }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding:8px 32px 28px;">
                        <a href="{{  route('dashboard') }}"
                           style="display:inline-block; background:#2f6fed; color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:8px; font-size:15px; font-weight:bold;">
                            View my bookings
                        </a>
                    </td>
                </tr>

                <tr>
                    <td style="padding:20px 32px; background:#f8f9fb; text-align:center; color:#8a90a0; font-size:12px; line-height:1.6;">
                        You are receiving this email because you made a booking on NexOra.<br>
                        &copy; {{ date('Y') }} NexOra
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>