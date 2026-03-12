<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfertasAplicaciones extends Model
{
    protected $table = 'oferta_aplicaciones';

    protected $fillable = [
        'oferta_id',
        'marca_id',
        'categoria_id',
        'porcentaje',
        'cantidad_minima'
    ];
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}