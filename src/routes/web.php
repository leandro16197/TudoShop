<?php

use App\Http\Controllers\Admin\ProductosController as AdminProductosController;
use App\Http\Controllers\Admin\CategoriasController ;
use App\Http\Controllers\Admin\OfertaController ;
use App\Http\Controllers\Fe\ProductosController as FeProductosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend clientes (React) - v1
|--------------------------------------------------------------------------
*/
Route::prefix('home')->group(function () {
    Route::get('/', function () {
        return view('frontend.home');
    });
});
Route::prefix('frontend/v1')->group(function () {
    Route::get('productos', [FeProductosController::class, 'search']);
    Route::get('productos/{id}', [FeProductosController::class, 'detail']);
});

/*
|--------------------------------------------------------------------------
| Panel administrativo - v2
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');
Route::get('products', [AdminProductosController::class, 'index'])->name('admin.productos.productos');
Route::get('categorias', [CategoriasController::class, 'index'])->name('admin.categorias');
Route::get('ofertas', [OfertaController::class, 'index'])->name('admin.ofertas');
Route::middleware(['auth', 'verified'])
    ->prefix('frontend/v2')
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('products', [AdminProductosController::class, 'store'])->name('admin.productos.store');
        Route::post('categorias', [CategoriasController::class, 'store'])->name('admin.categorias.store');
        Route::delete('categorias/{id}', [CategoriasController::class, 'destroy']);
        Route::delete('productos/{id}', [AdminProductosController::class, 'destroy']);
        Route::post('productos/{id}', [AdminProductosController::class, 'update']);
        Route::post('ofertas/{id}', [OfertaController::class, 'update']);
        Route::delete('ofertas/{id}', [OfertaController::class, 'destroy']);
        Route::post('ofertas', [OfertaController::class, 'store'])->name('admin.ofertas.store');

});

require __DIR__.'/auth.php';
