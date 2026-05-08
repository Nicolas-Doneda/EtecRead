@extends('layouts.app')

@section('title', 'Gerenciar Acervo')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Acervo</h1>
            <p class="text-lg text-gray-500">Adicione, edite ou remova livros do catálogo</p>
        </div>
        <div>
            <a href="{{ route('admin.livros.create') }}" class="inline-flex px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                + Novo Livro
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
        <form method="GET" action="{{ route('admin.livros.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <!-- Busca -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Título, autor ou ISBN..."
                       class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
            </div>

            <!-- Categoria -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Categoria</label>
                <select name="category_id" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
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

    <!-- Tabela de Livros -->
    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Obra</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">Categoria</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest">ISBN</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-center">Acervo</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-400 uppercase tracking-widest text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($books as $book)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <!-- Livro -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-16 bg-gray-100 rounded flex-shrink-0 overflow-hidden">
                                        @if($book->cover_image)
                                            <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                                 alt="{{ $book->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-gray-400 font-bold text-xs">{{ substr($book->title, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 truncate">{{ $book->title }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ $book->authors->first()->name ?? 'Sem autor' }}</p>
                                        @if($book->year)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $book->year }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Categoria -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded">
                                    {{ $book->category->name }}
                                </span>
                            </td>

                            <!-- ISBN -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <p class="text-sm text-gray-500 font-mono">{{ $book->isbn ?? 'N/A' }}</p>
                            </td>

                            <!-- Quantidade -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <div class="flex flex-col items-center">
                                    <span class="font-bold text-gray-900">{{ $book->available_quantity }}</span>
                                    <span class="text-xs text-gray-400">de {{ $book->total_quantity }}</span>
                                </div>
                            </td>

                            <!-- Ações -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.livros.edit', $book->id) }}" 
                                       class="text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.livros.destroy', $book->id) }}" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta obra permanentemente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-sm font-semibold text-red-400 hover:text-red-600 transition-colors">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <p class="text-gray-500 font-medium">Nenhuma obra localizada no acervo.</p>
                                <p class="text-gray-400 text-sm mt-1">Ajuste os filtros ou cadastre um novo livro.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>
@endsection