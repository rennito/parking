<?php
// ticket.modelo.php

class ModeloTicket {
    private static function conectar() {
        // Configura la conexión a la base de datos
        $dsn = "mysql:host=localhost;dbname=parking_system;charset=utf8";
        $usuario = "root"; // Reemplaza con tu usuario de la base de datos
        $contrasena = ""; // Reemplaza con tu contraseña de la base de datos

        try {
            $conexion = new PDO($dsn, $usuario, $contrasena);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function mdlGuardarTicket($data) {
        try {
            // Prepara la consulta SQL para insertar un nuevo ticket
            $conexion = self::conectar();
    
            $sql = "INSERT INTO tickets (registro_id, archivo_pdf, fecha) VALUES (:registro_id, :archivo_pdf, NOW())";
            $stmt = $conexion->prepare($sql);
            
            // Vincula los parámetros
            $stmt->bindParam(':registro_id', $data['registro_id'], PDO::PARAM_INT);
            $stmt->bindParam(':archivo_pdf', $data['archivo_pdf'], PDO::PARAM_LOB); // Cambiado a PARAM_LOB
    
            // Ejecuta la consulta
            if ($stmt->execute()) {
                return ["success" => true, "message" => "Ticket guardado con éxito."];
            } else {
                return ["success" => false, "message" => "Error al guardar el ticket."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error de base de datos: " . $e->getMessage()];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()]; // Captura otras excepciones
        }
    }
    
}
