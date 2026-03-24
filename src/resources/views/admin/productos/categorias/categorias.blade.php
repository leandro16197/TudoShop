@extends('admin.layouts.base')

@section('header')
<div class="container-fluid pt-4 px-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="fw-bold mb-0 text-gray-800">
            <i class="bi bi-grid-3x3-gap text-primary me-3"></i>Gestión de Categorías
        </h2>
        <button type="button" 
                class="btn btn-primary btn-sm d-flex align-items-center" 
                data-bs-toggle="modal" 
                data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg me-2"></i> Nueva Categoría
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
            <table id="categoriasTable" data-url="{{ route('admin.categorias') }}" style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th style="width: 155px !important;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@include('admin.productos.categorias.categorias-agregar')
@include('admin.productos.categorias.categorias-eliminar')
@push('scripts')
    @include('admin.productos.categorias.categoriasScript')
@endpush