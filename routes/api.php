<?php
use App\Http\Controllers\FlightControllerApi;
use Illuminate\Support\Facades\Route;

Route::get('/flights', [FlightControllerApi::class, 'best_flights']);
