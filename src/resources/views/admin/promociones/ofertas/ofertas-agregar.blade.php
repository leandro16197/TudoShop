
<div class="modal fade" id="createOfertaModal" tabindex="-1" aria-labelledby="createOfertaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="createOfertaModalLabel">Nueva Oferta</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="formCreateOferta"
              action="{{ route('admin.ofertas.store') }}"
              method="POST"
              enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text"
                   class="form-control bg-dark text-light border-secondary"
                   name="nombre"
                   required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control bg-dark text-light border-secondary"
                      name="descripcion"
                      rows="3"
                      placeholder="Descripción de la oferta"></textarea>
          </div>

          <div class="row">
            <div class="col-6 mb-3">
              <label class="form-label">Fecha desde</label>
              <input type="date"
                     class="form-control bg-dark text-light border-secondary"
                     name="fecha_desde"
                     required>
            </div>

            <div class="col-6 mb-3">
              <label class="form-label">Fecha hasta</label>
              <input type="date"
                     class="form-control bg-dark text-light border-secondary"
                     name="fecha_hasta"
                     required>
            </div>
          </div>
          <div class="row">
              <div class="col-6 mb-3">
                  <label class="form-label">Aplicar a Marca</label>
                  <select name="marca_id" class="form-select bg-dark text-light border-secondary">
                      <option value="">Ninguna</option>
                      </select>
              </div>

              <div class="col-6 mb-3">
                  <label class="form-label">Aplicar a Categoría</label>
                  <select name="categoria_id" class="form-select bg-dark text-light border-secondary">
                      <option value="">Ninguna</option>
                      </select>
              </div>
          </div>
          <div class="row">
              <div class="col-6 mb-3">
                  <label class="form-label">Descuento (%)</label>
                  <input type="number" 
                        name="porcentaje" 
                        class="form-control bg-dark text-light border-secondary" 
                        step="0.1" 
                        min="0" 
                        max="100" 
                        placeholder="Ej: 10">
              </div>

              <div class="col-6 mb-3">
                  <label class="form-label">Mínimo de unidades</label>
                  <input type="number" 
                        name="cantidad_minima" 
                        class="form-control bg-dark text-light border-secondary" 
                        min="1" 
                        value="1">
                  <small class="text-muted">Se aplicará si lleva esta cantidad o más.</small>
              </div>
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

@push('scripts')
<script>
  $('#createOfertaModal').on('show.bs.modal', function () {
      let $marcaSelect = $('select[name="marca_id"]');
      let $catSelect = $('select[name="categoria_id"]');
      $marcaSelect.html('<option>Cargando marcas...</option>');
      $catSelect.html('<option>Cargando categorías...</option>');

      $.ajax({
          url: "{{ route('admin.ofertas.relaciones') }}", 
          method: "GET",
          success: function(data) {
              $marcaSelect.empty().append('<option value="">Ninguna</option>');
              $catSelect.empty().append('<option value="">Ninguna</option>');

              $.each(data.marcas, function(i, m) {
                  $marcaSelect.append(`<option value="${m.id}">${m.nombre}</option>`);
              });

              $.each(data.categorias, function(i, c) {
                  $catSelect.append(`<option value="${c.id}">${c.nombre}</option>`);
              });
          }
      });
  });
</script>
@endpush