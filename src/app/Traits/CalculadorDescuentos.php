<?php

namespace App\Traits;

use App\Models\OfertasAplicaciones;

trait CalculadorDescuentos
{
    public function calcularPrecioFinal($producto, $cantidad = 1)
    {
        $marcaIds = $producto->marcas->pluck('id')->filter()->toArray();
        $categoriaIds = $producto->categorias->pluck('id')->filter()->toArray();

        if (empty($marcaIds) && empty($categoriaIds)) {
            return $producto->price;
        }

        $oferta = \App\Models\OfertasAplicaciones::where(function($q) use ($marcaIds, $categoriaIds) {
                if (!empty($marcaIds)) {
                    $q->orWhereIn('marca_id', $marcaIds);
                }
                if (!empty($categoriaIds)) {
                    $q->orWhereIn('categoria_id', $categoriaIds);
                }
            })
            ->where('cantidad_minima', '<=', $cantidad)
            ->first();

        if ($oferta && $oferta->porcentaje > 0) {
            $descuento = $producto->price * ($oferta->porcentaje / 100);
            return $producto->price - $descuento;
        }

        return $producto->price;
    }
}