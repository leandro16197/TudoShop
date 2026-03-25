<style>

.navbar {
    background-color: #111827 !important; 
    border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
}

.l-spacing-1 {
    letter-spacing: 2px;
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7) !important;
    text-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
}

.navbar-logo {
    filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.1));
    transition: all 0.3s ease;
}

.navbar-logo:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 0 8px rgba(13, 110, 253, 0.4));
}

.user-pill {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    transition: all 0.2s ease;
}

.user-pill:hover {
    background: rgba(255, 255, 255, 0.07);
    border-color: rgba(13, 110, 253, 0.5) !important;
}

.dropdown-menu.animate {
    background-color: #1f2937 !important; 
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    z-index: 1050; 
}

.dropdown-item {
    color: #ffffff !important;
    font-size: 0.9rem;
    padding: 0.7rem 1.2rem;
    display: flex;
    align-items: center;
}

.dropdown-header-custom {
    color: rgba(255, 255, 255, 0.5) !important;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0.5rem 1.2rem;
}

.dropdown-item:hover {
    background-color: rgba(13, 110, 253, 0.2) !important; 
    color: #ffffff !important;
}

.dropdown-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-2 shadow-sm">
  <div class="container-fluid px-4">
    
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link p-0" href="{{ route('dashboard') }}">
                <img src="{{ $logo_navbar }}" 
                    alt="ShopTudo Logo" 
                    class="navbar-logo img-fluid" 
                    style="height: 42px;">
            </a>
        </li>
    </ul> 

    <span class="navbar-brand position-absolute top-50 start-50 translate-middle text-uppercase l-spacing-1 d-none d-lg-block fw-bold">
      <i class="bi bi-layers-half me-2 opacity-50"></i>Panel de Administración
    </span>

    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown user-pill rounded-pill px-1"> 
        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-white py-1" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
          <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
              <i class="bi bi-person-fill text-white fs-6"></i>
          </div>
          <span class="fw-medium small me-1">{{ Auth::user()->name }}</span>
        </a>
        
        <ul class="dropdown-menu dropdown-menu-end animate slideIn shadow-lg">
          <li class="dropdown-header-custom">Administrador</li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="dropdown-item" type="submit">
                <i class="bi bi-power text-danger me-2"></i> 
                <span>Cerrar Sesión</span>
              </button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

