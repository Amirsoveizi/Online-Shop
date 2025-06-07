<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('address', AddressController::class);
});
