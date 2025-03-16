<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HoroscopeController;

Route::get('/', function () {
    return  ;
});



//генерируем гороскоп
// https://horoscope.staers.ru/api/horoscope

// Маршрут для обновления поля active через AJAX
Route::post('/horoscopes/{id}/toggle-active', [HoroscopeController::class, 'toggleActive'])->name('horoscopes.toggle-active');

Route::get('horoscope/create', [HoroscopeController::class, 'view'])->name('horoscopes.create');
Route::post('horoscope/store', [HoroscopeController::class, 'store'])->name('horoscopes.store');
Route::get('horoscope/store', [HoroscopeController::class, 'view'])->name('horoscopes.store');
// Маршрут для отображения формы редактирования
Route::get('/horoscopes/{id}/edit', [HoroscopeController::class, 'edit'])->name('horoscopes.edit');



// генерируем гороскоп
Route::get('horoscope/generation', [App\Http\Controllers\HoroscopeController::class, 'horoscope']);


Route::get('horoscope', [HoroscopeController::class, 'index'])->name('horoscopes.index');

// Маршрут для обновления записи
Route::put('/horoscopes/{id}', [HoroscopeController::class, 'update'])->name('horoscopes.update');


Route::get('horoscope/{id}', [HoroscopeController::class, 'show'])->name('horoscopes.show');
// Маршрут для удаления записи
Route::delete('/horoscopes/{id}', [HoroscopeController::class, 'destroy'])->name('horoscopes.destroy');
