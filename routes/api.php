<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
//use App\Http\Controllers\AuthController;
use App\Http\Controllers\HoroscopeController;

Route::get('/', function () {
    return  ;
});
//
//// работа с продуктами
//Route::prefix('products')->group(function () {
//    //Route::post('preparation', [ App\Http\Controllers\Admin\ProductsController::class, "preparationExcel"]);
//
//    Route::prefix('excel')->group(function () {
//        // определение загружаемых товаров excel
//        Route::post('upload', [ App\Http\Controllers\Admin\ProductsController::class, "uploadExcel"]);
//        Route::post('preparation', [ App\Http\Controllers\Admin\ProductsController::class, "preparationExcel"]);
//        //
//        Route::post('save', function () {
//            return  ;
//        });
//    });
//});
//
//Route::post('/auth/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'stepOne']);
//Route::post('/auth/register_confirm', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'stepTwo']);
//Route::post('/auth/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
//Route::post('/auth/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->middleware('auth:sanctum');
//
//
//
//Route::prefix('moon')->group(function () {
//        // определение загружаемых товаров excel
//        Route::get('marker/blood', [ App\Http\Controllers\Moon\MarketController::class, "takeMarketBlood"]);
//
//});
//
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('compatibility', [App\Http\Controllers\HoroscopeController::class, 'compatibility']);
Route::get('horoscope', [App\Http\Controllers\HoroscopeController::class, 'horoscope']);

Route::get('horoscope/info', [App\Http\Controllers\HoroscopeController::class, 'info']);
