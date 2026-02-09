<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $productos = [


            [
                'name' => 'Bolígrafo Paper Mate Azul',
                'description' => 'Bolígrafo con tinta azul de trazo suave.',
                'price' => 650,
                'stock' => 120,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Rojo',
                'description' => 'Lapicera con tinta roja de secado rápido.',
                'price' => 650,
                'stock' => 100,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Negro',
                'description' => 'Tinta negra intensa para escritura diaria.',
                'price' => 650,
                'stock' => 150,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Verde',
                'description' => 'Lapicera con tinta verde brillante.',
                'price' => 650,
                'stock' => 80,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Violeta',
                'description' => 'Ideal para subrayar y tomar apuntes.',
                'price' => 650,
                'stock' => 60,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Rosa',
                'description' => 'Lapicera con tinta rosa y diseño juvenil.',
                'price' => 650,
                'stock' => 70,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Celeste',
                'description' => 'Tinta celeste suave y cuerpo transparente.',
                'price' => 650,
                'stock' => 90,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Amarillo',
                'description' => 'Color amarillo ideal para destacar.',
                'price' => 650,
                'stock' => 50,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Naranja',
                'description' => 'Tinta naranja vibrante.',
                'price' => 650,
                'stock' => 55,
                'active' => 1,
            ],
            [
                'name' => 'Bolígrafo Paper Mate Marrón',
                'description' => 'Alternativa suave al negro tradicional.',
                'price' => 650,
                'stock' => 40,
                'active' => 1,
            ],

            [
                'name' => 'Cartuchera Simple Negra',
                'description' => 'Cartuchera compacta con cierre reforzado.',
                'price' => 3200,
                'stock' => 40,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera Simple Azul',
                'description' => 'Diseño clásico y resistente.',
                'price' => 3200,
                'stock' => 35,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera Doble Compartimento Roja',
                'description' => 'Dos compartimentos amplios.',
                'price' => 4200,
                'stock' => 30,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera Doble Compartimento Verde',
                'description' => 'Ideal para organización escolar.',
                'price' => 4200,
                'stock' => 28,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera Estampada Infantil',
                'description' => 'Diseño colorido para primaria.',
                'price' => 3800,
                'stock' => 50,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera Cilíndrica Negra',
                'description' => 'Formato tubular liviano.',
                'price' => 2900,
                'stock' => 45,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera Juvenil Negra y Rosa',
                'description' => 'Diseño moderno.',
                'price' => 4100,
                'stock' => 33,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera PVC Transparente',
                'description' => 'Permite ver el contenido.',
                'price' => 2700,
                'stock' => 60,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera Triple Compartimento Azul',
                'description' => 'Gran capacidad.',
                'price' => 5200,
                'stock' => 22,
                'active' => 1,
            ],
            [
                'name' => 'Cartuchera Infantil con Personajes',
                'description' => 'Estampas infantiles llamativas.',
                'price' => 3900,
                'stock' => 48,
                'active' => 1,
            ],


            [
                'name' => 'Lápices de Colores x12',
                'description' => 'Colores intensos y resistentes.',
                'price' => 4500,
                'stock' => 70,
                'active' => 1,
            ],
            [
                'name' => 'Lápices de Colores x24',
                'description' => 'Amplia variedad de colores.',
                'price' => 8200,
                'stock' => 55,
                'active' => 1,
            ],
            [
                'name' => 'Lápices de Colores x36',
                'description' => 'Ideal para dibujo artístico.',
                'price' => 12000,
                'stock' => 40,
                'active' => 1,
            ],
            [
                'name' => 'Crayones x12',
                'description' => 'Crayones suaves para niños.',
                'price' => 2800,
                'stock' => 65,
                'active' => 1,
            ],
            [
                'name' => 'Crayones Jumbo x24',
                'description' => 'Crayones grandes y resistentes.',
                'price' => 3900,
                'stock' => 60,
                'active' => 1,
            ],
            [
                'name' => 'Fibras Escolares x12',
                'description' => 'Colores brillantes.',
                'price' => 5100,
                'stock' => 58,
                'active' => 1,
            ],
            [
                'name' => 'Fibras Lavables x24',
                'description' => 'Fibras lavables y seguras.',
                'price' => 7600,
                'stock' => 45,
                'active' => 1,
            ],
            [
                'name' => 'Marcadores Permanentes x8',
                'description' => 'Para múltiples superficies.',
                'price' => 6900,
                'stock' => 35,
                'active' => 1,
            ],
            [
                'name' => 'Lápices Acuarelables x12',
                'description' => 'Se pueden usar con agua.',
                'price' => 8800,
                'stock' => 30,
                'active' => 1,
            ],
            [
                'name' => 'Lápices Pastel x24',
                'description' => 'Colores suaves y artísticos.',
                'price' => 9200,
                'stock' => 25,
                'active' => 1,
            ],

            [
                'name' => 'Mochila Escolar Negra',
                'description' => 'Clásica y resistente.',
                'price' => 18500,
                'stock' => 25,
                'active' => 1,
            ],
            [
                'name' => 'Mochila Escolar Azul',
                'description' => 'Diseño moderno.',
                'price' => 19000,
                'stock' => 22,
                'active' => 1,
            ],
            [
                'name' => 'Mochila Escolar Roja',
                'description' => 'Color vibrante y cómoda.',
                'price' => 17800,
                'stock' => 20,
                'active' => 1,
            ],
            [
                'name' => 'Mochila Escolar Rosa',
                'description' => 'Ideal para primaria.',
                'price' => 16200,
                'stock' => 18,
                'active' => 1,
            ],
            [
                'name' => 'Mochila Escolar Verde',
                'description' => 'Diseño deportivo.',
                'price' => 17000,
                'stock' => 16,
                'active' => 1,
            ],
            [
                'name' => 'Mochila con Porta Notebook',
                'description' => 'Compartimento acolchado.',
                'price' => 26500,
                'stock' => 14,
                'active' => 1,
            ],
            [
                'name' => 'Mochila Escolar Impermeable',
                'description' => 'Resistente al agua.',
                'price' => 24000,
                'stock' => 12,
                'active' => 1,
            ],
            [
                'name' => 'Mochila Escolar Infantil',
                'description' => 'Liviana y cómoda.',
                'price' => 15000,
                'stock' => 20,
                'active' => 1,
            ],
            [
                'name' => 'Mochila Escolar Grande',
                'description' => 'Gran capacidad.',
                'price' => 28000,
                'stock' => 10,
                'active' => 1,
            ],
            [
                'name' => 'Mochila Escolar con Carrito',
                'description' => 'Reduce el peso en la espalda.',
                'price' => 35000,
                'stock' => 8,
                'active' => 1,
            ],

            [
                'name' => 'Cuaderno Tapa Dura x80 Hojas',
                'description' => 'Ideal para uso intensivo.',
                'price' => 4200,
                'stock' => 90,
                'active' => 1,
            ],
            [
                'name' => 'Cuaderno Rayado x50 Hojas',
                'description' => 'Formato escolar.',
                'price' => 2600,
                'stock' => 110,
                'active' => 1,
            ],
            [
                'name' => 'Carpeta Escolar A4',
                'description' => 'Carpeta con anillos.',
                'price' => 5200,
                'stock' => 60,
                'active' => 1,
            ],
            [
                'name' => 'Repuesto de Hojas x96',
                'description' => 'Hojas rayadas A4.',
                'price' => 3100,
                'stock' => 100,
                'active' => 1,
            ],
            [
                'name' => 'Regla Plástica 30cm',
                'description' => 'Regla escolar resistente.',
                'price' => 900,
                'stock' => 150,
                'active' => 1,
            ],
            [
                'name' => 'Tijera Escolar Punta Redonda',
                'description' => 'Segura para niños.',
                'price' => 2500,
                'stock' => 75,
                'active' => 1,
            ],
            [
                'name' => 'Pegamento en Barra 40g',
                'description' => 'Secado rápido.',
                'price' => 1800,
                'stock' => 110,
                'active' => 1,
            ],
            [
                'name' => 'Corrector Líquido',
                'description' => 'Correcciones limpias.',
                'price' => 2100,
                'stock' => 85,
                'active' => 1,
            ],
            [
                'name' => 'Goma de Borrar Blanca',
                'description' => 'No daña el papel.',
                'price' => 700,
                'stock' => 200,
                'active' => 1,
            ],
            [
                'name' => 'Sacapuntas Metálico',
                'description' => 'Durable y preciso.',
                'price' => 1200,
                'stock' => 160,
                'active' => 1,
            ],
        ];

        foreach ($productos as &$producto) {
            $producto['created_at'] = $now;
            $producto['updated_at'] = $now;
        }

        DB::table('productos')->insert($productos);
    }
}
