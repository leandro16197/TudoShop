<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">
          <i class="bi bi-exclamation-triangle text-danger"></i>
          Confirmar eliminación
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="mb-1">¿Seguro que querés eliminar el producto?</p>
        <strong id="deleteProductName" class="text-warning"></strong>
        <p class="text-muted mt-2 mb-0">Esta acción no se puede deshacer.</p>
      </div>

      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
          Cancelar
        </button>
        <button type="button" class="btn btn-danger" id="confirmDeleteProduct">
          Eliminar
        </button>
      </div>
    </div>
  </div>
</div>
