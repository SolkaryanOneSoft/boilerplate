<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RegularBannerController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/refresh', [AuthController::class, 'refreshToken']);

Route::get('/regular-banner/{page}', [RegularBannerController::class, 'show']);
Route::get('/about', [AboutController::class, 'index']);
