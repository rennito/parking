<?php

require_once "conexion.php";

class ModeloVentasTickets
{

    /*=============================================
    MOSTRAR TICKETS
    =============================================*/
    public static function mdlMostrarTickets($tabla)
    {

        // Preparamos la consulta SQL para obtener los datos de los tickets y vehículos
        $stmt = Conexion::conectar()->prepare("SELECT r.tipo_vehiculo, r.nombre_vehiculo, r.numero_placa, t.id AS ticket_id, t.archivo_pdf
                                                FROM tickets t
                                                JOIN registro_vehiculos r ON t.registro_id = r.id
                                                WHERE t.archivo_pdf LIKE '%PDF%'

        ");

        // Ejecutamos la consulta
        $stmt->execute();

        // Retornamos los resultados
        return $stmt->fetchAll();
    }

    /*=============================================
    ELIMINAR TICKET
    =============================================*/
    public static function mdlEliminarTicket($tabla, $id)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

        $stmt->close();
        $stmt = null;
    }

    public static function mdlRangoFechasTickets($tabla, $fechaInicial, $fechaFinal)
    {
        if ($fechaInicial == null) {
            // Si no hay rango de fechas, obtener todos los registros con hora_salida y monto_total > 0
            $stmt = Conexion::conectar()->prepare("SELECT tipo_vehiculo, nombre_vehiculo, numero_placa, hora_entrada, hora_salida, monto_total
                                                   FROM $tabla
                                                   WHERE hora_salida IS NOT NULL AND monto_total > 0");
        } else {
            // Asegurarse de que las fechas estén en formato correcto con horas
            $fechaInicial .= " 00:00:00";
            $fechaFinal .= " 23:59:59";

            // Filtrar por hora_salida en lugar de hora_entrada
            $stmt = Conexion::conectar()->prepare("SELECT tipo_vehiculo, nombre_vehiculo, numero_placa, hora_entrada, hora_salida, monto_total
                                                   FROM $tabla
                                                   WHERE hora_salida BETWEEN :fechaInicial AND :fechaFinal
                                                   AND monto_total > 0");
            $stmt->bindParam(":fechaInicial", $fechaInicial, PDO::PARAM_STR);
            $stmt->bindParam(":fechaFinal", $fechaFinal, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }
}
