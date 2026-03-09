<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;

class Pedido extends Model
{
    
    protected $fillable = [
        'user_id',
        'email',
        'estado'
    ];

    public function productos()
    {
        return $this->belongsToMany(
            Product::class,       
            'pedidos_productos',  
            'pedido_id',          
            'producto_id'         
        )
        ->withPivot('cantidad') 
        ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function envio()
    {
        return $this->hasOne(EnvioPedido::class, 'pedido_id');
    }
    

    public function pago()
    {
        return $this->hasOne(Pagos::class, 'pedido_id');
    }
    public function cliente() 
    {
        return $this->belongsTo(Cliente::class, 'user_id');
    }
}