<?php

class ControladorGraficaVentas {

    /*=============================================
    OBTENER TOTALES DE VENTAS POR DÍA
    =============================================*/
    public static function ctrVentasPorDia() {

        $tabla = "registro_vehiculos";  // La tabla donde están las ventas

        // Llamamos al método del modelo
        $respuesta = ModeloGraficaVentas::mdlVentasPorDia($tabla);

        return $respuesta;  // Retornamos la respuesta para ser usada en el AJAX
    }
}
