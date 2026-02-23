<?php

namespace App\Http\Controllers\Fe;


use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class FeMarcasController extends Controller
{
    public function index()
    {
        $marcas = Marca::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();

        return response()->json($marcas);
    }
    
}