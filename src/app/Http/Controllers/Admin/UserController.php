<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')
                ->select('id', 'name', 'email', 'created_at')
                ->get()
                ->map(function ($user) {
                    $rol = $user->roles->first();

                    return [
                        'id'      => $user->id,
                        'name'    => $user->name,
                        'email'   => $user->email,
                        'rol_name'=> $rol ? ($rol->display_name ?? $rol->name) : 'Sin Rol',
                        'role_id' => $rol ? $rol->id : '', 
                        'fecha'   => $user->created_at->format('d/m/Y H:i'),
                        'acciones' => '' 
                    ];
                });

            return response()->json([
                'data' => $users
            ]);
        }

        $roles = Role::all(); 

        return view('admin.configuracion.usuarios.usuarios', compact('roles'));
    }

    public function create()
    {
        return view('admin.configuracion.usuarios.create');
    }

    public function store(Request $request)
    {   \Log::debug($request);
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role_id'  => 'required|exists:roles,id'
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => \Hash::make($request->password),
            ]);

            if ($request->role_id) {
                $user->roles()->attach($request->role_id);
            }

            \Log::info('Usuario creado: ' . $user->name);

            return response()->json(['status' => 'ok', 'message' => 'Usuario creado']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }
        $user->save();
        $user->roles()->sync([$request->role_id]);

        return response()->json(['message' => 'Usuario y rol actualizados correctamente']);
    }
}