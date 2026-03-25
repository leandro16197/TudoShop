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
            scrollY: "500px",   
            scrollCollapse: true,  
            paging: true,  
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
                    data: 'imagen',
                    searchable: false,
                    orderable: false,
                    className: 'text-center align-middle',
                    render: img =>
                        img
                            ? `
                                <div style="
                                    width:100%;
                                    height:90px;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                ">
                                    <img src="${img}"
                                        style="
                                            max-height:70px;
                                            max-width:100px;
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

        $('#formCreateCategory').submit(function (e) {
            e.preventDefault();

            const form = $(this)[0];
            const formData = new FormData(form);
            const editId = $(this).attr('data-edit-id');

            const url = editId
                ? `/frontend/v2/categorias/${editId}`
                : $(this).attr('action');

            showLoading();
            $('#createCategoryModal').modal('hide');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function () {
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

                    appCustom.smallBox('nok', msg, null, 3000);
                },

                complete: function () {
                    form.reset();
                    $('#formCreateCategory').removeAttr('data-edit-id');
                    $('#categoryImagePreview').html('');
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

        $('#categoryImage').on('change', function () {
            const preview = $('#categoryImagePreview');
            preview.html('');

            const file = this.files[0];
            const maxSize = 5 * 1024 * 1024; 

            if (!file) return;

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
                    <div class="col-12">
                        <img src="${e.target.result}"
                            class="img-fluid rounded border border-secondary"
                            style="max-height:150px; object-fit:cover;">
                    </div>
                `);
            };

            reader.readAsDataURL(file);
        });

    });

</script>