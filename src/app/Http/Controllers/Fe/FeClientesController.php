<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FeClientesController extends Controller
{
    public function index()
    {
        $clientes = Cliente::select('id', 'nombre', 'apellido', 'email')
            ->orderBy('nombre')
            ->get();

        return response()->json($clientes);
    }
    public function perfil(Request $request)
    {
        return response()->json($request->user());
    }


    public function actualizar(Request $request)
    {
        $cliente = $request->user();

   
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
        ]);
        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;

        if ($request->filled('password')) {
            $cliente->password = Hash::make($request->password);
        }

        $cliente->save();

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user'    => $cliente
        ]);
    }
    
}