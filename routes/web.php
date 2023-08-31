<?php

use Illuminate\Support\Facades\Route;
use Rabsana\Psp\Http\Controllers\Web\InvoiceController;

Route::prefix('/')->name('web.')->group(function () {

    // public routes
    Route::middleware(config('rabsana-psp.webMiddlewares.public', []))->group(function () {

        Route::get('invoices/auth/{token}', [InvoiceController::class, 'auth'])->name('invoices.auth');
        Route::post('invoices/pay/{token}', [InvoiceController::class, 'pay'])->name('invoices.pay');
        // 
    });

    Route::middleware(config('rabsana-psp.webMiddlewares.private', []))->group(function () {
    });
});
