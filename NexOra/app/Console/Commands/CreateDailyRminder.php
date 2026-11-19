<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateDailyRminder extends Command
{
    protected $signature = 'reminder:create-daily';

    protected $description = 'Email users a reminder about bookings starting within the next 24 hours';

    public function handle()
    {
        $now = now();

        $bookings = Booking::with(['user', 'room.workspace'])
            ->where('status', 'confirmed')
            ->whereBetween('start_date_time', [
                $now,
                $now->copy()->addDay()
            ])
            ->whereHas('user', function ($query) {
                $query->whereNull('reminder_sent_at');
            })
            ->get();

        $sent = 0;

        foreach ($bookings as $booking) {
            if (!$booking->user?->email) {
                continue;
            }

            try {
                Mail::send(
                    'emails.booking-reminder',
                    ['booking' => $booking],
                    function ($message) use ($booking) {
                        $message->to($booking->user->email)
                            ->subject('Reminder: your booking is coming up');
                    }
                );

                // Update reminder_sent_at in the USERS table
                $booking->user->update([
                    'reminder_sent_at' => now()
                ]);

                $sent++;

            } catch (\Throwable $e) {
                Log::error('REMINDER EMAIL FAILED', [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Reminders sent: {$sent}");

        return Command::SUCCESS;
    }
}
