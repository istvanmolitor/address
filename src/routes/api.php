<?php

use Illuminate\Support\Facades\Route;
use Molitor\Address\Http\Controllers\Api\CountryApiController;

Route::prefix('admin/address')
    ->middleware(['api', 'auth:sanctum'])
    ->name('address.')
    ->group(function (): void {
        Route::resource('countries', CountryApiController::class);
    });

