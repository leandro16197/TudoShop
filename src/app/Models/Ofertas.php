<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

}
