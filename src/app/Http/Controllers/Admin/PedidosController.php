<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ExportarPedidosJob;
use App\Models\Configuracion;
use App\Models\Pedido;
use Illuminate\Support\Facades\Cache;
class PedidosController extends Controller
{
    protected $viewPath = 'admin.pedidos.pedidos';

    public function index(Request $request)
    {   
        $esParaExcel = $request->input('exportar');
        if ($esParaExcel) {
            \App\Jobs\ExportarPedidosJob::dispatch(static::class, $request->all(), true);
            return $this->exportProgressPedidos();
        }

        if ($request->ajax()) {
            $result = self::obtenerPedidos($request);
            
            return response()->json([
                'data'            => $result['data'],
                'recordsTotal'    => $result['total'],
                'recordsFiltered' => $result['total'],
            ]);
        }
        return view("{$this->viewPath}.pedidos");
    }

    public static function obtenerPedidos($request, $esExcel = false)
    {
        $query = Pedido::with(['cliente', 'pago']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('created_at', [$request->desde, $request->hasta]);
        } elseif ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        } elseif ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        $total = $query->count();

        $data = $query->latest()->get()->map(function ($pedido) {
            return [
                'id'          => $pedido->id,
                'email'       => $pedido->email,
                'estado'      => $pedido->estado,
                'cliente'     => $pedido->cliente ? $pedido->cliente->nombre : 'N/A',
                'total'       => $pedido->pago ? $pedido->pago->total : '0.00',
                'transaccion' => $pedido->pago ? $pedido->pago->numero_transaccion : 'Pendiente',
                'fecha'       => $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : '-',
            ];
        });

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    
    private function exportProgressPedidos()
    {
        return response()->json([
            'status'  => 'success',
            'message' => 'Tu exportación está en proceso. Podrás descargarla en unos instantes desde la sección de reportes.',
            'reload'  => false
        ]);
    }

    public function checkProgress(Request $request)
    {
        $userId = 1; 
        $data = Cache::get("export_progress_{$userId}", ['status' => 'Iniciando', 'progress' => 0, 'url' => null]);
        
        return response()->json($data);
    }
}
