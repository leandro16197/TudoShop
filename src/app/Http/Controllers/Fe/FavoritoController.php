<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Cliente $user */
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        $favoritos = $user->favoritos()
            ->with(['imagenes', 'marcas']) 
            ->get()
            ->map(function ($product) {
                return [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'price' => (float) $product->price,
                    'image' => $product->imagenes->first() 
                        ? asset('storage/' . $product->imagenes->first()->imagen) 
                        : asset('images/placeholder.png'),
                    'marca' => $product->marcas ? $product->marcas->nombre : 'Sin marca',
                ];
            });

        return response()->json($favoritos);
    }
    

    public function toggle($id)
    {
        /** @var Cliente $user */
        $user = Auth::user(); 

        if (!$user instanceof Cliente) {
            return response()->json(['message' => 'Sesión inválida'], 401);
        }

        $producto = Product::findOrFail($id);
        $status = $user->favoritos()->toggle($producto->id);

        return response()->json([
            'status' => 'success',
            'is_favorite' => count($status['attached']) > 0,
            'message' => count($status['attached']) > 0 ? 'Agregado' : 'Eliminado'
        ]);
    }
}
