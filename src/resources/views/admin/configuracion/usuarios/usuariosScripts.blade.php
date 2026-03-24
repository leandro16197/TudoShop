<script>
$(document).ready(function() {

    showLoading();

    function showLoading() { $('#loadingGif').addClass('show'); }
    function hideLoading() { $('#loadingGif').removeClass('show'); }

    const tablaUsuarios = $('#pedidosTable').DataTable({
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
            url: "{{ route('admin.usuarios.index') }}",
            type: "GET",
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataSrc: 'data',
            complete: function () { hideLoading(); },
            error: function () {
                hideLoading();
                if(typeof appCustom !== 'undefined') {
                    appCustom.smallBox('nok', 'Error al cargar los usuarios', null, 4000);
                }
            }
        },
        columns: [
            { data: 'id' },
            { data: 'name' }, 
            { data: 'email' },
            { 
                data: 'fecha',
                render: function(data) {
                    return `<span class="text-white-50 small">${data}</span>`;
                }
            },
            { 
                data: 'rol_name', 
                render: data => `<span class="badge bg-info text-dark">${data}</span>` 
            },
            {
                data: 'id',
                orderable: false,
                className: 'text-end',
                render: data => `
                    <div class="dropdown table-actions">
                        <button class="btn btn-sm btn-dark-action dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear-fill text-white"></i>
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
        },
        drawCallback: function() {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm');
        }
    });
    let userIdAEliminar = null;

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        userIdAEliminar = $(this).data('id'); 
        $('#modalConfirmDeleteUser').modal('show');
    });
    $('#btnConfirmarEliminarUser').on('click', function() {
        $('#modalConfirmDeleteUser').modal('hide');
        showLoading();

        $.ajax({
            url: `/admin/usuarios/${userIdAEliminar}`, 
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                hideLoading();
                if ($.fn.DataTable.isDataTable('#pedidosTable')) {
                    $('#pedidosTable').DataTable().ajax.reload();
                }
                
                if(typeof appCustom !== 'undefined') {
                    appCustom.smallBox('ok', 'Usuario eliminado con éxito', null, 3000);
                }
            },
            error: function(xhr) {
                hideLoading();
                let msg = 'No se pudo eliminar el usuario';
                if(xhr.status === 403) msg = 'No puedes eliminar tu propia cuenta';
                
                if(typeof appCustom !== 'undefined') {
                    appCustom.smallBox('nok', msg, null, 4000);
                }
            }
        });
    });

    $('#formCreateUsuario').on('submit', function (e) {
        e.preventDefault();
        
        const form = $(this);
        const btnSubmit = form.find('button[type="submit"]');
        $('#modalUsuario').modal('hide');
        showLoading();
        btnSubmit.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(), 
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function (response) {
                hideLoading();
                $('#modalUsuario').modal('hide');
                form[0].reset();
                if ($.fn.DataTable.isDataTable('#pedidosTable')) {
                    $('#pedidosTable').DataTable().ajax.reload();
                }
                if(typeof appCustom !== 'undefined') {
                    appCustom.smallBox('ok', 'Usuario guardado con éxito', null, 3000);
                }
            },
            error: function (xhr) {
                hideLoading();
                btnSubmit.prop('disabled', false);
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
    $('#modalUsuario').on('hidden.bs.modal', function () {
        $('#formCreateUsuario')[0].reset();
    });
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
       
        const data = tablaUsuarios.row($(this).parents('tr')).data();

        $('#modalUsuarioLabel').text('Editar Usuario: ' + data.name);
        $('#editPasswordNote').removeClass('d-none');

        $('#user_id').val(data.id);
        $('#user_name').val(data.name);
        $('#user_email').val(data.email);
        $('#user_role_id').val(data.role_id); 

        $('#user_password, #user_password_confirmation').prop('required', false);
        $('#formCreateUsuario').attr('action', `/frontend/v2/usuarios/${data.id}`);
        

        $('#modalUsuario').modal('show');
    });

    $('#modalUsuario').on('hidden.bs.modal', function () {
        const form = $('#formCreateUsuario');
        form[0].reset();
        
        $('#modalUsuarioLabel').text('Nuevo Usuario Administrativo');
        $('#editPasswordNote').addClass('d-none');
        $('#user_id').val('');
        

        $('#user_password, #user_password_confirmation').prop('required', true);
        
        form.attr('action', "{{ route('admin.usuarios.store') }}");
    });
});
</script>