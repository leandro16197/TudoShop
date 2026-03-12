<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Ofertas extends Model
{
    use HasFactory;
    
    protected $table = 'ofertas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_desde',
        'fecha_hasta',
    ];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    public function aplicaciones(): HasMany
    {
        return $this->hasMany(OfertasAplicaciones::class, 'oferta_id');
    }
}
