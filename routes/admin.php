<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\RegularBannerController;
use App\Http\Controllers\SeoMetaController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/refresh', [AuthController::class, 'refreshToken']);

Route::middleware(['auth:api', 'check.token'])->group(function () {
    Route::middleware('role:super_admin')->group(function () {
        Route::post('/create-user', [AuthController::class, 'createUser']);
        Route::get('/users', [AuthController::class, 'index']);
        Route::delete('/delete-user/{user}', [AuthController::class, 'destroy']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::put('/update', [AuthController::class, 'update']);
    Route::get('/personal', [AuthController::class, 'personalInfo']);

    Route::apiResource('/about', AboutController::class)->names('admin.about');
    Route::apiResource('/contact-us', ContactUsController::class)->names('admin.contact-us');
    Route::apiResource('/faq', FaqController::class)->names('admin.faq');

    Route::post('/regular-banner', [RegularBannerController::class, 'store']);
    Route::get('/regular-banner/{page}', [RegularBannerController::class, 'show']);
    Route::put('/regular-banner/{page}', [RegularBannerController::class, 'update']);
    Route::delete('/regular-banner/{page}', [RegularBannerController::class, 'destroy']);

    Route::get('/seo-meta', [SeoMetaController::class, 'index']);
    Route::post('/seo-meta', [SeoMetaController::class, 'store']);
    Route::get('/seo-meta/{page}', [SeoMetaController::class, 'show']);
    Route::put('/seo-meta/{page}', [SeoMetaController::class, 'update']);
    Route::delete('/seo-meta/{page}', [SeoMetaController::class, 'destroy']);

    Route::post('/upload-images', [FileUploadController::class, 'uploadImages'])->name('admin.upload-images');
    Route::post('/upload-file', [FileUploadController::class, 'uploadFile'])->name('admin.upload-file');
});
