<?php

namespace App\Http\Controllers;

use App\Service\FlightService;
use Cache;
use Http;
use Illuminate\Http\Request;


class FlightController extends Controller
{
    protected $flightService;
    protected $flightHistoryService;
    public function __construct(FlightService $flightService, FlightHistoryController $flightHistoryController){
        $this->flightService = $flightService;
        $this->flightHistoryService = $flightHistoryController;
    }
    //
    public function index(Request $request) {
        $bestFlights = $this->flightService->getBestFlights($request);
        // começar a implementar aqui.
        if ($request->hasAny(['departure_id', 'arrival_id', 'outbound_date', 'return_date'])) {
                $this->flightHistoryService->registrarConsulta($request, $bestFlights);
        }
        return view('flights.index', compact('bestFlights'));
    }
}
