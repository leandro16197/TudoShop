
@php
    $productosActive =
        request()->routeIs('admin.productos.*') ||
        request()->routeIs('admin.categorias*') ||
        request()->routeIs('admin.marcas*');

    $promocionesActive = request()->routeIs('admin.promociones.ofertas*');
    $marcasActive = request()->routeIs('admin.marcas*');
    $usuariosActive = request()->routeIs('admin.clientes*');
@endphp

<div id="sidebar" class="d-flex flex-column p-3 ">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
        <span class="fs-4 fw-bold">Menu</span>
    </a>

    <hr style="color: azure !important">

        <ul class="nav nav-pills flex-column mb-auto">

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ $productosActive ? 'active' : '' }}"
                data-bs-toggle="collapse"
                href="#productosSubmenu"
                role="button"
                aria-expanded="{{ $productosActive ? 'true' : 'false' }}">
                    <span>
                        <i class="bi bi-box-seam me-2"></i> Productos
                    </span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </a>

                <div class="collapse {{ $productosActive ? 'show' : '' }}"
                    id="productosSubmenu"
                    style="margin-left:20px; margin-top:5px;">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li>
                            <a href="{{ route('admin.productos.productos') }}"
                            class="nav-link ps-4 {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                                <i class="bi bi-list-ul me-2"></i> Listado
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categorias') }}"
                            class="nav-link ps-4 {{ request()->routeIs('admin.categorias*') ? 'active' : '' }}">
                                <i class="bi bi-tags me-2"></i> Categorías
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.marcas') }}"
                            class="nav-link ps-4 {{ $marcasActive ? 'active' : '' }}">
                                <i class="bi bi-patch-check me-2"></i> Marcas
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ $promocionesActive ? 'active' : '' }}"
                data-bs-toggle="collapse"
                href="#promocionesSubmenu"
                role="button"
                aria-expanded="{{ $promocionesActive ? 'true' : 'false' }}">
                    <span>
                        <i class="bi bi-megaphone me-2"></i> Promociones
                    </span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </a>

                <div class="collapse {{ $promocionesActive ? 'show' : '' }}"
                    id="promocionesSubmenu"
                    style="margin-left:20px; margin-top:5px;">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li>
                            <a href="{{ route('admin.ofertas') }}"
                            class="nav-link ps-4 {{ request()->routeIs('admin.ofertas*') ? 'active' : '' }}">
                                <i class="bi bi-percent me-2"></i> Ofertas
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ $usuariosActive ? 'active' : '' }}"
                data-bs-toggle="collapse"
                href="#usuariosSubmenu"
                role="button"
                aria-expanded="{{ $usuariosActive ? 'true' : 'false' }}"
                aria-controls="usuariosSubmenu">
                    <span>
                        <i class="bi bi-people me-2"></i> Clientes
                    </span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </a>

                <div class="collapse {{ $usuariosActive ? 'show' : '' }}"
                    id="usuariosSubmenu"
                    style="margin-left:20px; margin-top:5px;">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li>
                            <a href="{{ route('admin.clientes') }}"
                            class="nav-link ps-4 {{ request()->routeIs('admin.clientes*') ? 'active' : '' }}">
                                <i class="bi bi-person-badge me-2"></i> Clientes
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

    </ul>
</div>


<script>
    document.querySelectorAll('#sidebar .nav-link[data-bs-toggle="collapse"]').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('#sidebar .nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>