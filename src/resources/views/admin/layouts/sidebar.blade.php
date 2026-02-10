
@php
    $productosActive =
        request()->routeIs('admin.productos.*') ||
        request()->routeIs('admin.categorias*') ||
        request()->routeIs('admin.marcas*');

    $promocionesActive =
        request()->routeIs('admin.promociones.ofertas*');
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
               aria-expanded="{{ $productosActive ? 'true' : 'false' }}">
                Productos
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <div class="collapse {{ $productosActive ? 'show' : '' }}"
                 id="productosSubmenu"
                 style="margin-left:20px; margin-top:5px;">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">

                    <li>
                        <a href="{{ route('admin.productos.productos') }}"
                           class="nav-link ps-4 {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                            Productos
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.categorias') }}"
                           class="nav-link ps-4 {{ request()->routeIs('admin.categorias*') ? 'active' : '' }}">
                            Categorías
                        </a>
                    </li>

                    <li>
                        <a href="#"
                           class="nav-link ps-4">
                            Marcas
                        </a>
                    </li>

                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center {{ $promocionesActive ? 'active' : '' }}"
               data-bs-toggle="collapse"
               href="#promocionesSubmenu"
               aria-expanded="{{ $promocionesActive ? 'true' : 'false' }}">
                Promociones
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>

            <div class="collapse {{ $promocionesActive ? 'show' : '' }}"
                 id="promocionesSubmenu"
                 style="margin-left:20px; margin-top:5px;">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">

                    <li>
                        <a href="{{ route('admin.ofertas') }}"
                           class="nav-link ps-4 {{ request()->routeIs('admin.ofertas*') ? 'active' : '' }}">
                            Ofertas
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#">
                Usuarios
            </a>
        </li>

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