<?php

use App\Http\Controllers\AdminDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('AdminDashboard');