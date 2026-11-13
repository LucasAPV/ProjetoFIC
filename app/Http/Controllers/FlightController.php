<?php

namespace App\Http\Controllers;

use App\Service\FlightService;
use Cache;
use Http;
use Illuminate\Http\Request;


class FlightController extends Controller
{
    //
    public function index(Request $request) {
        $service = app(FlightService::class);
        $bestFlights = $service->getBestFlights($request);

        return view('flights.index', compact('bestFlights'));
    }
}
