<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(MarcaSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(ProductosSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(MarcaProductoSeeder::class);
        $this->call(CategoriaProductoSeeder::class);
        $this->call(PedidosHistoricosSeeder::class);
        $this->call(UpdateFechasSeeder::class);
    }
}
