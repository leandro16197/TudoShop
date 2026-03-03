<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Configuracion;    

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $config = Configuracion::where('clave', 'logo_sitio')->first();
            
            $logoPath = ($config && $config->dato) 
                        ? asset('storage/' . $config->dato) 
                        : asset('images/logoShopTudo.png');

            $view->with('logo_navbar', $logoPath);
        });
    }
}