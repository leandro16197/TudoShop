@extends('admin.layouts.base')
@section('header')
<div class="container-fluid pt-4 px-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="fw-bold mb-0 text-gray-800">
            <i class="bi bi-tools text-primary me-3"></i>Configuración General
        </h2>
        <span class="text-muted fs-7">Administra la identidad y datos de tu negocio</span>
    </div>
    <hr class="my-3 text-gray-200">
</div>
@endsection
@section('content')
<div class="container-fluid pb-5 px-3">
    <form id="formConfiguracion" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-md-6 col-lg-5">
                <div class="admin-card h-100 shadow-sm">
                    <div class="card-header border-0 py-4 px-4 d-flex align-items-center">
                        <div class="header-icon-container header-icon-blue-bg me-3">
                            <i class="bi bi-image header-icon header-icon-blue-text"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-white fw-bold">Identidad Visual</h5>
                            <span class="text-muted fs-7">Controla el logo principal</span>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="mb-3 text-center">
                            <label class="form-label d-block text-start text-muted">Logo Actual del Sitio</label>
                            
                            <div class="logo-preview-wrapper">
                                <img src="{{ $configs['logo_sitio'] ?? asset('images/default-logo.png') }}" 
                                     id="preview-logo" class="img-fluid" style="max-height: 120px; border-radius: 8px;">
                            </div>
                            
                            <div class="custom-file-input">
                                <input type="file" name="logo_sitio" id="logo_input" accept="image/*" onchange="previewImage(event)" hidden>
                                <label for="logo_input" class="custom-file-label">
                                    <i class="bi bi-upload me-2"></i>Seleccionar nuevo logo
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2 fs-8">Sube archivos .png o .jpg (max: 2MB).</small>
                        </div>
                    </div>
                </div>
            </div>

           <div class="col-md-6 col-lg-7">
                <div class="admin-card h-100 shadow-sm">
                    <div class="card-header border-0 py-4 px-4 d-flex align-items-center">
                        <div class="header-icon-container header-icon-green-bg me-3">
                            <i class="bi bi-geo-alt header-icon header-icon-green-text"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-white fw-bold">Ubicación y Contacto</h5>
                            <span class="text-muted fs-7">Define la dirección de tu negocio</span>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="mb-4">
                            <label class="form-label text-muted">Calle y Número</label>
                            <input type="text" name="direccion" class="form-control form-control-lg shadow-none" 
                                value="{{ $configs['direccion'] ?? '' }}" placeholder="Ej: Av. Siempreviva 742">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted">Ciudad</label>
                                <input type="text" name="ciudad" class="form-control form-control-lg shadow-none" 
                                    value="{{ $configs['ciudad'] ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted">Código Postal</label>
                                <input type="text" name="codigo_postal" class="form-control form-control-lg shadow-none" 
                                    value="{{ $configs['codigo_postal'] ?? '' }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Email Principal de Soporte</label>
                            <input type="email" name="email_soporte" class="form-control form-control-lg shadow-none" 
                                value="{{ $configs['email_soporte'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="admin-card shadow-sm">
                    <div class="card-header border-0 py-4 px-4 d-flex align-items-center" style="background: linear-gradient(45deg, #009ee3, #007eb5);">
                        <div class="header-icon-container bg-white me-3">
                            <i class="bi bi-credit-card-2-back header-icon" style="color: #009ee3;"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-white fw-bold">Mercado Pago</h5>
                            <span class="text-white-50 fs-7">Configuración de la pasarela de pagos</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <label class="form-label text-muted fw-bold">Access Token de Producción</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-primary"></i></span>
                                    <input type="password" name="mp_access_token" class="form-control form-control-lg border-start-0 shadow-none" 
                                        value="{{ $configs['mp_access_token'] ?? '' }}" placeholder="APP_USR-XXXXXXXXXXXX-XXXXXX-XXXXXXXXXXXX-XXXXXXXX">
                                </div>
                                <small class="text-muted d-block mt-1">Obtén tu token en el <a href="https://www.mercadopago.com.ar/developers/panel" target="_blank" class="text-decoration-none">Panel de Desarrolladores</a>.</small>
                            </div>
                            
                            <div class="col-md-4 mb-4">
                                <label class="form-label text-muted fw-bold">Entorno (Modo)</label>
                                <select name="mp_mode" class="form-select form-select-lg shadow-none">
                                    <option value="sandbox" {{ ($configs['mp_mode'] ?? '') == 'sandbox' ? 'selected' : '' }}>Pruebas (Sandbox)</option>
                                    <option value="production" {{ ($configs['mp_mode'] ?? '') == 'production' ? 'selected' : '' }}>Producción (Ventas Reales)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-5 mb-4 text-center">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 btn-animated-save" id="btnGuardar">
                    <i class="bi bi-check-circle me-1"></i>
                    <span id="btnText">Guardar Todo</span>
                    <div class="spinner-border spinner-border-sm d-none" id="loader" role="status"></div>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
@include('admin.configuracion.configuracion.configuracionScript')
@endpush