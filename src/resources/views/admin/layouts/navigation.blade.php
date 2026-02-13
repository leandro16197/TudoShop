<nav class="navbar navbar-expand-lg navbar-dark border-secondary position-relative">
  <div class="container-fluid position-relative">
    
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link active" href="{{ route('dashboard') }}">ShopTudo</a>
      </li>
    </ul>


    <span class="navbar-brand position-absolute top-50 start-50 translate-middle text-white fw-bold">
      Panel de Administración
    </span>


    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          {{ Auth::user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="dropdown-item" type="submit">Log Out</button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
