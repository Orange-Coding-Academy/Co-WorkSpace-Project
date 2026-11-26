<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\Manager\ManagerAIController;
use App\Http\Controllers\Manager\ManagerReviewController;
use App\Http\Controllers\Member\WorkspaceAIController;
use App\Http\Controllers\PaymentController;
// use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\WorkspaceController;
use App\Models\MembershipPlan;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Manager\ManagerRoomController;
use App\Models\Room;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\UserChatbotController;
use App\Http\Controllers\Manager\ManagerMembershipPlanController;
use App\Http\Controllers\Manager\ManagerProfileController;
use App\Http\Controllers\Member\MyBookingController;
use App\Http\Controllers\Member\BookingCheckoutController;
use App\Http\Controllers\Guest\ExploreController;
use App\Http\Controllers\Guest\RoomDetailsController;
use App\Http\Controllers\Guest\RoomAvailabilityController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\Member\MemberProfileController;
use App\Http\Controllers\Member\MemberMembershipController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Member\MemberReviewController;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Public pages routes
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/rooms/{room}', [RoomDetailsController::class, 'show'])->name('rooms.details');
Route::get('/rooms/{room}/availability', [RoomAvailabilityController::class, 'index'])->name('rooms.availability');

Route::post('/rooms/{room}/book',[\App\Http\Controllers\Member\BookingController::class, 'store'])->name('rooms.book');

//Member pages routes
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () 
{
     Route::get('/workspace-ai', [WorkspaceAIController::class, 'index'])->name('workspace-ai.index');
     Route::post('/workspace-ai/recommend', [WorkspaceAIController::class, 'recommend'])->name('workspace-ai.recommend');
     Route::get('/chatbot', [UserChatbotController::class,'index'])->name('chatbot.index');
     Route::post('/chatbot/send', [UserChatbotController::class,'send'])->name('chatbot.send');
     Route::delete('/chatbot/clear', [UserChatbotController::class,'clear'])->name('chatbot.clear');
     Route::get('/my-bookings', [MyBookingController::class, 'index'])->name('bookings.index');
     Route::get('/my-bookings/{id}', [MyBookingController::class, 'show'])->name('bookings.show');
     Route::patch('/my-bookings/{id}/cancel', [MyBookingController::class, 'cancel'])->name('bookings.cancel');
     Route::get('/my-bookings/{id}/checkout', [BookingCheckoutController::class, 'index'])->name('bookings.checkout');
     Route::post('/stripe/checkout/booking', [StripeController::class, 'checkoutBooking'])->name('stripe.checkout.booking');
     Route::post('/stripe/checkout/membership', [StripeController::class, 'checkoutMembership'])->name('stripe.checkout.membership');
     Route::get('/profile', [MemberProfileController::class, 'index'])->name('profile.index');
     Route::put('/profile', [MemberProfileController::class, 'updatePersonal'])->name('profile.updatePersonal');
     Route::put('/profile/password', [MemberProfileController::class, 'updatePassword']) ->name('profile.updatePassword'); 
     Route::get('/memberships', [MemberMembershipController::class, 'index'])->name('memberships.index');
     Route::patch('/memberships/{id}/cancel',[MemberMembershipController::class, 'cancel'])->name('memberships.cancel');
     Route::get('/reviews',[MemberReviewController::class,'index'])->name('reviews.index');
     Route::get('/reviews/create', [MemberReviewController::class, 'createreview'])->name('reviews.create');
     Route::post('/reviews', [MemberReviewController::class, 'store'])->name('reviews.store');
     Route::get('/reviews/{id}/edit', [MemberReviewController::class, 'edit'])->name('reviews.edit');
     Route::put('/reviews/{id}', [MemberReviewController::class, 'update'])->name('reviews.update');
     Route::delete('/reviews/{id}', [MemberReviewController::class, 'destroy'])->name('reviews.destroy');  
       
});

