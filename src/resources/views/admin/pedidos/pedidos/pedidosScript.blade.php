<script>
    $(document).ready(function() {
        showLoading();

        function showLoading() { $('#loadingGif').addClass('show'); }
        function hideLoading() { $('#loadingGif').removeClass('show'); }

        var pedidosTable = $('#pedidosTable').DataTable({
            responsive: true,
            language: { url: "/js/es-ES.json" },
            ajax: {
                url: $('#pedidosTable').data('url'),
                type: 'GET',
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
                { data: 'estado', render: data => `<span class="badge ${data === 'pagado' ? 'bg-success' : 'bg-danger'}">${data}</span>` },
                { data: 'total', render: data => `$${parseFloat(data).toFixed(2)}` },
                { data: 'transaccion' },
                { data: 'fecha' },
                {
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
                }
            ]
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
    });
</script>