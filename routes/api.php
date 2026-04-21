<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\StudentDanceClassDetailController;
use App\Http\Controllers\Api\StudentDanceClassesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/sanctum/token', [MobileAuthController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/classes', StudentDanceClassesController::class);
    Route::get('/classes/{danceClass}', StudentDanceClassDetailController::class);
});
