@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Usuários</h1>
            <p class="text-lg text-gray-500">Adicione, edite ou remova membros do sistema</p>
        </div>
        <div>
            <a href="{{ route('admin.usuarios.create') }}" class="inline-flex px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                + Novo Usuário
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
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <!-- Busca -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nome, email ou RM..."
                       class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
            </div>

            <!-- Tipo de Conta -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Conta</label>
                <select name="role" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    <option value="">Todos</option>
                    <option value="aluno" {{ request('role') == 'aluno' ? 'selected' : '' }}>Alunos</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
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

    <!-- Tabela de Usuários -->
    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Membro</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Identificação</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Escolaridade</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">Tipo</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">Ativos</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <!-- Usuário -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex-shrink-0 overflow-hidden">
                                        @if($user->photo)
                                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-gray-400 font-bold text-xs">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 max-w-[200px]">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- RM -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="text-sm text-gray-500 font-mono">{{ $user->rm ?? '—' }}</p>
                            </td>

                            <!-- Ano Escolar -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($user->ano_escolar)
                                    <span class="text-sm text-gray-900 font-medium">{{ $user->ano_escolar }}º Ano</span>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>

                            <!-- Tipo -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($user->role === 'admin')
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-gray-900 bg-gray-100 border border-gray-200 rounded">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 rounded">
                                        Aluno
                                    </span>
                                @endif
                            </td>

                            <!-- Empréstimos -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @php $activeLoans = $user->loans()->where('status', 'ativo')->count(); @endphp
                                @if($activeLoans > 0)
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-100 rounded">
                                        {{ $activeLoans }} em posse
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>

                            <!-- Ações -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.usuarios.edit', $user->id) }}" 
                                       class="text-sm font-semibold text-gray-900 hover:text-gray-600 transition-colors">
                                        Editar
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.usuarios.destroy', $user->id) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja remover este membro do sistema?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-sm font-semibold text-red-400 hover:text-red-600 transition-colors ml-2">
                                                Remover
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <p class="text-gray-500 font-medium">Nenhum membro encontrado.</p>
                                <p class="text-gray-400 text-sm mt-1">Ajuste os filtros ou cadastre um novo membro.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection