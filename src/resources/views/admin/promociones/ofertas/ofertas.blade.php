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
        <button type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#createOfertaModal">
            <i class="bi bi-tags"></i> Nueva Oferta
        </button>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="ofertasTable"
                data-url="{{ route('admin.ofertas') }}"
                style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Desde</th>
                        <th>Fecha Hasta</th>
                        <th style="width: 155px !important;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection
@include('admin.promociones.ofertas.ofertas-agregar')
@include('admin.promociones.ofertas.ofertas-eliminar')
@push('scripts')
@include('admin.promociones.ofertas.ofertasScript')
@endpush