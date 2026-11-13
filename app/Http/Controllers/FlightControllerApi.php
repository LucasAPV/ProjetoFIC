<?php

namespace App\Http\Controllers;

use App\Service\FlightService;
use Cache;
use Http;
use Illuminate\Http\Request;

class FlightControllerApi extends Controller
{
        public function best_flights(Request $request)
    {
        $service = app(FlightService::class);
        $bestFlights = $service->getBestFlights($request);

        // Retorno do Json
        return $bestFlights;
    }
}
