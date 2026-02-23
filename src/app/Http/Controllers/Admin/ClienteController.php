<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{   

    protected $viewPath = 'admin.clientes'; 

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clientes = Cliente::select('id', 'nombre', 'apellido', 'email', 'created_at')
                ->get();

            return response()->json([
                'data' => $clientes
            ]);
        }

        return view("{$this->viewPath}.clientes"); 
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email'    => 'required|email|unique:clientes,email',
            'password' => 'required|min:8',
        ]);

        $data['password'] = bcrypt($data['password']);

        Cliente::create($data);

        return response()->json([
            'message' => 'Cliente creado exitosamente',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email'    => 'required|email|unique:clientes,email,' . $id,
            'password' => 'nullable|min:8',
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $cliente->update($data);

        return response()->json([
            'message' => 'Cliente actualizado exitosamente',
        ], 200);
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json([
            'message' => 'Cliente eliminado exitosamente',
        ], 200);
    }
}