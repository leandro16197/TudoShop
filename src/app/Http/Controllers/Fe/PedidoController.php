<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\PedidoProducto;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function agregarProducto(Request $request)
    {   

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad'    => 'required|integer|min:1',
            'replace'     => 'nullable|boolean' 
        ]);

        $user = Auth::user(); 
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $pedido = Pedido::firstOrCreate(
            [
                'user_id' => $user->id,
                'estado'  => 'pendiente'
            ],
            [
                'email' => $user->email
            ]
        );


        $detalle = PedidoProducto::where('pedido_id', $pedido->id)
            ->where('producto_id', $request->producto_id)
            ->first();

        if ($detalle) {
            if ($request->replace) {
                $detalle->cantidad = $request->cantidad;
            } else {
                $detalle->cantidad += $request->cantidad;
            }
            $detalle->save();
        } else {
            PedidoProducto::create([
                'pedido_id'   => $pedido->id,
                'producto_id' => $request->producto_id,
                'cantidad'    => $request->cantidad
            ]);
        }

        return response()->json([
            'ok' => true,
            'pedido_id' => $pedido->id,
            'message' => 'Producto procesado correctamente'
        ]);
    }

    public function obtenerCarrito($id = null)
    {
        $user = Auth::user();

        if ($id) {

            $pedido = Pedido::where('id', $id)
                ->where('user_id', $user->id)
                ->with(['productos.imagenes', 'envio'])
                ->first();
        } else {
            $pedido = Pedido::where('user_id', $user->id)
                ->where('estado', 'pendiente')
                ->with(['productos.imagenes', 'envio'])
                ->first();
        }

        if (!$pedido) {
            return response()->json([
                'productos' => [],
                'subtotal' => 0,
                'costo_envio' => 0,
                'total' => 0,
                'usuario' => [
                    'email' => $user->email,
                    'nombre' => $user->name
                ]
            ], 404); 
        }

        $subtotal = 0;

        $productosFormateados = $pedido->productos->map(function ($producto) use (&$subtotal) {
            $cantidad = $producto->pivot ? $producto->pivot->cantidad : 0;
            $precio = $producto->price;
            $subtotal += ($precio * $cantidad);

            $primeraImagen = $producto->imagenes->first();
            $urlImagen = $primeraImagen 
                ? asset('storage/' . $primeraImagen->imagen) 
                : asset('images/placeholder.png');

            return [
                'id'          => $producto->id,
                'nombre'      => $producto->name,  
                'precio'      => $precio, 
                'imagen'      => $urlImagen, 
                'cantidad'    => $cantidad,
                'total_linea' => $precio * $cantidad
            ];
        });

        $costoEnvio = 0;
        $datosEnvioGuardados = $pedido->envio;

        if ($datosEnvioGuardados) {
            $config = \DB::table('configuracions')->select('dato')->where('clave', 'codigo_postal')->first();
            $cpTienda = $config ? $config->dato : null;

            if ($cpTienda && $datosEnvioGuardados->cp !== $cpTienda) {
                $costoEnvio = 1000 + ($subtotal * 0.10);
            } else {
                $costoEnvio = 1000;
            }
        }

        return response()->json([
            'usuario'     => [
                'email'  => $user->email, 
                'nombre' => $user->name
            ],
            'productos'   => $productosFormateados,
            'pedido_id'   => $pedido->id,
            'estado'      => $pedido->estado, 
            'subtotal'    => round($subtotal, 2),
            'costo_envio' => round($costoEnvio, 2),
            'total'       => round($subtotal + $costoEnvio, 2),
            'datos_envio' => $datosEnvioGuardados 
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
    public function calcularEnvio(Request $request)
    {
        $request->validate([
            'cp_cliente' => 'required|string',
            'subtotal' => 'required|numeric'
        ]);

        $cpCliente = $request->input('cp_cliente');
        $subtotal = $request->input('subtotal');
        $configuracion = Configuracion::where('clave', 'codigo_postal')->first();
        $cpTienda = $configuracion ? $configuracion->dato : '7000';
        $costoEnvio = 0;

    
        if ($cpCliente === $cpTienda) {
            $costoEnvio = 0;
        } elseif (substr($cpCliente, 0, 2) === substr($cpTienda, 0, 2)) {
            $costoEnvio = 1500;
        } else {
            $costoEnvio = 3000 + ($subtotal * 0.02);
        }

        return response()->json([
            'envio' => $costoEnvio,
            'total' => $subtotal + $costoEnvio,
            'gratis' => ($costoEnvio == 0),
            'cp_tienda_usado' => $cpTienda 
        ]);
    }

    public function finalizarPedido(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'datos_envio' => 'required|array',
            'metodo_pago' => 'required'
        ]);

        $pedido = Pedido::find($request->pedido_id);

        $pedido->detalle_envio = json_encode($request->datos_envio);
        $pedido->estado = 'procesando';
        $pedido->save();

        return response()->json(['res' => true, 'mensaje' => '¡Pedido realizado con éxito!']);
    }

    public function misPedidos()
    {
        $user = Auth::user();

        $pedidos = Pedido::where('user_id', $user->id)
            ->whereIn('estado', ['pendiente', 'pagado', 'rechazado'])
            ->with(['productos.imagenes', 'envio'])
            ->orderBy('created_at', 'desc') 
            ->paginate(6);


        $pedidos->getCollection()->transform(function ($pedido) {
            $subtotal = 0;
            $productosFormateados = $pedido->productos->map(function ($producto) use (&$subtotal) {
                $cantidad = $producto->pivot ? $producto->pivot->cantidad : 0;
                $precio = $producto->price;
                $subtotal += ($precio * $cantidad);

                $primeraImagen = $producto->imagenes->first();
                return [
                    'id'       => $producto->id,
                    'nombre'   => $producto->name,
                    'precio'   => $precio,
                    'imagen'   => $primeraImagen ? asset('storage/' . $primeraImagen->imagen) : asset('images/placeholder.png'),
                    'cantidad' => $cantidad,
                    'total_linea' => $precio * $cantidad
                ];
            });

            return [
                'pedido_id'        => $pedido->id,
                'estado'           => ucfirst($pedido->estado),
                'total'            => round($subtotal + ($pedido->envio ? 1000 : 0), 2),
                'productos'        => $productosFormateados,
                'fecha_formateada' => $pedido->created_at->format('d/m/Y'),
            ];
        });

        return response()->json($pedidos);
    }
}