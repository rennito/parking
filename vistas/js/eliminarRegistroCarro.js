var idVehiculoEliminar;

// Cuando se hace clic en el botón de eliminar
$(document).on('click', '.btnEliminarVehiculo', function() {
    idVehiculoEliminar = $(this).attr('idVehiculo'); // Captura el ID del vehículo
    console.log("ID del vehículo a eliminar:", idVehiculoEliminar); // Verifica que el ID se captura correctamente
    $('#modalConfirmarEliminacion').modal('show'); // Muestra el modal de confirmación
});

// Cuando se confirma la eliminación
$('#confirmarEliminarVehiculo').on('click', function() {
    if (!idVehiculoEliminar) { // Verifica que el ID no sea nulo
        console.error("El ID del vehículo es nulo."); // Mensaje de error en la consola
        return; // Detiene la ejecución si el ID es nulo
    }

    // Realiza la llamada AJAX para eliminar el vehículo
    $.ajax({
        url: "ajax/carro.ajax.php",
        method: "POST",
        data: { 
            vehiculoId: idVehiculoEliminar, // Enviar el ID del vehículo
            action: 'delete' 
        },
        dataType: 'json', // Esperamos una respuesta JSON
        success: function(response) {
            if (response.success) {
                Swal.fire(
                    '¡Eliminado!',
                    'El vehículo ha sido eliminado correctamente.',
                    'success'
                ).then(() => {
                    window.location.reload(); // Recargar la página después de cerrar el modal
                });
            } else {
                Swal.fire(
                    'Error',
                    response.message ? response.message : 'Hubo un error al intentar eliminar el vehículo.',
                    'error'
                );
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", status, error);
            Swal.fire(
                'Error',
                'Hubo un error al intentar eliminar el vehículo.',
                'error'
            );
        }
    });

    $('#modalConfirmarEliminacion').modal('hide'); // Cierra el modal después de la confirmación
});
