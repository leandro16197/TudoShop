<?php

use App\Http\Controllers\Admin\ProductosController as AdminProductosController;
use App\Http\Controllers\Admin\CategoriasController ;
use App\Http\Controllers\Admin\MarcaController;
use App\Http\Controllers\Admin\OfertaController ;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\PedidosController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinanzasController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('home')->group(function () {
    Route::get('/', function () {
        return view('frontend.home');
    });
});

Route::middleware(['auth'])->prefix('panel')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::middleware(['role:administrador,vendedor'])->group(function () {
        Route::get('/', function () { return view('admin.dashboard'); })->name('dashboard');
        Route::get('/products', [AdminProductosController::class, 'index'])->name('admin.productos.productos');
        Route::get('/ofertas', [OfertaController::class, 'index'])->name('admin.ofertas');
        Route::get('/clientes', [ClienteController::class, 'index'])->name('admin.clientes');
        Route::get('/pedidos', [PedidosController::class, 'index'])->name('admin.pedidos');
    });
    Route::middleware(['role:administrador'])->group(function () {
        Route::get('/categorias', [CategoriasController::class, 'index'])->name('admin.categorias');
        Route::get('/marcas', [MarcaController::class,'index'])->name('admin.marcas');
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('admin.configuracion.general');
        Route::get('/finanzas', [FinanzasController::class, 'index'])->name('admin.finanzas.index');
        Route::get('/usuarios', [UserController::class, 'index'])->name('admin.usuarios.index');
        Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    });
});


Route::middleware(['auth', 'verified'])->prefix('frontend/v2')->group(function () {
    Route::middleware(['role:administrador,vendedor'])->group(function () {
        
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/panel/metricas', [DashboardController::class, 'metricas'])->name('admin.dashboard.metrics');
        Route::get('/panel/ventas-mes', [DashboardController::class, 'ventasMes']);
        Route::get('clientes', [ClienteController::class, 'index'])->name('clientes');
        Route::post('clientes/store', [ClienteController::class, 'store'])->name('clientes.store');
        Route::post('clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
        Route::get('categorias/lista', [CategoriasController::class, 'lista'])->name('admin.categorias.list');
        Route::get('/ofertas/relaciones', [OfertaController::class, 'relaciones'])->name('admin.ofertas.relaciones');
        Route::get('/pedidos/check-progress', [PedidosController::class, 'checkProgress']);
    });

    Route::middleware(['role:administrador'])->group(function () {
        Route::post('products', [AdminProductosController::class, 'store'])->name('admin.productos.store');
        Route::post('productos/{id}', [AdminProductosController::class, 'update']);
        Route::delete('productos/{id}', [AdminProductosController::class, 'destroy']);
        Route::post('categorias', [CategoriasController::class, 'store'])->name('admin.categorias.store');
        Route::post('categorias/{id}', [CategoriasController::class, 'update'])->name('admin.categorias.update');
        Route::delete('categorias/{id}', [CategoriasController::class, 'destroy']);
        Route::post('marcas', [MarcaController::class, 'store'])->name('admin.marcas.store');
        Route::post('marcas/{id}', [MarcaController::class, 'update']);
        Route::delete('marcas/{id}', [MarcaController::class, 'destroy']);
        Route::post('ofertas', [OfertaController::class, 'store'])->name('admin.ofertas.store');
        Route::post('ofertas/{id}', [OfertaController::class, 'update']);
        Route::delete('ofertas/{id}', [OfertaController::class, 'destroy']);
        Route::post('configuracion/update', [ConfiguracionController::class, 'update'])->name('admin.configuracion.update');
        Route::get('/cards-stats', [FinanzasController::class, 'getCardStats'])->name('admin.finanzas.cards');
        Route::get('/grafica-evolucion', [FinanzasController::class, 'getGraficaEvolucion'])->name('admin.finanzas.grafica');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('admin.usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('admin.usuarios.store');
        Route::post('/usuarios/{id}', [UserController::class, 'update'])->name('admin.usuarios.update');
        Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');
        Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
        Route::post('/roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    });

});
Route::view('/{any}', 'frontend.home')
    ->where('any', '^(?!panel|frontend|api).*$');
require __DIR__.'/auth.php';
