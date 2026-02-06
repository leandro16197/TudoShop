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

        $products = Product::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get(['id', 'name', 'description', 'price', 'stock', 'active']);

        return response()->json($products);
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

}
