<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuracion;

class ConfiguracionController extends Controller
{
    protected $viewPath = 'admin.configuracion.configuracion';
    public function index()
    {
        $configs = Configuracion::pluck('dato', 'clave')->toArray();

        if (isset($configs['logo_sitio'])) {
            $configs['logo_sitio'] = asset('storage/' . $configs['logo_sitio']);
        } else {
            $configs['logo_sitio'] = null;
        }

        return view($this->viewPath.'.configuracion', compact('configs'));
    }

    public function update(Request $request)
    {
        $datos = $request->except('_token');
        foreach ($datos as $clave => $valor) {
            if ($request->hasFile($clave)) {
                $path = $request->file($clave)->store('config', 'public');
                Configuracion::updateOrCreate(['clave' => $clave], ['dato' => $path, 'tipo' => 'img']);
            } else {
                Configuracion::updateOrCreate(['clave' => $clave], ['dato' => $valor, 'tipo' => 'text']);
            }
        }

        return response()->json(['message' => 'Configuración guardada!']);
    }
}
