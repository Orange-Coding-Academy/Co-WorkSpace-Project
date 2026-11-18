<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('userindex', [UserController::class, 'index'])->name('admin.users.index');

Route::put('deactivate/{id}', [UserController::class, 'deactivate'])->name('admin.users.deactivate');