<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\CategoriaProducto;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Imagen;
use App\Models\Marca;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{
    protected $viewPath = 'admin.productos.productos';


    public function index(Request $request)
    {   \Log::debug($request);
        if ($request->ajax()) {
            $query = Product::with(['imagenPrincipal', 'categorias', 'marcas'])
                ->select('id', 'name', 'description', 'price', 'stock', 'active');

            if ($request->filled('stock')) {
                if ($request->stock === 'con_stock') {
                    $query->where('stock', '>', 0);
                } elseif ($request->stock === 'sin_stock') {
                    $query->where('stock', '<=', 0);
                }
            }
            if ($request->has('search') && !empty($request->input('search')['value'])) {
                $searchValue = $request->input('search')['value'];
                $query->where('name', 'LIKE', "%{$searchValue}%");
            }
            if ($request->filled('categoria_id')) {
                $query->whereHas('categorias', function ($q) use ($request) {
                    $q->where('categorias.id', $request->categoria_id);
                });
            }
            if ($request->filled('marca_id')) {
                $query->whereHas('marcas', function ($q) use ($request) {
                    $q->where('marcas.id', $request->marca_id);
                });
            }

            $products = $query->get()->map(function ($product) {
                return [
                    'id'           => $product->id,
                    'name'         => $product->name,
                    'description'  => $product->description,
                    'price'        => $product->price,
                    'stock'        => $product->stock,
                    'active'       => $product->active,
                    'image'        => $product->imagenPrincipal 
                                        ? asset('storage/' . $product->imagenPrincipal->imagen) 
                                        : null,
                    'categoria_id' => $product->categorias->first()?->id,
                    'categorias'   => $product->categorias->pluck('nombre')->implode(', '),
                    'marca_id'     => $product->marcas->first()?->id, 
                    'marcas'       => $product->marcas->pluck('nombre')->implode(', '),
                ];
            });

            return response()->json(['data' => $products]);
        }

        $categorias = Categoria::orderBy('nombre')->get();
        $marcas = Marca::orderBy('nombre')->get();

        return view("{$this->viewPath}.productos", compact('categorias', 'marcas'));
    }



    public function create()
    {
        return view("{$this->viewPath}.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'active'       => 'nullable',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id'     => 'required|exists:marcas,id', 
            'images.*'     => 'nullable|image|max:5120',
        ]);

        $product = Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'active'      => $request->has('active'),
        ]);

        $product->categorias()->sync([$request->categoria_id]);
        $product->marcas()->sync([$request->marca_id]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('productos', 'public');
                Imagen::create([
                    'producto_id' => $product->id,
                    'imagen'      => $path,
                ]);
            }
        }

        $product->load(['imagenes', 'categorias', 'marcas']);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Producto creado correctamente',
            'product' => $product,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->merge([
            'active' => filter_var($request->active, FILTER_VALIDATE_BOOLEAN),
        ]);

        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'active'       => 'nullable|boolean',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id'     => 'required|exists:marcas,id', 
            'images.*'     => 'nullable|image|max:5120',
        ]);

        $product->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'active'      => $request->active ?? false,
        ]);

        $product->categorias()->sync([$request->categoria_id]);
        $product->marcas()->sync([$request->marca_id]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('productos', 'public');
                Imagen::create([
                    'producto_id' => $product->id,
                    'imagen'      => $path,
                ]);
            }
        }

        $product->load(['imagenes', 'categorias', 'marcas']);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Producto actualizado correctamente',
            'product' => $product,
        ]);
    }



    public function destroy($id)
    {
        $product = Product::with('imagenes')->findOrFail($id);

        foreach ($product->imagenes as $imagen) {
            if ($imagen->imagen && Storage::disk('public')->exists($imagen->imagen)) {
                Storage::disk('public')->delete($imagen->imagen);
            }
        }

        $product->imagenes()->delete();

        $product->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Producto eliminado correctamente'
        ]);
    }
}
