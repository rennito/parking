$(document).ready(function() {
    var idVehiculo; // Variable global para almacenar el ID del vehículo

    $(document).on('click', '.btnMarcarSalida', function() {
        idVehiculo = $(this).attr('idVehiculo'); // Captura el ID del vehículo
        $('#modalConfirmarSalida').modal('show'); // Muestra el modal de confirmación
    });

    // Cuando se hace clic en "Aceptar" en el modal
    $('#confirmarSalida').on('click', function() {
        // Realiza la solicitud AJAX
        $.ajax({
            url: 'ajax/marcarSalida.ajax.php',
            type: 'POST',
            data: { id: idVehiculo },
            dataType: 'json', // Esperamos recibir un JSON de respuesta
            success: function(response) {
                if (response.success) {
                    // Mostrar alerta con SweetAlert
                    Swal.fire({
                        title: 'Salida registrada con éxito',
                        html: '<b>Hora de entrada:</b> ' + response.horaEntrada + '<br>' +
                              '<b>Hora de salida:</b> ' + response.horaSalida + '<br>' +
                              '<b>Monto total:</b> $' + response.montoTotal,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Abrir el PDF en una nueva ventana
                            window.open('pdf/parking-t58.php?id=' + idVehiculo, '_blank');
                            window.location.href = 'registroentrada'; // Redirigir a la página de registro de entradas
                        }
                    });
                } else {
                    // Mostrar error con SweetAlert
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al marcar la salida.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });
            }
        });
        $('#modalConfirmarSalida').modal('hide'); // Ocultar el modal después de hacer clic en Aceptar
    });
});
