<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');
        
        // Filtro por categoria
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtro por disponibilidade
        if ($request->filled('disponivel')) {
            $query->where('available_quantity', '>', 0);
        }
        
        // Busca por título e autor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('authors', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $livros = $query->paginate(12);
        $categorias = Category::all();
        
        return view('livros.index', compact('livros', 'categorias'));
    }
    
    public function show($id)
{
    $livro = Book::with(['authors', 'category', 'loans', 'reservations'])->findOrFail($id);
    
    // Livros similares (mesma categoria)
    $livrosSimilares = Book::where('category_id', $livro->category_id)
        ->where('id', '!=', $livro->id)
        ->limit(4)
        ->get();
    
    return view('livros.show', compact('livro', 'livrosSimilares'));
}
}