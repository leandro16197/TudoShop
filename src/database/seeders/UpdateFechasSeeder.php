<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateFechasSeeder extends Seeder
{
    public function run(): void
    {
        $pedidos = DB::table('pedidos')->get();

        foreach ($pedidos as $pedido) {
            $fechaAleatoria = Carbon::now()->subYears(rand(1, 3))->subDays(rand(0, 365));
            DB::table('pedidos')->where('id', $pedido->id)->update([
                'created_at' => $fechaAleatoria,
                'updated_at' => $fechaAleatoria,
            ]);
            DB::table('pedidos_productos')->where('pedido_id', $pedido->id)->update([
                'created_at' => $fechaAleatoria,
                'updated_at' => $fechaAleatoria,
            ]);

            DB::table('envios_pedidos')->where('pedido_id', $pedido->id)->update([
                'created_at' => $fechaAleatoria,
                'updated_at' => $fechaAleatoria,
            ]);

            DB::table('pagos')->where('pedido_id', $pedido->id)->update([
                'created_at' => $fechaAleatoria,
                'updated_at' => $fechaAleatoria,
            ]);
        }
    }
}