<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ofertas;
use Illuminate\Http\Request;



class OfertaController extends Controller
{   
    protected $viewPath = 'admin.promociones.ofertas';

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $products = Ofertas::select('id', 'nombre', 'descripcion', 'fecha_desde', 'fecha_hasta')
                ->get();
            return response()->json([
                'data' => $products
            ]);
        }

        return view("{$this->viewPath}.ofertas");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        $oferta = Ofertas::create($data);

        return response()->json([
            'message' => 'Oferta creada exitosamente',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $oferta = Ofertas::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        $oferta->update($data);

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
}
