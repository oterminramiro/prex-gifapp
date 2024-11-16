<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GifController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'logger'])->group(function () {
    Route::prefix('gifs')->group(function () {
        Route::post('search', [GifController::class, 'search']);
        Route::post('find', [GifController::class, 'find']);
        Route::post('favorite', [GifController::class, 'favorite']);
    });
});

Route::post('login', [AuthController::class, 'login'])->name('login');