@extends('layouts.app')

@section('title', 'Editar Membro')

@section('content')
<div class="max-w-3xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-400 hover:text-gray-900 transition-colors mb-4">
            &larr; Voltar para Usuários
        </a>
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Editar Membro</h1>
        <p class="text-lg text-gray-500">Atualizando o registro de: {{ $usuario->name }}</p>
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
    <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            
            <!-- Foto do Usuário -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-4">Foto de Perfil</label>
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-gray-100 rounded-full overflow-hidden flex-shrink-0 border border-gray-200">
                        @if($usuario->photo)
                            <img id="preview" src="{{ asset('storage/' . $usuario->photo) }}" 
                                 alt="{{ $usuario->name }}" 
                                 class="w-full h-full object-cover">
                            <div id="preview-default" class="hidden"></div>
                        @else
                            <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            <div id="preview-default" class="w-full h-full flex items-center justify-center">
                                <span class="text-gray-400 font-bold text-sm">FOTO</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" name="photo" id="photo" accept="image/*"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                               onchange="previewImage(event)">
                        <p class="text-xs text-gray-500 mt-2">Formatos recomendados: JPG, PNG, WEBP (Max 2MB). Deixe em branco para manter a imagem atual.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            <div class="space-y-6">
                <!-- Nome -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo *</label>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}" 
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                           required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Correio Eletrônico *</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}" 
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                           required>
                </div>

                <!-- Senha -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nova Senha de Acesso</label>
                    <input type="password" name="password" 
                           placeholder="Deixe em branco para não alterar"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                    <p class="text-xs text-gray-500 mt-2">Utilize no mínimo 6 caracteres. Se deixar em branco, a senha atual permanecerá.</p>
                </div>

                <!-- Tipo de Conta e RM -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Cadastro *</label>
                        <select name="role" id="role" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                                required onchange="toggleAnoEscolar()">
                            <option value="">Selecione a categoria...</option>
                            <option value="aluno" {{ old('role', $usuario->role) == 'aluno' ? 'selected' : '' }}>Aluno</option>
                            <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Registro de Matrícula (RM)</label>
                        <input type="text" name="rm" value="{{ old('rm', $usuario->rm) }}" 
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                        <p class="text-xs text-gray-500 mt-2">Apenas para cadastro de alunos (Opcional).</p>
                    </div>
                </div>

                <!-- Ano Escolar (só aparece se for aluno) -->
                <div id="ano-escolar-field" style="display: {{ old('role', $usuario->role) == 'aluno' ? 'block' : 'none' }};">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Grau de Escolaridade</label>
                    <select name="ano_escolar" 
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                        <option value="">Selecione o ano letivo...</option>
                        <option value="1" {{ old('ano_escolar', $usuario->ano_escolar) == '1' ? 'selected' : '' }}>1º Ano do Ensino Médio</option>
                        <option value="2" {{ old('ano_escolar', $usuario->ano_escolar) == '2' ? 'selected' : '' }}>2º Ano do Ensino Médio</option>
                        <option value="3" {{ old('ano_escolar', $usuario->ano_escolar) == '3' ? 'selected' : '' }}>3º Ano do Ensino Médio</option>
                    </select>
                </div>
            </div>

            <!-- Info sobre empréstimos -->
            @php $activeLoans = $usuario->loans()->where('status', 'ativo')->count(); @endphp
            @if($activeLoans > 0)
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg text-sm text-yellow-800">
                <p class="font-semibold mb-1">Aviso de Bloqueio de Ações Estruturais</p>
                <p>Este membro possui <strong>{{ $activeLoans }} empréstimo(s) em aberto</strong>. Isso pode prevenir a exclusão da conta até a devolução dos volumes.</p>
            </div>
            @endif

            <!-- Botões -->
            <div class="pt-8 border-t border-gray-100 flex gap-4">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    Salvar Alterações
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
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
                if (defaultPreview) {
                    defaultPreview.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function toggleAnoEscolar() {
        const role = document.getElementById('role').value;
        const anoEscolarField = document.getElementById('ano-escolar-field');
        
        if (role === 'aluno') {
            anoEscolarField.style.display = 'block';
        } else {
            anoEscolarField.style.display = 'none';
        }
    }
</script>
@endsection