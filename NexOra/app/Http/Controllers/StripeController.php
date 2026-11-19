<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeController extends Controller
{
  
public function checkoutBooking(Request $request)
{
    $data = $request->validate([
        'booking_id' => ['required', 'integer', 'exists:bookings,id'],
    ]);

    // Only allow the owner to pay for their booking.
    $booking = Booking::where('user_id', Auth::id())
        ->where('status', 'pending')
        ->findOrFail($data['booking_id']);

    $room = Room::findOrFail($booking->room_id);

    // Recheck availability, excluding this booking itself.
    $overlap = Booking::where('room_id', $booking->room_id)
        ->where('id', '!=', $booking->id)
        ->whereIn('status', ['pending', 'confirmed'])
        ->where('start_date_time', '<', $booking->end_date_time)
        ->where('end_date_time', '>', $booking->start_date_time)
        ->exists();

    if ($overlap) {
        return back()->withErrors([
            'booking_id' => 'This room is no longer available for the selected time.',
        ]);
    }

    // Reuse an existing payment instead of creating duplicates.
    $payment = Payment::firstOrCreate(
        ['booking_id' => $booking->id],
        [
            'user_id' => Auth::id(),
            'membership_id' => null,
            'provider' => 'stripe',
            'amount' => $booking->total_price,
            'currency' => 'USD',
            'status' => 'pending',
        ]
    );

    if ($payment->status === 'paid') {
        return redirect()
            ->route('member.bookings.show', $booking->id)
            ->with('success', 'This booking has already been paid.');
    }

    if ($payment->status !== 'pending') {
        return back()->withErrors([
            'booking_id' => 'This payment cannot be processed. Please contact support.',
        ]);
    }

    // Use the existing booking amount. Do not create another booking.
    return $this->redirectToStripe(
        $payment,
        'Booking: ' . $room->name
    );
}

    public function checkoutMembership(Request $request)
    {
        Log::info('MEMBERSHIP CHECKOUT STARTED', [
            'user_id' => Auth::id(),
            'request' => $request->only(['plan_id']),
        ]);

        try {
            $data = $request->validate([
                'plan_id' => ['required', 'integer', 'exists:membership_plans,id'],
            ]);

            $plan = MembershipPlan::where('is_active', true)
                ->findOrFail($data['plan_id']);

            Log::info('MEMBERSHIP PLAN LOADED', [
                'plan_id' => $plan->id,
                'plan_name' => $plan->plan_name,
                'price' => $plan->price,
                'duration_days' => $plan->duration_days,
            ]);

            $payment = DB::transaction(function () use ($plan) {
                $membership = Membership::create([
                    'user_id' => Auth::id(),
                    'plan_id' => $plan->id,
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays($plan->duration_days)->toDateString(),
                    'status' => 'pending',
                ]);

                Log::info('MEMBERSHIP CREATED', [
                    'membership_id' => $membership->id,
                ]);

                $payment = Payment::create([
                    'user_id' => Auth::id(),
                    'booking_id' => null,
                    'membership_id' => $membership->id,
                    'provider' => 'stripe',
                    'amount' => $plan->price,
                    'currency' => 'USD',
                    'status' => 'pending',
                ]);

                Log::info('MEMBERSHIP PAYMENT CREATED', [
                    'payment_id' => $payment->id,
                    'membership_id' => $membership->id,
                    'amount' => $payment->amount,
                ]);

                return $payment;
            });

            return $this->redirectToStripe(
                $payment,
                $plan->plan_name . ' membership'
            );

        } catch (\Throwable $e) {
            Log::error('MEMBERSHIP CHECKOUT FAILED', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }

    private function redirectToStripe(Payment $payment, string $name)
    {
        Log::info('STRIPE SESSION CREATION STARTED', [
            'payment_id' => $payment->id,
            'name' => $name,
            'amount' => $payment->amount,
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            if ($payment->stripe_checkout_session_id) {

    $existingSession = Session::retrieve(
        $payment->stripe_checkout_session_id
    );

    if ($existingSession->status === 'open') {
        return redirect($existingSession->url);
    }
}

            $session = Session::create([
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => $name],
                        'unit_amount' => (int) round((float) $payment->amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'payment_id' => (string) $payment->id,
                ],
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
            ]);

            Log::info('STRIPE SESSION CREATED', [
                'payment_id' => $payment->id,
                'session_id' => $session->id,
                'session_status' => $session->status,
            ]);

            $payment->update([
                'stripe_checkout_session_id' => $session->id,
            ]);

            Log::info('PAYMENT UPDATED WITH STRIPE SESSION', [
                'payment_id' => $payment->id,
                'session_id' => $session->id,
            ]);

            return redirect($session->url);

        } catch (\Throwable $e) {
            Log::error('STRIPE SESSION CREATION FAILED', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }

    public function success()
    {
        Log::info('CUSTOMER RETURNED TO SUCCESS PAGE', [
            'session_id' => request('session_id'),
        ]);

        return view('payment.success');
    }

    public function cancel()
    {
        Log::warning('CUSTOMER RETURNED TO CANCEL PAGE');

        return view('payment.cancel');
    }

    public function webhook(Request $request)
    {
        Log::info('STRIPE WEBHOOK RECEIVED', [
            'signature_present' => $request->hasHeader('Stripe-Signature'),
            'payload_length' => strlen($request->getContent()),
        ]);

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret')
            );

            Log::info('STRIPE WEBHOOK VERIFIED', [
                'event_id' => $event->id,
                'event_type' => $event->type,
            ]);

        } catch (\UnexpectedValueException | SignatureVerificationException $e) {
            Log::error('STRIPE WEBHOOK VERIFICATION FAILED', [
                'error' => $e->getMessage(),
            ]);

            return response('Invalid', 400);
        }

        if ($event->type !== 'checkout.session.completed') {
            Log::info('STRIPE EVENT IGNORED', [
                'event_type' => $event->type,
            ]);

            return response()->json(['received' => true]);
        }

        $session = $event->data->object;

        Log::info('CHECKOUT SESSION DATA', [
            'session_id' => $session->id,
            'payment_id' => $session->metadata->payment_id ?? null,
            'payment_status' => $session->payment_status ?? null,
        ]);

        if (($session->payment_status ?? null) !== 'paid') {
            Log::warning('CHECKOUT SESSION NOT PAID', [
                'session_id' => $session->id,
                'payment_status' => $session->payment_status ?? null,
            ]);

            return response()->json(['received' => true]);
        }

        $paymentId = $session->metadata->payment_id ?? null;

        if (!$paymentId) {
            Log::error('WEBHOOK HAS NO PAYMENT ID', [
                'session_id' => $session->id,
            ]);

            return response()->json(['received' => true]);
        }

        $payment = Payment::find($paymentId);

        Log::info('PAYMENT LOOKUP', [
            'payment_id' => $paymentId,
            'found' => $payment !== null,
            'status' => $payment?->status,
        ]);

        if (!$payment) {
            Log::error('PAYMENT NOT FOUND', [
                'payment_id' => $paymentId,
                'session_id' => $session->id,
            ]);

            return response()->json(['received' => true]);
        }

        if ($payment->status === 'paid') {
            Log::info('PAYMENT ALREADY PAID - DUPLICATE EVENT', [
                'payment_id' => $payment->id,
            ]);

            return response()->json(['received' => true]);
        }

        try {
            DB::transaction(function () use ($payment, $session) {

                Log::info('PAYMENT TRANSACTION STARTED', [
                    'payment_id' => $payment->id,
                ]);

                $payment->update([
                    'status' => 'paid',
                    'stripe_checkout_session_id' => $session->id,
                    'paid_at' => now(),
                ]);

                Log::info('PAYMENT STATUS UPDATED TO PAID', [
                    'payment_id' => $payment->id,
                ]);

                if ($payment->booking_id) {
                    $booking = Booking::find($payment->booking_id);

                    if (!$booking) {
                        throw new \RuntimeException(
                            'Booking not found: ' . $payment->booking_id
                        );
                    }

                    $booking->update([
                        'status' => 'confirmed',
                    ]);

                    Log::info('BOOKING CONFIRMED', [
                        'booking_id' => $booking->id,
                    ]);
                }

                if ($payment->membership_id) {
                    $membership = Membership::find($payment->membership_id);

                    if (!$membership) {
                        throw new \RuntimeException(
                            'Membership not found: ' . $payment->membership_id
                        );
                    }

                    $plan = MembershipPlan::find($membership->plan_id);

                    if (!$plan) {
                        throw new \RuntimeException(
                            'Membership plan not found: ' . $membership->plan_id
                        );
                    }

                    $membership->update([
                        'status' => 'active',
                        'start_date' => now()->toDateString(),
                        'end_date' => now()->addDays($plan->duration_days)->toDateString(),
                    ]);

                    Log::info('MEMBERSHIP ACTIVATED', [
                        'membership_id' => $membership->id,
                        'plan_id' => $plan->id,
                        'end_date' => $membership->end_date,
                    ]);
                }

                Log::info('PAID', [
                    'payment' => $payment->id,
                ]);
            });

            Log::info('PAYMENT TRANSACTION COMMITTED', [
                'payment_id' => $payment->id,
            ]);

        } catch (\Throwable $e) {
            Log::error('WEBHOOK DATABASE TRANSACTION FAILED', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }

        return response()->json(['received' => true]);
    }
}
