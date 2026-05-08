@extends('layouts.app')

@section('title', 'Meus Empréstimos')

@section('content')
<div class="max-w-7xl mx-auto px-6 pt-12 pb-24">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Meus Empréstimos</h1>
        <p class="text-lg text-gray-500">Acompanhe seus livros e prazos de devolução</p>
    </div>

    <!-- Stats Minimal -->
    <div class="grid grid-cols-3 gap-8 mb-12">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Ativos</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $emprestimosAtivos->count() }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Devolvidos</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $emprestimosConcluidos }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Atrasados</p>
            <p class="text-4xl font-extrabold {{ $emprestimosAtrasados > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $emprestimosAtrasados }}</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-8 border-b border-gray-100">
        <nav class="flex gap-8">
            <button onclick="showTab('ativos')" id="tab-ativos" class="tab-button active text-sm font-bold text-gray-900 border-b-2 border-gray-900 pb-4 transition-all">
                Em andamento ({{ $emprestimosAtivos->count() }})
            </button>
            <button onclick="showTab('historico')" id="tab-historico" class="tab-button text-sm font-semibold text-gray-400 hover:text-gray-600 border-b-2 border-transparent pb-4 transition-all">
                Histórico
            </button>
        </nav>
    </div>

    <!-- Tab Content: Ativos -->
    <div id="content-ativos" class="tab-content">
        @if($emprestimosAtivos->count() > 0)
            <div class="space-y-6">
                @foreach($emprestimosAtivos as $emprestimo)
                    @php
                        $dueDate = \Carbon\Carbon::parse($emprestimo->due_date);
                        $loanDate = \Carbon\Carbon::parse($emprestimo->loan_date);
                        $isOverdue = $dueDate->isPast();
                        $totalDays = $loanDate->diffInDays($dueDate);
                        $daysLeft = round(now()->diffInDays($dueDate, false));
                        $daysPassed = $totalDays - $daysLeft;
                        $percentage = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
                    @endphp

                    <div class="flex flex-col sm:flex-row gap-6 p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                        <!-- Book Cover -->
                        <div class="w-24 h-36 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                            @if($emprestimo->book->cover_image)
                                <img src="{{ asset('storage/' . $emprestimo->book->cover_image) }}" 
                                     alt="{{ $emprestimo->book->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 flex flex-col justify-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $emprestimo->book->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $emprestimo->book->authors->first()->name ?? 'Autor Desconhecido' }}</p>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                                <div>
                                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Empréstimo</span>
                                    {{ $loanDate->format('d/m/Y') }}
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Devolução</span>
                                    <span class="{{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-900 font-medium' }}">
                                        {{ $dueDate->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mt-auto">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-sm font-semibold {{ $isOverdue ? 'text-red-600' : ($percentage >= 70 ? 'text-yellow-600' : 'text-green-600') }}">
                                        @if($isOverdue)
                                            Atrasado há {{ abs($daysLeft) }} dia(s)
                                        @elseif($daysLeft == 0)
                                            Devolva hoje!
                                        @else
                                            Faltam {{ $daysLeft }} dia(s)
                                        @endif
                                    </span>
                                    <span class="text-xs font-medium text-gray-400">{{ min(100, $percentage) }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $isOverdue ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                         style="width: {{ min(100, $percentage) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="py-20 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Você não tem empréstimos ativos</h3>
                <p class="text-gray-500 mb-6">Explore o catálogo e encontre sua próxima leitura.</p>
                <a href="{{ route('livros.index') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors duration-200">
                    Ir para o Catálogo
                </a>
            </div>
        @endif
    </div>

    <!-- Tab Content: Histórico -->
    <div id="content-historico" class="tab-content hidden">
        @if($todosEmprestimos->count() > 0)
            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Livro</th>
                            <th class="px-6 py-4">Data Empréstimo</th>
                            <th class="px-6 py-4">Data Devolução</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($todosEmprestimos as $emprestimo)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $emprestimo->book->title }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($emprestimo->loan_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($emprestimo->due_date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($emprestimo->status === 'ativo')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-50 rounded">Ativo</span>
                                    @elseif($emprestimo->status === 'concluido')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded">Devolvido</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded">Atrasado</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $todosEmprestimos->links() }}
            </div>
        @else
            <div class="py-20 text-center">
                <p class="text-gray-500">Seu histórico de empréstimos está vazio.</p>
            </div>
        @endif
    </div>
</div>

<script>
    function showTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'font-bold', 'text-gray-900', 'border-gray-900');
            button.classList.add('font-semibold', 'text-gray-400', 'border-transparent');
        });
        
        // Show selected tab
        document.getElementById('content-' + tab).classList.remove('hidden');
        
        // Add active class to selected button
        const activeButton = document.getElementById('tab-' + tab);
        activeButton.classList.add('active', 'font-bold', 'text-gray-900', 'border-gray-900');
        activeButton.classList.remove('font-semibold', 'text-gray-400', 'border-transparent');
    }
</script>
@endsection