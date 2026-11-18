<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/best-flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/history', [FlightHistoryController::class, 'index'])->name('flights.history');
Route::post('/flights/history', [FlightHistoryController::class, 'store'])->name('flights.history.store');