/*$(document).ready(function () {
  // Al hacer clic en el botón de marcar salida
  $(document).on("click", ".btnMarcarSalida", function () {
      var idVehiculo = parseInt($(this).attr("idVehiculo"), 10);
      console.log("ID del vehículo:", idVehiculo); // Verifica el ID

      // Verifica que el ID sea un número válido
      if (isNaN(idVehiculo)) {
          console.error("ID del vehículo no es válido.");
          Swal.fire({
              title: "Error",
              text: "ID del vehículo no es válido.",
              icon: "error",
              confirmButtonText: "Aceptar",
              customClass: {
                  confirmButton: "btn btn-danger",
              },
              buttonsStyling: false,
          });
          return; // Sale de la función si el ID no es válido
      }

      // Muestra la alerta con información de la salida
      Swal.fire({
          title: 'Salida registrada con éxito',
          html: '<b>ID del vehículo:</b> ' + idVehiculo + '<br>' +
                '<b>¿Desea imprimir el ticket?</b>',
          icon: 'success',
          showCancelButton: true, // Mostrar botón de cancelar
          confirmButtonText: 'Imprimir Ticket',
          customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-secondary'
          },
          buttonsStyling: false
      }).then((result) => {
          if (result.isConfirmed) {
              // Al hacer clic en "Imprimir Ticket", realizar la solicitud AJAX
              $.ajax({
                  url: "ajax/marcarSalidaImpDirec.ajax.php", // Asegúrate de que la ruta sea correcta
                  type: "POST",
                  data: { id: idVehiculo },
                  dataType: "json",
                  success: function (response) {
                      console.log("Respuesta del servidor:", response); // Verifica la respuesta
                      if (response.success) {
                          // Aquí puedes mostrar un mensaje de éxito después de imprimir
                          Swal.fire({
                              title: 'Impresión completada',
                              text: 'El ticket ha sido impreso correctamente.',
                              icon: 'success',
                              confirmButtonText: 'Aceptar',
                              customClass: {
                                  confirmButton: 'btn btn-success'
                              },
                              buttonsStyling: false
                          }).then(() => {
                              // Recarga la página al cerrar el mensaje de éxito
                              location.reload();
                          });
                      } else {
                          Swal.fire({
                              title: "Error",
                              text: response.message,
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
                          text: "Ocurrió un error al marcar la salida DEL SERVIDOR.",
                          icon: "error",
                          confirmButtonText: "Aceptar",
                          customClass: {
                              confirmButton: "btn btn-danger",
                          },
                          buttonsStyling: false,
                      });
                  },
              });
          }
      });
  });
});
*/