// Space Manager Routes
Route::middleware(['auth', 'role:spaceManager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');;
    Route::get('/ai', [ManagerAIController::class, 'index'])->name('ai.index');
    Route::post('/ai/ask', [ManagerAIController::class, 'ask'])->name('ai.ask');
    Route::post('/ai/marketing', [ManagerAIController::class, 'generateMarketing'])->name('ai.marketing');
    Route::post('/ai/marketing/send', [ManagerAIController::class, 'sendMarketing'])->name('ai.marketing.send');
    Route::get('/workspaces/create', [WorkspaceController::class, 'create'])->name('workspaces.create');
    Route::post('/workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/workspaces/{workspaceId}/details',[ManagerRoomController::class, 'index'])->name('workspaces.details');
    Route::post('/workspaces/{workspaceId}/rooms',[ManagerRoomController::class, 'store'])->name('rooms.store');
    Route::put('/rooms/{roomId}',[ManagerRoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{roomId}',[ManagerRoomController::class, 'destroy'])->name('rooms.destroy');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancelManagerBooking'])->name('bookings.cancel');
    Route::get('/membership-plans', [ManagerMembershipPlanController::class, 'index'])->name('membership-plans.index');
    Route::get('/membership-plans/create',[ManagerMembershipPlanController::class, 'create'])->name('membership-plans.create');
    Route::post('/membership-plans', [ManagerMembershipPlanController::class, 'store'])->name('membership-plans.store');
    Route::get('/membership-plans/{id}',[ManagerMembershipPlanController::class, 'show'])->whereNumber('id')->name('membership-plans.show');
    Route::get('/membership-plans/{id}/edit',[ManagerMembershipPlanController::class, 'edit'])->whereNumber('id')->name('membership-plans.edit');
    Route::put('/membership-plans/{id}',[ManagerMembershipPlanController::class, 'update'])->whereNumber('id')->name('membership-plans.update');
    Route::patch('/membership-plans/{id}/status',[ManagerMembershipPlanController::class, 'updateStatus'])->whereNumber('id')->name('membership-plans.status');
    Route::get('/membership-plans/{id}/subscribers',[ManagerMembershipPlanController::class, 'subscribers']) ->whereNumber('id')->name('membership-plans.subscribers');
    Route::get('/profile', [ManagerProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ManagerProfileController::class, 'updatePersonal'])->name('profile.updatePersonal');
    Route::put('/profile/password', [ManagerProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::get('/reviews', [ManagerReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [ManagerReviewController::class, 'show'])->whereNumber('id')->name('reviews.show');
    });
// admin pages routes
Route::middleware(['auth', 'role:admin'])
->prefix('admin')->name('admin.')->group(function () {
   Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('AdminDashboard');
   Route::get('/userindex', [UserController::class, 'index'])->name('users.index');
   Route::put('deactivate/{id}', [UserController::class, 'deactivate'])->name('users.deactivate');
   Route::get('usershow/{id}', [UserController::class, 'show'])->name('users.show');
   Route::get('/workspaces', [WorkspaceController::class, 'index'])->name('workspaces');
   Route::patch('/workspaces/{workspace}/disable', [WorkspaceController::class, 'destroy'])->name('destroy');
   Route::patch('/workspaces/{workspace}/UpdateStatus', [WorkspaceController::class, 'UpdateStatus'])->name('UpdateStatus');
   Route::patch('/workspaces/{workspace}/enable', [WorkspaceController::class, 'enable'])->name('Enable');
   Route::get('/workspaces/{workspaceId}', [RoomController::class, 'index'])->name('workspace.details');
   Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
   Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
   Route::patch('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
   Route::patch('/reviews/{id}/hide', [AdminReviewController::class, 'hide'])->name('reviews.hide');
   Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
   Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
   Route::get('/payments/{id}', [PaymentController::class, 'show'])->whereNumber('id')->name('payments.show');
   Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile/personal', [AdminProfileController::class, 'updatePersonal'])->name('profile.personal');
    Route::patch('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password'); 
   });

Route::get('/preview-email', function () {
    $booking = \App\Models\Booking::with(['user', 'room.workspace'])->latest()->first();

    return view('emails.booking-reminder', ['booking' => $booking]);
});

Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::post('/stripe/webhook', [StripeController::class, 'webhook']);

