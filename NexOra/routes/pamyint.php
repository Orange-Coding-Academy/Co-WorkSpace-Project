<?php

use App\Http\Controllers\StripeController;
use App\Models\Room;
use App\Models\MembershipPlan;
use Illuminate\Support\Facades\Route;

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