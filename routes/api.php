<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::prefix('v1')->group(function () {
    Route::apiResource('contacts', ContactController::class)->names('contacts');
});