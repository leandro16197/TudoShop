@extends('admin.layouts.base')

@section('header')
<div class="container-fluid pt-4 px-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="fw-bold mb-0 text-gray-800">
            <i class="bi bi-box-seam text-primary me-3"></i>Gestión de Productos
        </h2>
        <button type="button" 
                class="btn btn-primary btn-sm d-flex align-items-center" 
                data-bs-toggle="modal" 
                data-bs-target="#createProductModal">
            <i class="bi bi-plus-lg me-2"></i> Nuevo Producto
        </button>
    </div>
    <hr class="my-3 text-gray-200">
</div>
@endsection
<style>
    #loadingGif{
        display:flex; 
    }
</style>
@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="contenedorFiltrosProductos" class="d-none">
                <div class="d-flex align-items-center gap-2">
                    <select id="filterStock" class="form-select form-select-sm" style="width: 130px;">
                        <option value="">Todos (Stock)</option>
                        <option value="con_stock">Con Stock</option>
                        <option value="sin_stock">Sin Stock</option>
                    </select>

                    <select id="filterCategoria" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">Categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>

                    <select id="filterMarca" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">Marca</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>

                    <button type="button" id="btnResetFilters" class="btn btn-outline-secondary btn-sm" title="Limpiar Filtros">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>

            <table id="productsTable" data-url="{{ route('admin.productos.productos') }}" style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Activo</th>
                        <th style="width: 155px !important;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@include('admin.productos.productos.productos-agregar')
@include('admin.productos.productos.productos-eliminar')
@push('scripts')
@include('admin.productos.productos.productosScript')
@endpush