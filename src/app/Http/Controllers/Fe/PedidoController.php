<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\PedidoProducto;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function agregarProducto(Request $request)
    {   
        \Log::debug($request->all());
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);
        $user = Auth::user(); 
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $pedido = Pedido::firstOrCreate(
            [
                'user_id' => $user->id,
                'estado' => 'pendiente'
            ],
            [
                'email' => $user->email
            ]
        );

        $detalle = PedidoProducto::where('pedido_id', $pedido->id)
            ->where('producto_id', $request->producto_id)
            ->first();

        if ($detalle) {
            $detalle->cantidad += $request->cantidad;
            $detalle->save();
        } else {
            PedidoProducto::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $request->producto_id,
                'cantidad' => $request->cantidad
            ]);
        }

        return response()->json([
            'ok' => true,
            'pedido_id' => $pedido->id
        ]);
    }

    public function obtenerCarrito()
    {
        $user = Auth::user();

        $pedido = Pedido::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->with('productos.imagenes') 
            ->first();

        if (!$pedido) {
            return response()->json(['productos' => []]);
        }

        $productosFormateados = $pedido->productos->map(function ($producto) {

            $primeraImagen = $producto->imagenes->first();

            $urlImagen = $primeraImagen 
                ? asset('storage/' . $primeraImagen->imagen) 
                : asset('images/placeholder.png');

            return [
                'id'       => $producto->id,
                'nombre'   => $producto->name,  
                'precio'   => $producto->price, 
                'imagen'   => $urlImagen, 
                'cantidad' => $producto->pivot ? $producto->pivot->cantidad : 0,
            ];
        });

        return response()->json([
            'productos' => $productosFormateados,
            'pedido_id' => $pedido->id
        ]);
    }

    public function actualizarCantidad(Request $request)
    { 
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $user = Auth::user();

        $pedido = Pedido::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->first();

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $pedido->productos()->updateExistingPivot($request->producto_id, [
            'cantidad' => $request->cantidad
        ]);

        return response()->json(['ok' => true]);
    }

    public function eliminarProducto($productoId)
    {
        $user = Auth::user();

        $pedido = Pedido::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->first();

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }
        $pedido->productos()->detach($productoId);

        return response()->json(['ok' => true]);
    }
}