// Escuchar el evento click en los botones de eliminar ticket
document.addEventListener("DOMContentLoaded", function() {
    var botonesEliminar = document.querySelectorAll('.btnEliminarTicket');

    botonesEliminar.forEach(function(boton) {
        boton.addEventListener('click', function() {
            var ticketId = this.getAttribute('data-id');

            // Mostrar SweetAlert de confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hacer una solicitud AJAX para eliminar el ticket
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "ajax/eliminar_ticket.ajax.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            // Mostrar SweetAlert de éxito o error según la respuesta
                            Swal.fire({
                                title: 'Eliminado!',
                                text: xhr.responseText,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Recargar la página para ver los cambios
                                location.reload();
                            });
                        } else if (xhr.status !== 200) {
                            // Mostrar SweetAlert de error
                            Swal.fire({
                                title: 'Error!',
                                text: 'No se pudo eliminar el ticket.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    };

                    // Enviamos el ID del ticket a eliminar
                    xhr.send("id=" + ticketId);
                }
            });
        });
    });
});
