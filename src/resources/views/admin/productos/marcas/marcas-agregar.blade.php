<div class="modal fade" id="createMarcaModal" tabindex="-1" aria-labelledby="createMarcaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="createMarcaModalLabel">Nueva Marca</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="formCreateMarca" action="{{ route('admin.marcas.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control bg-dark text-light border-secondary" name="nombre" required>
          </div>

          <div class="mb-3">
              <label class="form-label">Imagen de Marca (máx. 5MB)</label>

              <input type="file"
                    class="form-control bg-dark text-light border-secondary"
                    name="img"
                    accept="image/*"
                    id="marcaImage">

              <div class="form-text text-secondary">
                  Solo se permite una imagen
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
  $('#createMarcaModal').on('hidden.bs.modal', function () {

      $('#formCreateMarca')[0].reset();

      $('#imagePreview').html('');

      $('#marcaImage').val('');

  });
  $('#marcaImage').on('change', function () {

      const preview = $('#imagePreview');
      preview.html('');

      const file = this.files[0];
      if (!file) return;

      const maxSize = 5 * 1024 * 1024;

      if (file.size > maxSize) {
          appCustom.smallBox(
              'nok',
              `La imagen "${file.name}" supera los 5MB`,
              null,
              4000
          );

          this.value = '';
          return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
          preview.html(`
              <div class="col-6 mb-2">
                  <img src="${e.target.result}"
                      class="img-fluid rounded border border-secondary"
                      style="max-height:150px; object-fit:cover;">
              </div>
          `);
      };

      reader.readAsDataURL(file);
  });
</script>
@endpush
