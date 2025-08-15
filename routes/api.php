<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RegularBannerController;
use App\Http\Controllers\SeoMetaController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/refresh', [AuthController::class, 'refreshToken']);

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback']);

Route::middleware(['auth:api', 'check.token'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/personal', [AuthController::class, 'personalInfo']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
});

Route::get('/regular-banner/{page}', [RegularBannerController::class, 'show']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/seo-meta/{page}', [SeoMetaController::class, 'show']);
