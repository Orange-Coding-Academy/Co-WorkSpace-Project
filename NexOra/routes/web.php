<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerAIController;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('manager')->group(function () {

    Route::get('/ai', [ManagerAIController::class, 'index'])
        ->name('manager.ai.index');

    Route::post('/ai/ask', [ManagerAIController::class, 'ask'])
        ->name('manager.ai.ask');

});
Route::post('/ai/marketing', [ManagerAIController::class, 'generateMarketing'])
    ->name('manager.ai.marketing');
    Route::post('/ai/marketing/send', [ManagerAIController::class, 'sendMarketing'])
    ->name('manager.ai.marketing.send');