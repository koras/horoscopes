<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HoroscopeController;

Route::get('/', function () {
    return  ;
});



Route::get('compatibility', [App\Http\Controllers\HoroscopeController::class, 'compatibility']);

// генерируем гороскоп
Route::get('horoscope', [App\Http\Controllers\HoroscopeController::class, 'horoscope']);

Route::get('horoscope/info', [App\Http\Controllers\HoroscopeController::class, 'info']);
