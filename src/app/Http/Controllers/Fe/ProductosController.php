<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $productos = Product::with('imagenPrincipal')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($producto) {
                $imagen = $producto->imagenPrincipal
                    ? asset('storage/' . $producto->imagenPrincipal->imagen)
                    : null;

                return [
                    'id' => $producto->id,
                    'nombre' => $producto->name,
                    'descripcion' => $producto->description,
                    'precio' => (float) $producto->price,
                    'activo' => (bool) $producto->active,
                    'imagen' => $imagen,
                ];
            });

        return response()->json($productos);
    }


    public function detail($id)
    {
        $product = Product::with('imagenes')->findOrFail($id);

        return response()->json([
            'id'          => $product->id,
            'name'        => $product->name,
            'price'       => $product->price,
            'stock'       => $product->stock,
            'description' => $product->description,
            'images'      => $product->imagenes->map(fn ($img) =>
                asset('storage/' . $img->imagen)
            ),
            'features'    => [
                'Marca' => 'Paper Mate', 
            ],
        ]);
    }

    public function featured()
    {
        $productos = Product::with('imagenPrincipal')
            ->where('active', 1)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($producto) {

                $imagen = $producto->imagenPrincipal
                    ? asset('storage/' . $producto->imagenPrincipal->imagen)
                    : null;

                return [
                    'id' => $producto->id,
                    'nombre' => $producto->name,
                    'precio' => (float) $producto->price,
                    'imagen' => $imagen,
                ];
            });

        return response()->json($productos);
    }



}
