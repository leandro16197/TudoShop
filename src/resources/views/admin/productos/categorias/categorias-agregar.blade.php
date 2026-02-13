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
          enctype="multipart/form-data"
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
          <div class="mb-3">
            <label class="form-label">Imagen de la categoría</label>
            <input
              type="file"
              class="form-control bg-dark text-light border-secondary"
              id="categoryImage"
              name="imagen"
              accept=".jpg,.jpeg,.png,.webp"
            >
          </div>

          <div class="row" id="categoryImagePreview"></div>


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

