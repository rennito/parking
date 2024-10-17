// Aseguramos que moment.js use el idioma español
moment.locale('es');  // Cambiamos el idioma a español

// Verificamos que el locale está configurado correctamente
/*console.log("Locale actual:", moment.locale());  // Debería mostrar 'es'
*/



// Función para cargar el gráfico de ventas por día de la semana y número de día
function cargarGraficoVentasPorDiaYNumero() {
    $.ajax({
        url: "ajax/graficaVentas.ajax.php",  // Ruta al archivo PHP que obtiene los datos de ventas
        method: "POST",
        dataType: "json",
        success: function (respuesta) {
            /*console.log("Datos recibidos del backend:", respuesta); // Ver los datos recibidos en la consola para depuración*/

            // Verificamos si la respuesta tiene datos
            if (!respuesta || respuesta.length === 0) {
                console.warn("No se recibieron datos válidos para la gráfica.");
                return;
            }

            // Crear un objeto para agrupar ventas por día de la semana y número del día
            var ventasPorDia = {};

            // Recorrer los datos recibidos y agrupar por día de la semana y número del día
            respuesta.forEach(function(item) {
                var fecha = moment(item.fecha, 'YYYY-MM-DD');  // Convertimos la fecha
                var diaYNumero = fecha.format('dddd D');  // Obtenemos el nombre del día y el número del día (por ejemplo "Lunes 14")
                /*console.log("Fecha procesada:", item.fecha, "Día de la semana con número:", diaYNumero, "Monto:", item.total);*/

                // Si el día ya existe en el objeto, sumamos las ventas, si no lo creamos
                if (!ventasPorDia[diaYNumero]) {
                    ventasPorDia[diaYNumero] = 0;
                }
                ventasPorDia[diaYNumero] += parseFloat(item.total) || 0;
            });

            /*console.log("Ventas agrupadas por día y número:", ventasPorDia);*/

            // Convertimos el objeto en un array para Morris.js
            var datosFiltrados = [];
            Object.keys(ventasPorDia).forEach(function(diaYNumero) {
                datosFiltrados.push({
                    dia: diaYNumero,           // Nombre del día con el número (ej. "Lunes 14")
                    total: ventasPorDia[diaYNumero]  // Total de ventas para ese día
                });
            });

            /*console.log("Datos filtrados para la gráfica:", datosFiltrados);*/

            // Limpiar el contenedor de la gráfica antes de redibujarla
            $('#line-chart-ventas').empty();

            // Crear la gráfica con los datos procesados
            var line = new Morris.Line({
                element: 'line-chart-ventas',
                resize: true,
                data: datosFiltrados,    // Los datos procesados
                xkey: 'dia',             // Clave para el eje X (nombre del día de la semana con número)
                ykeys: ['total'],        // Clave para el eje Y (ventas totales)
                labels: ['Total Ventas'],// Etiqueta para los valores
                lineColors: ['#efefef'], // Personaliza los colores de la línea
                lineWidth: 2,
                hideHover: 'auto',
                gridTextColor: '#fff',
                gridStrokeWidth: 0.4,
                pointSize: 4,
                pointStrokeColors: ['#efefef'],
                gridLineColor: '#efefef',
                gridTextFamily: 'Open Sans',
                preUnits: '$',           // Símbolo de la unidad antes de los valores
                gridTextSize: 10,
                parseTime: false,        // Desactivar el análisis de tiempo para que Morris.js use el eje X como categorías de texto
                xLabelAngle: 45          // Ángulo de rotación para mejorar la visibilidad
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los datos de ventas:", error);
        }
    });
}

// Ejecutar la función para cargar el gráfico al cargar la página
$(document).ready(function () {
    cargarGraficoVentasPorDiaYNumero();  // Cargar los datos agrupados por día y número
});
