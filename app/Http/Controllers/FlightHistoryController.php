<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FlightHistoryController extends Controller
{
    /**
     * Registra no banco de dados uma consulta de voos realizada pela API ou Web.
     *
     * Consumido pelo controller do Lucas (FlightControllerApi e FlightController).
     */
    public static function registrarConsulta(Request $request, array $bestFlights = []): void
    {
        $dadosConsulta = self::montarDadosConsulta($request, $bestFlights);
        self::persistirConsulta($dadosConsulta);
    }

    /**
     * Exibe a página Blade com o histórico de consultas registradas no banco.
     */
    public function index(Request $request): View
    {
        $histories = $this->listarHistoricoFiltrado($request);

        return view('flights.history', compact('histories'));
    }

    /**
     * Registra manualmente uma consulta via POST (Web ou API JSON).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $dadosConsulta = self::montarDadosConsulta($request);
        $salvo = self::persistirConsulta($dadosConsulta);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'saved' => $salvo,
                'message' => $salvo
                    ? 'Consulta registrada no histórico.'
                    : 'Fluxo preparado; aguardando integração da classe de inserção no BD.',
                'data' => $dadosConsulta,
            ], 201);
        }

        $status = $salvo
            ? 'Consulta registrada no histórico com sucesso.'
            : 'Fluxo de inserção preparado (aguardando classe de inserção no BD).';

        return redirect()
            ->route('flights.history')
            ->with('status', $status);
    }

    /**
     * Retorna em JSON a listagem do histórico de consultas registradas.
     */
    public function listApi(Request $request): JsonResponse
    {
        $histories = $this->listarHistoricoFiltrado($request);

        return response()->json($histories->values());
    }

    /**
     * Normaliza os dados da consulta realizada para envio à classe de inserção.
     */
    private static function montarDadosConsulta(Request $request, array $bestFlights = []): array
    {
        $lowestPrice = collect($bestFlights)
            ->pluck('price')
            ->filter(fn ($price) => is_numeric($price))
            ->min();

        $price = $request->filled('price') && is_numeric($request->input('price'))
            ? (float) $request->input('price')
            : ($lowestPrice !== null ? (float) $lowestPrice : null);

        $resultsCount = $request->filled('results_count') && is_numeric($request->input('results_count'))
            ? (int) $request->input('results_count')
            : count($bestFlights);

        return [
            'departure_id' => strtoupper(trim((string) $request->input('departure_id', 'GRU'))),
            'arrival_id' => strtoupper(trim((string) $request->input('arrival_id', 'DCA'))),
            'outbound_date' => (string) $request->input('outbound_date', now()->format('Y-m-d')),
            'return_date' => (string) $request->input('return_date', now()->format('Y-m-d')),
            'price' => $price,
            'results_count' => $resultsCount,
        ];
    }

    /**
     * Executa a inserção no banco de dados consumindo a classe de inserção do projeto.
     */
    private static function persistirConsulta(array $dadosConsulta): bool
    {
        try {
            // TODO: Conectar aqui a classe de inserção criada pelo outro integrante.
            // Os dados normalizados em $dadosConsulta contêm:
            // departure_id, arrival_id, outbound_date, return_date, price e results_count.
            // Exemplo:
            // return (bool) app(\App\Service\FlightHistoryInsertService::class)->inserir($dadosConsulta);

            return false;
        } catch (\Throwable $e) {
            Log::warning('Falha ao registrar consulta no histórico: ' . $e->getMessage(), [
                'consulta' => $dadosConsulta,
            ]);

            return false;
        }
    }

    /**
     * Busca o histórico no banco e aplica os filtros opcionais da requisição.
     */
    private function listarHistoricoFiltrado(Request $request): Collection
    {
        $histories = $this->buscarHistoricoDoBanco();

        $airportFilters = [
            'departure_id' => ['departure_id', 'origem'],
            'arrival_id' => ['arrival_id', 'destino'],
        ];

        $dateFilters = [
            'outbound_date' => ['outbound_date', 'data_ida'],
            'return_date' => ['return_date', 'data_volta'],
        ];

        return $histories
            ->filter(function ($item) use ($request, $airportFilters, $dateFilters): bool {
                foreach ($airportFilters as $param => $keys) {
                    $needle = trim((string) $request->input($param, ''));
                    if ($needle === '') {
                        continue;
                    }

                    $value = (string) ($this->extractFieldValue($item, $keys) ?? '');
                    if (!str_contains(strtoupper($value), strtoupper($needle))) {
                        return false;
                    }
                }

                foreach ($dateFilters as $param => $keys) {
                    $needle = trim((string) $request->input($param, ''));
                    if ($needle === '') {
                        continue;
                    }

                    $value = (string) ($this->extractFieldValue($item, $keys) ?? '');
                    if (!str_starts_with($value, $needle)) {
                        return false;
                    }
                }

                return true;
            })
            ->values();
    }

    /**
     * Consome a classe de listagem do banco de dados com fallback seguro.
     */
    private function buscarHistoricoDoBanco(): Collection
    {
        try {
            // TODO: Conectar aqui a classe de listagem criada pelo outro integrante.
            // Exemplo:
            // return collect(app(\App\Service\FlightHistoryListService::class)->listar());

            if (Schema::hasTable('flights')) {
                return Flight::query()->latest()->get();
            }

            return collect();
        } catch (\Throwable $e) {
            Log::warning('Falha ao listar histórico de consultas: ' . $e->getMessage());

            return collect();
        }
    }

    /**
     * Lê o primeiro campo não nulo encontrado entre as variações de nome (inglês/português).
     */
    private function extractFieldValue(mixed $item, array $keys): mixed
    {
        foreach ($keys as $key) {
            $value = data_get($item, $key);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
