<!DOCTYPE html>
<html lang="pt-BR" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EtecRead - Biblioteca')</title>
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .dropdown-menu {
            visibility: hidden;
            opacity: 0;
            transform: translateY(4px);
            transition: opacity 0.15s cubic-bezier(0.16, 1, 0.3, 1), transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.15s;
        }
        .dropdown-menu.show {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-[#fcfcfc] text-gray-900 flex flex-col min-h-screen">

    @auth
        <nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('home') }}" 
                       class="flex items-center gap-3 group">
                        <div class="bg-red-600 text-white w-8 h-8 rounded flex items-center justify-center font-bold tracking-tight group-hover:bg-red-500 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-semibold tracking-tight text-white group-hover:text-gray-200 transition-colors duration-200">EtecRead</span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center gap-1">
                        @if(auth()->user()->role === 'aluno')
                            <a href="{{ route('home') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('home') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Home</a>
                            <a href="{{ route('dashboard') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Dashboard</a>
                            <a href="{{ route('livros.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('livros.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Livros</a>
                            <a href="{{ route('emprestimos.meus') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('emprestimos.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Empréstimos</a>
                            <a href="{{ route('reservas.minhas') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('reservas.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Reservas</a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Dashboard</a>
                            <a href="{{ route('admin.livros.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.livros.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Livros</a>
                            <a href="{{ route('admin.autores.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.autores.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Autores</a>
                            <a href="{{ route('admin.categorias.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.categorias.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Categorias</a>
                            <a href="{{ route('admin.usuarios.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.usuarios.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Usuários</a>
                            <a href="{{ route('admin.emprestimos.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.emprestimos.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Empréstimos</a>
                            <a href="{{ route('admin.reservas.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.reservas.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">Reservas</a>
                        @endif
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative ml-4">
                        <button onclick="toggleDropdown()" class="flex items-center gap-2.5 focus:outline-none group">
                            @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover ring-1 ring-gray-700 group-hover:ring-gray-500 transition-all duration-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center group-hover:border-gray-600 transition-colors duration-200">
                                    <span class="text-gray-300 font-medium text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-300 group-hover:text-white transition-colors duration-200 hidden sm:block">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        </button>

                        <div id="dropdownMenu" class="dropdown-menu absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-50">
                            <div class="px-4 py-3 bg-gray-50/50 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-1">
                                <a href="{{ route('perfil.show') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-gray-100 transition-colors duration-150">
                                    Meu Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-gray-100 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-3 py-2 text-sm text-red-600 rounded-md hover:bg-red-50 transition-colors duration-150">
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} <span class="font-medium text-gray-900">EtecRead</span>. Todos os direitos reservados.
            </p>
        </div>
    </footer>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('show');
        }

        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdownMenu');
            if (dropdown && !e.target.closest('button') && !e.target.closest('#dropdownMenu')) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>