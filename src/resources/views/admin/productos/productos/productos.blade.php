@extends('admin.layouts.base')

@section('header')
<h2 class="fw-bold">Productos</h2>
@endsection
<style>
    #loadingGif{
        display:flex; 
    }
</style>
@section('content')
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
            <i class="bi bi-plus-lg"></i> Nuevo Producto
        </button>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="productsTable" data-url="{{ route('admin.productos.productos') }}" style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
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