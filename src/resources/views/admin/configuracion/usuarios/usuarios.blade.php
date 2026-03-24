@extends('admin.layouts.base')

@section('header')
<div class="container-fluid pt-4 px-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="fw-bold mb-0 text-gray-800">
            <i class="bi bi-person-gear text-primary me-3"></i>Configuración Usuarios
        </h2>
        <button class="btn btn-primary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="bi bi-plus-lg me-2"></i> Nuevo Usuario
        </button>
    </div>
    <hr class="my-3 text-gray-200">
</div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="pedidosTable" data-url="{{ route('admin.usuarios.index') }}"  style="width: 100%">
                <thead class="table-dark"> <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Fecha</th>
                        <th>Rol</th>
                        <th></th>
                        {{--<th style="width: 155px !important;">Acciones</th>--}}
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@include('admin.configuracion.usuarios.usuario-agregar')
@include('admin.configuracion.usuarios.usuario-eliminar')
@push('scripts')
    @include('admin.configuracion.usuarios.usuariosScripts')
@endpush