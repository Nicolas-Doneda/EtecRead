@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Reservas</h1>
        <p class="text-lg text-gray-500">Acompanhe as solicitações de reserva feitas pelos alunos.</p>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="mb-8 bg-green-50 text-green-800 p-4 rounded-lg text-sm font-medium border border-green-100">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 bg-red-50 text-red-800 p-4 rounded-lg text-sm font-medium border border-red-100">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filtros e Busca -->
    <div class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-100">
        <form method="GET" action="{{ route('admin.reservas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <!-- Busca -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nome do aluno ou título do livro..."
                       class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    <option value="">Todos</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="confirmado" {{ request('status') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <!-- Botão Filtrar -->
            <div>
                <button type="submit" class="w-full px-6 py-2.5 bg-white text-gray-900 text-sm font-semibold border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                    Filtrar Resultados
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border border-gray-100 rounded-xl p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Pendentes</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stats['pendentes'] ?? 0 }}</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Confirmadas</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stats['confirmadas'] ?? 0 }}</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Canceladas</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stats['canceladas'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Tabela de Reservas -->
    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Obra</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Leitor</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Data do Pedido</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reservations as $reservation)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <!-- Livro -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-14 bg-gray-100 rounded flex-shrink-0 overflow-hidden">
                                        @if($reservation->book->cover_image)
                                            <img src="{{ asset('storage/' . $reservation->book->cover_image) }}" 
                                                 alt="{{ $reservation->book->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-gray-400 font-bold text-xs">{{ substr($reservation->book->title, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 max-w-[200px]">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $reservation->book->title }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $reservation->book->authors->first()->name ?? 'Sem autor' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Estoque: {{ $reservation->book->available_quantity }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Aluno -->
                            <td class="py-4 px-6">
                                <div class="min-w-0 max-w-[200px]">
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $reservation->user->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $reservation->user->email }}</p>
                                    @if($reservation->user->rm)
                                        <p class="text-xs text-gray-400 mt-0.5">RM: {{ $reservation->user->rm }}</p>
                                    @endif
                                </div>
                            </td>

                            <!-- Data Reserva -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="text-sm text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($reservation->reserved_at)->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($reservation->reserved_at)->diffForHumans() }}
                                </p>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($reservation->status === 'pendente')
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-yellow-700 bg-yellow-50 border border-yellow-100 rounded">
                                        Pendente
                                    </span>
                                @elseif($reservation->status === 'confirmado')
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-100 rounded">
                                        Confirmado
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-red-700 bg-red-50 border border-red-100 rounded">
                                        Cancelado
                                    </span>
                                @endif
                            </td>

                            <!-- Ações -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($reservation->status === 'pendente')
                                        <form method="POST" action="{{ route('admin.reservas.confirmar', $reservation->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-sm font-semibold text-gray-900 hover:text-gray-600 transition-colors">
                                                Confirmar
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($reservation->status !== 'cancelado')
                                        <form method="POST" action="{{ route('admin.reservas.cancelar', $reservation->id) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja cancelar esta solicitação?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-sm font-semibold text-red-400 hover:text-red-600 transition-colors ml-2">
                                                Cancelar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <p class="text-gray-500 font-medium">Nenhuma reserva localizada.</p>
                                <p class="text-gray-400 text-sm mt-1">As reservas aparecem aqui quando os alunos solicitam obras.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection