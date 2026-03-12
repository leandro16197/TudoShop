<style>
    .bg-warning{
       background-color: rgb(147 110 0) !important;
    }
    .dataTables_length{
        width: 300px;
    }
    .row.d-flex.justify-content-between.align-items-center{
        margin-bottom: 20px;
    }
</style>
<script>
    $(document).ready(function() {
        showLoading();

        function showLoading() { $('#loadingGif').addClass('show'); }
        function hideLoading() { $('#loadingGif').removeClass('show'); }

        var pedidosTable = $('#pedidosTable').DataTable({
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
            responsive: true,
            language: { url: "/js/es-ES.json" ,lengthMenu: "_MENU_"},
            ajax: {
                url: $('#pedidosTable').data('url'),
                type: 'GET',
                data: function(d) {
                    d.estado = $('#filterEstado').val();
                    d.desde = $('#filterDesde').val();
                    d.hasta = $('#filterHasta').val();
                },
                dataSrc: 'data',
                complete: function () { hideLoading(); },
                error: function () {
                    hideLoading();
                    appCustom.smallBox('nok', 'Error al cargar los pedidos', null, 4000);
                }
            },
            order: [[0, 'desc']],
            columns: [
                { data: 'id' },
                { data: 'cliente' },
                { data: 'email' },
                { 
                    data: 'estado', 
                    render: data => {
                        const statusColors = {
                            'pagado': 'bg-success', 
                            'pendiente': 'bg-warning',  
                            'rechazado': 'bg-danger'    
                        };
                        const badgeClass = statusColors[data] || 'bg-secondary';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    } 
                },
                { data: 'total', render: data => `$${parseFloat(data).toFixed(2)}` },
                { data: 'transaccion' },
                { data: 'fecha' },
                /*{
                    data: 'id',
                    orderable: false,
                    render: data => `
                        <div class="dropdown table-actions">
                            <button class="btn btn-sm btn-dark-action dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill text-white"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item btn-delete" href="#" data-id="${data}"><i class="bi bi-trash"></i> Eliminar</a></li>
                            </ul>
                        </div>
                    `
                }*/
            ],
        
            initComplete: function() {
                var filtros = $('#contenedorFiltros').detach().removeClass('d-none'); 
                var filaNativa = $('.dataTables_wrapper .row:first-child');
                filaNativa.addClass('d-flex justify-content-between align-items-center');
                
                var divCantidad = filaNativa.find('.dataTables_length').parent();
                var divBuscador = filaNativa.find('.dataTables_filter').parent();

                filaNativa.prepend(divBuscador); 
                filaNativa.append(divCantidad);  

                divBuscador.removeClass('col-md-6').addClass('col-md-3 d-flex justify-content-start');
                
                divCantidad.removeClass('col-md-6').addClass('col-md-3 d-flex justify-content-end align-items-center gap-2');
                divCantidad.prepend(`
                    <button type="button" id="btnExportarBackend" class="btn btn-success btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-excel"></i> Exportar
                    </button>
                `);

                divBuscador.after(
                    '<div class="col-md-6 d-flex justify-content-center align-items-center gap-2">' + 
                        filtros.html() + 
                    '</div>'
                );
                $('.dataTables_filter, .dataTables_length').css({ 'margin': '0', 'width': 'auto' });
            }
        });

        $(document).on('change', '#filterEstado, #filterDesde, #filterHasta', function() {
            pedidosTable.ajax.reload(); 
        });

        $('#filterEstado, #filterDesde, #filterHasta').on('change', function() {
            pedidosTable.ajax.reload();
        });
        let deletePedidoId = null;
        $('#pedidosTable').on('click', '.btn-delete', function () {
            deletePedidoId = $(this).data('id');
            $('#deletePedidoName').text('Pedido #' + deletePedidoId);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('deletePedidoModal')).show();
        });

        $('#confirmDeletePedido').on('click', function () {
            if (!deletePedidoId) return;
            showLoading();
            $.ajax({
                url: `/panel/pedidos/${deletePedidoId}`,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    pedidosTable.ajax.reload(null, false);
                    appCustom.smallBox('ok', 'Pedido eliminado correctamente', null, 3000);
                    bootstrap.Modal.getInstance(document.getElementById('deletePedidoModal')).hide();
                },
                error: function () {
                    appCustom.smallBox('nok', 'Error al eliminar el pedido', null, 3000);
                },
                complete: function () { hideLoading(); }
            });
        });
        $(document).on('click', '#btnExportarBackend', function() {
            const datosExportacion = {
                estado: $('#filterEstado').val(),
                desde: $('#filterDesde').val(),
                hasta: $('#filterHasta').val(),
                exportar: 'yes', 
                _token: $('meta[name="csrf-token"]').attr('content') 
            };

         
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

            $.ajax({
                url: $('#pedidosTable').data('url'), 
                type: 'GET', 
                data: datosExportacion,
                success: function(response) {
                    appCustom.smallBox('ok', 'Exportación iniciada. Recibirás un aviso al finalizar.', null, 4000);
                },
                error: function() {
                    appCustom.smallBox('nok', 'Error al solicitar la exportación', null, 4000);
                },
                complete: function() {
                    $('#btnExportarBackend').prop('disabled', false).html('<i class="bi bi-file-earmark-excel"></i> Exportar');
                }
            });
        });


        $(document).on('click', '#btnExportarBackend', function() {
            const datosExportacion = {
                estado: $('#filterEstado').val(),
                desde: $('#filterDesde').val(),
                hasta: $('#filterHasta').val(),
                exportExcel: 'true',
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
            
            $('#downloadLinkArea').empty();
            $('#exportStatusContainer').show();
            $('#progressBar').css({'width': '0%', 'background-color': '#0d6efd'});

            $.ajax({
                url: $('#pedidosTable').data('url'),
                type: 'GET',
                data: datosExportacion,
                success: function(response) {
                    appCustom.smallBox('ok', 'Exportación iniciada...', null, 3000);
                    
                    let interval = setInterval(function() {
                        fetch('/frontend/v2/pedidos/check-progress')
                            .then(response => response.json())
                            .then(data => {
                                $('#progressBar').css('width', data.progress + '%');
                                $('#statusText').text(data.status);
                                if (data.progress >= 100) {
                                    clearInterval(interval);
                                    $btn.prop('disabled', false).html('<i class="bi bi-file-earmark-excel"></i> Exportar');
                                    
                                    if (data.url) {
                                        $('#progressBar').css('background-color', '#198754');
                                        $('#downloadLinkArea').html(`
                                            <a href="${data.url}" class="btn btn-success mt-2" download>
                                                <i class="bi bi-download"></i> Descargar Excel
                                            </a>
                                        `);
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error en polling:', error);
                                clearInterval(interval);
                                $btn.prop('disabled', false).html('<i class="bi bi-file-earmark-excel"></i> Exportar');
                            });
                    }, 2000);
                },
                error: function() {
                    $('#exportStatusContainer').hide(); // Ocultar si falla
                    appCustom.smallBox('nok', 'Error al iniciar la exportación', null, 4000);
                    $btn.prop('disabled', false).html('<i class="bi bi-file-earmark-excel"></i> Exportar');
                }
            });
        });
    });

</script>