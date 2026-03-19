@extends('admin.layouts.base')

@section('header')
    <h2 class="fw-bold">Gestion de Finanzas</h2>
@endsection
<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1); 
        cursor: pointer;
    }
    
    #filterForm label {
        color: #a2a2c2;
        font-size: 0.65rem;
        text-transform: uppercase;
    }

    .bg-secondary {
        background-color: #2d2d44 !important;
    }
    .btn-primary {
        background-color: #5865f2 !important;
        border: none;
    }

    .btn-primary:hover {
        background-color: #4752c4 !important;
        transform: scale(1.05);
    }
</style>
@section('content')
    <div class="container-fluid py-4" style="background-color: #1a1a2e; min-height: 100vh; color: white;">

        <div class="row mb-4 justify-content-center g-3">

            <div class="col-lg-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100 finance-card">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="mb-2">
                            <i class="bi bi-cash-stack text-primary"></i>
                        </div>
                        <h6 class="card-title-custom mb-1">Mes Actual</h6>
                        <h4 class="fw-bold m-0" id="card-mes-actual">$0</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100 finance-card">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="mb-2">
                            <i class="bi bi-calendar-check text-secondary"></i>
                        </div>
                        <h6 class="card-title-custom mb-1">Mes Anterior</h6>
                        <h4 class="fw-bold m-0" id="card-mes-pasado">$0</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100 finance-card border-bottom border-success border-4">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="mb-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <h6 class="card-title-custom mb-1">Completados</h6>
                        <h4 class="fw-bold m-0" id="card-completados">0</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100 finance-card border-bottom border-danger border-4">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="mb-2">
                            <i class="bi bi-x-circle-fill text-danger"></i>
                        </div>
                        <h6 class="card-title-custom mb-1">Rechazados</h6>
                        <h4 class="fw-bold m-0" id="card-rechazados">0</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100 finance-card border-bottom border-warning border-4">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <div class="mb-2">
                            <i class="bi bi-clock-history text-warning"></i>
                        </div>
                        <h6 class="card-title-custom mb-1">Pendientes</h6>
                        <h4 class="fw-bold m-0" id="card-pendientes">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-dark text-white border-0 shadow-lg mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-0">Ventas últimos 30 días</h5>
                        <p class="text-muted small mb-0">Ingresos diarios acumulados</p>
                    </div>
                    <form id="filterForm" class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                        <div class="d-flex align-items-center bg-secondary rounded-3 px-2 py-1">
                            <label class="small fw-bold me-2 mb-0">De:</label>
                            <input type="date" id="fecha_inicio" class="form-control form-control-sm bg-transparent text-white border-0 p-0 shadow-none" style="width: 110px;">
                        </div>
                        
                        <div class="d-flex align-items-center bg-secondary rounded-3 px-2 py-1">
                            <label class="small fw-bold me-2 mb-0">A:</label>
                            <input type="date" id="fecha_fin" class="form-control form-control-sm bg-transparent text-white border-0 p-0 shadow-none" style="width: 110px;">
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm rounded-circle p-2 shadow-sm" title="Filtrar">
                            <i class="bi bi-search d-flex"></i>
                        </button>
                    </form>
                </div>

                <div style="height: 350px; position: relative;">
                    <canvas id="canvasFinanzas"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('admin.pagos.pagosScripts')
@endpush
