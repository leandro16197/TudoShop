<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Models\Pedido;

class PedidosController extends Controller
{
    protected $viewPath = 'admin.pedidos.pedidos';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pedidos = Pedido::with(['cliente', 'pago'])->latest()->get()->map(function ($pedido) {
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

            return response()->json(['data' => $pedidos]);
        }

        return view("{$this->viewPath}.pedidos");
    }

}
