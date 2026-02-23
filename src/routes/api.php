<?php

use App\Http\Controllers\Fe\ProductosController as FeProductosController;
use App\Http\Controllers\Fe\FeCategoriasController as FeCategoriasController;
use App\Http\Controllers\Fe\FeMarcasController as FeMarcasController;
use App\Http\Controllers\Fe\FeClientesController;
use App\Http\Controllers\Fe\AuthClienteController;
use Illuminate\Support\Facades\Route;

Route::prefix('frontend/v1')->group(function () {

    Route::post('/register', [AuthClienteController::class, 'register']);
    Route::post('/login', [AuthClienteController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/perfil', [AuthClienteController::class, 'me']);
        Route::post('/logout', [AuthClienteController::class, 'logout']);
        Route::put('perfil/actualizar', [FeClientesController::class, 'actualizar']);
    });

    Route::get('productos', [FeProductosController::class, 'search']);
    Route::get('productos/{id}', [FeProductosController::class, 'detail']);
    Route::get('categorias', [FeCategoriasController::class, 'index']);
    Route::get('destacados', [FeProductosController::class, 'featured']);
    Route::get('productos/categoria/{categoriaId}', [FeProductosController::class, 'byCategory']);
    Route::get('catalogo', [FeProductosController::class, 'catalogo']);
    Route::get('marcas', [FeMarcasController::class, 'index']);
});