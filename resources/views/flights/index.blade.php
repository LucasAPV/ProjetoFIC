<!DOCTYPE html>
<html lang="pt-br">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Busca de Voos</title>

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link
      href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
      rel="stylesheet">

   {{-- Se o app já usa @vite com Tailwind configurado, troque este script pelo seu bundle
   (@vite(['resources/css/app.css', 'resources/js/app.js'])) e remova o tailwind.config abaixo. --}}
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
      /* Furo de perfuração do "bilhete" — o círculo usa a cor do fundo da página
           para simular um recorte real na borda do card. */
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
      <header class="mb-10 md:mb-14 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
         <div>
            <h1 class="font-mono text-3xl md:text-5xl font-semibold text-[#FBF8F2] tracking-tight">
               Encontre seu próximo voo
            </h1>
            <p class="mt-3 text-[#A9B4C4] max-w-md">
               Compare preços e horários entre aeroportos em segundos.
            </p>
         </div>

         <a href="{{ route('flights.history') }}"
            class="inline-flex items-center gap-2 self-start md:self-auto whitespace-nowrap shrink-0 px-4 py-2.5 rounded-lg border border-[#C9973E]/50 text-sm font-medium text-[#FBF8F2] hover:border-[#C9973E] hover:text-[#C9973E] transition-colors">
            <span>Ver histórico de consultas</span>
            <span aria-hidden="true">&rarr;</span>
         </a>
      </header>

      <!-- CARD: Formulário em forma de bilhete -->
      <div class="relative bg-[#FBF8F2] rounded-2xl shadow-2xl shadow-black/40 overflow-hidden">
         <form action="{{ route('flights.index') }}" method="GET">

            <!-- Origem / Destino -->
            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-6 p-8 md:p-10 items-end">
               <div class="flex flex-col">
                  <label for="departure_id" class="text-sm font-medium text-[#4B5568] mb-2">Origem</label>
                  <input type="text" id="departure_id" name="departure_id" maxlength="3" placeholder="GRU"
                     value="{{ request('departure_id', 'GRU') }}" required
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
                  <label for="arrival_id" class="text-sm font-medium text-[#4B5568] mb-2">Destino</label>
                  <input type="text" id="arrival_id" name="arrival_id" maxlength="3" placeholder="DCA"
                     value="{{ request('arrival_id', 'DCA') }}" required
                     class="uppercase font-mono text-xl font-semibold tracking-wider text-[#263041] bg-transparent border-b-2 border-[#D8D2C4] pb-2 focus:outline-none focus:border-[#C9973E] transition-colors">
               </div>
            </div>

            <!-- Datas -->
            <div class="grid grid-cols-2 gap-6 px-8 md:px-10 pb-8 md:pb-10">
               <div class="flex flex-col">
                  <label for="outbound_date" class="text-sm font-medium text-[#4B5568] mb-2">Ida</label>
                  <input type="date" id="outbound_date" name="outbound_date"
                     value="{{ request('outbound_date', now()->format('Y-m-d')) }}" required
                     class="font-mono text-sm text-[#263041] bg-white border border-[#D8D2C4] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#C9973E]">
               </div>
               <div class="flex flex-col">
                  <label for="return_date" class="text-sm font-medium text-[#4B5568] mb-2">Volta</label>
                  <input type="date" id="return_date" name="return_date"
                     value="{{ request('return_date', now()->format('Y-m-d')) }}" required
                     class="font-mono text-sm text-[#263041] bg-white border border-[#D8D2C4] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#C9973E]">
               </div>
            </div>

            <!-- Perfuração do bilhete -->
            <div class="relative border-t-2 border-dashed border-[#D8D2C4]">
               <span class="punch -left-3 -top-3"></span>
               <span class="punch -right-3 -top-3"></span>
            </div>

            <!-- Canhoto: botão de busca -->
            <div class="flex items-center justify-end px-8 md:px-10 py-6 bg-[#F3EEE3]">
               <button type="submit"
                  class="bg-[#0E1B2C] text-[#FBF8F2] font-medium px-8 py-3 rounded-lg hover:bg-[#1B2C42] focus:outline-none focus:ring-2 focus:ring-[#C9973E] focus:ring-offset-2 focus:ring-offset-[#F3EEE3] transition-colors">
                  Buscar voos
               </button>
            </div>
         </form>
      </div>

      <!-- RESULTADOS -->
      <section class="mt-14">
         <div class="flex items-baseline justify-between mb-6">
            <h2 class="font-mono text-xl font-semibold text-[#FBF8F2]">Resultados</h2>
            @if(!empty($bestFlights))
               <p class="text-sm font-mono text-[#8B93A1]">
                  {{ count($bestFlights) }} {{ count($bestFlights) === 1 ? 'voo encontrado' : 'voos encontrados' }}
               </p>
            @endif
         </div>

         @if(empty($bestFlights))
            <div class="bg-[#16273D] border border-[#233247] rounded-2xl p-10 text-center">
               <p class="text-[#C7CEDA]">Nenhum voo encontrado para esse trajeto.</p>
               <p class="text-sm text-[#8B93A1] mt-1">Tente outro aeroporto ou outra data de partida.</p>
            </div>
         @else
            <div class="flex flex-col gap-4">
               @foreach($bestFlights as $flight)
                  @php
                     $mins = $flight['total_duration'] ?? null;
                     $duration = $mins ? floor($mins / 60) . 'h ' . ($mins % 60) . 'min' : 'N/A';
                     $legs = $flight['flights'] ?? [];
                     $stops = count($legs) > 0 ? count($legs) - 1 : null;
                  @endphp
                  <div
                     class="relative flex flex-col md:flex-row bg-[#FBF8F2] rounded-2xl overflow-hidden shadow-lg shadow-black/30">

                     <div class="flex-1 p-6 md:p-7">
                        <div class="flex items-center gap-3 font-mono text-lg font-semibold text-[#263041]">
                           <span>{{ strtoupper(request('departure_id', 'GRU')) }}</span>
                           <span class="text-[#C9973E]">&rarr;</span>
                           <span>{{ strtoupper(request('arrival_id', 'DCA')) }}</span>
                        </div>
                        <p class="text-sm text-[#6B7686] mt-2">Duração: {{ $duration }}</p>

                        @if(!is_null($stops))
                           <span
                              class="inline-block mt-3 text-xs font-medium text-[#4B5568] bg-[#EFEAE0] rounded-full px-3 py-1">
                              {{ $stops === 0 ? 'Voo direto' : $stops . ' parada' . ($stops > 1 ? 's' : '') }}
                           </span>
                        @endif
                     </div>

                     <!-- Perfuração vertical -->
                     <div class="hidden md:block relative w-px border-l-2 border-dashed border-[#D8D2C4]">
                        <span class="punch -top-3 -left-3"></span>
                        <span class="punch -bottom-3 -left-3"></span>
                     </div>

                     <div class="flex items-center justify-center md:w-40 bg-[#F3EEE3] p-6">
                        <p class="font-mono text-2xl font-semibold text-[#2F6F4E]">
                           R$ {{ number_format($flight['price'] ?? 0, 2, ',', '.') }}
                        </p>
                     </div>
                  </div>
               @endforeach
            </div>
         @endif
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