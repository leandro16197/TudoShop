<?php

namespace App\Http\Controllers\FE;

use App\Http\Controllers\Controller;
use App\Models\EnvioPedido;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function guardarEnvio(Request $request)
    {
        $validated = $request->validate([
            'pedido_id'           => 'required|exists:pedidos,id',
            'cp'                  => 'required|string',
            'localidad'           => 'required|string',
            'direccion'           => 'required|string',
            'nombre_destinatario' => 'required|string',
            'telefono'            => 'required|string',
        ]);


        $envio = EnvioPedido::updateOrCreate(
            ['pedido_id' => $validated['pedido_id']],
            $validated
        );

        return response()->json([
            'status' => 'success',
            'data'   => $envio
        ], 200);
    }
}
