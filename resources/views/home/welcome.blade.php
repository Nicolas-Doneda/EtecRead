@extends('layouts.app')

@section('title', 'EtecRead - Sistema de Biblioteca Digital')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-24 pb-32">
    <!-- Hero Section -->
    <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
        
        <!-- Left Side: Typography & CTA -->
        <div class="w-full lg:w-1/2 space-y-10">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-700 text-xs font-semibold tracking-wide uppercase rounded-full">
                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                    Biblioteca Digital Ativa
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 leading-[1.1]">
                    O conhecimento <br class="hidden md:block"/>
                    da <span class="text-red-600">ETEC</span> em <br class="hidden md:block"/>
                    suas mãos.
                </h1>
                
                <p class="text-lg md:text-xl text-gray-500 max-w-lg leading-relaxed pt-2">
                    Explore o catálogo completo da nossa biblioteca. Pesquise títulos, acompanhe seus prazos de devolução e faça reservas sem sair de casa.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <a href="{{ route('livros.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    Explorar Catálogo
                </a>
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                        Ir para o Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                        Acessar Conta
                    </a>
                @endauth
            </div>
        </div>

        <!-- Right Side: Visual Composition -->
        <div class="w-full lg:w-1/2 relative">
            <div class="absolute inset-0 bg-gray-100 rounded-3xl transform rotate-3 scale-105 z-0 transition-transform duration-500 hover:rotate-6"></div>
            <div class="relative z-10 bg-white p-8 md:p-12 rounded-3xl border border-gray-100 shadow-2xl flex flex-col gap-8">
                <!-- Abstract Book Display -->
                <div class="flex items-end gap-4 h-48 border-b border-gray-100 pb-8">
                    <div class="w-1/3 bg-gray-900 rounded-lg h-3/4 shadow-sm relative overflow-hidden group">
                        <div class="absolute inset-0 bg-white/10 transform -translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                    </div>
                    <div class="w-1/3 bg-red-600 rounded-lg h-full shadow-lg relative overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 transform -translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                    </div>
                    <div class="w-1/3 bg-gray-200 rounded-lg h-2/3 shadow-sm relative overflow-hidden group">
                        <div class="absolute inset-0 bg-white/50 transform -translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
                    </div>
                </div>
                
                <!-- Quick Search Simulation -->
                <div class="space-y-4">
                    <div class="h-4 w-1/4 bg-gray-200 rounded"></div>
                    <div class="flex gap-3">
                        <div class="h-10 flex-grow bg-gray-50 border border-gray-100 rounded-lg"></div>
                        <div class="h-10 w-24 bg-gray-900 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Features Section -->
<div class="border-t border-gray-100 bg-white">
    <div class="max-w-7xl mx-auto px-6 py-24">
        
        <div class="mb-16 max-w-2xl">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-4">
                Ferramentas essenciais para sua leitura.
            </h2>
            <p class="text-lg text-gray-500">
                Uma experiência fluida e silenciosa, desenhada para facilitar o seu dia a dia na escola.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Feature 1 -->
            <div class="group">
                <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center mb-6 group-hover:border-gray-300 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Pesquisa em Tempo Real</h3>
                <p class="text-gray-500 leading-relaxed">
                    Filtre instantaneamente por autor, categoria ou título. Encontre a exata edição que você procura sem precisar consultar o balcão.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="group">
                <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center mb-6 group-hover:border-gray-300 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Controle de Prazos</h3>
                <p class="text-gray-500 leading-relaxed">
                    Acompanhe os dias restantes do seu empréstimo. O sistema deixa claro quando você precisa devolver ou se pode renovar.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="group">
                <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center mb-6 group-hover:border-gray-300 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Fila de Reservas</h3>
                <p class="text-gray-500 leading-relaxed">
                    Alguém já pegou o livro? Entre na fila digital. Assim que for devolvido, você será notificado automaticamente.
                </p>
            </div>
        </div>
        
    </div>
</div>
@endsection