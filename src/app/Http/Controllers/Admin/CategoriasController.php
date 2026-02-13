<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriasController extends Controller
{   
     protected $viewPath = 'admin.productos.categorias';

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $categorias = Categoria::select('id', 'nombre', 'imagen')
                ->get()
                ->map(function ($categoria) {

                    return [
                        'id' => $categoria->id,
                        'nombre' => $categoria->nombre,
                        'imagen' => $categoria->imagen
                            ? asset('storage/' . $categoria->imagen)
                            : null,
                    ];
                });

            return response()->json([
                'data' => $categorias
            ]);
        }

        return view("{$this->viewPath}.categorias");
    }

    public function store(Request $request)
    {  
        $request->validate([
            'nombre' => 'required|string|max:255',
            'images.*'     => 'nullable|image|max:5120',
        ]);

        $data = [
            'nombre' => $request->nombre,
        ];

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')
                ->store('categorias', 'public');
        }

        Categoria::create($data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Categoría creada correctamente',
        ]);
    }

    public function update(Request $request, $id)
    {   
        $request->validate([
            'nombre' => 'required|string|max:255',
            'images.*'     => 'nullable|image|max:5120',
        ]);
        $categoria = Categoria::findOrFail($id);

        $data = [
            'nombre' => $request->nombre,
        ];

        if ($request->hasFile('imagen')) {
            if ($categoria->imagen &&
                Storage::disk('public')->exists($categoria->imagen)) {

                Storage::disk('public')->delete($categoria->imagen);
            }

            $data['imagen'] = $request->file('imagen')
                ->store('categorias', 'public');
        }

        $categoria->update($data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Categoría actualizada correctamente',
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
    public function lista()
    {
        $categorias = Categoria::select('id', 'nombre')->get();

        return response()->json([
            'data' => $categorias
        ]);
    }
}
