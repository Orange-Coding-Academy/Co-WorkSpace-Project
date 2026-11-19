<?php

use App\Http\Controllers\StripeController;
use App\Models\Room;
use App\Models\MembershipPlan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;


// add this to make auth work 
Auth::routes();



// add for add for stripe

Route::get('/stripe/test', function () {
    return view('payment.test', [
        'rooms' => Room::all(),
        'plans' => MembershipPlan::all(),
    ]);
})->name('stripe.test');


Route::post('/stripe/checkout/booking', [StripeController::class, 'checkoutBooking'])->name('stripe.checkout.booking');
Route::post('/stripe/checkout/membership', [StripeController::class, 'checkoutMembership'])->name('stripe.checkout.membership');
Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::post('/stripe/webhook', [StripeController::class, 'webhook']);


Route::get('/', function () {
    return view('welcome');
});


// this for payment admin



Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/payments', [PaymentController::class, 'index'])
            ->name('payments.index');

        Route::get('/payments/{id}', [PaymentController::class, 'show'])
            ->whereNumber('id')
            ->name('payments.show');
    });



// email to send 

Route::get('/preview-email', function () {
    $booking = \App\Models\Booking::with(['user', 'room.workspace'])->latest()->first();

    return view('emails.booking-reminder', ['booking' => $booking]);
});

Route::get('/dashboard', function () {
    return view('User.dashboard')->name('dashboard');
});