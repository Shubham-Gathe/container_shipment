<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Auth\PasswordResetViewController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/login', function () {
//     return view('auth.login'); // or whatever your login view is
// })->name('login');

Route::get('/reset-password', [PasswordResetViewController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetViewController::class, 'handleReset'])->name('password.update');

Route::resource('users', UserController::class);