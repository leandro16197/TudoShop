<div class="modal fade" id="createClienteModal" tabindex="-1" aria-labelledby="createClienteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="createClienteModalLabel">Nuevo Cliente</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="formCreateCliente"
              action="{{ route('clientes.store') }}"
              method="POST">
          @csrf

          <div class="row">
            <div class="col-6 mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control bg-dark text-light border-secondary" name="nombre" required>
            </div>
            <div class="col-6 mb-3">
              <label class="form-label">Apellido</label>
              <input type="text" class="form-control bg-dark text-light border-secondary" name="apellido" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control bg-dark text-light border-secondary" name="email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control bg-dark text-light border-secondary" name="password" placeholder="Mínimo 8 caracteres">
            <small class="text-muted">Si estás editando, dejalo vacío para no cambiarla.</small>
          </div>

          <div class="text-end mt-3">
            <button type="button" class="btn btn-outline-light me-2" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>