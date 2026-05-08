@extends('layouts.app')

@section('title', 'Empréstimos')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Empréstimos</h1>
            <p class="text-lg text-gray-500">Visualize e gerencie os empréstimos ativos e histórico</p>
        </div>
        <div>
            <a href="{{ route('admin.emprestimos.create') }}" class="inline-flex px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                + Novo Empréstimo
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="mb-8 bg-green-50 text-green-800 p-4 rounded-lg text-sm font-medium border border-green-100">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros e Busca -->
    <div class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-100">
        <form method="GET" action="{{ route('admin.emprestimos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
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
                    <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="finalizado" {{ request('status') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="atrasado" {{ request('status') == 'atrasado' ? 'selected' : '' }}>Atrasado</option>
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

    <!-- Tabela de Empréstimos -->
    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Obra</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Leitor</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Retirada</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Devolução</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($loans as $loan)
                        @php
                            $isOverdue = $loan->status === 'ativo' && \Carbon\Carbon::parse($loan->due_date)->isPast();
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <!-- Livro -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-14 bg-gray-100 rounded flex-shrink-0 overflow-hidden">
                                        @if($loan->book->cover_image)
                                            <img src="{{ asset('storage/' . $loan->book->cover_image) }}" 
                                                 alt="{{ $loan->book->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-gray-400 font-bold text-xs">{{ substr($loan->book->title, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 max-w-[200px]">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $loan->book->title }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $loan->book->authors->first()->name ?? 'Sem autor' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Aluno -->
                            <td class="py-4 px-6">
                                <div class="min-w-0 max-w-[200px]">
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $loan->user->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $loan->user->email }}</p>
                                </div>
                            </td>

                            <!-- Data Empréstimo -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</p>
                            </td>

                            <!-- Data Devolução -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="text-sm font-medium {{ $isOverdue ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}
                                </p>
                                @if($isOverdue)
                                    <p class="text-xs text-red-500 font-medium mt-0.5">
                                        Atraso de {{ \Carbon\Carbon::parse($loan->due_date)->diffInDays(now()) }} dia(s)
                                    </p>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($loan->status === 'ativo')
                                    @if($isOverdue)
                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-red-700 bg-red-50 border border-red-100 rounded">
                                            Atrasado
                                        </span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-100 rounded">
                                            Ativo
                                        </span>
                                    @endif
                                @elseif($loan->status === 'finalizado')
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 rounded">
                                        Finalizado
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 rounded">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Ações -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($loan->status === 'ativo')
                                        <form method="POST" action="{{ route('admin.emprestimos.return', $loan->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-sm font-semibold text-gray-900 hover:text-gray-600 transition-colors">
                                                Registrar Devolução
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.emprestimos.destroy', $loan->id) }}" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir o registro deste empréstimo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-sm font-semibold text-red-400 hover:text-red-600 transition-colors ml-2">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <p class="text-gray-500 font-medium">Nenhum registro de empréstimo encontrado.</p>
                                <p class="text-gray-400 text-sm mt-1">Ajuste os filtros ou registre um novo empréstimo.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($loans->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection