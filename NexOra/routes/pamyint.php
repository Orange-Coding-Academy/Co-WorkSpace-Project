<?php

use App\Http\Controllers\Manager\ManagerReviewController;
use App\Http\Controllers\StripeController;
use App\Models\Room;
use App\Models\MembershipPlan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Manager\ManagerMembershipPlanController;


// add this to make auth work 



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



Auth::routes();

Route::get('/Manager/reviews', [ManagerReviewController::class, 'index'])
    ->name('reviews.index');

Route::get('/Manager/reviews/{id}', [ManagerReviewController::class, 'show'])
    ->whereNumber('id')
    ->name('reviews.show');










          
    Auth::routes();

Route::prefix('manager')
    ->name('manager.')
    ->group(function () {

        Route::get('/membership-plans',
            [ManagerMembershipPlanController::class, 'index'])
            ->name('membership-plans.index');

        Route::get('/membership-plans/create',
            [ManagerMembershipPlanController::class, 'create'])
            ->name('membership-plans.create');

        Route::post('/membership-plans',
            [ManagerMembershipPlanController::class, 'store'])
            ->name('membership-plans.store');

        Route::get('/membership-plans/{id}',
            [ManagerMembershipPlanController::class, 'show'])
            ->whereNumber('id')
            ->name('membership-plans.show');

        Route::get('/membership-plans/{id}/edit',
            [ManagerMembershipPlanController::class, 'edit'])
            ->whereNumber('id')
            ->name('membership-plans.edit');

        Route::put('/membership-plans/{id}',
            [ManagerMembershipPlanController::class, 'update'])
            ->whereNumber('id')
            ->name('membership-plans.update');

        Route::patch('/membership-plans/{id}/status',
            [ManagerMembershipPlanController::class, 'updateStatus'])
            ->whereNumber('id')
            ->name('membership-plans.status');

        Route::get('/membership-plans/{id}/subscribers',
            [ManagerMembershipPlanController::class, 'subscribers'])
            ->whereNumber('id')
            ->name('membership-plans.subscribers');
    });
