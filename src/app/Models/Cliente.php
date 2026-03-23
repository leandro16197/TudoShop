<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    protected $table = 'clientes';
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function favoritos()
    {
        return $this->belongsToMany(
            Product::class, 
            'cliente_producto_favorito', 
            'user_id',
            'producto_id'
        )->withTimestamps();
    }

}