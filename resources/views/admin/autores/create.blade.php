@extends('layouts.app')

@section('title', 'Adicionar Autor')

@section('content')
<div class="max-w-3xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <a href="{{ route('admin.autores.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors mb-4">
            &larr; Voltar para Autores
        </a>
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Novo Autor</h1>
        <p class="text-lg text-gray-500">Cadastre as informações de um novo autor no catálogo.</p>
    </div>

    <!-- Erros -->
    @if($errors->any())
        <div class="mb-8 bg-red-50 text-red-800 p-4 rounded-lg text-sm font-medium border border-red-100">
            <p class="mb-2">Não foi possível criar o registro:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário -->
    <form method="POST" action="{{ route('admin.autores.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="space-y-8">
            
            <!-- Foto do Autor -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-4">Retrato</label>
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-gray-100 rounded-full overflow-hidden flex-shrink-0 border border-gray-200">
                        <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        <div id="preview-default" class="w-full h-full flex items-center justify-center">
                            <span class="text-gray-400 font-bold text-sm">FOTO</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="photo" id="photo" accept="image/*"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                               onchange="previewImage(event)">
                        <p class="text-xs text-gray-500 mt-2">Formatos recomendados: JPG, PNG, WEBP (Max 2MB).</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            <div class="space-y-6">
                <!-- Nome -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo *</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                           required>
                </div>

                <!-- Biografia -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Biografia</label>
                    <textarea name="bio" rows="6" 
                              class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">{{ old('bio') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Um breve resumo sobre a vida e obra do autor.</p>
                </div>

                <!-- Datas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nascimento</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" 
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Falecimento</label>
                        <input type="date" name="death_date" value="{{ old('death_date') }}" 
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                        <p class="text-xs text-gray-500 mt-2">Deixe em branco caso o autor seja vivo.</p>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="pt-8 border-t border-gray-100 flex gap-4">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    Salvar Registro
                </button>
                <a href="{{ route('admin.autores.index') }}" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Descartar Alterações
                </a>
            </div>
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
</script>
@endsection