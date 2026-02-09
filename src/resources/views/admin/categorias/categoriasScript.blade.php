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
        var table = $('#categoriasTable').DataTable({
            responsive: true,
            language: { url: "/js/es-ES.json" },

            ajax: {
                url: $('#categoriasTable').data('url'),
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

        $('#formCreateCategory').on('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = $(form).serialize();
            const editId = $(form).attr('data-edit-id');

            showLoading();
            $('#createCategoryModal').modal('hide');

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: formData + (editId ? '&id=' + editId : ''),
                success: function (res) {

                    reloadTable();

                    appCustom.smallBox(
                        'ok',
                        editId
                            ? 'Categoría actualizada correctamente'
                            : 'Categoría creada correctamente',
                        null,
                        3000
                    );
                },
                error: function (xhr) {
                    let msg = 'Ocurrió un error al guardar la categoría';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }

                    appCustom.smallBox('nok', msg, null, 'NO_TIME_OUT');
                },
                complete: function () {
                    form.reset();
                    $(form).removeAttr('data-edit-id');
                    $('#createCategoryModalLabel').text('Nueva Categoría');
                    hideLoading();
                }
            });
        });
        $('#categoriasTable').on('click', '.btn-edit', function (e) {
            e.preventDefault();

            const id = $(this).data('id');

            const rowData = table.row(function (idx, data) {
                return data.id == id;
            }).data();

            if (!rowData) return;

            $('#formCreateCategory input[name="nombre"]').val(rowData.nombre);
            $('#formCreateCategory').attr('data-edit-id', id);

            $('#createCategoryModalLabel').text('Editar Categoría');
            $('#createCategoryModal').modal('show');
        });


        $('#categoriasTable').on('click', '.btn-delete', function () {
            deleteCategoryId = $(this).data('id');

            const rowData = table.row($(this).closest('tr')).data();
            $('#deleteCategoryName').text(rowData.nombre);


            const modalEl = document.getElementById('deleteCategoryModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

        $('#confirmDeleteCategory').on('click', function () {
            if (!deleteCategoryId) return;

            const modalEl = document.getElementById('deleteCategoryModal');
            const modal = bootstrap.Modal.getInstance(modalEl);

            document.activeElement.blur(); 
            modal.hide();

            showLoading();

            $.ajax({
                url: `/frontend/v2/categorias/${deleteCategoryId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    table.ajax.reload(null, false);

                    appCustom.smallBox(
                        'ok',
                        'Categoría eliminada correctamente',
                        null,
                        3000
                    );
                },
                error: function () {
                    appCustom.smallBox(
                        'nok',
                        'Error al eliminar la categoría',
                        null,
                        3000
                    );
                },
                complete: function () {
                    hideLoading();
                    deleteCategoryId = null;
                }
            });
        });

    });

</script>