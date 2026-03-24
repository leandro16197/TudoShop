<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="modalUsuarioLabel">Nuevo Usuario Administrativo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="formCreateUsuario" action="{{ route('admin.usuarios.store') }}" method="POST">
          @csrf
          <input type="hidden" name="user_id" id="user_id">

          <div class="mb-3">
            <label class="form-label small text-white-50">Nombre Completo</label>
            <input type="text" class="form-control bg-dark text-light border-secondary" name="name" id="user_name" placeholder="Ej: Juan Pérez" required>
          </div>

          <div class="mb-3">
            <label class="form-label small text-white-50">Correo Electrónico</label>
            <input type="email" class="form-control bg-dark text-light border-secondary" name="email" id="user_email" placeholder="usuario@empresa.com" required>
          </div>

          <div class="mb-3">
            <label class="form-label small text-white-50">Rol de Usuario</label>
            <select class="form-select bg-dark text-light border-secondary" name="role_id" id="user_role_id" required>
                <option value="" selected disabled>Seleccione un rol...</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}">{{ $rol->display_name ?? $rol->name }}</option>
                @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label small text-white-50">Contraseña</label>
            <input type="password" class="form-control bg-dark text-light border-secondary" name="password" id="user_password">
            <div id="passwordHelp" class="form-text text-secondary small">Mínimo 8 caracteres. <span class="text-info d-none" id="editPasswordNote">Dejar en blanco para no cambiar.</span></div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-white-50">Confirmar Contraseña</label>
            <input type="password" class="form-control bg-dark text-light border-secondary" name="password_confirmation" id="user_password_confirmation">
          </div>

          <div class="text-end mt-4">
            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="btnGuardarUsuario">
              <i class="bi bi-save me-1"></i> Guardar Usuario
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>