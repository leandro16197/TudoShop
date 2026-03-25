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
        var table = $('#productsTable').DataTable({
            responsive: true,
            language: { url: "/js/es-ES.json" },
            scrollY: "500px",
            scrollCollapse: true,
            paging: true,
            order: [[1, 'asc']],
            ajax: {
                url: $('#productsTable').data('url'),
                type: 'GET',
                data: function(d) {
                    d.stock = $('#filterStock').val();
                    d.categoria_id = $('#filterCategoria').val();
                    d.marca_id = $('#filterMarca').val();
                },
                dataSrc: function (json) {
                    return Array.isArray(json.data) ? json.data : [];
                },
                complete: function () {
                    hideLoading();
                },
                error: function () {
                    hideLoading();
                    appCustom.smallBox('nok', 'Error al cargar los productos', null, 4000);
                }
            },
            columns: [
                { data: 'id', searchable: false, defaultContent: '-' },
                { data: 'name', defaultContent: '-',searchable: true },
                {
                    data: 'image',
                    searchable: false,
                    orderable: false,
                    className: 'text-center',
                    render: img => img ? `
                        <div style="width:110px;height:80px;display:flex;align-items:center;justify-content:center;border-radius:8px;padding:6px;">
                            <img src="${img}" style="max-width:100%; max-height:100%; object-fit:contain;">
                        </div>` : '<span class="text-muted">—</span>'
                },
                { data: 'description', searchable: false, defaultContent: '-' },
                { data: 'categorias', searchable: false, defaultContent: '-' },
                { data: 'marcas', searchable: true, defaultContent: '-' },
                {
                    data: 'price',
                    searchable: false,
                    defaultContent: 0,
                    render: d => '$' + parseFloat(d ?? 0).toFixed(2)
                },
                { data: 'stock', searchable: false, defaultContent: 0 },
                {
                    data: 'active',
                    searchable: false,
                    defaultContent: 0,
                    render: d => Number(d) 
                        ? '<span class="badge bg-success">Sí</span>' 
                        : '<span class="badge bg-secondary">No</span>'
                },
                {
                    data: 'id',
                    searchable: false,
                    orderable: false,
                    defaultContent: '',
                    render: data => data ? `
                        <div class="dropdown table-actions">
                            <button class="btn btn-sm btn-dark-action dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill text-white"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-actions">
                                <li><a class="dropdown-item btn-edit" href="#" data-id="${data}"><i class="bi bi-pencil"></i> Editar</a></li>
                                <li><a class="dropdown-item text-danger btn-delete" href="#" data-id="${data}"><i class="bi bi-trash"></i> Eliminar</a></li>
                            </ul>
                        </div>` : ''
                }
            ],
            dom: '<"row"<"col-md-4"f><"col-md-4 text-center"i><"col-md-4"l>>rtip', 
            initComplete: function() {
                var filaNativa = $('.dataTables_wrapper .row:first-child');
                filaNativa.addClass('d-flex justify-content-between align-items-center mb-3');

                var divBuscador = filaNativa.find('.dataTables_filter').parent();
                var divCantidad = filaNativa.find('.dataTables_length').parent();
                var filtrosHtml = $('#contenedorFiltrosProductos').removeClass('d-none').detach();
                filaNativa.empty();
                var colIzq = $('<div class="col-md-3 d-flex justify-content-start align-items-center gap-2"></div>');
                colIzq.append(divBuscador.find('.dataTables_filter'));
                var colCentro = $('<div class="col-md-6 d-flex justify-content-center align-items-center"></div>');
                colCentro.append(filtrosHtml);
                var colDer = $('<div class="col-md-3 d-flex justify-content-end align-items-center gap-2"></div>');
                colDer.append(divCantidad.find('.dataTables_length'));
                filaNativa.append(colIzq).append(colCentro).append(colDer);
                $('.dataTables_filter, .dataTables_length').css({ 'margin': '0', 'width': 'auto' });
                $('.dataTables_filter input').addClass('form-control-sm').attr('placeholder', 'Buscar...');
                $('.dataTables_length select').addClass('form-select-sm');
                $(document).on('change', '#filterStock, #filterCategoria, #filterMarca', function() {
                    table.ajax.reload();
                });
                $(document).on('keyup', '.dataTables_filter input', function() {
                    table.search(this.value).draw();
                });

                $(document).on('click', '#btnResetFilters', function() {
                    $('#filterStock, #filterCategoria, #filterMarca').val('');
                    table.ajax.reload();
                });
            }
        });



        $('#productsTable').on('click', '.btn-features', function (e) {
            e.preventDefault();
            openFeaturesModal($(this).data('id'));
        });


        $('#formCreateProduct').submit(function (e) {
            e.preventDefault();

            const form = $(this)[0];
            const formData = new FormData(form);
            const editId = $(this).attr('data-edit-id');
            if (editId) {
                formData.append('_method', 'PUT');
            }

            const url = editId
                ? `/frontend/v2/productos/${editId}` 
                : $(this).attr('action');           
            showLoading();
            
            $('#createProductModal').modal('hide');

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
                    table.ajax.reload(null, false);

                    appCustom.smallBox(
                        'ok',
                        editId ? 'Producto actualizado con éxito' : 'Producto creado con éxito',
                        null,
                        3000
                    );
                },
                error: function (xhr) {

                    $('#createProductModal').modal('show');
                    
                    let msg = 'Error al procesar la solicitud';
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                    appCustom.smallBox('nok', msg, null, 4000);
                },
                complete: function () {
                    hideLoading();
                }
            });
        });


        $('#productsTable').on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            editProduct(id);
        });

        function editProduct(id) {
            var rowData = table.row(function (idx, data) {
                return data.id == id;
            }).data();

            if (!rowData) return;

            $('#formCreateProduct')[0].reset();
            $('#imagePreview').html('');
            $('#formCreateProduct input[name="name"]').val(rowData.name);
            $('#formCreateProduct textarea[name="description"]').val(rowData.description || '');
            $('#formCreateProduct input[name="price"]').val(rowData.price);
            $('#formCreateProduct input[name="stock"]').val(rowData.stock);
            $('#formCreateProduct input[name="active"]').prop('checked', Number(rowData.active) === 1);

            loadCategorias(rowData.categoria_id); 
            loadMarcas(rowData.marca_id);

            $('#formCreateProduct').attr('data-edit-id', id);
            $('#createProductModalLabel').html(`<i class="bi bi-pencil-square"></i> Editar Producto: <span class="text-primary">${rowData.name}</span>`);
            $('#createProductModal').modal('show');
        }

        function loadCategorias(selected = null) {
            return $.get("{{ route('admin.categorias.list') }}", function (response) {
                let select = $('#productCategorias');
                
                select.html(`<option value="" disabled ${!selected ? 'selected' : ''}>Seleccione categoría</option>`);

                if(response.data) {
                    response.data.forEach(cat => {
                        let isSelected = (selected == cat.id) ? 'selected' : '';
                        select.append(`<option value="${cat.id}" ${isSelected}>${cat.nombre}</option>`);
                    });
                }
            });
        }
        function loadMarcas(selected = null) {
            return $.get("{{ route('admin.marcas') }}", function (response) {
                let select = $('#productMarcas');
                select.html(`<option value="" disabled ${!selected ? 'selected' : ''}>Seleccione marca</option>`);

                if(response.data) {
                    response.data.forEach(marca => {
                        let isSelected = (selected == marca.id) ? 'selected' : '';
                        select.append(`<option value="${marca.id}" ${isSelected}>${marca.nombre}</option>`);
                    });
                }
            }).fail(function() {
                console.error("Error al cargar las marcas");
            });
        }
        let deleteProductId = null;


        $('#productsTable').on('click', '.btn-delete', function () {
            deleteProductId = $(this).data('id');

            const rowData = table.row($(this).closest('tr')).data();
            $('#deleteProductName').text(rowData.name);

            const modalEl = document.getElementById('deleteProductModal');
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

        $('#confirmDeleteProduct').on('click', function () {
            if (!deleteProductId) return;

            const modalEl = document.getElementById('deleteProductModal');
            const modal = bootstrap.Modal.getInstance(modalEl);

            document.activeElement.blur(); 
            modal.hide();

            showLoading();

            $.ajax({
                url: `/frontend/v2/productos/${deleteProductId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    table.ajax.reload(null, false);

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
            loadCategorias(); 
        });

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
        
    });

</script>