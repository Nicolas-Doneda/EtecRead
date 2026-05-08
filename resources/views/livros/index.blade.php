@extends('layouts.app')

@section('title', 'Catálogo de Livros')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Catálogo de Livros</h1>
        <p class="text-lg text-gray-500">Explore nossa coleção completa de {{ $livros->total() }} títulos</p>
    </div>

    <!-- Filters & Search -->
    <div class="mb-12">
        <form method="GET" action="{{ route('livros.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Search -->
            <div class="flex-grow w-full">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Buscar</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por título ou autor..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200 placeholder-gray-400">
                    <svg class="absolute left-3.5 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="w-full md:w-64 flex-shrink-0">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Categoria</label>
                <select name="category_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200 appearance-none">
                    <option value="">Todas as categorias</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('category_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-gray-900 hover:bg-gray-800 text-white font-medium text-sm px-6 py-2.5 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Books Grid -->
    @if($livros->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-10 mb-12">
            @foreach($livros as $livro)
                <a href="{{ route('livros.show', $livro->id) }}" class="group block">
                    <!-- Book Cover -->
                    <div class="relative aspect-[2/3] bg-gray-100 rounded-lg overflow-hidden mb-4 shadow-sm group-hover:shadow-md transition-all duration-300">
                        @if($livro->cover_image)
                            <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                                 alt="{{ $livro->title }}"
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500 ease-out">
                        @else
                            <div class="w-full h-full bg-gray-100 flex flex-col items-center justify-center p-4 text-center">
                                <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="text-xs text-gray-400 font-medium uppercase tracking-widest line-clamp-2">{{ $livro->title }}</span>
                            </div>
                        @endif
                        
                        <!-- Status Indicator -->
                        <div class="absolute top-2 right-2 flex gap-1">
                            @if($livro->available_quantity > 0)
                                <div class="w-2.5 h-2.5 bg-green-500 rounded-full shadow-sm" title="Disponível"></div>
                            @else
                                <div class="w-2.5 h-2.5 bg-red-500 rounded-full shadow-sm" title="Emprestado"></div>
                            @endif
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 truncate">
                            {{ $livro->category->name }}
                        </p>
                        <h3 class="font-bold text-gray-900 text-sm line-clamp-2 leading-snug group-hover:text-red-600 transition-colors duration-200">
                            {{ $livro->title }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1 truncate">
                            {{ $livro->authors->first()->name ?? 'Autor Desconhecido' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div>
            {{ $livros->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="py-20 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Nenhum título encontrado</h3>
            <p class="text-gray-500 mb-6">Tente ajustar seus filtros de busca para encontrar o que procura.</p>
            <a href="{{ route('livros.index') }}" class="inline-flex items-center text-sm font-medium text-gray-900 hover:text-red-600 transition-colors duration-200">
                Limpar filtros
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    @endif
</div>
@endsection