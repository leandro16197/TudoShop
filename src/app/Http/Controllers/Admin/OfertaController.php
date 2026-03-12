<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Ofertas;
use Illuminate\Http\Request;



class OfertaController extends Controller
{   
    protected $viewPath = 'admin.promociones.ofertas';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ofertas = Ofertas::with([
                'aplicaciones.marca:id,nombre', 
                'aplicaciones.categoria:id,nombre'
            ])
            ->select('id', 'nombre', 'descripcion', 'fecha_desde', 'fecha_hasta')
            ->get();

            return response()->json([
                'data' => $ofertas
            ]);
        }

        return view("{$this->viewPath}.ofertas");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date',
            'porcentaje' => 'nullable|numeric',
            'cantidad_minima' => 'nullable|integer',
        ]);

        $oferta = Ofertas::create($request->only(['nombre', 'descripcion', 'fecha_desde', 'fecha_hasta']));
        $oferta->aplicaciones()->create([
            'marca_id' => $request->marca_id,
            'categoria_id' => $request->categoria_id,
            'porcentaje' => $request->porcentaje,
            'cantidad_minima' => $request->cantidad_minima ?? 1,
        ]);

        return redirect()->back()->with('success', 'Oferta creada con éxito');
    }

    public function update(Request $request, $id)
    {
        $oferta = Ofertas::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
            'porcentaje' => 'nullable|numeric|min:0|max:100',
            'cantidad_minima' => 'nullable|integer|min:1',
        ]);
        $oferta->update($request->only(['nombre', 'descripcion', 'fecha_desde', 'fecha_hasta']));
        $oferta->aplicaciones()->updateOrCreate(
            ['oferta_id' => $oferta->id], 
            [
                'marca_id' => $request->marca_id,
                'categoria_id' => $request->categoria_id,
                'porcentaje' => $request->porcentaje,
                'cantidad_minima' => $request->cantidad_minima ?? 1,
            ]
        );

        return response()->json([
            'message' => 'Oferta actualizada exitosamente',
        ], 200);
    }

    public function destroy($id)
    {
        $oferta = Ofertas::findOrFail($id);
        $oferta->delete();

        return response()->json([
            'message' => 'Oferta eliminada exitosamente',
        ], 200);
    }

    public function relaciones()
    {
        return response()->json([
            'marcas' => Marca::all(['id', 'nombre']),
            'categorias' => Categoria::all(['id', 'nombre']),
        ]);
    }
}
