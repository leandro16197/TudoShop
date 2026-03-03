<script>
    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function(){
            let output = document.getElementById('preview-logo');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    $('#formConfiguracion').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        $('#btnGuardar').prop('disabled', true);
        $('#loader').removeClass('d-none');

        $.ajax({
            url: "{{ route('admin.configuracion.update') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                appCustom.smallBox(
                        'ok',
                        'Configuración actualizada exitosamente',
                        null,
                        3000
                );
                $('#loader').addClass('d-none');
                $('#btnGuardar').prop('disabled', false);
            },
            error: function(xhr) {
                 appCustom.smallBox(
                        'error',
                        'Error al actualizar la configuración',
                        null,
                        3000
                );
                $('#loader').addClass('d-none');
                $('#btnGuardar').prop('disabled', false);
            }
        });
    });
</script>