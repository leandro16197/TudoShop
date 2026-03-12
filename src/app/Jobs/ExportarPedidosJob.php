<?php

namespace App\Jobs;

use App\Exports\PedidosExport;
use App\Http\Controllers\Admin\PedidosController;
use App\Models\Exportacion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;

class ExportarPedidosJob implements ShouldQueue
{
    use Queueable;

    protected $controllerName;
    protected $filtros;
    protected $esExcel;

    public function __construct($controllerName, $filtros, $esExcel)
    {
        $this->controllerName = $controllerName;
        $this->filtros = $filtros;
        $this->esExcel = $esExcel;
    }

    public function handle(): void
    {
        $userId = 1; 
        $cacheKey = "export_progress_{$userId}";

        try {

            $request = Request::create('/', 'GET', $this->filtros);
            Cache::put($cacheKey, ['status' => 'Iniciando', 'progress' => 10], 3600);


            $resultado = PedidosController::obtenerPedidos($request, true);
            Cache::put($cacheKey, ['status' => 'Procesando datos', 'progress' => 40], 3600);

            $data = is_array($resultado['data']) ? $resultado['data'] : $resultado['data']->toArray();

            $nombreArchivo = 'pedidos_' . time() . '.xlsx';
            $ruta = 'exports/' . $nombreArchivo;

            Excel::store(new PedidosExport($data), $ruta, 'public');

            Exportacion::updateOrCreate(
                ['user_id' => 1], 
                [
                    'nombre_archivo' => $nombreArchivo,
                    'estado'         => 'completado',
                    'ruta'           => 'storage/' . $ruta,
                    'updated_at'     => now()
                ]
            );
            Cache::put($cacheKey, ['status' => 'Generando archivo', 'progress' => 70], 3600);
            $urlDescarga = asset('storage/' . $ruta); 
            Cache::put($cacheKey, [ 'status'   => 'Completado', 'progress' => 100, 'url' => $urlDescarga ], 3600);

        } catch (\Exception $e) {
            throw $e; 
        }
    }
}