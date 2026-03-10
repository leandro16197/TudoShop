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

        $ventasHoy = Pagos::whereDate('created_at', $hoy)->sum('total');
        $pedidosHoy = Pedido::whereDate('created_at', $hoy)->count();
        $clientesHoy = Cliente::whereDate('created_at', $hoy)->count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $topProductos = PedidoProducto::selectRaw('productos.name, SUM(pedidos_productos.cantidad) as total_vendidos')
            ->join('productos', 'pedidos_productos.producto_id', '=', 'productos.id')
            ->groupBy('productos.name')
            ->orderByDesc('total_vendidos')
            ->limit(3)
            ->get();

        return response()->json([
            'ventas_hoy' => $ventasHoy,
            'pedidos_hoy' => $pedidosHoy,
            'clientes_hoy' => $clientesHoy,
            'pedidos_pendientes' => $pedidosPendientes,
            'top_productos' => $topProductos
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
