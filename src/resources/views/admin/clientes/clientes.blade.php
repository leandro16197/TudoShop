@extends('admin.layouts.base')

@section('header')
<div class="container-fluid pt-4 px-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="fw-bold mb-0 text-gray-800">
            <i class="bi bi-people text-primary me-3"></i>Gestión de Clientes
        </h2>
        <button type="button" 
                class="btn btn-primary btn-sm d-flex align-items-center" 
                data-bs-toggle="modal" 
                data-bs-target="#modalCliente">
            <i class="bi bi-plus-lg me-2"></i> Nuevo Cliente
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
            <table id="clientesTable"
                data-url="{{ route('admin.clientes') }}"
                style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Mail</th>
                        <th style="width: 155px !important;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection
@include('admin.Clientes.clientes-agregar')
@include('admin.Clientes.clientes-eliminar')
@push('scripts')
@include('admin.Clientes.clientesScript')
@endpush