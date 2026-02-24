<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    
}