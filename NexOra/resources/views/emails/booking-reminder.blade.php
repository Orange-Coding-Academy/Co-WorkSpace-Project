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
<body style="margin:0; padding:0; background:#F5F5F5; font-family:Manrope, Inter, Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F5F5F5; padding:24px 0;">
    <tr>
        <td align="center">
            <table width="560" cellpadding="0" cellspacing="0" style="max-width:560px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #E2E8E2;">

                <tr>
                    <td style="background:#121212; padding:24px; text-align:center;">
                        <span style="color:#ffffff; font-size:22px; font-weight:800; letter-spacing:-0.5px;">NexOra</span>
                    </td>
                </tr>

                <tr>
                    <td style="padding:32px 32px 8px;">
                        <h2 style="margin:0 0 12px; color:#203D34; font-size:22px;">
                            Your booking is coming up
                        </h2>
                        <p style="margin:0; color:#53615A; font-size:15px; line-height:1.6;">
                            Hi {{ $booking->user->name }}, this is a friendly reminder about your reservation in the next 24 hours.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:20px 32px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#F1F2F2; border-radius:10px; border:1px solid #E2E8E2;">
                            <tr>
                                <td style="padding:16px 20px; border-bottom:1px solid #E2E8E2;">
                                    <div style="color:#7F8B84; font-size:12px; text-transform:uppercase;">Room</div>
                                    <div style="color:#203D34; font-size:16px; font-weight:bold;">
                                        {{ $room }}@if($workspace) &middot; {{ $workspace }}@endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:16px 20px; border-bottom:1px solid #E2E8E2;">
                                    <div style="color:#7F8B84; font-size:12px; text-transform:uppercase;">Date</div>
                                    <div style="color:#203D34; font-size:16px; font-weight:bold;">
                                        {{ $start->format('l, d M Y') }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:16px 20px;">
                                    <div style="color:#7F8B84; font-size:12px; text-transform:uppercase;">Time</div>
                                    <div style="color:#203D34; font-size:16px; font-weight:bold;">
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
                           style="display:inline-block; background:#121212; color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:999px; font-size:15px; font-weight:bold;">
                            View my bookings
                        </a>
                    </td>
                </tr>

                <tr>
                    <td style="padding:20px 32px; background:#F1F2F2; text-align:center; color:#7F8B84; font-size:12px; line-height:1.6;">
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