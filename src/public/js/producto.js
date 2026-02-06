$(document).ready(function() {
    function showLoading() {
        $('#loadingGif').addClass('show');
    }

    function hideLoading() {
        $('#loadingGif').removeClass('show');
    }
    function reloadTable() {
        showLoading();
        table.ajax.reload(function() {
            hideLoading();
        }, false);
    }
    var table = $('#productsTable').DataTable({
        responsive: true,
        language: { url: "/js/es-ES.json" },
        ajax: {
            url: $('#productsTable').data('url')
        },
        order: [[1, 'asc']],
        columns: [
            { data: 'id', searchable: false },
            { data: 'name' },
            { data: 'description', searchable: false },
            { data: 'price', searchable: false, render: d => '$' + parseFloat(d).toFixed(2) },
            { data: 'stock', searchable: false },
            { data: 'active', searchable: false, render: d =>
                d ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>'
            },
            {
                data: 'id',
                searchable: false,
                orderable: false,
                render: data => `
                    <button class="btn btn-sm btn-primary btn-edit" data-id="${data}">Editar</button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${data}">Eliminar</button>
                `
            }
        ],
        initComplete: function() { $('#loadingGif').hide(); }
    });


    $('#loadingGif').show();

    $('#formCreateProduct').submit(function (e) {
        e.preventDefault();

        const form = $(this);
        const formData = form.serialize();
        const editId = form.attr('data-edit-id');

        const url = editId
            ? `/frontend/v2/productos/${editId}`
            : form.attr('action');

        showLoading();

        $('#createProductModal').modal('hide');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (product) {

                if (editId) {
                    const row = table.row(function (idx, data) {
                        return data.id == editId;
                    });

                    row.data({
                        id: product.id,
                        name: product.name,
                        description: product.description || '',
                        price: product.price,
                        stock: product.stock,
                        active: product.active
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
                        name: product.name,
                        description: product.description || '',
                        price: product.price,
                        stock: product.stock,
                        active: product.active
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
                } else if (xhr.responseJSON?.msg) {
                    msg = xhr.responseJSON.msg;
                }

                appCustom.smallBox(
                    'nok',
                    msg,
                    null,
                    'NO_TIME_OUT'
                );
            },
            complete: function () {
                form[0].reset();
                form.removeAttr('data-edit-id');
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
