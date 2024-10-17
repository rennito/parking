/*
$(document).ready(function() {
    // Manejar el envío del formulario de registro de vehículo
    $('#formRegistrarVehiculo').on('submit', function(e) {
        e.preventDefault(); // Evitar que el formulario se envíe normalmente

        // Obtener los datos del formulario
        var tipoVehiculo = $('#tipoVehiculo').val();
        var nombreVehiculo = $('#nombreVehiculo').val();
        var numeroPlaca = $('#numeroPlaca').val();

        // Verificar que los campos no estén vacíos
        if (tipoVehiculo === "" || nombreVehiculo === "" || numeroPlaca === "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, completa todos los campos.'
            });
            return; // No continuar si algún campo está vacío
        }

        // Enviar los datos mediante AJAX
        $.ajax({
            url: 'ajax/registroVehiculo.ajax.php', // Asegúrate de que la ruta sea correcta
            method: 'POST',
            data: {
                tipoVehiculo: tipoVehiculo,
                nombreVehiculo: nombreVehiculo,
                numeroPlaca: numeroPlaca
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Vehículo registrado!',
                        text: response.message
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Recargar la página para ver el nuevo vehículo en la lista
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al registrar el vehículo. Inténtalo nuevamente.'
                });
            }
        });
    });
});
*/