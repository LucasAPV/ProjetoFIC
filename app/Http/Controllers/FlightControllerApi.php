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

        try {
            // Integração da inserção no BD no controller de requisição da API (Lucas).
            // Centralizado em FlightHistoryController::registrarConsulta() com os parâmetros:
            // departure_id, arrival_id, outbound_date e return_date (além dos resultados).
            // Caso prefira chamar diretamente a classe de inserção do colega, substitua esta chamada.
            FlightHistoryController::registrarConsulta(
                $request,
                is_array($bestFlights) ? $bestFlights : []
            );
        } catch (\Throwable $e) {
            // Mantém o retorno da API funcional enquanto a migration/classe de inserção não estiver ativa.
        }

        // Retorno do Json
        return $bestFlights;
    }
}
