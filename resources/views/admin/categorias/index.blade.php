@extends('layouts.app')

@section('title', 'Gerenciar Categorias')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Gerenciar Categorias</h1>
            <p class="text-gray-600">Adicione, edite ou remova categorias de livros</p>
        </div>
        <a href="{{ route('admin.categorias.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Adicionar Categoria
        </a>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Busca -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.categorias.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por nome..."
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-semibold px-6 py-2 rounded-lg transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Buscar
            </button>
        </form>
    </div>

    <!-- Grid de Categorias -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition group">
                <!-- Header da Categoria -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ $category->name }}</h3>
                </div>

                <!-- Info da Categoria -->
                <div class="p-6">
                    @if($category->description)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $category->description }}</p>
                    @else
                        <p class="text-gray-400 italic text-sm mb-4">Sem descrição</p>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center justify-center mb-4 text-sm">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="font-bold text-lg text-gray-900">{{ $category->books->count() }}</span>
                            <span class="ml-1">livro(s)</span>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.categorias.edit', $category->id) }}" 
                           class="flex-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 font-semibold py-2 rounded-lg transition text-center flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.categorias.destroy', $category->id) }}" 
                              onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-semibold py-2 rounded-lg transition flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium text-lg">Nenhuma categoria encontrada</p>
                    <p class="text-gray-400 text-sm mt-2">Adicione uma nova categoria ao sistema</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="mt-8">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection