<!DOCTYPE html>
<html lang="pt-br">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Histórico de Consultas</title>

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link
      href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
      rel="stylesheet">

   <script src="https://cdn.tailwindcss.com"></script>
   <script>
      tailwind.config = {
         theme: {
            extend: {
               fontFamily: {
                  sans: ['Inter', 'sans-serif'],
                  mono: ['"IBM Plex Mono"', 'monospace'],
               },
            }
         }
      }
   </script>

   <style>
      .punch {
         position: absolute;
         width: 1.5rem;
         height: 1.5rem;
         border-radius: 9999px;
         background: #0E1B2C;
      }
   </style>
</head>

<body class="bg-[#0E1B2C] font-sans min-h-screen">

   <div class="max-w-3xl mx-auto px-4 py-12 md:py-20">

      <!-- Cabeçalho -->
      <header class="mb-10 md:mb-14">
         <div class="mb-6">
            <a href="{{ route('flights.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-[#C9973E] hover:text-[#DFB365] transition-colors">
               <span aria-hidden="true">&larr;</span>
               <span>Voltar para busca de voos</span>
            </a>
         </div>

         <h1 class="font-mono text-3xl md:text-5xl font-semibold text-[#FBF8F2] tracking-tight">
            Histórico de consultas
         </h1>
         <p class="mt-3 text-[#A9B4C4] max-w-lg">
            Consulte as pesquisas de voos registradas no banco de dados e refaça qualquer trajeto rapidamente.
         </p>
      </header>

      @if(session('status'))
         <div class="mb-8 bg-[#16273D] border border-[#C9973E]/40 text-[#FBF8F2] rounded-xl px-5 py-4 text-sm">
            {{ session('status') }}
         </div>
      @endif

      <!-- CARD: Filtro de histórico em forma de bilhete -->
      <div class="relative bg-[#FBF8F2] rounded-2xl shadow-2xl shadow-black/40 overflow-hidden">
         <form action="{{ route('flights.history') }}" method="GET">

            <!-- Origem / Destino -->
            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-6 p-8 md:p-10 items-end">
               <div class="flex flex-col">
                  <label for="departure_id" class="text-sm font-medium text-[#4B5568] mb-2">Filtrar por origem</label>
                  <input type="text" id="departure_id" name="departure_id" maxlength="3" placeholder="GRU"
                     value="{{ request('departure_id') }}"
                     class="uppercase font-mono text-xl font-semibold tracking-wider text-[#263041] bg-transparent border-b-2 border-[#D8D2C4] pb-2 focus:outline-none focus:border-[#C9973E] transition-colors">
               </div>

               <button type="button" id="swap-btn" aria-label="Trocar origem e destino"
                  class="hidden md:flex items-center justify-center w-10 h-10 mb-2 rounded-full border border-[#D8D2C4] text-[#8B93A1] hover:text-[#C9973E] hover:border-[#C9973E] transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <path d="M7 16V4M7 4L3 8M7 4l4 4" />
                     <path d="M17 8v12m0 0l4-4m-4 4l-4-4" />
                  </svg>
               </button>

               <div class="flex flex-col">
                  <label for="arrival_id" class="text-sm font-medium text-[#4B5568] mb-2">Filtrar por destino</label>
                  <input type="text" id="arrival_id" name="arrival_id" maxlength="3" placeholder="DCA"
                     value="{{ request('arrival_id') }}"
                     class="uppercase font-mono text-xl font-semibold tracking-wider text-[#263041] bg-transparent border-b-2 border-[#D8D2C4] pb-2 focus:outline-none focus:border-[#C9973E] transition-colors">
               </div>
            </div>

            <!-- Datas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 px-8 md:px-10 pb-8 md:pb-10">
               <div class="flex flex-col">
                  <label for="outbound_date" class="text-sm font-medium text-[#4B5568] mb-2">Data de ida</label>
                  <input type="date" id="outbound_date" name="outbound_date" value="{{ request('outbound_date') }}"
                     class="font-mono text-sm text-[#263041] bg-white border border-[#D8D2C4] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#C9973E]">
               </div>
               <div class="flex flex-col">
                  <label for="return_date" class="text-sm font-medium text-[#4B5568] mb-2">Data de volta</label>
                  <input type="date" id="return_date" name="return_date" value="{{ request('return_date') }}"
                     class="font-mono text-sm text-[#263041] bg-white border border-[#D8D2C4] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#C9973E]">
               </div>
            </div>

            <!-- Perfuração do bilhete -->
            <div class="relative border-t-2 border-dashed border-[#D8D2C4]">
               <span class="punch -left-3 -top-3"></span>
               <span class="punch -right-3 -top-3"></span>
            </div>

            <!-- Canhoto: ações do filtro -->
            <div class="flex flex-wrap items-center justify-between gap-4 px-8 md:px-10 py-6 bg-[#F3EEE3]">
               <a href="{{ route('flights.history') }}"
                  class="text-sm font-medium text-[#4B5568] hover:text-[#0E1B2C] transition-colors">
                  Limpar filtros
               </a>
               <button type="submit"
                  class="bg-[#0E1B2C] text-[#FBF8F2] font-medium px-8 py-3 rounded-lg hover:bg-[#1B2C42] focus:outline-none focus:ring-2 focus:ring-[#C9973E] focus:ring-offset-2 focus:ring-offset-[#F3EEE3] transition-colors">
                  Filtrar histórico
               </button>
            </div>
         </form>
      </div>

      <!-- LISTAGEM DO HISTÓRICO -->
      <section class="mt-14">
         @php
            $totalHistories = count($histories ?? []);
         @endphp

         <div class="flex items-baseline justify-between mb-6">
            <h2 class="font-mono text-xl font-semibold text-[#FBF8F2]">Consultas registradas</h2>
            @if($totalHistories > 0)
               <p class="text-sm font-mono text-[#8B93A1]">
                  {{ $totalHistories }} {{ $totalHistories === 1 ? 'consulta encontrada' : 'consultas encontradas' }}
               </p>
            @endif
         </div>

         @php
            $pickField = static function ($item, array $keys, $default = null) {
               foreach ($keys as $key) {
                  $value = data_get($item, $key);
                  if ($value !== null && $value !== '') {
                     return $value;
                  }
               }
               return $default;
            };

            $formatDate = static function ($value, string $format = 'd/m/Y'): ?string {
               if (empty($value)) {
                  return null;
               }
               try {
                  return \Carbon\Carbon::parse($value)->format($format);
               } catch (\Throwable $e) {
                  return (string) $value;
               }
            };
         @endphp

         <div class="flex flex-col gap-4">
            @forelse($histories as $history)
               @php
                  $origin = $pickField($history, ['origin', 'departure_id', 'origem']);
                  $destination = $pickField($history, ['destination', 'arrival_id', 'destino']);
                  $outboundRaw = $pickField($history, ['departure_date', 'outbound_date', 'data_ida']);
                  $returnRaw = $pickField($history, ['return_date', 'data_volta']);
                  $resultsCount = $history->flights->count();
                  $flightNumber = $pickField($history, ['flight_number', 'numero_voo']);
                  $flightName = $pickField($history, ['name', 'nome']);
                  $resultsCount = $pickField($history, ['results_count', 'quantidade_resultados']);
                  $searchedAtRaw = $pickField($history, ['created_at', 'data_consulta']);

                  $originDisplay = $origin ? strtoupper((string) $origin) : '---';
                  $destinationDisplay = $destination ? strtoupper((string) $destination) : '---';
                  $outboundFormatted = $formatDate($outboundRaw);
                  $returnFormatted = $formatDate($returnRaw);
                  $searchedAtFormatted = $formatDate($searchedAtRaw, 'd/m/Y H:i');

                  $recheckParams = array_filter([
                     'departure_id' => $origin ? strtoupper((string) $origin) : null,
                     'arrival_id' => $destination ? strtoupper((string) $destination) : null,
                     'outbound_date' => $outboundRaw ? substr((string) $outboundRaw, 0, 10) : null,
                     'return_date' => $returnRaw ? substr((string) $returnRaw, 0, 10) : null,
                  ], static fn ($value) => $value !== null && $value !== '');
               @endphp


                 <div class="relative flex flex-col bg-[#FBF8F2] rounded-2xl overflow-hidden shadow-lg shadow-black/30">

                     <!-- Informações da pesquisa -->
                     <div class="p-6 md:p-7">
                         <div class="flex items-center gap-3 font-mono text-lg font-semibold text-[#263041]">
                             <span>{{ $originDisplay }}</span>
                             <span class="text-[#C9973E]">&rarr;</span>
                             <span>{{ $destinationDisplay }}</span>
                         </div>

                         @if($flightName || $flightNumber)
                             <p class="text-sm text-[#4B5568] mt-1">
                                 {{ $flightName ?? 'Voo' }}
                                 @if($flightNumber)
                                     <span class="font-mono text-[#6B7686]">#{{ $flightNumber }}</span>
                                 @endif
                             </p>
                         @endif

                         @if($outboundFormatted || $returnFormatted)
                             <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-[#6B7686] mt-2">
                                 @if($outboundFormatted)
                                     <span>Ida: <strong class="font-mono font-medium text-[#263041]">{{ $outboundFormatted }}</strong></span>
                                 @endif
                                 @if($returnFormatted)
                                     <span>Volta: <strong class="font-mono font-medium text-[#263041]">{{ $returnFormatted }}</strong></span>
                                 @endif
                             </div>
                         @endif

                         <div class="mt-3 flex flex-wrap items-center gap-2">
                             @if($searchedAtFormatted)
                                 <span class="inline-block text-xs font-medium text-[#4B5568] bg-[#EFEAE0] rounded-full px-3 py-1">
                    Consultado em {{ $searchedAtFormatted }}
                </span>
                             @endif

                             @if($resultsCount !== null)
                                 <span class="inline-block text-xs font-medium text-[#4B5568] bg-[#EFEAE0] rounded-full px-3 py-1">
                    {{ $resultsCount }} {{ (int) $resultsCount === 1 ? 'resultado' : 'resultados' }}
                </span>
                             @endif
                         </div>
                     </div>

                     <!-- Separador horizontal -->
                     <div class="relative border-t-2 border-dashed border-[#D8D2C4]">
                         <span class="punch -left-3 -top-3"></span>
                         <span class="punch -right-3 -top-3"></span>
                     </div>

                     <!-- Listagem de voos -->
                     <div class="p-6 md:p-7 bg-[#F3EEE3]">

                         <h3 class="font-mono text-sm font-semibold text-[#263041] mb-4">
                             Voos encontrados
                         </h3>

                         <div class="flex flex-col gap-3">
                             @forelse($history->flights as $flight)

                                 @php
                                     $duration = $flight->duration_minutes;
                                     $hours = $duration !== null ? intdiv((int) $duration, 60) : 0;
                                     $minutes = $duration !== null ? (int) $duration % 60 : 0;

                                     $departureAt = $flight->departure_at
                                         ? \Carbon\Carbon::parse($flight->departure_at)
                                         : null;

                                     $arrivalAt = $flight->arrival_at
                                         ? \Carbon\Carbon::parse($flight->arrival_at)
                                         : null;

                                     $stops = $flight->stops;
                                 @endphp

                                 <div class="rounded-xl border border-[#D8D2C4] bg-white p-5">

                                     <!-- Aeroportos e horários -->
                                     <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-4 items-center">

                                         <!-- Partida -->
                                         <div>
                                             <p class="text-xs text-[#8B93A1] font-medium uppercase tracking-wider">
                                                 Partida
                                             </p>

                                             <p class="font-mono text-lg font-semibold text-[#263041] mt-1">
                                                 {{ $departureAt?->format('H:i') ?? '--:--' }}
                                             </p>

                                             <p class="text-sm text-[#4B5568] mt-1 break-words">
                                                 {{ $flight->departure_airport_name ?? 'Aeroporto não informado' }}
                                             </p>

                                             @if($departureAt)
                                                 <p class="text-xs text-[#8B93A1] mt-1">
                                                     {{ $departureAt->format('d/m/Y') }}
                                                 </p>
                                             @endif
                                         </div>

                                         <!-- Duração -->
                                         <div class="flex flex-col items-center justify-center">
                                             <p class="text-xs text-[#8B93A1] font-medium">
                                                 {{ $duration !== null ? "{$hours}h {$minutes}min" : '--' }}
                                             </p>

                                             <div class="flex items-center gap-2 w-full mt-2">
                                                 <div class="h-px bg-[#D8D2C4] flex-1"></div>
                                                 <span class="text-[#C9973E] text-lg">✈</span>
                                                 <div class="h-px bg-[#D8D2C4] flex-1"></div>
                                             </div>

                                             <p class="text-xs text-[#8B93A1] mt-2">
                                                 @if($stops === null)
                                                     Escalas não informadas
                                                 @elseif($stops == 0)
                                                     Voo direto
                                                 @else
                                                     {{ $stops }} {{ $stops == 1 ? 'escala' : 'escalas' }}
                                                 @endif
                                             </p>
                                         </div>

                                         <!-- Chegada -->
                                         <div class="md:text-right">
                                             <p class="text-xs text-[#8B93A1] font-medium uppercase tracking-wider">
                                                 Chegada
                                             </p>

                                             <p class="font-mono text-lg font-semibold text-[#263041] mt-1">
                                                 {{ $arrivalAt?->format('H:i') ?? '--:--' }}
                                             </p>

                                             <p class="text-sm text-[#4B5568] mt-1 break-words">
                                                 {{ $flight->arrival_airport_name ?? 'Aeroporto não informado' }}
                                             </p>

                                             @if($arrivalAt)
                                                 <p class="text-xs text-[#8B93A1] mt-1">
                                                     {{ $arrivalAt->format('d/m/Y') }}
                                                 </p>
                                             @endif
                                         </div>

                                     </div>

                                     <!-- Separador -->
                                     <div class="border-t border-dashed border-[#D8D2C4] my-4"></div>

                                     <!-- Preço e informações adicionais -->
                                     <div class="flex flex-wrap items-center justify-between gap-3">

                                         <div>
                                             <p class="text-xs text-[#8B93A1] font-medium uppercase tracking-wider">
                                                 Preço por pessoa
                                             </p>

                                             <p class="font-mono text-xl font-bold text-[#2F6F4E]">
                                                 {{ $flight->price !== null
                                                     ? 'R$ ' . number_format((float) $flight->price, 2, ',', '.')
                                                     : 'Não informado' }}
                                             </p>
                                         </div>

                                         <span class="text-xs text-[#6B7686] bg-[#EFEAE0] rounded-full px-3 py-1">
                        {{ $stops === 0 ? 'Sem conexões' : ($stops === 1 ? '1 conexão' : ($stops > 1 ? $stops . ' conexões' : 'Escalas desconhecidas')) }}
                    </span>

                                     </div>

                                 </div>

                             @empty
                                 <p class="text-sm text-[#8B93A1]">
                                     Nenhum voo registrado nesta consulta.
                                 </p>
                             @endforelse
                         </div>

                     </div>

                 </div>

             @empty
               <div class="bg-[#16273D] border border-[#233247] rounded-2xl p-10 text-center">
                  <p class="text-[#C7CEDA]">Nenhuma consulta encontrada no histórico.</p>
                  <p class="text-sm text-[#8B93A1] mt-1">
                     Realize uma busca de voos ou tente ajustar os filtros informados.
                  </p>
                  <a href="{{ route('flights.index') }}"
                     class="inline-block mt-6 bg-[#C9973E] text-[#0E1B2C] font-medium px-6 py-2.5 rounded-lg hover:bg-[#D9A74E] transition-colors">
                     Buscar voos
                  </a>
               </div>
            @endforelse
         </div>
          <div class="mt-4">
              {{ $histories->links() }}
          </div>
      </section>
   </div>

   <script>
      document.getElementById('swap-btn')?.addEventListener('click', function () {
         const departure = document.getElementById('departure_id');
         const arrival = document.getElementById('arrival_id');
         [departure.value, arrival.value] = [arrival.value, departure.value];
      });
   </script>

</body>

</html>
