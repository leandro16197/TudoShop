<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PedidosHistoricosSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = DB::table('clientes')->get();

        $productosPool = DB::table('productos')->get();

        if ($clientes->isEmpty() || $productosPool->isEmpty()) {
            $this->command->error("Necesitas clientes y productos para ejecutar este seeder.");
            return;
        }

        for ($i = 30; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            
            $pedidosEseDia = rand(2, 8);

            for ($j = 0; $j < $pedidosEseDia; $j++) {
                $cliente = $clientes->random();
                $esPagado = rand(1, 100) <= 80;
                $estado = $esPagado ? 'pagado' : 'pendiente';

                $pedidoId = DB::table('pedidos')->insertGetId([
                    'user_id'    => $cliente->id,
                    'email'      => $cliente->email,
                    'estado'     => $estado,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);


                $productosSeleccionados = $productosPool->random(rand(1, 3));
                $totalPedido = 0;

                foreach ($productosSeleccionados as $producto) {
                    $cantidad = rand(1, 2);
                    $totalPedido += $producto->price * $cantidad;

                    DB::table('pedidos_productos')->insert([
                        'pedido_id'   => $pedidoId,
                        'producto_id' => $producto->id,
                        'cantidad'    => $cantidad,
                        'created_at'  => $fecha,
                        'updated_at'  => $fecha,
                    ]);
                }

                DB::table('envios_pedidos')->insert([
                    'pedido_id'           => $pedidoId,
                    'cp'                  => 'B7000',
                    'localidad'           => 'Tandil',
                    'direccion'           => 'Calle Falsa ' . rand(100, 999),
                    'nombre_destinatario' => $cliente->nombre ?? 'Cliente Prueba',
                    'telefono'            => '2494' . rand(100000, 999999),
                    'created_at'          => $fecha,
                    'updated_at'          => $fecha,
                ]);

                if ($esPagado) {
                    DB::table('pagos')->insert([
                        'pedido_id'          => $pedidoId,
                        'user_id'            => $cliente->id,
                        'total'              => $totalPedido,
                        'numero_transaccion' => strtoupper(Str::random(12)),
                        'created_at'         => $fecha,
                        'updated_at'         => $fecha,
                    ]);
                }
            }
        }
    }
}