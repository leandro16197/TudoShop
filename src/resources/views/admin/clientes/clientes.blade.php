@extends('admin.layouts.base')

@section('header')
<h2 class="fw-bold">Clientes</h2>
@endsection
<style>
    #loadingGif{
        display:flex; 
    }
</style>
@section('content')
    <div class="d-flex justify-content-end mb-3">
        <button type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#createOfertaModal">
            <i class="bi bi-tags"></i> Nuevo Cliente
        </button>
    </div>
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