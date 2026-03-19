<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagos;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinanzasController extends Controller
{
    protected $viewPath = 'admin.pagos';



    public function index(Request $request)
    {   
        return view("{$this->viewPath}.pagos");
    }

    public function getCardStats()
    {
       
        $completados = Pedido::where('estado', 'pagado')->count();
        $rechazados  = Pedido::where('estado', 'rechazado')->count();
        $pendientes  = Pedido::where('estado', 'pendiente')->count();

        $mesActualTotal = Pagos::
            whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        $mesPasadoTotal = Pagos::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total');

        return response()->json([
            'status' => 'success',
            'cards' => [
                'completados' => $completados,
                'rechazados'  => $rechazados,
                'pendientes'  => $pendientes,
            ],
            'comparativa' => [
                'mes_actual' => (float)$mesActualTotal,
                'mes_pasado' => (float)$mesPasadoTotal,
                'diferencia_porcentaje' => $this->calcularPorcentaje($mesActualTotal, $mesPasadoTotal)
            ]
        ]);
    }
    public function getGraficaEvolucion(Request $request)
    {
        $query = \DB::table('pagos')
            ->select(
                \DB::raw('DATE(created_at) as fecha'),
                \DB::raw('CAST(SUM(total) AS UNSIGNED) as total')
            );

        if ($request->has('inicio') && $request->has('fin') && $request->inicio != null) {
            $query->whereBetween('created_at', [$request->inicio . " 00:00:00", $request->fin . " 23:59:59"]);
        } else {
            $dias = $request->get('dias', 30);
            $query->where('created_at', '>=', now()->subDays($dias));
        }

        $datos = $query->groupBy('fecha')
            ->orderBy('fecha', 'ASC')
            ->get();

        return response()->json($datos);
    }
    private function calcularPorcentaje($actual, $pasado)
    {
        if ($pasado <= 0) return $actual > 0 ? 100 : 0;
        return round((($actual - $pasado) / $pasado) * 100, 2);
    }
}
