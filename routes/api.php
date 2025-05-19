<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('modules', controller: ModuleController::class);
Route::apiResource('courses', controller: CourseController::class);
Route::apiResource('carts', CartController::class);
Route::apiResource('contacts', ContactController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('blogs', BlogController::class);
Route::apiResource('vouchers', VoucherController::class);
Route::apiResource('users', UserController::class);
Route::get('vouchers/find-by-name', [VoucherController::class, 'findByCode']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function() {
    return response()->json(['message' => 'API is working']);
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    // Các route cần auth khác
});
Route::post('password/reset', [AuthController::class, 'resetPassword']);
// Route::post('media/upload', [MediaController::class, 'upload'])->middleware('auth:sanctum');
// Route::post('payment/pay', [PaymentController::class, 'pay'])->middleware('auth:sanctum');
// Route::post('payment/callback', action: [PaymentController::class, 'callback']);
Route::get('carts/find-by-user-and-course', [CartController::class, 'findByUserAndCourse']);

// Progress routes
Route::get('progress/{courseId}', [ProgressController::class, 'getProgress']);
Route::get('progress', [ProgressController::class, 'getAllProgress']);
Route::post('progress/update-lesson', [ProgressController::class, 'updateLessonProgress']);
Route::post('progress/init', [ProgressController::class, 'initProgress']);