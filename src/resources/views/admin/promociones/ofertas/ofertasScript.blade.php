<Script>
    $(document).ready(function() {
       showLoading();

        function showLoading() {
            $('#loadingGif').addClass('show');
        }

        function hideLoading() {
            $('#loadingGif').removeClass('show');
        }

        function reloadTable() {
            ofertasTable.ajax.reload(function () {
                hideLoading();
            }, false);
        }

        var ofertasTable = $('#ofertasTable').DataTable({
            responsive: true,
            language: { url: "/js/es-ES.json" },

            ajax: {
                url: $('#ofertasTable').data('url'),
                type: 'GET',
                dataSrc: function (json) {
                    return Array.isArray(json.data) ? json.data : [];
                },
                complete: function () {
                    hideLoading();
                },
                error: function () {
                    hideLoading();
                    appCustom.smallBox(
                        'nok',
                        'Error al cargar las ofertas',
                        null,
                        4000
                    );
                }
            },

            order: [[1, 'asc']],

            columns: [
                {
                    data: 'id',
                    searchable: false,
                    defaultContent: '-'
                },
                {
                    data: 'nombre',
                    defaultContent: '-'
                },
                {
                    data: 'descripcion',
                    searchable: false,
                    defaultContent: '-'
                },
                {
                    data: 'fecha_desde',
                    defaultContent: '-'
                },
                {
                    data: 'fecha_hasta',
                    defaultContent: '-'
                },
                {
                    data: 'id',
                    searchable: false,
                    orderable: false,
                    render: data => `
                        <div class="dropdown table-actions">
                            <button class="btn btn-sm btn-dark-action dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill text-white"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-dark dropdown-actions">
                                <li>
                                    <a class="dropdown-item btn-edit" href="#" data-id="${data}">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger btn-delete" href="#" data-id="${data}">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    `
                }
            ]
        });


        $('#productsTable').on('click', '.btn-features', function (e) {
            e.preventDefault();
            openFeaturesModal($(this).data('id'));
        });


     $('#formCreateOferta').submit(function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const editId = $(this).attr('data-edit-id');

            const url = editId
                ? `/frontend/v2/ofertas/${editId}`
                : $(this).attr('action');

            showLoading();
            $('#createOfertaModal').modal('hide');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function () {
                    ofertasTable.ajax.reload(null, false);

                    appCustom.smallBox(
                        'ok',
                        editId
                            ? 'Oferta actualizada correctamente'
                            : 'Oferta creada correctamente',
                        null,
                        3000
                    );
                },
                error: function (xhr) {
                    let msg = 'Ocurrió un error al guardar la oferta';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }

                    appCustom.smallBox('nok', msg, null, 'NO_TIME_OUT');
                },
                complete: function () {
                    form.reset();
                    $('#formCreateOferta').removeAttr('data-edit-id');
                    $('#createOfertaModalLabel').text('Nueva Oferta');
                    hideLoading();
                }
            });
        });



        $('#ofertasTable').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            editOferta(id);
        });

        function editOferta(id) {
            var rowData = ofertasTable.row(function (idx, data) {
                return data.id == id;
            }).data();

            if (!rowData) return;

            $('#formCreateOferta input[name="nombre"]').val(rowData.nombre);
            $('#formCreateOferta textarea[name="descripcion"]').val(rowData.descripcion || '');
            $('#formCreateOferta input[name="fecha_desde"]').val(rowData.fecha_desde);
            $('#formCreateOferta input[name="fecha_hasta"]').val(rowData.fecha_hasta);
            $('#formCreateOferta input[name="active"]').prop('checked', !!rowData.active);

            $('#formCreateOferta').attr('data-edit-id', id);

            $('#createOfertaModalLabel').text(`Editar Oferta > ${rowData.nombre}`);

            $('#createOfertaModal').modal('show');
        }

        let deleteOfertaId = null;


        $('#ofertasTable').on('click', '.btn-delete', function () {
            deleteOfertaId = $(this).data('id');

            const rowData = ofertasTable.row($(this).closest('tr')).data();
            $('#deleteOfertaName').text(rowData.nombre);

            const modalEl = document.getElementById('deleteOfertaModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

        $('#confirmDeleteOferta').on('click', function () {
    
            if (!deleteOfertaId) return;

            const modalEl = document.getElementById('deleteOfertaModal');
            const modal = bootstrap.Modal.getInstance(modalEl);

            document.activeElement.blur(); 
            modal.hide();

            showLoading();

            $.ajax({
                url: `/frontend/v2/ofertas/${deleteOfertaId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    ofertasTable.ajax.reload(null, false);

                    appCustom.smallBox(
                        'ok',
                        'Producto eliminado correctamente',
                        null,
                        3000
                    );
                },
                error: function () {
                    appCustom.smallBox(
                        'nok',
                        'Error al eliminar el producto',
                        null,
                        3000
                    );
                },
                complete: function () {
                    hideLoading();
                    deleteProductId = null;
                }
            });
        });





        $('#createProductModal').on('hidden.bs.modal', function () {
            $('#formCreateProduct')[0].reset();
            $('#formCreateProduct').removeAttr('data-edit-id');
            $('#createProductModalLabel').text('Nuevo Producto');
        });



    });
</Script>