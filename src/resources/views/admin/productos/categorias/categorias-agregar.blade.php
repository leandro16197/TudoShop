<div class="modal fade" id="createCategoryModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content bg-dark text-light border-secondary">

      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="createCategoryModalLabel">Nueva Categoría</h5>
        <button
          type="button"
          class="btn-close btn-close-white"
          data-bs-dismiss="modal"
          aria-label="Cerrar"
        ></button>
      </div>

      <div class="modal-body">
        <form
          id="formCreateCategory"
          action="{{ route('admin.categorias.store') }}"
          method="POST"
        >
          @csrf

          <div class="mb-3">
            <label class="form-label">Nombre de la categoría</label>
            <input
              type="text"
              class="form-control bg-dark text-light border-secondary"
              name="nombre"
              placeholder="Ej: Mochilas"
              required
            >
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">
              Guardar
            </button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
