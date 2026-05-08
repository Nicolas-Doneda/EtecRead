@extends('layouts.app')

@section('title', 'Adicionar Livro')

@section('content')
<div class="max-w-4xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <a href="{{ route('admin.livros.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors mb-4">
            &larr; Voltar para Acervo
        </a>
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Adicionar Livro</h1>
        <p class="text-lg text-gray-500">Cadastre uma nova obra no catálogo da biblioteca.</p>
    </div>

    <!-- Erros -->
    @if($errors->any())
        <div class="mb-8 bg-red-50 text-red-800 p-4 rounded-lg text-sm font-medium border border-red-100">
            <p class="mb-2">Erro ao cadastrar obra:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário -->
    <form method="POST" action="{{ route('admin.livros.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Capa do Livro -->
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-4">Capa da Obra</h3>
            <div class="flex items-center gap-6">
                <div class="w-32 h-44 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                    <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                    <div id="preview-default" class="w-full h-full flex items-center justify-center">
                        <span class="text-gray-400 text-sm font-semibold">Sem Capa</span>
                    </div>
                </div>
                <div class="flex-1">
                    <input type="file" name="cover_image" id="cover_image" accept="image/*"
                        class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-md file:border-0
                               file:text-sm file:font-semibold
                               file:bg-gray-100 file:text-gray-700
                               hover:file:bg-gray-200"
                        onchange="previewImage(event)">
                    <p class="text-xs text-gray-400 mt-2">Formatos: JPG, PNG ou WEBP. Máx: 2MB</p>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Informações Principais</h3>
            <div class="space-y-5">
                <!-- Título -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                        required>
                </div>

                <!-- Categoria e ISBN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Categoria *</label>
                        <select name="category_id"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                            required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('category_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Autores -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Autores</label>
                    <select name="authors[]" id="authors-select" multiple>
                        @foreach($autores as $autor)
                            <option value="{{ $autor->id }}" {{ in_array($autor->id, old('authors', [])) ? 'selected' : '' }}>
                                {{ $autor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Estoque e Detalhes</h3>
            <div class="space-y-5">
                <!-- Ano e Quantidades -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ano de Publicação</label>
                        <input type="number" name="year" value="{{ old('year') }}" min="1000" max="{{ date('Y') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Estoque Total *</label>
                        <input type="number" name="total_quantity" value="{{ old('total_quantity', 1) }}" min="0"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Qtd. Disponível *</label>
                        <input type="number" name="available_quantity" value="{{ old('available_quantity', 1) }}" min="0"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                            required>
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">{{ old('description') }}</textarea>
                </div>

                <!-- Notas -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notas Internas</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="pt-8 border-t border-gray-100 flex gap-4">
            <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                Cadastrar Obra
            </button>
            <a href="{{ route('admin.livros.index') }}" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const defaultPreview = document.getElementById('preview-default');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            defaultPreview.classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('authors-select');
    const choices = new Choices(element, {
        removeItemButton: true,
        searchEnabled: true,
        searchPlaceholderValue: 'Buscar autor...',
        noResultsText: 'Nenhum autor encontrado',
        itemSelectText: 'Clique para selecionar',
        placeholderValue: 'Selecione os autores',
        maxItemCount: 10,
        shouldSort: true,
    });
});
</script>
@endsection