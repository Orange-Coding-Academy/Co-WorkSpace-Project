<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\Manager\ManagerAIController;
use App\Http\Controllers\Manager\ManagerReviewController;
use App\Http\Controllers\Member\WorkspaceAIController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\WorkspaceController;
use App\Models\MembershipPlan;
use App\Http\Controllers\BookingController;
use App\Models\Room;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\Member\UserChatbotController;






Route::get('/', function () {
    return view('welcome');
});

Route::prefix('manager')->name('manager.')->group(function () {

    Route::get('/ai', [ManagerAIController::class, 'index'])
        ->name('ai.index');

    Route::post('/ai/ask', [ManagerAIController::class, 'ask'])
        ->name('ai.ask');


Route::post('/ai/marketing', [ManagerAIController::class, 'generateMarketing'])
    ->name('ai.marketing');
    Route::post('/ai/marketing/send', [ManagerAIController::class, 'sendMarketing'])
    ->name('ai.marketing.send');
    Route::get('/Manager/reviews', [ManagerReviewController::class, 'index'])
    ->name('reviews.index');


    
Route::get('/reviews', [ManagerReviewController::class, 'index'])
    ->name('reviews.index');
    Route::get('/reviews/{id}', [ManagerReviewController::class, 'show'])
    ->whereNumber('id')
    ->name('reviews.show');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancelManagerBooking'])->name('bookings.cancel');


    });
//   Route::post('/reviews', [ReviewController::class, 'store'])
//     ->name('reviews.store');
Route::prefix('admin')->name('admin.')->group(function () {
Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('AdminDashboard');
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

             Route::get('/workspaces', [WorkspaceController::class, 'index'])
    ->name('workspaces');

Route::patch('/workspaces/{workspace}/disable', [WorkspaceController::class, 'destroy'])
    ->name('destroy');

Route::patch('/workspaces/{workspace}/UpdateStatus', [WorkspaceController::class, 'UpdateStatus'])
    ->name('UpdateStatus');

Route::patch('/workspaces/{workspace}/enable', [WorkspaceController::class, 'enable'])
    ->name('Enable');

Route::get('/workspaces/{workspaceId}', [RoomController::class, 'index'])
    ->name('workspace.details');

Route::get('/userindex', [UserController::class, 'index'])->name('users.index');

Route::put('deactivate/{id}', [UserController::class, 'deactivate'])->name('users.deactivate');

Route::get('usershow/{id}', [UserController::class, 'show'])->name('users.show');
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


    Route::get('/workspace-ai', [WorkspaceAIController::class, 'index'])
        ->name('workspace-ai.index');

    Route::post('/workspace-ai/recommend', [WorkspaceAIController::class, 'recommend'])
        ->name('workspace-ai.recommend');

         Route::get('/chatbot', [
            UserChatbotController::class,
            'index'
        ])->name('chatbot.index');

        Route::post('/chatbot/send', [
            UserChatbotController::class,
            'send'
        ])->name('chatbot.send');

        Route::delete('/chatbot/clear', [
            UserChatbotController::class,
            'clear'
        ])->name('chatbot.clear');


