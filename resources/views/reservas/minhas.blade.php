@extends('layouts.app')

@section('title', 'Minhas Reservas')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Minhas Reservas</h1>
        <p class="text-lg text-gray-500">Acompanhe seus livros reservados e seja notificado</p>
    </div>

    <!-- Stats Minimal -->
    <div class="grid grid-cols-3 gap-8 mb-12">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Pendentes</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $reservasPendentes }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Confirmadas</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $reservasConfirmadas }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Canceladas</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $reservasCanceladas }}</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-8 border-b border-gray-100">
        <nav class="flex gap-8">
            <button onclick="showTab('pendentes')" id="tab-pendentes" class="tab-button active text-sm font-bold text-gray-900 border-b-2 border-gray-900 pb-4 transition-all">
                Pendentes ({{ $reservasPendentes }})
            </button>
            <button onclick="showTab('historico')" id="tab-historico" class="tab-button text-sm font-semibold text-gray-400 hover:text-gray-600 border-b-2 border-transparent pb-4 transition-all">
                Histórico
            </button>
        </nav>
    </div>

    <!-- Tab Content: Pendentes -->
    <div id="content-pendentes" class="tab-content">
        @if($reservas->where('status', 'pendente')->count() > 0)
            <div class="space-y-6">
                @foreach($reservas->where('status', 'pendente') as $reserva)
                    <div class="flex flex-col sm:flex-row gap-6 p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                        <!-- Book Cover -->
                        <div class="w-24 h-36 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                            @if($reserva->book->cover_image)
                                <img src="{{ asset('storage/' . $reserva->book->cover_image) }}" 
                                     alt="{{ $reserva->book->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Book Info -->
                        <div class="flex-1 flex flex-col justify-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $reserva->book->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $reserva->book->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                            
                            <div class="text-sm text-gray-600 mb-4">
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Reservado em</span>
                                {{ \Carbon\Carbon::parse($reserva->reserved_at)->format('d/m/Y H:i') }}
                            </div>

                            <div class="flex flex-wrap items-center gap-3 mt-auto">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    Aguardando disponibilidade
                                </span>
                                @if($reserva->book->available_quantity > 0)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></div>
                                        Livro disponível!
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col justify-center gap-3 sm:w-48">
                            <a href="{{ route('livros.show', $reserva->book->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors">
                                Ver Livro
                            </a>
                            <form method="POST" action="{{ route('reservas.cancelar', $reserva->id) }}" onsubmit="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                    Cancelar Reserva
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="py-20 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Você não tem reservas pendentes</h3>
                <p class="text-gray-500 mb-6">Explore o catálogo e reserve seus livros favoritos.</p>
                <a href="{{ route('livros.index') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    Ir para o Catálogo
                </a>
            </div>
        @endif
    </div>

    <!-- Tab Content: Histórico -->
    <div id="content-historico" class="tab-content hidden">
        @if($todasReservas->count() > 0)
            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Livro</th>
                            <th class="px-6 py-4">Data Reserva</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($todasReservas as $reserva)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $reserva->book->title }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($reserva->reserved_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    @if($reserva->status === 'pendente')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded">Pendente</span>
                                    @elseif($reserva->status === 'confirmado')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold text-green-700 bg-green-50 rounded">Confirmada</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded">Cancelada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('livros.show', $reserva->book->id) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                                        Ver livro &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $todasReservas->links() }}
            </div>
        @else
            <div class="py-20 text-center">
                <p class="text-gray-500">Seu histórico de reservas está vazio.</p>
            </div>
        @endif
    </div>
</div>

<script>
    function showTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'font-bold', 'text-gray-900', 'border-gray-900');
            button.classList.add('font-semibold', 'text-gray-400', 'border-transparent');
        });
        
        // Show selected tab
        document.getElementById('content-' + tab).classList.remove('hidden');
        
        // Add active class to selected button
        const activeButton = document.getElementById('tab-' + tab);
        activeButton.classList.add('active', 'font-bold', 'text-gray-900', 'border-gray-900');
        activeButton.classList.remove('font-semibold', 'text-gray-400', 'border-transparent');
    }
</script>
@endsection