<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

// Protect routes with sanctum middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);

Route::middleware('auth:sanctum','role:admin','expire.sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
   
});

Route::middleware('auth:sanctum','role:admin','expire.sanctum')->group(function () {
    Route::post('/addRestaurants', [RestaurantController::class,'store']);
    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);
    Route::post('/assign-manager', [RestaurantController::class, 'assignRestaurantToManager']);
}); 

Route::middleware('auth:sanctum','role:admin,manager','expire.sanctum')->group(function () {
    Route::apiResource('containers', ContainerController::class);
});

Route::middleware('auth:sanctum','role:admin,manager,driver','expire.sanctum')->group(function () {
    Route::get('/order', [OrderController::class, 'index']);
    Route::get('/order/{container}', [OrderController::class, 'show']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::put('/order/{container}', [OrderController::class, 'update']);
    Route::delete('/order/{container}', [OrderController::class, 'destroy']);
    Route::post('/order/{orderId}/assign-driver', [OrderController::class, 'assignDriver']);
});

Route::middleware('auth:sanctum','role:driver','expire.sanctum')->group(function(){
    Route::post('/order/{order}/respond', [OrderController::class, 'respondToOrder']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateOrderStatus']);
});