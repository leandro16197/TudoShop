<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Mail\PedidoAprobadoMail;
use App\Mail\PedidoRechazadoMail;
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
                "notification_url" => url('/api/frontend/v1/mp/webhook'),
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
        \Log::debug("Webhook recibido", $request->all());

        $token = DB::table('configuracions')->where('clave', 'mp_access_token')->value('dato');
        if (!$token) return response()->json(['error' => 'Config'], 500);

        MercadoPagoConfig::setAccessToken($token);
        $type = $request->input('type') ?? $request->input('topic');
        $paymentId = $request->input('data.id') ?? $request->input('id');

        if ($type === 'payment' && $paymentId) {
            try {
                $client = new PaymentClient();
                $payment = $client->get($paymentId);
                $externalRef = $payment->external_reference;

                if (!$externalRef) return response()->json(['status' => 'no_ref'], 200);

                $pedido = Pedido::with('user')->find($externalRef);
                if (!$pedido) return response()->json(['status' => 'no_pedido'], 200);

                if ($payment->status === 'approved') {
                    $this->procesarPagoAprobado($externalRef, $paymentId, $payment->transaction_amount);
                    
                    \Mail::to($pedido->user->email)->queue(new PedidoAprobadoMail($pedido));
                    \Log::info("Pago Aprobado: Mail enviado pedido #{$pedido->id}");
                } 
                
                elseif (in_array($payment->status, ['rejected', 'cancelled', 'refunded', 'charged_back'])) {
                    \Mail::to($pedido->user->email)->queue(new PedidoRechazadoMail($pedido));
                    \Log::warning("Pago Rechazado ({$payment->status}): Mail enviado pedido #{$pedido->id}");
                }

            } catch (\Exception $e) {
                \Log::error("Error en Webhook/Mail: " . $e->getMessage());
                return response()->json(['status' => 'error_handled'], 200);
            }
        }

        return response()->json(['status' => 'received'], 200);
    }
    
    private function procesarPagoAprobado($pedidoId, $paymentId, $totalMp)
    {
        return DB::transaction(function () use ($pedidoId, $paymentId, $totalMp) {

            $pedido = Pedido::with(['productos', 'cliente', 'pago'])
                ->where('id', $pedidoId)
                ->where('estado', 'pendiente')
                ->lockForUpdate()
                ->first();

            if (!$pedido) return false;

            foreach ($pedido->productos as $producto) {
                $cantidad = (int) $producto->pivot->cantidad;

                DB::table('productos')
                    ->where('id', $producto->id)
                    ->decrement('stock', $cantidad);
            }

            Pagos::create([
                'pedido_id' => $pedido->id,
                'user_id' => $pedido->user_id,
                'total' => (float) $totalMp, 
                'numero_transaccion' => $paymentId,
            ]);

            $pedido->update(['estado' => 'pagado']);

            return $pedido->fresh(['cliente', 'pago', 'productos']);
        });
    }
}
