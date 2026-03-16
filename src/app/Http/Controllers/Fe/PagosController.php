<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Pagos;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use App\Traits\CalculadorDescuentos;

class PagosController extends Controller
{
    use CalculadorDescuentos;

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
        $pedido = Pedido::with(['productos.marcas', 'productos.categorias', 'envio'])
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
            $precioFinal = $this->calcularPrecioFinal($producto, $cantidad);
            if ($precio > 0 && $cantidad > 0) {
                $items[] = [
                    "title" => (string) $producto->name,
                    "quantity" => $cantidad,
                    "unit_price" => round((float) $precioFinal, 2),
                    "currency_id" => "ARS"
                ];
                $subtotal += ($precioFinal * $cantidad);
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
        \Log::debug("entro al webhook");

        $token = DB::table('configuracions')->where('clave', 'mp_access_token')->value('dato');

        if (!$token) {

            return response()->json(['error' => 'Configuración no encontrada'], 500);
        }

        MercadoPagoConfig::setAccessToken($token);

        $type = $request->input('type') ?? $request->input('topic');
        $paymentId = $request->input('data.id') ?? $request->input('id');

        if ($type === 'payment' && $paymentId) {
            try {
                $client = new PaymentClient();
                $payment = $client->get($paymentId);
                $pedidoId = $payment->external_reference;



                if ($type === 'payment' && $paymentId) {
                    $payment = $client->get($paymentId);
                    if ($payment->status === 'approved') {
                        $this->procesarPagoAprobado($payment->external_reference, $paymentId);
                    }
                }
            } catch (\Exception $e) {
                return response()->json(['error_handled' => $e->getMessage()], 200);
            }
        }

        return response()->json(['status' => 'received'], 200);
    }

    public function confirmarPagoManual(Request $request)
    {
        /*if ($request->status !== 'approved') {
            return response()->json(['message' => 'El pago no está aprobado'], 400);
        }

        try {
            return DB::transaction(function () use ($request) {
                $pedido = Pedido::with(['productos.marcas', 'productos.categorias']) 
                    ->where('id', $request->pedido_id)
                    ->where('estado', 'pendiente')
                    ->lockForUpdate()
                    ->first();

                if (!$pedido) {
                    return response()->json(['message' => 'Pedido no encontrado o ya procesado'], 200);
                }

                $totalCalculado = 0;

                foreach ($pedido->productos as $producto) {
                    $cantidad = (int) $producto->pivot->cantidad;
                    $precioFinal = $this->calcularPrecioFinal($producto, $cantidad);
                    $totalCalculado += ($cantidad * $precioFinal);
                    DB::table('productos')
                        ->where('id', $producto->id)
                        ->decrement('stock', $cantidad);
                }
                Pagos::create([
                    'pedido_id'          => $pedido->id,
                    'user_id'            => $pedido->user_id,
                    'total'              => (float) $totalCalculado,
                    'numero_transaccion' => $request->payment_id,
                ]);

                $pedido->update(['estado' => 'pagado']);

                return response()->json(['message' => 'Pago registrado y stock actualizado']);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error interno', 'error' => $e->getMessage()], 500);
        }*/
    }

    private function procesarPagoAprobado($pedidoId, $paymentId) {
        return DB::transaction(function () use ($pedidoId, $paymentId) {
            $pedido = Pedido::with('productos')->where('id', $pedidoId)->where('estado', 'pendiente')->lockForUpdate()->first();

            if (!$pedido) return false;

            $total = 0;
            foreach ($pedido->productos as $producto) {
                $cantidad = (int) $producto->pivot->cantidad;
                $precioFinal = $this->calcularPrecioFinal($producto, $cantidad);
                $total += ($cantidad * $precioFinal);
                
                DB::table('productos')->where('id', $producto->id)->decrement('stock', $cantidad);
            }

            Pagos::create([
                'pedido_id' => $pedido->id,
                'user_id' => $pedido->user_id,
                'total' => (float) $total,
                'numero_transaccion' => $paymentId,
            ]);

            $pedido->update(['estado' => 'pagado']);
            return true;
        });
    }
}
