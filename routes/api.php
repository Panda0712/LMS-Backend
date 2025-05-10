<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('modules', ModuleController::class);
Route::apiResource('carts', CartController::class);
Route::apiResource('contacts', ContactController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('blogs', BlogController::class);
Route::apiResource('vouchers', VoucherController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('password/reset', [AuthController::class, 'resetPassword']);
Route::post('media/upload', [MediaController::class, 'upload'])->middleware('auth:sanctum');
Route::post('payment/pay', [PaymentController::class, 'pay'])->middleware('auth:sanctum');
Route::post('payment/callback', [PaymentController::class, 'callback']);