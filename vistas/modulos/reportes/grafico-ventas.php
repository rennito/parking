<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de Ventas</title>
    <!-- Cargar moment.js con locales -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <!-- Cargar jQuery y Morris.js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <!-- Aquí puedes incluir tus estilos CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
</head>
<body>
    <!-- Contenedor HTML para el gráfico -->
    <div class="box box-solid bg-teal-gradient">
        <div class="box-header">
            <i class="fa fa-th"></i>
            <h3 class="box-title">Gráfico de Ventas</h3>
        </div>

        <div class="box-body border-radius-none nuevoGraficoVentas">
            <!-- Este es el contenedor donde se dibujará la gráfica -->
            <div class="chart" id="line-chart-ventas" style="height: 400px;"></div>
        </div>
    </div>

</body>
</html>
