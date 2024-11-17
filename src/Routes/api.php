<?php

use Illuminate\Support\Facades\Route;
use RezaK\OAuthGoogle\Http\Controllers\GoogleAuthController;

Route::prefix('api/auth/oauth')->group(function () {
    Route::get('google', [GoogleAuthController::class, 'redirectToGoogle']);
    Route::get('google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});
