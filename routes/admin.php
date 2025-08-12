<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\RegularBannerController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/refresh', [AuthController::class, 'refreshToken']);

Route::middleware(['auth:api', 'check.token'])->group(function () {
    Route::middleware('role:super_admin')->group(function () {
        Route::post('/create-admin', [AuthController::class, 'createAdmin']);
        Route::get('/users', [AuthController::class, 'index']);
        Route::delete('/delete-user/{user}', [AuthController::class, 'destroy']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::put('/update', [AuthController::class, 'update']);
    Route::get('/personal', [AuthController::class, 'personalInfo']);

    Route::apiResource('/about', AboutController::class)->names('admin.about');

    Route::post('/regular-banner', [RegularBannerController::class, 'store']);
    Route::get('/regular-banner/{page}', [RegularBannerController::class, 'show']);
    Route::put('/regular-banner/{page}', [RegularBannerController::class, 'update']);
    Route::delete('/regular-banner/{page}', [RegularBannerController::class, 'destroy']);

    Route::post('/upload-images', [FileUploadController::class, 'uploadImages'])->name('admin.upload-images');
    Route::post('/upload-file', [FileUploadController::class, 'uploadFile'])->name('admin.upload-file');
});
