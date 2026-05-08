@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('content')
<div class="max-w-3xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <a href="{{ route('admin.categorias.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors mb-4">
            &larr; Voltar para Categorias
        </a>
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Editar Categoria</h1>
        <p class="text-lg text-gray-500">Atualizando o registro: {{ $categoria->name }}</p>
    </div>

    <!-- Erros -->
    @if($errors->any())
        <div class="mb-8 bg-red-50 text-red-800 p-4 rounded-lg text-sm font-medium border border-red-100">
            <p class="mb-2">Não foi possível atualizar o registro:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário -->
    <form method="POST" action="{{ route('admin.categorias.update', $categoria->id) }}">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Nome -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nome da Categoria *</label>
                <input type="text" name="name" value="{{ old('name', $categoria->name) }}" 
                       placeholder="Ex: Ficção, Romance, Tecnologia..."
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                       required>
            </div>

            <!-- Descrição -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Descrição</label>
                <textarea name="description" rows="4" 
                          placeholder="Descreva sobre que tipo de livros pertencem a essa categoria..."
                          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">{{ old('description', $categoria->description) }}</textarea>
                <p class="text-xs text-gray-500 mt-2">Informações adicionais sobre o gênero ou categoria.</p>
            </div>

            <!-- Info sobre livros -->
            @if($categoria->books->count() > 0)
            <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg text-sm text-gray-800">
                <p>Esta categoria possui <strong>{{ $categoria->books->count() }} obra(s) vinculada(s)</strong> em nosso sistema.</p>
            </div>
            @endif

            <!-- Botões -->
            <div class="pt-8 border-t border-gray-100 flex gap-4">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    Salvar Alterações
                </button>
                <a href="{{ route('admin.categorias.index') }}" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Descartar Alterações
                </a>
            </div>
        </div>
    </form>
</div>
@endsection