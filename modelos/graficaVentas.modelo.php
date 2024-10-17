<?php

require_once "conexion.php";

class ModeloGraficaVentas {

    /*=============================================
    OBTENER TOTALES DE VENTAS POR DÍA
    =============================================*/
    public static function mdlVentasPorDia($tabla) {

        // Consulta SQL para obtener el total de ventas por día (basado en la fecha de salida)
        $stmt = Conexion::conectar()->prepare("
            SELECT DATE(hora_salida) AS fecha, SUM(monto_total) AS total
            FROM $tabla
            WHERE estado_pago = 1  -- Solo las ventas pagadas
            GROUP BY DATE(hora_salida)
            ORDER BY fecha ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll();  // Retornamos los resultados
    }
}
