<?php

use App\Http\Controllers\adminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Auth\PasswordResetViewController;
use App\Http\Controllers\UserController;

use Illuminate\View\View;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/login', function () {
//     return view('auth.login'); // or whatever your login view is
// })->name('login');

Route::get('/reset-password', [PasswordResetViewController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetViewController::class, 'handleReset'])->name('password.update');

Route::resource('users', UserController::class);



// Route::get('/admin' , function():view {
//     return view("admin.master");
// });


// Route::get('/admin', function () {
//     return view('admin.master');
// });
Route::get('/admin', [adminController::class , 'index']);

Route::get('/tables', [adminController::class , 'tables']);


Route::get('/static', [adminController::class , 'static']);
Route::get('/light', [adminController::class , 'light']);



Route::get('/master', [adminController::class , 'master']);


