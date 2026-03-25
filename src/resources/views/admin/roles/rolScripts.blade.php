<script>
$(document).ready(function() {

    showLoading();

    function showLoading() { $('#loadingGif').addClass('show'); }
    function hideLoading() { $('#loadingGif').removeClass('show'); }

    const tablaRoles = $('#rolesTable').DataTable({
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
        responsive: true,
        processing: false,
        serverSide: false,
        order: [[0, 'desc']],
        language: { 
            url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
            lengthMenu: "_MENU_" 
        },
        ajax: {
            url: "{{ route('admin.roles.index') }}",
            type: "GET",
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataSrc: 'data',
            complete: function () { hideLoading(); },
            error: function () {
                hideLoading();
                if(typeof appCustom !== 'undefined') {
                    appCustom.smallBox('nok', 'Error al cargar los roles', null, 4000);
                }
            }
        },
        columns: [
            { data: 'id' },
            { 
                data: 'name',
                render: function(data) {
                    return `<span class="badge bg-secondary text-light">${data}</span>`;
                }
            }, 
            { data: 'display_name' },
            { 
                data: 'created_at',
                render: function(data) {
                    return `<span class="text-white-50 small">${data}</span>`;
                }
            },
            {
                data: 'id',
                orderable: false,
                className: 'text-end',
                render: data => `
                    <div class="dropdown table-actions">
                        <button class="btn btn-sm btn-dark-action dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-shield-fill text-white"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item btn-edit" href="#" data-id="${data}"><i class="bi bi-pencil"></i> Editar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item btn-delete text-danger" href="#" data-id="${data}"><i class="bi bi-trash"></i> Eliminar</a></li>
                        </ul>
                    </div>
                `
            }
        ],
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
        },
        drawCallback: function() {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm');
        }
    });

    let rolIdAEliminar = null;

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        rolIdAEliminar = $(this).data('id');
        $('#modalConfirmDelete').modal('show');
    });
    $('#btnConfirmarEliminar').on('click', function() {
        $('#modalConfirmDelete').modal('hide');
        showLoading();

        $.ajax({
            url: `/frontend/v2/roles/${rolIdAEliminar}`,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function() {
                hideLoading();
                tablaRoles.ajax.reload();
                appCustom.smallBox('ok', 'Rol eliminado correctamente', null, 3000);
            },
            error: function() {
                hideLoading();
                appCustom.smallBox('nok', 'Error al eliminar el rol', null, 4000);
            }
        });
    });

    $('#formCreateRol').on('submit', function (e) {
        e.preventDefault();
        
        const form = $(this);
        const btnSubmit = form.find('button[type="submit"]');
        
        $('#modalRol').modal('hide');
        showLoading();
        btnSubmit.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(), 
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                hideLoading();
                form[0].reset();
                btnSubmit.prop('disabled', false);
                if ($.fn.DataTable.isDataTable('#rolesTable')) {
                    $('#rolesTable').DataTable().ajax.reload();
                }
                if(typeof appCustom !== 'undefined') {
                    appCustom.smallBox('ok', 'Rol guardado con éxito', null, 3000);
                }
            },
            error: function (xhr) {
                hideLoading();
                btnSubmit.prop('disabled', false);
                $('#modalRol').modal('show'); 
                
                let errorMsg = 'Error al procesar la solicitud';
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                }

                if(typeof appCustom !== 'undefined') {
                    appCustom.smallBox('nok', errorMsg, null, 5000);
                }
            }
        });
    });

    $('#modalRol').on('hidden.bs.modal', function () {
        $('#formCreateRol')[0].reset();
    });

    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        const data = tablaRoles.row($(this).parents('tr')).data();
        $('#rol_id').val(data.id);
        $('#rol_name').val(data.name);
        $('#rol_display_name').val(data.display_name);
        $('#modalRolLabel').text('Editar Rol: ' + data.display_name);
        $('#formCreateRol').attr('action', `/frontend/v2/roles/${data.id}`);
        $('#modalRol').modal('show');
    });
    $('#modalRol').on('hidden.bs.modal', function () {
        const form = $('#formCreateRol');
        form[0].reset();
        $('#rol_id').val('');
        $('#modalRolLabel').text('Configurar Rol');
        form.attr('action', "{{ route('admin.roles.store') }}");
    });
});
</script>