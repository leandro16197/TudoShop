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

            ajax: {
                url: $('#productsTable').data('url'),
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
                    data: 'name',
                    defaultContent: '-'
                },
                {
                    data: 'image',
                    searchable: false,
                    orderable: false,
                    render: img =>
                        img
                            ? `<img src="${img}" style="width:40px;height:40px;object-fit:cover;border-radius:6px">`
                            : '<span class="text-muted">—</span>'
                },
                {
                    data: 'description',
                    searchable: false,
                    defaultContent: '-'
                },
                {
                    data: 'price',
                    searchable: false,
                    defaultContent: 0,
                    render: d => '$' + parseFloat(d ?? 0).toFixed(2)
                },
                {
                    data: 'stock',
                    searchable: false,
                    defaultContent: 0
                },
                {
                    data: 'active',
                    searchable: false,
                    defaultContent: 0,
                    render: d =>
                        Number(d)
                            ? '<span class="badge bg-success">Sí</span>'
                            : '<span class="badge bg-secondary">No</span>'
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



        $('#productsTable').on('click', '.btn-features', function (e) {
            e.preventDefault();
            openFeaturesModal($(this).data('id'));
        });


        $('#formCreateProduct').submit(function (e) {
            e.preventDefault();

            const form = $(this)[0];
            const formData = new FormData(form);
            const editId = $(this).attr('data-edit-id');

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

                success: function (res) {

                    const product = res.product; 

                    if (editId) {

                        const row = table.row(function (idx, data) {
                            return data.id == editId;
                        });

                        row.data({
                            id: product.id,
                            name: product.name ?? '-',
                            description: product.description ?? '',
                            price: product.price ?? 0,
                            stock: product.stock ?? 0,
                            active: product.active ? 1 : 0
                        }).draw(false);

                        appCustom.smallBox(
                            'ok',
                            'Producto actualizado correctamente',
                            null,
                            3000
                        );

                    } else {

                        table.row.add({
                            id: product.id,
                            name: product.name ?? '-',
                            description: product.description ?? '',
                            price: product.price ?? 0,
                            stock: product.stock ?? 0,
                            active: product.active ? 1 : 0
                        }).draw(false);

                        appCustom.smallBox(
                            'ok',
                            'Producto creado correctamente',
                            null,
                            3000
                        );
                    }
                },

                error: function (xhr) {
                    let msg = 'Ocurrió un error al guardar el producto';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }

                    appCustom.smallBox('nok', msg, null, 'NO_TIME_OUT');
                },

                complete: function () {
                    form.reset();
                    $('#formCreateProduct').removeAttr('data-edit-id');
                    $('#createProductModalLabel').text('Nuevo Producto');
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

            $('#formCreateProduct input[name="name"]').val(rowData.name);
            $('#formCreateProduct textarea[name="description"]').val(rowData.description || '');
            $('#formCreateProduct input[name="price"]').val(rowData.price);
            $('#formCreateProduct input[name="stock"]').val(rowData.stock);
            $('#formCreateProduct input[name="active"]').prop('checked', !!rowData.active);

            $('#formCreateProduct').attr('data-edit-id', id);

            $('#createProductModalLabel').text(`Editar Producto > ${rowData.name}`);

            $('#createProductModal').modal('show');
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
                    table.row(function (idx, data) {
                        return data.id == deleteProductId;
                    }).remove().draw(false);

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
                        'NO_TIME_OUT'
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

</script>