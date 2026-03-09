@extends('admin.layouts.base')

@section('header')
<h2 class="fw-bold">Gestión de Pedidos</h2>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="pedidosTable" data-url="{{ route('admin.pedidos') }}"  style="width: 100%">
                <thead class="table-dark"> <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Transacción</th>
                        <th>Fecha</th>
                        <th style="width: 155px !important;">Acciones</th>
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