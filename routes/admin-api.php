<?php

use Illuminate\Support\Facades\Route;
use Rabsana\Psp\Http\Controllers\Admin\GetDataController;
use Rabsana\Psp\Http\Controllers\Admin\InvoiceController;
use Rabsana\Psp\Http\Controllers\Admin\MerchantController;

Route::prefix("admin-api")->name('admin-api.')->group(function () {

    Route::prefix('v1')->name('v1.')->group(function () {

        // public routes
        Route::middleware(config('rabsana-psp.adminApiMiddlewares.public', []))->group(function () {
        });

        Route::middleware(config('rabsana-psp.adminApiMiddlewares.private', []))->group(function () {

            Route::prefix("get")->name('get.')->group(function () {
                Route::get("users", [GetDataController::class, 'users'])->name('users');
                Route::get("currencies", [GetDataController::class, 'currencies'])->name('currencies');
            });

            Route::middleware(config('rabsana-psp.adminApiMiddlewares.merchant', []))->group(function (){

                Route::get('merchants/documents',[MerchantController::class, 'document'])->name('merchants.document');

                Route::resource('merchants', MerchantController::class);

                Route::get('merchants/refresh/{merchant}', [MerchantController::class, 'refreshToken'])->name('refresh.token');

            });

            Route::middleware(config('rabsana-psp.adminApiMiddlewares.invoice', []))->group(function (){

                Route::get("invoices", [InvoiceController::class, 'index'])->name("invoices.index");
            });
            //
        });


        // 

    });
});
