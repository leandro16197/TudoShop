@extends('admin.layouts.base')

@section('header')
<h2 class="fw-bold">Gestión de Pedidos</h2>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="contenedorFiltros" class="d-flex gap-2 d-none">
                <div class="flex-fill">
                    <select id="filterEstado" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="pagado">Pagado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>
                <div class="flex-fill">
                    <input type="date" id="filterDesde" class="form-control form-control-sm" title="Desde">
                </div>
                <div class="flex-fill">
                    <input type="date" id="filterHasta" class="form-control form-control-sm" title="Hasta">
                </div>
            </div>
            <div id="exportStatusContainer" style="display: none;">
                <div class="progress" style="height: 20px;">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                </div>
                <span id="statusText" class="text-white">Iniciando...</span>
                <div id="downloadLinkArea"></div>
            </div>
            <table id="pedidosTable" data-url="{{ route('admin.pedidos') }}"  style="width: 100%">
                <thead class="table-dark"> <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Transacción</th>
                        <th>Fecha</th>
                        {{--<th style="width: 155px !important;">Acciones</th>--}}
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@include('admin.pedidos.pedidos.pedidos-eliminar')

@push('scripts')
    @include('admin.pedidos.pedidos.pedidosScript')
@endpush