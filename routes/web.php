<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightHistoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('flights.index');
});

Route::get('/best-flights', [FlightController::class, 'index'])->name('flights.index');

Route::middleware('auth')->group(function () {
    Route::get('/flights/history', [FlightHistoryController::class, 'index'])->name('flights.history');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
