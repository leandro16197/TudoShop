<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PedidoCompletoSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = DB::table('clientes')->pluck('id');

        foreach ($clientes as $clienteId) {
            $cantidadPedidos = rand(2, 3);

            for ($i = 0; $i < $cantidadPedidos; $i++) {
                $esPagado = (rand(1, 100) <= 80);
                $estado = $esPagado ? 'pagado' : 'rechazado';

                $pedidoId = DB::table('pedidos')->insertGetId([
                    'user_id'    => $clienteId,
                    'email'      => DB::table('clientes')->where('id', $clienteId)->value('email'),
                    'estado'     => $estado,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $productoIds = DB::table('productos')->inRandomOrder()->limit(rand(1, 3))->get();
                
                $totalPedido = 0;
                foreach ($productoIds as $producto) {
                    $cantidad = rand(1, 2);
                    $totalPedido += ($producto->price * $cantidad);

                    DB::table('pedidos_productos')->insert([
                        'pedido_id'   => $pedidoId,
                        'producto_id' => $producto->id,
                        'cantidad'    => $cantidad,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
                DB::table('envios_pedidos')->insert([
                    'pedido_id'           => $pedidoId,
                    'cp'                  => 'B7000',
                    'localidad'           => 'Tandil',
                    'direccion'           => 'Calle Falsa ' . rand(100, 999),
                    'nombre_destinatario' => 'Destinatario ' . $clienteId,
                    'telefono'            => '02494123456',
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);

                if ($esPagado) {
                    DB::table('pagos')->insert([
                        'pedido_id'          => $pedidoId,
                        'user_id'            => $clienteId,
                        'total'              => $totalPedido,
                        'numero_transaccion' => Str::random(16),
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                }
            }
        }
    }
}