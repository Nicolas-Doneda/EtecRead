@extends('layouts.app')

@section('title', $livro->title)

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-8 pb-24">
    <!-- Back Button -->
    <a href="{{ route('livros.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 mb-10 transition-colors duration-200 group">
        <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Voltar para o catálogo
    </a>

    <div class="flex flex-col lg:flex-row gap-16">
        <!-- Left Column - Book Cover -->
        <div class="w-full lg:w-1/3 flex-shrink-0">
            <div class="sticky top-24">
                <div class="relative aspect-[2/3] bg-gray-100 rounded-lg overflow-hidden shadow-sm mb-6">
                    @if($livro->cover_image)
                        <img src="{{ asset('storage/' . $livro->cover_image) }}" 
                             alt="{{ $livro->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Primary Action Area -->
                <div class="space-y-4">
                    @if(auth()->user()->role === 'aluno')
                        @if($livro->available_quantity > 0)
                            <div class="bg-green-50 border border-green-100 rounded-lg p-5">
                                <div class="flex gap-3">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mt-1.5 flex-shrink-0"></div>
                                    <div>
                                        <h4 class="text-green-800 font-semibold text-sm">Disponível para empréstimo</h4>
                                        <p class="text-green-700 text-sm mt-1 leading-relaxed">Este livro tem {{ $livro->available_quantity }} cópias disponíveis. Dirija-se à biblioteca escolar para retirá-lo.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            @php
                                $jaReservou = $livro->reservations()
                                    ->where('user_id', auth()->id())
                                    ->where('status', 'pendente')
                                    ->exists();
                            @endphp

                            @if(!$jaReservou)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                                    <div class="flex gap-3 mb-4">
                                        <div class="w-2 h-2 rounded-full bg-red-500 mt-1.5 flex-shrink-0"></div>
                                        <div>
                                            <h4 class="text-gray-900 font-semibold text-sm">Indisponível no momento</h4>
                                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">Todas as cópias estão emprestadas. Você pode entrar na fila de reserva e ser avisado quando um exemplar for devolvido.</p>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('reservas.store') }}">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $livro->id }}">
                                        <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium text-sm py-3 rounded-lg transition-colors duration-200 flex justify-center items-center gap-2">
                                            Entrar na fila de reserva
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-purple-50 border border-purple-100 rounded-lg p-5">
                                    <div class="flex gap-3">
                                        <div class="w-2 h-2 rounded-full bg-purple-500 mt-1.5 flex-shrink-0"></div>
                                        <div>
                                            <h4 class="text-purple-800 font-semibold text-sm">Reserva ativa</h4>
                                            <p class="text-purple-700 text-sm mt-1 leading-relaxed">Você já está na fila de espera para este livro. Fique de olho no seu painel para saber quando retirar.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @else
                        <!-- Admin View Status -->
                        @if($livro->available_quantity > 0)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-700">
                                <strong>Status Admin:</strong> Cópias disponíveis para novos empréstimos.
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-700">
                                <strong>Status Admin:</strong> Acervo esgotado. Verifique as reservas ativas.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Editorial Content -->
        <div class="w-full lg:w-2/3">
            <div class="mb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-widest">
                    {{ $livro->category->name }}
                </span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 leading-tight mb-4">
                {{ $livro->title }}
            </h1>
            
            <p class="text-xl text-gray-600 mb-10 font-medium">
                {{ $livro->authors->first()->name ?? 'Autor Desconhecido' }}
            </p>

            <div class="border-t border-gray-100 my-10"></div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Disponíveis</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $livro->available_quantity }} <span class="text-sm font-medium text-gray-500">de {{ $livro->total_quantity }}</span></p>
                </div>
                @if($livro->year)
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Ano</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $livro->year }}</p>
                </div>
                @endif
                @if($livro->isbn)
                <div class="col-span-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">ISBN</p>
                    <p class="text-lg font-mono text-gray-900">{{ $livro->isbn }}</p>
                </div>
                @endif
            </div>

            <!-- About the Author -->
            @if($livro->authors->first())
                @php
                    $autor = $livro->authors->first();
                @endphp
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Sobre {{ explode(' ', $autor->name)[0] }}</h3>
                    <div class="flex items-start gap-4">
                        @if($autor->photo)
                            <img src="{{ asset('storage/' . $autor->photo) }}" 
                                 alt="{{ $autor->name }}"
                                 class="w-16 h-16 rounded-full object-cover border border-gray-200">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                <span class="text-gray-500 font-bold text-xl">{{ strtoupper(substr($autor->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            @if($autor->bio)
                                <p class="text-gray-600 leading-relaxed">{{ $autor->bio }}</p>
                            @else
                                <p class="text-gray-500 italic text-sm mt-2">Biografia não cadastrada.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="border-t border-gray-100 my-10"></div>

            <!-- Synopsis -->
            @if($livro->description)
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Sinopse</h3>
                    <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                        <p>{{ $livro->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($livro->notes)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mt-12">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Observação da Biblioteca
                    </h4>
                    <p class="text-sm text-gray-600">{{ $livro->notes }}</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection