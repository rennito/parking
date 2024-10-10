$(document).ready(function() {
    $(document).on('click', '.btnMarcarSalida', function() {
        var idVehiculo = $(this).attr('idVehiculo');

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
                            var win = window.open('pdf/parking-t58.php?id=' + idVehiculo, '_blank'); 

                            // Esperar a que el PDF cargue y luego imprimir
                            win.onload = function() {
                                win.print(); // Imprimir automáticamente
                            };

                            location.reload(); // Recargar toda la página
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
    });
});
