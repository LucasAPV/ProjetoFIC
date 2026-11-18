<?php
use App\Http\Controllers\FlightControllerApi;
use App\Http\Controllers\FlightHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/flights', [FlightControllerApi::class, 'best_flights']);
Route::get('/flights/history', [FlightHistoryController::class, 'listApi'])->name('api.flights.history');
Route::post('/flights/history', [FlightHistoryController::class, 'store'])->name('api.flights.history.store');