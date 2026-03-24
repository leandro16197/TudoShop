<div class="modal fade" id="modalRol" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalRolLabel">Configurar Rol</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formCreateRol" action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="rol_id">
                    <div class="mb-3">
                        <label class="form-label small text-white-50">Nombre Interno (Slug)</label>
                        <input type="text" class="form-control bg-dark text-light border-secondary" 
                               name="name" id="rol_name" placeholder="ej: admin_ventas" required>
                        <div class="form-text text-secondary small">Sin espacios, solo minúsculas y guiones.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-white-50">Nombre Visible (Display Name)</label>
                        <input type="text" class="form-control bg-dark text-light border-secondary" 
                               name="display_name" id="rol_display_name" placeholder="ej: Administrador de Ventas">
                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardarRol">
                            <i class="bi bi-shield-check me-1"></i> Guardar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>