<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;

class PagosController extends Controller
{
    public function pagar(Request $request)
    {
        $configs = DB::table('configuracions')
            ->whereIn('clave', ['mp_access_token', 'codigo_postal'])
            ->get()
            ->pluck('dato', 'clave');

        if (!isset($configs['mp_access_token'])) {
            return response()->json(['error' => 'Configuración de Mercado Pago no encontrada'], 500);
        }

        MercadoPagoConfig::setAccessToken($configs['mp_access_token']);
        $pedido = Pedido::with(['productos', 'envio'])
            ->where('id', $request->id_pedido)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $client = new PreferenceClient();
        $items = [];
        $subtotal = 0;

        foreach ($pedido->productos as $producto) {
            $precio = round((float) $producto->price, 2);
            $cantidad = (int) $producto->pivot->cantidad;

            if ($precio > 0 && $cantidad > 0) {
                $items[] = [
                    "title" => (string) $producto->name,
                    "quantity" => $cantidad,
                    "unit_price" => $precio,
                    "currency_id" => "ARS"
                ];
                $subtotal += ($precio * $cantidad);
            }
        }

        if ($pedido->envio) {
            $cpTienda = $configs['codigo_postal'] ?? '7000';
            $costoCalculado = ($pedido->envio->cp !== $cpTienda) 
                ? 1000 + ($subtotal * 0.10) 
                : 1000;
            
            $items[] = [
                "title" => "Costo de Envío",
                "quantity" => 1,
                "unit_price" => round((float) $costoCalculado, 2),
                "currency_id" => "ARS"
            ];
        }

        try {
            if (empty($items)) {
                throw new \Exception("El carrito está vacío.");
            }

            $preference_data = [
                "items" => $items,
                "back_urls" => [
                    "success" => url('/checkout/success'), 
                    "failure" => url('/checkout/error'),
                    "pending" => url('/checkout/error')
                ],
                "auto_return" => "approved",
                "external_reference" => (string) $pedido->id,
                "binary_mode" => true,
                "statement_descriptor" => "SHOPTUDO",
            ];
            
            $preference = $client->create($preference_data);

            return response()->json([
                "status" => "success",
                "init_point" => $preference->init_point
            ]);

        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            $response = $e->getApiResponse();
            $content = $response ? $response->getContent() : [];
            $message = isset($content['message']) ? $content['message'] : $e->getMessage();

            if (str_contains($message, 'auto_return')) {
                unset($preference_data['auto_return']);
                $preference = $client->create($preference_data);
                
                return response()->json([
                    "status" => "success",
                    "init_point" => $preference->init_point,
                    "warning" => "Retorno automático desactivado por restricciones de la cuenta."
                ]);
            }

            return response()->json([
                'error_message' => 'Error de validación en Mercado Pago (API)',
                'detail' => $content,
                'debug_data' => [
                    'items_enviados' => $items,
                    'total' => $subtotal
                ]
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error_message' => 'Error general',
                'detail' => $e->getMessage()
            ], 500);
        }
    }
    public function webhook(Request $request)
    {
     

        $token = DB::table('configuracions')->where('clave', 'mp_access_token')->value('dato');
        
        if (!$token) {

            return response()->json(['error' => 'Configuración no encontrada'], 500);
        }

        MercadoPagoConfig::setAccessToken($token);

        $type = $request->input('type') ?? $request->input('topic');
        $paymentId = $request->input('data.id') ?? $request->input('id');
        
        if ($paymentId == "123456") {
            return response()->json(['status' => 'simulacion_ok'], 200);
        }

        if ($type === 'payment' && $paymentId) {
            try {
                $client = new PaymentClient();
                $payment = $client->get($paymentId);
                $pedidoId = $payment->external_reference;
                
         

                if ($payment->status === 'approved' && $pedidoId) {
                    $actualizado = DB::table('pedidos')
                        ->where('id', $pedidoId)
                        ->update([
                            'estado' => 'pagado',
                            'updated_at' => now()
                        ]);

                    if ($actualizado) {

                    }
                    
                    return response()->json(['status' => 'success'], 200);
                }
            } catch (\Exception $e) {
                return response()->json(['error_handled' => $e->getMessage()], 200);
            }
        }

        return response()->json(['status' => 'received'], 200);
    }

        public function confirmarPagoManual(Request $request)
        {
            if ($request->status === 'approved') {
                try {
                    DB::transaction(function () use ($request) {

                        $pedido = Pedido::with('productos')
                            ->where('id', $request->pedido_id)
                            ->where('estado', 'pendiente')
                            ->first();

                        if ($pedido) {
                            foreach ($pedido->productos as $producto) {
                                $cantidadComprada = $producto->pivot->cantidad;

                                DB::table('productos')
                                    ->where('id', $producto->id)
                                    ->decrement('stock', $cantidadComprada);
                            }
                            DB::table('pedidos')
                                ->where('id', $request->pedido_id)
                                ->update([
                                    'estado' => 'pagado',
                                    'payment_id' => $request->payment_id,
                                    'updated_at' => now()
                                ]);
                        }
                    });

                    return response()->json(['message' => 'Pago confirmado y stock descontado con éxito']);

                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Error al procesar el stock',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }

            return response()->json(['message' => 'El pago no está aprobado'], 400);
        }
}