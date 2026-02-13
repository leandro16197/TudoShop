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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMarcaModal">
            <i class="bi bi-plus-lg"></i> Nueva Marca
        </button>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="marcasTable" data-url="{{ route('admin.marcas') }}" style="width: 100%">
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
@include('admin.productos.marcas.marcas-agregar')
@include('admin.productos.marcas.marcas-eliminar')
@push('scripts')
@include('admin.productos.marcas.marcasScript')
@endpush