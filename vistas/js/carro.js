$(document).ready(function () {
    $("#formRegistrarEntradaVehiculo").on("submit", function (e) {
        e.preventDefault(); // Evita el comportamiento predeterminado del formulario

        // Obtener los datos del formulario
        var tipoVehiculo = $("#tipoVehiculo").val();
        var nombreVehiculo = $("#nombreVehiculo").val();
        var numeroPlaca = $("#numeroPlaca").val();

        // Validar que los campos no estén vacíos
        if (!tipoVehiculo || !nombreVehiculo || !numeroPlaca) {
            Swal.fire({
                title: "Error",
                text: "Por favor, completa todos los campos.",
                icon: "error"
            });
            return;
        }

        var datos = new FormData();
        datos.append('tipoVehiculo', tipoVehiculo);
        datos.append('nombreVehiculo', nombreVehiculo);
        datos.append('numeroPlaca', numeroPlaca);

        $.ajax({
            url: "ajax/carro.ajax.php", // Asegúrate de que la ruta sea correcta
            type: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Abre el PDF en una nueva pestaña
                    var pdfUrl = 'pdf/parking-t58-Entrada.php?id=' + response.registro_id; // Asegúrate de que esta URL sea correcta
                    window.open(pdfUrl, '_blank'); // Abre el PDF en una nueva pestaña

                    Swal.fire({
                        title: 'Registro Exitoso',
                        text: 'El vehículo ha sido registrado exitosamente.',
                        icon: 'success'
                    }).then(() => {
                        $('#modalRegistrarEntradaVehiculo').modal('hide'); // Cerrar el modal
                        location.reload(); // Recargar la página
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message,
                        icon: "error"
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                Swal.fire({
                    title: "Error",
                    text: "Ocurrió un error al registrar la entrada del vehículo. Por favor, intente nuevamente.",
                    icon: "error"
                });
            }
        });
    });
});
