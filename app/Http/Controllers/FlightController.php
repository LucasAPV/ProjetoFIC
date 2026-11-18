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

        if ($request->hasAny(['departure_id', 'arrival_id', 'outbound_date', 'return_date'])) {
            FlightHistoryController::registrarConsulta(
                $request,
                is_array($bestFlights) ? $bestFlights : []
            );
        }

        return view('flights.index', compact('bestFlights'));
    }
}
