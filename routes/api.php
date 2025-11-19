<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (tanpa autentikasi)
Route::post('/auth/login', [AuthApiController::class, 'login']);

// Protected routes (membutuhkan token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthApiController::class, 'getUser']);
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::post('/auth/logout-current', [AuthApiController::class, 'logoutCurrent']);
});
