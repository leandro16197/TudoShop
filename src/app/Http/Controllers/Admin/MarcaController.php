<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{

    protected $viewPath = 'admin.productos.marcas';

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $marcas = Marca::select('id', 'nombre', 'img')
                ->get()
                ->map(function ($marca) {
                    return [
                        'id'     => $marca->id,
                        'nombre' => $marca->nombre,
                        'img'    => $marca->img 
                                    ? asset('storage/'.$marca->img)
                                    : null,
                    ];
                });

            return response()->json([
                'data' => $marcas
            ]);
        }

        return view("{$this->viewPath}.marcas");
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:marcas,nombre',
            'img' => 'nullable|image|max:5120',
            'activa' => 'nullable|boolean'
        ]);

        $marca = new Marca();
        $marca->nombre = $validated['nombre'];
        $marca->activa = $request->boolean('activa');

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('marcas', 'public');
            $marca->img = $path;
        }

        $marca->save();

        return response()->json([
            'success' => true,
            'message' => 'Marca creada correctamente',
        ], 201);
    }



    public function show(Marca $marca)
    {
        return response()->json($marca);
    }


    public function update(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:marcas,nombre,' . $id,
            'img' => 'nullable|image|max:5120',
        ]);

        $marca->nombre = $validated['nombre'];

        if ($request->hasFile('img')) {

            if ($marca->img && Storage::disk('public')->exists($marca->img)) {
                Storage::disk('public')->delete($marca->img);
            }

            $path = $request->file('img')->store('marcas', 'public');
            $marca->img = $path;
        }

        $marca->save();

        return response()->json([
            'success' => true,
            'message' => 'Marca actualizada correctamente',
        ]);
    }

    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);

        $marca->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Marca eliminada correctamente'
        ]);
    }
}
