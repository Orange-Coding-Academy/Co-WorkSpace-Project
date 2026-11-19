<?php

use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerAIController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StripeController;
use App\Models\Room;
use App\Models\MembershipPlan;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminBookingController;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('AdminDashboard');
Route::prefix('manager')->group(function () {

    Route::get('/ai', [ManagerAIController::class, 'index'])
        ->name('manager.ai.index');

    Route::post('/ai/ask', [ManagerAIController::class, 'ask'])
        ->name('manager.ai.ask');


Route::post('/ai/marketing', [ManagerAIController::class, 'generateMarketing'])
    ->name('manager.ai.marketing');
    Route::post('/ai/marketing/send', [ManagerAIController::class, 'sendMarketing'])
    ->name('manager.ai.marketing.send');
    });
  Route::post('/reviews', [ReviewController::class, 'store'])
    ->name('reviews.store');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/reviews', [AdminReviewController::class, 'index'])
        ->name('reviews.index');

    Route::patch('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])
        ->name('reviews.approve');

    Route::patch('/reviews/{id}/hide', [AdminReviewController::class, 'hide'])
        ->name('reviews.hide');

    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])
        ->name('reviews.destroy');
        Route::get('/payments', [PaymentController::class, 'index'])
            ->name('payments.index');

        Route::get('/payments/{id}', [PaymentController::class, 'show'])
            ->whereNumber('id')
            ->name('payments.show');

Route::get('userindex', [UserController::class, 'index'])->name('admin.users.index');

Route::put('deactivate/{id}', [UserController::class, 'deactivate'])->name('admin.users.deactivate');

Route::get('usershow/{id}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('/bookings', [AdminBookingController::class, 'index'])
    ->name('bookings.index');
});
Route::get('/preview-email', function () {
    $booking = \App\Models\Booking::with(['user', 'room.workspace'])->latest()->first();

    return view('emails.booking-reminder', ['booking' => $booking]);
});

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





