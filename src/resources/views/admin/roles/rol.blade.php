@extends('admin.layouts.base')

@section('header')
<div class="container-fluid pt-4 px-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="fw-bold mb-0 text-gray-800">
            <i class="bi bi-shield-lock text-primary me-3"></i>Gestión de Roles
        </h2>
        <button class="btn btn-primary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalRol">
            <i class="bi bi-plus-lg me-2"></i> Nuevo Rol
        </button>
    </div>
    <hr class="my-3 text-gray-200">
</div>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table id="rolesTable" data-url="{{ route('admin.roles.index') }}"  style="width: 100%">
                <thead class="table-dark"> <tr>
                        <th>ID</th>
                        <th>Nombre Interno</th> 
                        <th>Nombre Visible</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th> 
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection


@include('admin.roles.rol-agregar')
@include('admin.roles.rol-eliminar')

@push('scripts')
    @include('admin.roles.rolScripts')
@endpush