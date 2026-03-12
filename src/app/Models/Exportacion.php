<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exportacion extends Model
{
    protected $table = 'exportaciones';
    protected $fillable = [
        'user_id', 
        'nombre_archivo', 
        'ruta', 
        'estado'
    ];
}