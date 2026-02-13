<script>
    $(document).ready(function() {
        showLoading();
        function showLoading() {
            $('#loadingGif').addClass('show');
        }

        function hideLoading() {
            $('#loadingGif').removeClass('show');
        }
        function reloadTable() {
            console.log("entro aca");
            
            table.ajax.reload(function() {
                hideLoading();
            }, false);
        }
        var table = $('#marcasTable').DataTable({
            responsive: true,
            language: { url: "/js/es-ES.json" },
            scrollY: "500px",   
            scrollCollapse: true,  
            paging: true,  
            ajax: {
                url: $('#marcasTable').data('url'),
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
                        'Error al cargar los productos',
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
                    data: 'img',
                    searchable: false,
                    orderable: false,
                    className: 'text-center',
                    render: img =>
                        img
                            ? `
                                <div style="width:110px;height:80px;display:flex;align-items:center;justify-content:center;border-radius:8px;padding:6px;
                                ">
                                    <img src="${img}"
                                        style="
                                            max-width:100%;
                                            max-height:100%;
                                            object-fit:contain;
                                        ">
                                </div>
                            `
                            : '<span class="text-muted">—</span>'
                },

                {
                    data: 'id',
                    searchable: false,
                    orderable: false,
                    defaultContent: '',
                    render: data => data
                        ? `
                            <div class="dropdown table-actions">
                                <button
                                    class="btn btn-sm btn-dark-action dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <i class="bi bi-gear-fill text-white"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-dark dropdown-actions">
                                    <li>
                                        <a class="dropdown-item btn-features" href="#" data-id="${data}">
                                            <i class="bi bi-sliders"></i> Características
                                        </a>
                                    </li>
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
                        : ''
                }
            ]
        });



        $('#marcasTable').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            editMarca(id);
        });

        function editMarca(id) {
            var rowData = table.row(function (idx, data) {
                return data.id == id;
            }).data();

            if (!rowData) return;

            $('#formCreateMarca input[name="nombre"]').val(rowData.nombre);
            $('#formCreateMarca input[name="activa"]').prop('checked', !!rowData.activa);

            $('#formCreateMarca').attr('data-edit-id', id);

            $('#createMarcaModalLabel').text(`Editar Marca > ${rowData.nombre}`);

            $('#createMarcaModal').modal('show');
        }

        let deleteMarcaId = null;

        $('#marcasTable').on('click', '.btn-delete', function () {
            deleteMarcaId = $(this).data('id');

            const rowData = table.row($(this).closest('tr')).data();
            $('#deleteMarcaName').text(rowData.nombre);

            const modalEl = document.getElementById('deleteMarcaModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

        $('#confirmDeleteMarca').on('click', function () {
            if (!deleteMarcaId) return;

            const modalEl = document.getElementById('deleteMarcaModal');
            const modal = bootstrap.Modal.getInstance(modalEl);

            document.activeElement.blur(); 
            modal.hide();

            showLoading();

            $.ajax({
                url: `/frontend/v2/marcas/${deleteMarcaId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    table.ajax.reload(null, false);

                    appCustom.smallBox(
                        'ok',
                        'Marca eliminada correctamente',
                        null,
                        3000
                    );
                },
                error: function () {
                    appCustom.smallBox(
                        'nok',
                        'Error al eliminar la marca',
                        null,
                        3000
                    );
                },
                complete: function () {
                    hideLoading();
                    deleteMarcaId = null;
                }
            });
        });

        $('#formCreateMarca').submit(function (e) {
            e.preventDefault();

            const form = $(this)[0];
            const formData = new FormData(form);
            const editId = $(this).attr('data-edit-id');

            const url = editId
                ? `/frontend/v2/marcas/${editId}`
                : $(this).attr('action');

            showLoading();
            $('#createMarcaModal').modal('hide');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function () {
                    table.ajax.reload(null, false);
                    table.draw(false);

                    appCustom.smallBox(
                        'ok',
                        editId
                            ? 'Marca actualizada correctamente'
                            : 'Marca creada correctamente',
                        null,
                        3000
                    );
                },
                error: function (xhr) {
                    let msg = 'Ocurrió un error al guardar la marca';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }

                    appCustom.smallBox('nok', msg, null,3000);
                },
                complete: function () {
                    form.reset();
                    $('#formCreateMarca').removeAttr('data-edit-id');
                    $('#createMarcaModalLabel').text('Nueva Marca');
                    hideLoading();
                }
            });
        });


        $('#createMarcaModal').on('hidden.bs.modal', function () {
            $('#formCreateMarca')[0].reset();
            $('#formCreateMarca').removeAttr('data-edit-id');
            $('#createMarcaModalLabel').text('Nueva Marca');
        });




    });

</script>