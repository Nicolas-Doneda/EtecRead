@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="max-w-5xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Meu Perfil</h1>
        <p class="text-lg text-gray-500">Gerencie suas informações pessoais e preferências</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <!-- Coluna Esquerda - Card de Perfil -->
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                <!-- Foto de Perfil -->
                <div class="flex flex-col items-center mb-8">
                    <div class="w-32 h-32 rounded-full overflow-hidden mb-4 bg-gray-100 flex items-center justify-center">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-400 font-bold text-4xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 text-center">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $user->email }}</p>

                    <!-- Badge do Tipo de Conta -->
                    <div class="mt-4">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $user->role === 'admin' ? 'Administrador' : 'Aluno' }}
                        </span>
                    </div>
                </div>

                <!-- Informações Detalhadas -->
                <div class="space-y-4 pt-6 border-t border-gray-100">
                    @if($user->rm)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">RM</p>
                        <p class="text-gray-900 font-bold">{{ $user->rm }}</p>
                    </div>
                    @endif

                    @if($user->ano_escolar)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Ano Escolar</p>
                        <p class="text-gray-900 font-bold">{{ $user->ano_escolar }}º ano</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Membro desde</p>
                        <p class="text-gray-900 font-bold">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                @if($user->role === 'aluno')
                <!-- Estatísticas do Aluno -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Minhas Estatísticas</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Empréstimos Ativos</span>
                            <span class="font-bold text-gray-900">{{ $user->loans()->where('status', 'ativo')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Total de Empréstimos</span>
                            <span class="font-bold text-gray-900">{{ $user->loans()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Reservas Pendentes</span>
                            <span class="font-bold text-gray-900">{{ $user->reservations()->where('status', 'pendente')->count() }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Coluna Direita - Formulário de Edição -->
        <div class="lg:col-span-2">
            
            <!-- Alertas -->
            @if(session('success'))
                <div class="mb-8 bg-green-50 text-green-800 p-4 rounded-lg text-sm font-medium border border-green-100">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 bg-red-50 text-red-800 p-4 rounded-lg text-sm font-medium border border-red-100">
                    <p class="mb-2">Erro ao atualizar perfil:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Foto de Perfil -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Foto de Perfil</h3>
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 flex-shrink-0 flex items-center justify-center">
                            @if($user->photo)
                                <img id="preview" src="{{ asset('storage/' . $user->photo) }}" 
                                     alt="Preview" 
                                     class="w-full h-full object-cover">
                            @else
                                <div id="preview-default" class="w-full h-full flex items-center justify-center">
                                    <span class="text-gray-400 font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <img id="preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/*"
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-gray-100 file:text-gray-700
                                          hover:file:bg-gray-200"
                                   onchange="previewPhoto(event)">
                            <p class="text-xs text-gray-400 mt-2">JPG, PNG ou WEBP. Máximo 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Dados Pessoais -->
                <div class="pt-8 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Informações Básicas</h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nome Completo</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all" 
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Segurança -->
                <div class="pt-8 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Segurança</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nova Senha</label>
                            <input type="password" name="password" 
                                   placeholder="Deixe em branco para manter"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmar Senha</label>
                            <input type="password" name="password_confirmation" 
                                   placeholder="Confirme a nova senha"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                <!-- Informações Protegidas -->
                @if($user->rm || $user->ano_escolar || $user->role)
                <div class="pt-8 border-t border-gray-100">
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 text-sm">
                        <p class="font-bold text-gray-900 mb-1">Informações Institucionais</p>
                        <p class="text-gray-500 mb-4">Estes dados são gerenciados pela administração.</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            @if($user->rm)
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">RM</span>
                                <span class="font-medium text-gray-900">{{ $user->rm }}</span>
                            </div>
                            @endif
                            @if($user->ano_escolar)
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Série</span>
                                <span class="font-medium text-gray-900">{{ $user->ano_escolar }}º ano</span>
                            </div>
                            @endif
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Perfil</span>
                                <span class="font-medium text-gray-900">{{ $user->role === 'admin' ? 'Administrador' : 'Aluno' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Botões -->
                <div class="pt-8 border-t border-gray-100 flex gap-4">
                    <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                        Salvar Alterações
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewPhoto(event) {
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
</script>
@endsection