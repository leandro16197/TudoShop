<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::select('id', 'name', 'display_name', 'created_at')
                ->get()
                ->map(function ($role) {
                    return [
                        'id'           => $role->id,
                        'name'         => $role->name,
                        'display_name' => $role->display_name ?? 'Sin nombre visible',
                        'created_at'   => $role->created_at->format('d/m/Y'),
                    ];
                });

            return response()->json([
                'data' => $roles
            ]);
        }
        return view('admin.roles.rol');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
            'display_name' => 'nullable|max:255',
        ]);

        try {
            $role = Role::create([
                'name' => strtolower($request->name),
                'display_name' => $request->display_name,
            ]);

            return response()->json(['message' => 'Rol creado con éxito', 'role' => $role]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['error' => [$e->getMessage()]]], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $id,
            'display_name' => 'nullable|max:255',
        ]);

        $role->update($request->all());

        return response()->json(['message' => 'Rol actualizado correctamente']);
    }


    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        if($role->name === 'admin') {
            return response()->json(['errors' => ['error' => ['No se puede eliminar el rol administrador']]], 403);
        }

        $role->delete();
        return response()->json(['message' => 'Rol eliminado']);
    }
}
