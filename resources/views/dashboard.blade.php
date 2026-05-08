@extends('layouts.app')

@section('title', 'Painel do Aluno')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 mb-2">Olá, {{ explode(' ', auth()->user()->name)[0] }}.</h1>
            <p class="text-lg text-gray-500">Bem-vindo de volta à biblioteca EtecRead.</p>
        </div>
        <div class="text-sm font-semibold text-gray-400 uppercase tracking-widest">
            Membro desde {{ auth()->user()->created_at->format('M/Y') }}
        </div>
    </div>

    <!-- Minimal Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Acervo Total</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $stats['total_livros'] }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Disponíveis</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $stats['livros_disponiveis'] }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Meus Empréstimos</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $stats['meus_emprestimos'] }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Minhas Reservas</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $stats['minhas_reservas'] }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-16">
        
        <!-- Left Column: Empréstimos Ativos -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Lendo Agora</h2>
                @if($emprestimosAtivos->count() > 0)
                    <a href="{{ route('emprestimos.meus') }}" class="text-sm font-semibold text-red-600 hover:text-red-700 transition-colors">
                        Ver todos &rarr;
                    </a>
                @endif
            </div>

            @if($emprestimosAtivos->count() > 0)
                <div class="space-y-6">
                    @foreach($emprestimosAtivos as $emprestimo)
                        @php
                            $dueDate = \Carbon\Carbon::parse($emprestimo->due_date);
                            $isOverdue = $dueDate->isPast();
                            $daysLeft = round(now()->diffInDays($dueDate, false));
                        @endphp
                        <div class="flex gap-6 group">
                            <!-- Book Cover -->
                            <div class="w-20 h-32 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                @if($emprestimo->book->cover_image)
                                    <img src="{{ asset('storage/' . $emprestimo->book->cover_image) }}" 
                                         alt="{{ $emprestimo->book->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="flex flex-col py-1">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors mb-1">{{ $emprestimo->book->title }}</h3>
                                <p class="text-sm text-gray-500 mb-auto">{{ $emprestimo->book->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                                
                                <div class="mt-4">
                                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Devolução</span>
                                    <p class="text-sm font-medium {{ $isOverdue ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $dueDate->format('d/m/Y') }} 
                                        @if($isOverdue)
                                            (Atrasado)
                                        @elseif($daysLeft == 0)
                                            (Hoje)
                                        @else
                                            (em {{ $daysLeft }} dias)
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12">
                    <p class="text-gray-500 mb-6">Você não tem livros emprestados no momento.</p>
                    <a href="{{ route('livros.index') }}" class="inline-flex px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors">
                        Explorar Catálogo
                    </a>
                </div>
            @endif
        </div>

        <!-- Right Column: Destaques / Recentes -->
        <div class="lg:col-span-1">
            <div class="mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Adições Recentes</h2>
            </div>
            
            <div class="space-y-6">
                @forelse($livrosDisponiveis->take(4) as $livro)
                    <a href="{{ route('livros.show', $livro->id) }}" class="flex gap-4 group block">
                        <div class="w-12 h-16 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                            @if($livro->cover_image)
                                <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                                     alt="{{ $livro->title }}"
                                     class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex flex-col justify-center">
                            <h4 class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition-colors line-clamp-1">{{ $livro->title }}</h4>
                            <p class="text-xs text-gray-500">{{ $livro->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 text-sm">Nenhum livro disponível no momento.</p>
                @endforelse
            </div>

            <!-- Quick Links -->
            <div class="mt-12 space-y-3">
                <a href="{{ route('reservas.minhas') }}" class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-bold text-gray-900">Minhas Reservas</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-900 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('perfil.show') }}" class="flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-bold text-gray-900">Meu Perfil</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-900 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection