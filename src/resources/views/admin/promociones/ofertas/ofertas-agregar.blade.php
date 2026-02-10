
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
@endpush