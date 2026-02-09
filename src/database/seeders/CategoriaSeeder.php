<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            [
                'id' => 1,
                'nombre' => 'Bolígrafos',
            ],
            [
                'id' => 2,
                'nombre' => 'Cartucheras',
            ],
            [
                'id' => 3,
                'nombre' => 'Lápices, Crayones y Fibras',
            ],
            [
                'id' => 4,
                'nombre' => 'Mochilas',
            ],
            [
                'id' => 5,
                'nombre' => 'Papelería',
            ],
            [
                'id' => 6,
                'nombre' => 'Útiles escolares',
            ],
        ]);
    }
}
