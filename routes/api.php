<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\IsAdmin;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\API\UserSubscriptionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // User subscriptions
    Route::post('/subscribe', [UserSubscriptionController::class, 'subscribe']);
    Route::get('/my-subscriptions', [UserSubscriptionController::class, 'mySubscriptions']);
});

Route::middleware(['auth:sanctum', IsAdmin::class])->group(function () {
    Route::apiResource('/subscription-plans', SubscriptionPlanController::class);
});
