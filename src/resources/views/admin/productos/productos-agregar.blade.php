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
$('#productImages').on('change', function () {
    const preview = $('#imagePreview');
    preview.html('');

    const files = this.files;
    const maxSize = 5 * 1024 * 1024; 

    for (let file of files) {
        if (file.size > maxSize) {
            appCustom.smallBox(
                'nok',
                `La imagen "${file.name}" supera los 5MB`,
                null,
                4000
            );

            this.value = '';
            preview.html('');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.append(`
                <div class="col-4 mb-2">
                    <img src="${e.target.result}"
                         class="img-fluid rounded border border-secondary"
                         style="max-height:120px; object-fit:cover;">
                </div>
            `);
        };
        reader.readAsDataURL(file);
    }
});

</script>

@endpush