<?php

# Esse e um servico, sua unica funcao e se comunicar com a API para pegar os dados
# Ele foi criado com o intuito de reutilizacao de codigo tanto na area da API
# E tanto na area de vizualizacao dos dados na WEB

namespace App\Service;

use Cache;
use Http;
use Illuminate\Http\Request;

class FlightService
{
   public function getBestFlights(Request $request)
   {
      $departure = $request->input('departure_id', 'GRU');
      $arrival = $request->input('arrival_id', 'DCA');
      $outboundDate = $request->input('outbound_date', now()->format('Y-m-d'));
      $returnDate = $request->input('return_date', now()->format('Y-m-d'));
      $cacheKey = "flights:{$departure}:{$arrival}:{$outboundDate}:{$returnDate}";

      return Cache::remember($cacheKey, 40, function () use ($departure, $arrival, $outboundDate, $returnDate) {
         $response = Http::get(config('services.serpapi.base_url'), [
            'api_key' => config('services.serpapi.api_key'),
            'engine' => 'google_flights',
            'departure_id' => $departure,
            'arrival_id' => $arrival,
            'outbound_date' => $outboundDate,
            'return_date' => $returnDate,
            'currency' => 'BRL',
            'hl' => 'pt-br',
            'gl' => 'br',
         ]);

         if ($response->failed()) {
            return [];
         }

         return $response->json('best_flights', []);
      });
   }
}