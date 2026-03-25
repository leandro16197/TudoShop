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
            clientesTable.ajax.reload(function () {
                hideLoading();
            }, false);
        }


        var clientesTable = $('#clientesTable').DataTable({
            responsive: true,
            language: { url: "/js/es-ES.json" },

            ajax: {
                url: $('#clientesTable').data('url'), 
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
                        'Error al cargar los clientes',
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
                    data: 'apellido', 
                    defaultContent: '-'
                },
                {
                    data: 'email', 
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
            ],
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
            initComplete: function() {
                var filaNativa = $('.dataTables_wrapper .row:first-child');
                filaNativa.addClass('d-flex justify-content-between align-items-center');
                var divCantidad = filaNativa.find('.dataTables_length').parent();
                var divBuscador = filaNativa.find('.dataTables_filter').parent();
                filaNativa.prepend(divBuscador); 
                filaNativa.append(divCantidad);  

                divBuscador.removeClass('col-md-6').addClass('col-md-4 d-flex justify-content-start');
                divCantidad.removeClass('col-md-6').addClass('col-md-4 d-flex justify-content-end align-items-center gap-2');
                $('.dataTables_filter, .dataTables_length').css({ 'margin': '0', 'width': 'auto' });
                $('.dataTables_filter input').addClass('form-control-sm');
                $('.dataTables_length select').addClass('form-select-sm');
            }
        });

        $('#formCreateCliente').submit(function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const editId = $(this).attr('data-edit-id');


            const url = editId
                ? `/frontend/v2/clientes/${editId}`
                : $(this).attr('action');

            const method = editId ? 'POST' : 'POST'; 

            showLoading();
            $('#createClienteModal').modal('hide');

            $.ajax({
                url: url,
                type: 'POST', 
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function () {
                    clientesTable.ajax.reload(null, false);

                    appCustom.smallBox(
                        'ok',
                        editId
                            ? 'Cliente actualizado correctamente'
                            : 'Cliente creado correctamente',
                        null,
                        3000
                    );
                },
                error: function (xhr) {
                    let msg = 'Ocurrió un error al guardar el cliente';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }

                    appCustom.smallBox('nok', msg, null, 'NO_TIME_OUT');
                },
                complete: function () {
                    form.reset();
                    $('#formCreateCliente').removeAttr('data-edit-id');
                    $('#createClienteModalLabel').text('Nuevo Cliente');
                    hideLoading();
                }
            });
        });

        $('#clientesTable').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            editCliente(id);
        });

        function editCliente(id) {
            var rowData = clientesTable.row(function (idx, data) {
                return data.id == id;
            }).data();

            if (!rowData) return;

            $('#formCreateCliente input[name="nombre"]').val(rowData.nombre);
            $('#formCreateCliente input[name="apellido"]').val(rowData.apellido);
            $('#formCreateCliente input[name="email"]').val(rowData.email);
            
            $('#formCreateCliente').attr('data-edit-id', id);
            $('#createClienteModalLabel').text(`Editar Cliente > ${rowData.nombre} ${rowData.apellido}`);
            $('#createClienteModal').modal('show');
        }
        let deleteClienteId = null;

        $('#clientesTable').on('click', '.btn-delete', function () {
            deleteClienteId = $(this).data('id');
            const rowData = clientesTable.row($(this).closest('tr')).data();
            $('#deleteClienteName').text(`${rowData.nombre} ${rowData.apellido}`);

            const modalEl = document.getElementById('deleteClienteModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

        $('#confirmDeleteCliente').on('click', function () {
            if (!deleteClienteId) return;

            const modalEl = document.getElementById('deleteClienteModal');
            const modal = bootstrap.Modal.getInstance(modalEl);

            document.activeElement.blur(); 
            modal.hide();

            showLoading();

            $.ajax({
                url: `/frontend/v2/clientes/${deleteClienteId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    clientesTable.ajax.reload(null, false);
                    appCustom.smallBox('ok', 'Cliente eliminado correctamente', null, 3000);
                },
                error: function () {
                    appCustom.smallBox('nok', 'Error al eliminar el cliente', null, 3000);
                },
                complete: function () {
                    hideLoading();
                    deleteClienteId = null;
                }
            });
        });

        // Limpieza al cerrar modal
        $('#createClienteModal').on('hidden.bs.modal', function () {
            $('#formCreateCliente')[0].reset();
            $('#formCreateCliente').removeAttr('data-edit-id');
            $('#createClienteModalLabel').text('Nuevo Cliente');
        });
    });
</script>