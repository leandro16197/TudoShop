<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
   protected $table = 'pagos';

    protected $fillable = [
        'pedido_id',
        'user_id',
        'total',
        'numero_transaccion'
    ];
}
