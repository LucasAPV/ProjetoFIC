<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\FlightSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FlightHistoryController extends Controller
{
    function registrarConsulta(Request $request, $bestFlights) {

        return DB::transaction(function () use ($request, $bestFlights) {

            $flightSearch =  $request->user()->flightSearches()->create([
                'origin' => $request->input('departure_id'),
                'destination' => $request->input('arrival_id'),
                'departure_date' => $request->input('outbound_date'),
                'return_date' => $request->input('return_date'),
            ]);

            $this->registrarVoos($flightSearch, $bestFlights);

            return $flightSearch;
        });
    }
    private function registrarVoos(FlightSearch $flightSearch, $bestFlights)
    {
        foreach ($bestFlights as $flight) {
            $legs = $flight['flights'] ?? [];
            $firstLeg = $legs[0] ?? [];
            $lastLeg = !empty($legs) ? end($legs) : [];
            $flightSearch->flights()->create([
                'departure_airport_name' => data_get($firstLeg, 'departure_airport.name'),
                'arrival_airport_name' => data_get($lastLeg, 'arrival_airport.name'),
                'departure_at' => data_get($firstLeg, 'departure_airport.time'),
                'arrival_at' => data_get($lastLeg, 'arrival_airport.time'),
                'duration_minutes' => data_get($flight, 'total_duration'),
                'stops' => count($legs) > 0 ? count($legs) - 1 : null,
                'price' => data_get($flight, 'price'),
            ]);
        }
    }
    public function index(Request $request)
    {
        $histories = $this->getHistory($request);
        return view('flights.history', compact('histories'));
    }

    private function getHistory(Request $request)
    {
        $query = $request->user()->flightSearches()->with('flights');
        $this->applyFilters($query, $request);
        return $query->latest('created_at')->paginate(10)->withQueryString();
    }

    private function applyFilters($query, Request $request)
    {
        $query->when($request->filled('departure_id'), function ($query) use ($request) {
            $query->where('origin', strtoupper($request->input('departure_id')));
        })->when($request->filled('arrival_id'), function ($query) use ($request) {
            $query->where('destination', strtoupper($request->input('arrival_id')));
        })->when($request->filled('outbound_date'), function ($query) use ($request) {
            $query->whereDate('departure_date', $request->input('outbound_date'));
        })->when($request->filled('return_date'), function ($query) use ($request) {
            $query->whereDate('return_date', $request->input('return_date'));
        });
    }

}
