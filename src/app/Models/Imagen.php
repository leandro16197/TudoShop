<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;

 
    protected $table = 'imagenes';


    protected $fillable = [
        'producto_id',
        'imagen',
    ];


    public function producto()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->imagen);
    }
}
