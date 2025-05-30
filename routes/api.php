<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ForgotPasswordController;

Route::post('/login', [AuthController::class, 'login']);

// Protect routes with sanctum middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);