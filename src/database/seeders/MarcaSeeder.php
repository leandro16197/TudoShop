<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            'Paper Mate',
            'BIC',
            'Mooving',
            'Pizzini',
            'Faber-Castell',
            'Staedtler',
            'Sharpie',
            'Jansport',
            'Xtrem',
            'Rivadavia',
            'Éxito',
            'Maped',
            'Pritt'
        ];

        foreach ($marcas as $nombre) {
            Marca::firstOrCreate(
                ['nombre' => $nombre],
            );
        }
    }
}
