<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="createProductModalLabel">Nuevo Producto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="formCreateProduct" action="{{ route('admin.productos.store') }}" method="POST"  enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control bg-dark text-light border-secondary" name="name" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control bg-dark text-light border-secondary"
                      name="description"
                      rows="3"
                      placeholder="Descripción del producto"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label">Categoría</label>

              <select name="categoria_id"
                      id="productCategorias"
                      class="form-select bg-dark text-light border-secondary">

                  <option value="" selected disabled>
                      Seleccione categoría
                  </option>

              </select>
          </div>


          <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01"
                   class="form-control bg-dark text-light border-secondary"
                   name="price" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number"
                   class="form-control bg-dark text-light border-secondary"
                   name="stock" required>
          </div>

          <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="active" id="active">
            <label class="form-check-label" for="active">Activo</label>
          </div>
          <div class="mb-3">
              <label class="form-label">Imágenes (máx. 5MB c/u)</label>

              <input type="file"
                    class="form-control bg-dark text-light border-secondary"
                    name="images[]"
                    accept="image/*"
                    multiple
                    id="productImages">

              <div class="form-text text-secondary">
                  Podés subir una o más imágenes
              </div>
          </div>

          <div class="row mt-2" id="imagePreview"></div>

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
@push('scripts')
<script>
  $(document).ready(function () {
    let categoriasSelect;

    function loadCategorias(selected = null) {
        $.get("{{ route('admin.categorias.list') }}", function (response) {

            let select = $('#productCategorias');
            select.html(`
                <option value="" disabled ${!selected ? 'selected' : ''}>
                    Seleccione categoría
                </option>
            `);

            response.data.forEach(cat => {

                let isSelected = selected == cat.id ? 'selected' : '';

                select.append(`
                    <option value="${cat.id}" ${isSelected}>
                        ${cat.nombre}
                    </option>
                `);
            });

        });
    }

    $('#createProductModal').on('shown.bs.modal', function () {
        loadCategorias();
    });

  });

</script>
@endpush