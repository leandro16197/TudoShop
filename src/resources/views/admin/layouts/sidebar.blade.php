<div id="sidebar" class="d-flex flex-column p-3 ">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
        <span class="fs-4 fw-bold">Menu</span>
    </a>
    <hr style="color: azure !important">
    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#tiendaSubmenu" role="button" aria-expanded="false" aria-controls="tiendaSubmenu">
                Productos
                <i class="bi bi-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse" id="tiendaSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li>
                        <a href="{{ route('admin.productos.productos') }}" 
                        class="nav-link ps-4 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        Productos
                        </a>
                    </li>
                    <li><a href="#" class="nav-link ps-4">Categorías</a></li>
                    <li><a href="#" class="nav-link ps-4">Marcas</a></li>
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