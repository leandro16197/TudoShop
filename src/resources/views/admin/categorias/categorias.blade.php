@extends('admin.layouts.base')

@section('header')
<h2 class="fw-bold">Categorias</h2>
@endsection
<style>
    #loadingGif{
        display:flex; 
    }
</style>
@section('content')
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="bi bi-plus-lg"></i> Nueva Categoria
        </button>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="categoriasTable" data-url="{{ route('admin.categorias') }}" style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th style="width: 155px !important;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@include('admin.categorias.categorias-agregar')
@include('admin.categorias.categorias-eliminar')
@push('scripts')
    @include('admin.categorias.categoriasScript')
@endpush