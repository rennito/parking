/*
$(document).on("submit", "#formRegistrarEntradaVehiculo", function(e) {
    e.preventDefault();

    var datos = new FormData(this);

    $.ajax({
        url: "ajax/registroVehiculo.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response); // Muestra la respuesta en la consola

            // Verifica si response es un objeto
            if (typeof response === "string") {
                response = JSON.parse(response); // Convierte a objeto si es una cadena
            }

            // Analiza la respuesta
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Registro exitoso!',
                    text: response.message,
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "registroentrada"; // Redirigir a la página de vehículos
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Hubo un problema al registrar el vehículo.',
                    showConfirmButton: true
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.log(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error en la solicitud.',
                text: 'Por favor, verifica tu conexión o intenta nuevamente más tarde.',
                showConfirmButton: true
            });
        }
    });
});
*/