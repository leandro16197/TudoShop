<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Marca;
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
        $product = Product::with(['imagenes', 'categorias'])->findOrFail($id);

        return response()->json([
            'id'          => $product->id,
            'name'        => $product->name,
            'price'       => $product->price,
            'stock'       => $product->stock,
            'description' => $product->description,
            'categorias'  => $product->categorias->map(fn ($cat) => [
                'id'   => $cat->id,
                'name' => $cat->name
            ]),
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


    public function byCategory($categoriaId)
    {
        $productos = Product::whereHas('categorias', function ($query) use ($categoriaId) {
                $query->where('categorias.id', $categoriaId);
            })
            ->with('imagenes')
            ->take(12)
            ->get();

        return $productos->map(function ($producto) {

            $imagen = $producto->imagenes->first()
                ? asset('storage/' . $producto->imagenes->first()->imagen)
                : asset('images/no-image.webp');

            return [
                'id'          => $producto->id,
                'nombre'      => $producto->name,
                'descripcion' => $producto->description,
                'precio'      => (float) $producto->price,
                'activo'      => (bool) $producto->active,
                'imagen'      => $imagen,
            ];
        });
    }

    public function catalogo(Request $request)
    {
        $query = Product::with('imagenPrincipal', 'categorias')
            ->where('active', 1);

        //Filtro por texto
        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $categoriaId = $request->categoria;

            $query->whereHas('categorias', function ($q) use ($categoriaId) {
                $q->where('categorias.id', $categoriaId);
            });
        }

        // Filtro por precio mínimo
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        // Filtro por precio máximo
        if ($request->filled('max_price') && $request->max_price > 0) {
            $query->where('price', '<=', (float) $request->max_price);
        }
        
        // Filtro por marca
        if ($request->filled('marca')) {
            $marcaId = $request->marca;
            $query->whereHas('marcas', function ($q) use ($marcaId) {
                $q->where('marcas.id', $marcaId);
            });
        }

        //Ordenamiento
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'precio_asc':
                    $query->orderBy('price', 'asc');
                    break;

                case 'precio_desc':
                    $query->orderBy('price', 'desc');
                    break;

                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        //Paginación
        $productos = $query->paginate(12);
        $productos->getCollection()->transform(function ($producto) {

            $imagen = $producto->imagenPrincipal
                ? asset('storage/' . $producto->imagenPrincipal->imagen)
                : null;

            return [
                'id' => $producto->id,
                'nombre' => $producto->name,
                'descripcion' => $producto->description,
                'precio' => (float) $producto->price,
                'activo' => (bool) $producto->active,
                'stock' =>$producto->stock,
                'imagen' => $imagen,
            ];
        });

        return response()->json($productos);
    }

}
