<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvioPedido extends Model
{
    protected $table = 'envios_pedidos'; 

    protected $fillable = [
        'pedido_id', 
        'cp', 
        'localidad', 
        'direccion', 
        'nombre_destinatario', 
        'telefono'
    ];
}
