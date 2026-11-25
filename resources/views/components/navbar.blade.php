<nav class="bg-[#0E1B2C]  border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="text-xl font-bold text-white">
                CheapyFly
            </a>

            {{-- Links --}}
            <div class="flex items-center gap-6">

                @auth
                    <span class="text-sm text-white">
                        Olá, {{ Auth::user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="text-sm text-red-600 hover:text-red-800">
                            Sair
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-white hover:text-indigo-600">
                        Entrar
                    </a>

                    <a href="{{ route('register') }}"
                       class="text-sm rounded-lg py-2.5 px-2.5 border border-[#C9973E]/50 font-medium text-[#FBF8F2] hover:border-[#C9973E] hover:text-[#C9973E] transition-colors">
                        Criar conta
                    </a>
                @endauth

            </div>
        </div>
    </div>
</nav>
