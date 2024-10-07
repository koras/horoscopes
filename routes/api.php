<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return  ;
});

// работа с продуктами
Route::prefix('products')->group(function () {
    //Route::post('preparation', [ App\Http\Controllers\Admin\ProductsController::class, "preparationExcel"]);

    Route::prefix('excel')->group(function () {
        // определение загружаемых товаров excel
        Route::post('upload', [ App\Http\Controllers\Admin\ProductsController::class, "uploadExcel"]);
        Route::post('preparation', [ App\Http\Controllers\Admin\ProductsController::class, "preparationExcel"]);
        //
        Route::post('save', function () {
            return  ;
        });
    });
});



Route::post('/auth/register', [AuthController::class, 'register1Step']);
Route::post('/auth/register_confirm', [AuthController::class, 'register2Confirm']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
