<?php

namespace App\Http\Controllers\Fe;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthClienteController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $cliente = Cliente::where('email', $request->email)->first();

        if (!$cliente || !Hash::check($request->password, $cliente->password)) {
            return response()->json([
                'message' => 'Contraseña o email incorrectos'
            ], 401);
        }

        $token = $cliente->createToken('cliente-token')->plainTextToken;

        return response()->json([
            'cliente' => [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'email' => $cliente->email,
            ],
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout correcto'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
    
    public function register(Request $request)
    {
        $messages = [
            'nombre.required'      => 'El nombre es obligatorio.',
            'apellido.required'    => 'El apellido es obligatorio.',
            'email.required'       => 'El correo electrónico es obligatorio.',
            'email.email'          => 'Ingresa un formato de correo válido.',
            'email.unique'         => 'Este correo ya está registrado.',
            'password.required'    => 'La contraseña es obligatoria.',
            'password.min'         => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
        ];

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email'    => 'required|email|unique:clientes,email',
            'password' => 'required|min:6|confirmed'
        ], $messages);

        $cliente = Cliente::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $cliente->createToken('cliente-token')->plainTextToken;

        return response()->json([
            'cliente' => [
                'id'       => $cliente->id,
                'nombre'   => $cliente->nombre,
                'apellido' => $cliente->apellido,
                'email'    => $cliente->email,
            ],
            'token' => $token
        ], 201);
    }
}