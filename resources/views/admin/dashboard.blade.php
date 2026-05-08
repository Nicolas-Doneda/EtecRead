@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 mb-2">Visão Geral</h1>
            <p class="text-lg text-gray-500">Gestão do acervo e atividades da EtecRead.</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.livros.create') }}" class="px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                + Novo Livro
            </a>
            <a href="{{ route('admin.emprestimos.create') }}" class="px-5 py-2.5 bg-gray-100 text-gray-900 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                + Novo Empréstimo
            </a>
        </div>
    </div>

    <!-- Minimal Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Acervo Total</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $stats['total_livros'] }}</p>
            <p class="text-sm font-medium text-green-600 mt-1">{{ $stats['livros_disponiveis'] }} disponíveis</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Membros</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $stats['total_usuarios'] }}</p>
            <p class="text-sm font-medium text-gray-500 mt-1">{{ $stats['total_alunos'] }} alunos</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Empréstimos</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $stats['emprestimos_ativos'] }}</p>
            <p class="text-sm font-medium text-gray-500 mt-1">{{ $stats['total_emprestimos'] }} no histórico</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Reservas Pendentes</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $stats['reservas_pendentes'] }}</p>
        </div>
    </div>

    <!-- Alertas -->
    @if($stats['emprestimos_atrasados'] > 0)
    <div class="mb-16 border-l-2 border-red-500 pl-6 py-2">
        <h3 class="text-xl font-bold text-gray-900 mb-1">Atenção Necessária</h3>
        <p class="text-red-600 font-medium mb-3">Existem {{ $stats['emprestimos_atrasados'] }} empréstimo(s) em atraso.</p>
        <a href="{{ route('admin.emprestimos.index') }}" class="text-sm font-bold text-red-600 hover:text-red-800 transition-colors">
            Revisar Atrasos &rarr;
        </a>
    </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-16">
        
        <!-- Empréstimos Recentes -->
        <div>
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Empréstimos Recentes</h2>
                <a href="{{ route('admin.emprestimos.index') }}" class="text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors">
                    Ver todos
                </a>
            </div>

            @if($emprestimosRecentes->count() > 0)
                <div class="space-y-6">
                    @foreach($emprestimosRecentes as $emprestimo)
                        <div class="flex gap-4 items-center group">
                            <!-- User/Book representation -->
                            <div class="w-12 h-16 bg-gray-100 rounded flex-shrink-0 overflow-hidden">
                                @if($emprestimo->book->cover_image)
                                    <img src="{{ asset('storage/' . $emprestimo->book->cover_image) }}" 
                                         alt="{{ $emprestimo->book->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-gray-400 text-xs font-bold">{{ substr($emprestimo->book->title, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.livros.show', $emprestimo->book->id) }}" class="font-bold text-gray-900 group-hover:text-red-600 transition-colors truncate block">
                                    {{ $emprestimo->book->title }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $emprestimo->user->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}
                                </p>
                            </div>
                            
                            <div class="flex-shrink-0 text-right">
                                @if($emprestimo->status === 'ativo')
                                    <span class="inline-flex px-2 py-1 text-xs font-bold text-green-700 bg-green-50 rounded">Ativo</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-bold text-gray-600 bg-gray-100 rounded">{{ ucfirst($emprestimo->status) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 py-4">Nenhum empréstimo recente.</p>
            @endif
        </div>

        <!-- Reservas Pendentes -->
        <div>
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Aguardando Retirada</h2>
                <a href="{{ route('admin.reservas.index') }}" class="text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors">
                    Ver todas
                </a>
            </div>

            @if($reservasPendentes->count() > 0)
                <div class="space-y-6">
                    @foreach($reservasPendentes as $reserva)
                        <div class="flex gap-4 items-center group">
                            <div class="w-12 h-16 bg-gray-100 rounded flex-shrink-0 overflow-hidden">
                                @if($reserva->book->cover_image)
                                    <img src="{{ asset('storage/' . $reserva->book->cover_image) }}" 
                                         alt="{{ $reserva->book->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-gray-400 text-xs font-bold">{{ substr($reserva->book->title, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.livros.show', $reserva->book->id) }}" class="font-bold text-gray-900 group-hover:text-red-600 transition-colors truncate block">
                                    {{ $reserva->book->title }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $reserva->user->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Reservado em {{ \Carbon\Carbon::parse($reserva->reserved_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 py-4">Nenhuma reserva pendente.</p>
            @endif
        </div>
    </div>
</div>
@endsection