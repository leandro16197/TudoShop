<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'productos';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'active',
    ];


   
    public function imagenes()
    {
        return $this->hasMany(Imagen::class, 'producto_id');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(Imagen::class, 'producto_id')->oldest('id');
    }
    public function categorias()
    {
        return $this->belongsToMany(
            Categoria::class,
            'categoria_producto',
            'producto_id',
            'categoria_id'
        );
    }
    
}
