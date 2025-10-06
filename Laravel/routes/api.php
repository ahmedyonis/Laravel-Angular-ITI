<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShowController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\AdminShowController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Route::apiResource('bookings', BookingController::class);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    // Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'cancel']);
});

Route::get('/shows', [ShowController::class, 'index']);
Route::get('/shows/{show}', [ShowController::class, 'show']);


// Route::middleware(['auth:sanctum', EnsureUserIsAdmin::class])->group(function () {
//     Route::apiResource('admin/shows', AdminShowController::class);
// });

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('admin/shows', AdminShowController::class)->only(['index', 'store', 'destroy']);
    Route::get('/admin/bookings', [BookingController::class, 'adminIndex']);
    Route::delete('/admin/bookings/{booking}', [BookingController::class, 'adminDestroy']);
});

Route::get('/food', [FoodController::class, 'index']);


