<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return  ;
});

// работа с продуктами
Route::prefix('products')->group(function () {
    //Route::post('preparation', [ App\Http\Controllers\Admin\ProductsController::class, "preparationExcel"]);

    Route::prefix('excel')->group(function () {
        // определение загружаемых товаров excel
        Route::post('preparation', [ App\Http\Controllers\Admin\ProductsController::class, "preparationExcel"]);
        //
        Route::post('save', function () {
            return  ;
        });
    });
});
