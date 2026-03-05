<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class FeCategoriasController extends Controller
{
    public function index()
    {
        $categorias = Categoria::select('id', 'nombre', 'imagen')
            ->orderBy('nombre')
            ->get()
            ->map(function ($categoria) {
                return [
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'imagen' => $categoria->imagen ? asset('storage/' . $categoria->imagen) : null,
                ];
            });

        return response()->json($categorias);
    }
    
}