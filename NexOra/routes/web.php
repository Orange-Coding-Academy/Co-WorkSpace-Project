<?php

use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerAIController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\ReviewController;

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
});


