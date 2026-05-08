@extends('layouts.app')

@section('title', 'Novo Empréstimo')

@section('content')
<div class="max-w-3xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <a href="{{ route('admin.emprestimos.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors mb-4">
            &larr; Voltar para Empréstimos
        </a>
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Novo Empréstimo</h1>
        <p class="text-lg text-gray-500">Registre a retirada de um livro por um aluno.</p>
    </div>

    <!-- Erros -->
    @if($errors->any())
        <div class="mb-8 bg-red-50 text-red-800 p-4 rounded-lg text-sm font-medium border border-red-100">
            <p class="mb-2">Não foi possível registrar o empréstimo:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário -->
    <form method="POST" action="{{ route('admin.emprestimos.store') }}">
        @csrf

        <div class="space-y-8">
            <div class="space-y-6">
                <!-- Aluno -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Aluno *</label>
                    <select name="user_id" id="user-select" 
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                            required>
                        <option value="">Selecione o aluno...</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Livro -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Obra *</label>
                    <select name="book_id" id="book-select" 
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                            required>
                        <option value="">Selecione a obra...</option>
                        @foreach($livros as $livro)
                            <option value="{{ $livro->id }}" {{ old('book_id') == $livro->id ? 'selected' : '' }}>
                                {{ $livro->title }} 
                                @if($livro->authors->first())
                                    — {{ $livro->authors->first()->name }}
                                @endif
                                ({{ $livro->available_quantity }} em estoque)
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">A lista exibe apenas obras com estoque disponível.</p>
                </div>

                <!-- Data de Devolução -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Previsão de Devolução *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', \Carbon\Carbon::now()->addDays(7)->format('Y-m-d')) }}" 
                           min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                           class="w-full md:w-1/2 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                           required>
                    <p class="text-xs text-gray-500 mt-2">O padrão sugerido é de 7 dias.</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg text-sm text-gray-600">
                <p class="font-semibold text-gray-900 mb-1">Notas do Sistema</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>A data de retirada é registrada automaticamente com o dia atual.</li>
                    <li>O estoque da obra será ajustado automaticamente.</li>
                </ul>
            </div>

            <!-- Botões -->
            <div class="pt-8 border-t border-gray-100 flex gap-4">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    Registrar Retirada
                </button>
                <a href="{{ route('admin.emprestimos.index') }}" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user-select');
        const bookSelect = document.getElementById('book-select');
        
        new Choices(userSelect, {
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar por nome ou email...',
            noResultsText: 'Nenhum leitor encontrado',
            itemSelectText: 'Selecionar',
            shouldSort: false,
        });
        
        new Choices(bookSelect, {
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar por título ou autor...',
            noResultsText: 'Nenhuma obra encontrada',
            itemSelectText: 'Selecionar',
            shouldSort: false,
        });
    });
</script>
@endsection