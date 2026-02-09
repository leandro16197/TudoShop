<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriaProductoSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [];

    
        for ($i = 2; $i <= 12; $i++) {
            $data[] = [
                'producto_id' => $i,
                'categoria_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

       
        for ($i = 13; $i <= 22; $i++) {
            $data[] = [
                'producto_id' => $i,
                'categoria_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        for ($i = 23; $i <= 32; $i++) {
            $data[] = [
                'producto_id' => $i,
                'categoria_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        for ($i = 33; $i <= 42; $i++) {
            $data[] = [
                'producto_id' => $i,
                'categoria_id' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

    
        for ($i = 43; $i <= 46; $i++) {
            $data[] = [
                'producto_id' => $i,
                'categoria_id' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

      
        for ($i = 47; $i <= 52; $i++) {
            $data[] = [
                'producto_id' => $i,
                'categoria_id' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('categoria_producto')->insert($data);
    }
}
