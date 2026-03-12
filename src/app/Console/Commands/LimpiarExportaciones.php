<?php

namespace App\Console\Commands;

use App\Models\Exportacion;
use Illuminate\Console\Command;

class LimpiarExportaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:limpiar-exportaciones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limite = now()->subSeconds(10);
        $viejos = Exportacion::where('created_at', '<', $limite)->get();

        foreach ($viejos as $item) {
            if (empty($item->ruta)) {
                $this->warn("Registro ID {$item->id} sin ruta, borrando solo de DB.");
                $item->delete();
                continue;
            }

            $archivoRelativo = str_replace('storage/', '', $item->ruta);
            
            try {
                if (\Storage::disk('public')->exists($archivoRelativo)) {
                    \Storage::disk('public')->delete($archivoRelativo);
                    $this->info("Archivo {$archivoRelativo} eliminado.");
                } else {
                    $this->error("Archivo no encontrado: {$archivoRelativo}");
                }
                $item->delete();
            } catch (\Exception $e) {
                $this->error("Error al procesar ID {$item->id}: " . $e->getMessage());
            }
        }
    }
}
