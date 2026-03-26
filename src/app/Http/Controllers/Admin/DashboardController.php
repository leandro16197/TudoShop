<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Pagos;
use App\Models\PedidoProducto;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function metricas()
    {
        $hoy = Carbon::today();
        $haceUnaSemana = Carbon::now()->subDays(7);

        $pedidosHoy = Pedido::whereDate('created_at', $hoy)->count();
        $clientesHoy = Cliente::whereDate('created_at', $hoy)->count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();   
        $productosBajoStock = Product::where('stock', '<=', 5)
            ->select('name', 'stock') 
            ->get();
        $topProductos = PedidoProducto::selectRaw('productos.name, SUM(pedidos_productos.cantidad) as total_vendidos')
            ->join('productos', 'pedidos_productos.producto_id', '=', 'productos.id')
            ->where('pedidos_productos.created_at', '>=', $haceUnaSemana)
            ->groupBy('productos.name', 'productos.id')
            ->orderByDesc('total_vendidos')
            ->limit(3)
            ->get();

        return response()->json([
            'pedidos_hoy'        => $pedidosHoy,
            'clientes_nuevos'    => $clientesHoy,
            'pedidos_pendientes' => $pedidosPendientes,
            'stock_critico'      => $productosBajoStock->count(),
            'productos_detalles' => $productosBajoStock,        
            'top_productos'      => $topProductos
        ]);
    }
    public function ventasMes()
    {
        $start = Carbon::now()->subDays(29)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $ventas = Pagos::selectRaw("
                DATE(created_at) as fecha,
                COUNT(*) as pedidos,
                SUM(total) as total
            ")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $dias = [];

        for ($i = 0; $i < 30; $i++) {
            $fecha = Carbon::now()->subDays(29 - $i)->format('Y-m-d');

            $dias[] = [
                'fecha' => $fecha,
                'pedidos' => $ventas[$fecha]->pedidos ?? 0,
                'total' => $ventas[$fecha]->total ?? 0
            ];
        }

        return response()->json($dias);
    }
}
