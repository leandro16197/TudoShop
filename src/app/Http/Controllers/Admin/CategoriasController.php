<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{   
     protected $viewPath = 'admin.productos.categorias';

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $categoria = Categoria::select('id', 'nombre')
                ->get();

            return response()->json([
                'data' => $categoria
            ]);
        }

        return view("{$this->viewPath}.categorias");
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        if ($request->filled('id')) {
            $categoria = Categoria::findOrFail($request->id);
            $categoria->update([
                'nombre' => $request->nombre,
            ]);

            return response()->json([
                'status' => 'ok',
                'message' => 'Categoría actualizada correctamente',
            ]);
        }

        Categoria::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Categoría creada correctamente',
        ]);
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        $categoria->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Categoría eliminada correctamente'
        ]);
    }
}
