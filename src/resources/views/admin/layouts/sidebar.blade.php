@php
    // Lógica de navegación (se mantiene igual, está correcta)
    $productosActive = request()->routeIs('admin.productos.*') || 
                       request()->routeIs('admin.categorias*') || 
                       request()->routeIs('admin.marcas*');
    $pedidosActive = request()->routeIs('admin.pedidos*');
    $promocionesActive = request()->routeIs('admin.promociones.ofertas*') || 
                         request()->routeIs('admin.ofertas*');
    $usuariosActive = request()->routeIs('admin.clientes*');
    $configActive = request()->routeIs('admin.configuracion.*') || 
                    request()->routeIs('admin.usuarios.*');
    $rolesActive = request()->routeIs('admin.roles.*');
    $finanzasActive = request()->routeIs('admin.finanzas.*');
@endphp

<div id="sidebar" class="d-flex flex-column p-3">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none text-white">
        <span class="fs-4 fw-bold">Menu</span>
    </a>

    <hr style="color: azure !important">

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center {{ $productosActive ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#productosSubmenu" role="button" 
               aria-expanded="{{ $productosActive ? 'true' : 'false' }}">
                <span><i class="bi bi-box-seam me-2"></i> Productos</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse {{ $productosActive ? 'show' : '' }}" id="productosSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4 mt-1">
                    <li>
                        <a href="{{ route('admin.productos.productos') }}" 
                           class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                            <i class="bi bi-list-ul me-2"></i> Productos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categorias') }}" 
                           class="nav-link {{ request()->routeIs('admin.categorias*') ? 'active' : '' }}">
                            <i class="bi bi-tags me-2"></i> Categorías
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.marcas') }}" 
                           class="nav-link {{ request()->routeIs('admin.marcas*') ? 'active' : '' }}">
                            <i class="bi bi-patch-check me-2"></i> Marcas
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center {{ $pedidosActive ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#pedidosSubmenu" role="button"
               aria-expanded="{{ $pedidosActive ? 'true' : 'false' }}">
                <span><i class="bi bi-cart3 me-2"></i> Pedidos</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse {{ $pedidosActive ? 'show' : '' }}" id="pedidosSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4 mt-1">
                    <li>
                        <a href="{{ route('admin.pedidos') }}" 
                           class="nav-link {{ request()->routeIs('admin.pedidos*') ? 'active' : '' }}">
                            <i class="bi bi-list-check me-2"></i> Gestión de Pedidos
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center {{ $promocionesActive ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#promocionesSubmenu" role="button"
               aria-expanded="{{ $promocionesActive ? 'true' : 'false' }}">
                <span><i class="bi bi-megaphone me-2"></i> Promociones</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse {{ $promocionesActive ? 'show' : '' }}" id="promocionesSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4 mt-1">
                    <li>
                        <a href="{{ route('admin.ofertas') }}" 
                           class="nav-link {{ request()->routeIs('admin.ofertas*') ? 'active' : '' }}">
                            <i class="bi bi-percent me-2"></i> Ofertas
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center {{ $usuariosActive ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#usuariosSubmenu" role="button"
               aria-expanded="{{ $usuariosActive ? 'true' : 'false' }}">
                <span><i class="bi bi-people me-2"></i> Clientes</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse {{ $usuariosActive ? 'show' : '' }}" id="usuariosSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4 mt-1">
                    <li>
                        <a href="{{ route('admin.clientes') }}" 
                           class="nav-link {{ request()->routeIs('admin.clientes*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge me-2"></i> Clientes
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center {{ $configActive ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#configSubmenu" role="button"
               aria-expanded="{{ $configActive ? 'true' : 'false' }}">
                <span><i class="bi bi-gear me-2"></i> Configuración</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse {{ $configActive ? 'show' : '' }}" id="configSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4 mt-1">
                    <li>
                        <a href="{{ route('admin.configuracion.general') }}" 
                           class="nav-link {{ request()->routeIs('admin.configuracion.general') ? 'active' : '' }}">
                            <i class="bi bi-sliders me-2"></i> General
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.usuarios.index') }}" 
                           class="nav-link {{ request()->routeIs('admin.usuarios.index') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i> Usuarios
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center {{ $rolesActive ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#rolesSubmenu" role="button"
               aria-expanded="{{ $rolesActive ? 'true' : 'false' }}">
                <span><i class="bi bi-shield-lock me-2"></i> Permisos</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse {{ $rolesActive ? 'show' : '' }}" id="rolesSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4 mt-1">
                    <li>
                        <a href="{{ route('admin.roles.index') }}" 
                           class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
                            <i class="bi bi-person-badge me-2"></i> Gestionar Roles
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center {{ $finanzasActive ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#finanzasSubmenu" role="button"
               aria-expanded="{{ $finanzasActive ? 'true' : 'false' }}">
                <span><i class="bi bi-wallet2 me-2"></i> Finanzas</span>
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse {{ $finanzasActive ? 'show' : '' }}" id="finanzasSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4 mt-1">
                    <li>
                        <a href="{{ route('admin.finanzas.index') }}" 
                           class="nav-link {{ request()->routeIs('admin.finanzas.index') ? 'active' : '' }}">
                            <i class="bi bi-graph-up-arrow me-2"></i> Pagos y Reportes
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('#sidebar');
    sidebar.querySelectorAll('.nav-link[data-bs-toggle="collapse"]').forEach(link => {
        link.addEventListener('click', function() {
            const otherItems = sidebar.querySelectorAll('.collapse.show');
            otherItems.forEach(item => {
                if (item !== document.querySelector(this.getAttribute('href'))) {
                    const bsCollapse = bootstrap.Collapse.getInstance(item);
                    if (bsCollapse) bsCollapse.hide();
                }
            });
        });
    });
    sidebar.addEventListener('show.bs.collapse', function (e) {
        const button = document.querySelector(`[href="#${e.target.id}"]`);
        if (button) button.classList.add('menu-open');
    });
    sidebar.addEventListener('hide.bs.collapse', function (e) {
        const button = document.querySelector(`[href="#${e.target.id}"]`);
        if (button) button.classList.remove('menu-open');
    });
});
</script>