/*
document.addEventListener('DOMContentLoaded', function() {
    // Evento para registrar un vehículo al enviar el formulario
    document.getElementById('formRegistrarVehiculo').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        // Capturamos los valores de los campos del formulario
        const tipoVehiculo = document.getElementById('tipoVehiculo').value;
        const nombreVehiculo = document.getElementById('nombreVehiculo').value;
        const numeroPlaca = document.getElementById('numeroPlaca').value; // Cambiado a numeroPlaca

        // Verificamos que todos los campos estén llenos
        if (tipoVehiculo === "" || nombreVehiculo === "" || numeroPlaca === "") { // Cambiado a numeroPlaca
            Swal.fire({
                icon: 'error',
                title: 'Todos los campos son obligatorios.',
                showConfirmButton: true
            });
            return; // Salimos si faltan datos
        }

        // Crear un objeto FormData para enviar los datos
        var datos = new FormData();
        datos.append('tipoVehiculo', tipoVehiculo);
        datos.append('nombreVehiculo', nombreVehiculo);
        datos.append('numeroPlaca', numeroPlaca); // Cambiado a numeroPlaca

        // Realiza la solicitud AJAX para registrar el vehículo
        $.ajax({
            url: 'ajax/registroVehiculo.ajax.php', // Ruta hacia tu archivo AJAX
            method: 'POST',
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Vehículo registrado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Redireccionar después de registrar correctamente el vehículo
                        window.location.href = 'registroVehiculo'; // Cambia la ruta según donde quieras redirigir
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al registrar el vehículo',
                        showConfirmButton: true
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.log(xhr.responseText); // Muestra la respuesta del servidor
                Swal.fire({
                    icon: 'error',
                    title: 'Error en la solicitud.',
                    text: 'Por favor, verifica tu conexión o intenta nuevamente más tarde.',
                    showConfirmButton: true
                });
            }
        });
    });
});
*/