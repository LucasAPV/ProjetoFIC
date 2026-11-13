<?php

use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/best-flights', [FlightController::class, 'index'])->name('flights.index');