$(document).ready(function () {
    // Capturar el evento submit del formulario
    $("#formRegistrarEntradaVehiculo").on("submit", function (e) {
        e.preventDefault(); // Evita el comportamiento predeterminado del formulario (recargar la página)

        // Obtener los datos del formulario
        var tipoVehiculo = $("#tipoVehiculo").val();
        var nombreVehiculo = $("#nombreVehiculo").val();
        var numeroPlaca = $("#numeroPlaca").val();

        // Validar que los campos no estén vacíos
        if (!tipoVehiculo || !nombreVehiculo || !numeroPlaca) {
            Swal.fire({
                title: "Error",
                text: "Por favor, completa todos los campos.",
                icon: "error",
                confirmButtonText: "Aceptar",
                customClass: {
                    confirmButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });
            return; // Detener si faltan campos
        }

        // Llamada AJAX para registrar el vehículo
        $.ajax({
            url: "ajax/registroVehiculoImpDirec.ajax.php",  // Ruta al archivo PHP
            type: "POST",
            data: {
                tipoVehiculo: tipoVehiculo,
                nombreVehiculo: nombreVehiculo,
                numeroPlaca: numeroPlaca
            },
            dataType: "json",
            success: function (response) {
                // Validar la respuesta del servidor
                if (response && response.success) {
                    // Mostrar el QR si la respuesta incluye la ruta
                    if (response.codigo_qr) {
                        // Asume que tienes un contenedor en tu HTML para mostrar el QR
                        $('#qrContainer').html('<img src="' + response.codigo_qr + '" alt="Código QR del Vehículo">');
                    }

                    Swal.fire({
                        title: 'Registro Exitoso',
                        text: 'El vehículo ha sido registrado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            confirmButton: 'btn btn-success',
                        },
                        buttonsStyling: false,
                    }).then(() => {
                        // Cerrar el modal y recargar la página o actualizar la tabla
                        if ($('#modalRegistrarEntradaVehiculo').length) {
                            $('#modalRegistrarEntradaVehiculo').modal('hide'); // Cerrar el modal si existe
                        }
                        location.reload(); // Recargar la página o actualizar tabla
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message ? response.message : "Ocurrió un error al registrar el vehículo.",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                        customClass: {
                            confirmButton: "btn btn-danger",
                        },
                        buttonsStyling: false,
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                Swal.fire({
                    title: "Error",
                    text: "Ocurrió un error al registrar el vehículo. Por favor, intente nuevamente.",
                    icon: "error",
                    confirmButtonText: "Aceptar",
                    customClass: {
                        confirmButton: "btn btn-danger",
                    },
                    buttonsStyling: false,
                });
            }
        });
    });
});
