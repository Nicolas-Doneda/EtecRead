@extends('layouts.app')

@section('title', 'Autores')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Autores</h1>
            <p class="text-lg text-gray-500">Adicione, edite ou remova autores do catálogo</p>
        </div>
        <div>
            <a href="{{ route('admin.autores.create') }}" class="inline-flex px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                + Novo Autor
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="mb-8 bg-green-50 text-green-800 p-4 rounded-lg text-sm font-medium border border-green-100">
            {{ session('success') }}
        </div>
    @endif

    <!-- Busca -->
    <div class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-100">
        <form method="GET" action="{{ route('admin.autores.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por nome..."
                       class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
            </div>
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-white text-gray-900 text-sm font-semibold border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Grid de Autores -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($authors as $author)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-gray-300 transition-colors group flex flex-col">
                <!-- Foto do Autor -->
                <div class="relative h-48 bg-gray-100 border-b border-gray-100">
                    @if($author->photo)
                        <img src="{{ asset('storage/' . $author->photo) }}" 
                             alt="{{ $author->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-gray-300 font-extrabold text-6xl">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Info do Autor -->
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $author->name }}</h3>
                    
                    @if($author->bio)
                        <p class="text-gray-500 text-sm mb-4 line-clamp-3 flex-1">{{ $author->bio }}</p>
                    @else
                        <p class="text-gray-400 italic text-sm mb-4 flex-1">Sem biografia disponível</p>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center gap-2 mb-6 text-sm text-gray-500">
                        <span class="font-semibold text-gray-900">{{ $author->books->count() }}</span>
                        <span>obras cadastradas</span>
                    </div>

                    <!-- Ações -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.autores.edit', $author->id) }}" 
                           class="text-sm font-semibold text-gray-900 hover:text-gray-600 transition-colors">
                            Editar Perfil
                        </a>
                        <form method="POST" action="{{ route('admin.autores.destroy', $author->id) }}" 
                              onsubmit="return confirm('Tem certeza que deseja excluir este autor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-sm font-semibold text-red-400 hover:text-red-600 transition-colors">
                                Remover
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center border border-gray-200 rounded-xl border-dashed">
                <p class="text-gray-500 font-medium">Nenhum autor encontrado no acervo.</p>
                <p class="text-gray-400 text-sm mt-1">Utilize o botão acima para adicionar um novo registro.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($authors->hasPages())
        <div class="mt-8">
            {{ $authors->links() }}
        </div>
    @endif
</div>
@endsection