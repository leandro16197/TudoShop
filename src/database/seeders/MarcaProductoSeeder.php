<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcaProductoSeeder extends Seeder
{
    public function run(): void
    {
        $relaciones = [
            [2, 3], [3, 3], [4, 3], [5, 3], [6, 3], [7, 3], [8, 3], [9, 3], [10, 3], [11, 3], [12, 3],
            [13, 5], [14, 5], [15, 5], [16, 5], [17, 5], [18, 5], [19, 5], [20, 5], [21, 5], [22, 5],
            [23, 7], [24, 7], [25, 7], [28, 14], [29, 14], [31, 7],
            [30, 9],
            [33, 10], [34, 10], [38, 10], [35, 11], [36, 11], [37, 11], [39, 11], [40, 11], [41, 11], [42, 11],
            [43, 12], [44, 12], [46, 13],
            [47, 6], [48, 14], [49, 15], [50, 4], [51, 14], [52, 14],
        ];

        foreach ($relaciones as $rel) {
            DB::table('marca_producto')->updateOrInsert(
                ['producto_id' => $rel[0], 'marca_id' => $rel[1]],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}