<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\StudentDanceClassDetailController;
use App\Http\Controllers\Api\StudentDanceClassesController;
use App\Http\Controllers\Api\StudentDanceClassVideoUrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/sanctum/token', [MobileAuthController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sanctum/logout', [MobileAuthController::class, 'destroy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/classes', StudentDanceClassesController::class);
    Route::get('/classes/{danceClass}', StudentDanceClassDetailController::class);
    Route::get('/classes/{danceClass}/video-url', StudentDanceClassVideoUrlController::class);
});
