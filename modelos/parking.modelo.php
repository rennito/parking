<?php

require_once "conexion.php";

class ModeloParking {

    /*=============================================
    REGISTRAR ENTRADA
    =============================================*/
    static public function mdlRegistrarEntrada($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(vehicle_id, entry_time) VALUES (:vehicle_id, NOW())");
        $stmt->bindParam(":vehicle_id", $datos["vehicle_id"], PDO::PARAM_INT);

        if($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    /*=============================================
    REGISTRAR SALIDA Y CALCULAR TARIFA
    =============================================*/
    static public function mdlRegistrarSalida($tabla, $datos) {
        // Registrar salida
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET exit_time = NOW() WHERE vehicle_id = :vehicle_id AND exit_time IS NULL");
        $stmt->bindParam(":vehicle_id", $datos["vehicle_id"], PDO::PARAM_INT);

        if($stmt->execute()) {
            // Calcular tarifa
            $stmt = Conexion::conectar()->prepare("SELECT TIMESTAMPDIFF(HOUR, entry_time, exit_time) AS hours FROM $tabla WHERE vehicle_id = :vehicle_id ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(":vehicle_id", $datos["vehicle_id"], PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            $hoursParked = $result['hours'];

            // Obtener tarifa por hora
            $stmt = Conexion::conectar()->prepare("SELECT hourly_rate FROM tariffs WHERE vehicle_type = :vehicle_type");
            $stmt->bindParam(":vehicle_type", $datos["vehicle_type"], PDO::PARAM_STR);
            $stmt->execute();
            $rate = $stmt->fetch();

            $fee = $hoursParked * $rate['hourly_rate'];

            // Guardar la tarifa
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fee = :fee, paid = 0 WHERE vehicle_id = :vehicle_id AND exit_time IS NOT NULL ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(":fee", $fee, PDO::PARAM_STR);
            $stmt->bindParam(":vehicle_id", $datos["vehicle_id"], PDO::PARAM_INT);

            if($stmt->execute()){
                return $fee;
            } else {
                return "error";
            }
        } else {
            return "error";
        }
    }

    /*=============================================
    MOSTRAR REGISTROS DE ENTRADAS Y SALIDAS
    =============================================*/
    static public function mdlMostrarRegistros($tabla, $item, $valor) {
        if($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    /*=============================================
    ACTUALIZAR REGISTRO
    =============================================*/
    static public function mdlActualizarRegistro($tabla, $item1, $valor1, $valor) {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");
        $stmt->bindParam(":".$item1, $valor1, PDO::PARAM_STR);
        $stmt->bindParam(":id", $valor, PDO::PARAM_INT);

        if($stmt->execute()) {
            return "ok";
        } else {
            return "error";    
        }
    }

    /*=============================================
    ELIMINAR REGISTRO
    =============================================*/
    static public function mdlEliminarRegistro($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);

        if($stmt->execute()) {
            return "ok";
        } else {
            return "error";    
        }
    }

    /*=============================================
    MOSTRAR VEHÍCULOS
    =============================================*/
    static public function mdlMostrarVehiculos($tabla, $item, $valor) {
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }
}

/*
Funciones Principales:
- mdlRegistrarEntrada: Inserta un nuevo registro de entrada del vehículo.
- mdlRegistrarSalida: Registra la salida del vehículo, calcula la tarifa según el tiempo estacionado, y la guarda.
- mdlMostrarRegistros: Muestra los registros de entradas y salidas, con opción de filtrar por un valor específico.
- mdlActualizarRegistro: Actualiza cualquier campo de un registro existente en la tabla.
- mdlEliminarRegistro: Elimina un registro del sistema basado en el ID del registro.
- mdlMostrarVehiculos: Muestra información sobre vehículos, permitiendo buscar por un campo específico.

Este modelo ahora está preparado para interactuar con el sistema de parking que estás desarrollando. Puedes integrar el controlador y las vistas para completar la funcionalidad.
¡Espero que esto te ayude! Si necesitas más ajustes, no dudes en decírmelo!
*/
