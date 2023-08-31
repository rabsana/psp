<?php

use Illuminate\Support\Facades\Route;
use Rabsana\Psp\Http\Controllers\Api\CurrencyController;
use Rabsana\Psp\Http\Controllers\Api\InvoiceController;

Route::prefix("api")->name('api.')->group(function () {

    Route::prefix('v1')->name('v1.')->group(function () {

        // public routes
        Route::middleware(config('rabsana-psp.apiMiddlewares.public', []))->group(function () {

            Route::middleware('checkMerchantToken')->group(function () {

                Route::get("currencies", [CurrencyController::class, 'index'])->name('currencies.index');
                Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
                Route::get('invoices/{token}', [InvoiceController::class, 'show'])->name('invoices.show');
                // 
            });
            // 
        });

        Route::middleware(config('rabsana-psp.apiMiddlewares.private', []))->group(function () {
        });


        // 

    });
});
