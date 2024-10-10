<?php

require_once "conexion.php"; // Asegúrate de que esta ruta sea correcta

class ModeloVehiculos
{
    // Método para registrar un vehículo
    public static function mdlRegistrarVehiculo($tabla, $tipoVehiculo, $nombreVehiculo, $numeroPlaca)
    {
        try {
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (tipo_vehiculo, nombre_vehiculo, numero_placa) VALUES (:tipoVehiculo, :nombreVehiculo, :numeroPlaca)");
            $stmt->bindParam(":tipoVehiculo", $tipoVehiculo, PDO::PARAM_STR);
            $stmt->bindParam(":nombreVehiculo", $nombreVehiculo, PDO::PARAM_STR);
            $stmt->bindParam(":numeroPlaca", $numeroPlaca, PDO::PARAM_STR);
            $stmt->execute();
            return true; // Retorna true si la inserción fue exitosa
        } catch (PDOException $e) {
            error_log("Error en mdlRegistrarVehiculo: " . $e->getMessage()); // Log del error
            return false; // Retorna false si hubo un error
        }
    }

    // Método para mostrar vehículos
    public static function mdlMostrarVehiculos($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna un solo registro como un array asociativo
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los registros como un array asociativo
        }
    }

    // Método para eliminar un vehículo
    public static function mdlEliminarVehiculo($tabla, $idVehiculo)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $idVehiculo, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true; // Se eliminó correctamente
        } else {
            // Captura el error para depuración
            $errorInfo = $stmt->errorInfo();
            error_log("Error en mdlEliminarVehiculo: " . print_r($errorInfo, true)); // Log en el archivo de errores
            return false; // Hubo un error
        }
    }

    // Método para registrar la entrada de un vehículo
    public static function mdlRegistrarEntrada($tablaVehiculos, $tablaRegistro, $tipoVehiculo, $nombreVehiculo, $numeroPlaca)
    {
        date_default_timezone_set('America/Mazatlan');

        // Establecer la hora de entrada
        $horaEntrada = date("Y-m-d H:i:s");

        // Verificar si el vehículo ya existe
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaVehiculos WHERE numero_placa = :numeroPlaca");
        $stmt->bindParam(":numeroPlaca", $numeroPlaca, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Si el vehículo ya existe, solo registrar la entrada
            return self::insertarRegistroEntrada($tablaRegistro, $tipoVehiculo, $nombreVehiculo, $numeroPlaca, $horaEntrada);
        } else {
            // Si el vehículo no existe, primero registrar en la tabla de vehículos
            if (self::mdlRegistrarVehiculo($tablaVehiculos, $tipoVehiculo, $nombreVehiculo, $numeroPlaca)) {
                // Ahora registrar en la tabla de registro de vehículos
                return self::insertarRegistroEntrada($tablaRegistro, $tipoVehiculo, $nombreVehiculo, $numeroPlaca, $horaEntrada);
            } else {
                return false; // Retorna false si hubo un error al registrar el vehículo
            }
        }
    }

    private static function insertarRegistroEntrada($tablaRegistro, $tipoVehiculo, $nombreVehiculo, $numeroPlaca, $horaEntrada)
    {
        $stmtRegistro = Conexion::conectar()->prepare("INSERT INTO $tablaRegistro (tipo_vehiculo, nombre_vehiculo, numero_placa, hora_entrada) VALUES (:tipoVehiculo, :nombreVehiculo, :numeroPlaca, :horaEntrada)");
        $stmtRegistro->bindParam(":tipoVehiculo", $tipoVehiculo, PDO::PARAM_STR);
        $stmtRegistro->bindParam(":nombreVehiculo", $nombreVehiculo, PDO::PARAM_STR);
        $stmtRegistro->bindParam(":numeroPlaca", $numeroPlaca, PDO::PARAM_STR);
        $stmtRegistro->bindParam(":horaEntrada", $horaEntrada, PDO::PARAM_STR);

        return $stmtRegistro->execute(); // Retorna true si la inserción fue exitosa
    }

    // Método para marcar la salida del vehículo y calcular el monto total
    public static function mdlMarcarSalidaVehiculo($tabla, $idVehiculo, $horaSalida, $montoTotal)
    {
        // Conexión a la base de datos
        $stmt = Conexion::conectar()->prepare("
            UPDATE $tabla 
            SET hora_salida = :horaSalida, 
                monto_total = :montoTotal, 
                estado_pago = 1 
            WHERE id = :id
        ");
        
        // Asignar los parámetros
        $stmt->bindParam(":horaSalida", $horaSalida, PDO::PARAM_STR);
        $stmt->bindParam(":montoTotal", $montoTotal, PDO::PARAM_STR);
        $stmt->bindParam(":id", $idVehiculo, PDO::PARAM_INT);
        
        // Ejecutar la consulta y verificar si se actualizó
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    
        $stmt->close();
        $stmt = null;
    }
    
    
    


    public static function calcularMontoTotal($tiempoEstancia) {
        // Conectar a la base de datos
        $stmt = Conexion::conectar()->prepare("SELECT monto FROM tarifas WHERE tiempo = :tiempo");
        $stmt->bindParam(":tiempo", $tiempoEstancia, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si se encontró la tarifa correspondiente
        if ($stmt->rowCount() > 0) {
            $tarifa = $stmt->fetch(PDO::FETCH_ASSOC);
            return $tarifa['monto']; // Retorna el monto correspondiente
        } else {
            return 0; // Retorna 0 si no se encontró la tarifa
        }
    }
    
    
/*
    // Mostrar todos los vehículos
    public static function mdlMostrarEntradasVehiculos($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
*/
//------------------------------/////
// Mostrar todas las entradas de vehículos
// Mostrar entradas de vehículos
// Mostrar todos los vehículos
public static function mdlMostrarEntradasVehiculos($tabla, $item, $valor)
{
    if ($item != null) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT); // Asegúrate de usar PDO::PARAM_INT si el ID es un número
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}




   

  // Método para obtener el monto basado en los minutos transcurridos
public static function obtenerMontoPorMinutos($minutos)
{
    // Aquí asumimos que las tarifas se encuentran en una tabla de la base de datos
    $tablaTarifas = "tarifas";
    
    // Consultar las tarifas desde la base de datos
    $stmtTarifas = Conexion::conectar()->prepare("SELECT tiempo, monto FROM $tablaTarifas ORDER BY tiempo ASC");
    $stmtTarifas->execute();
    
    $tarifas = [];
    while ($fila = $stmtTarifas->fetch(PDO::FETCH_ASSOC)) {
        $tarifas[$fila['tiempo']] = $fila['monto'];
    }

    // Determinar el monto a cobrar basado en las tarifas
    if ($minutos <= 15) {
        return isset($tarifas[15]) ? $tarifas[15] : 0;
    } elseif ($minutos <= 30) {
        return isset($tarifas[30]) ? $tarifas[30] : 0;
    } elseif ($minutos <= 60) {
        return isset($tarifas[60]) ? $tarifas[60] : 0;
    } else {
        // Cobro por horas adicionales
        $montoTotal = isset($tarifas[60]) ? $tarifas[60] : 0; // Tarifa base para la primera hora
        $horasAdicionales = ceil(($minutos - 60) / 60);
        $tarifaPorHoraAdicional = isset($tarifas[60]) ? $tarifas[60] / 2 : 0; // Tarifa por hora adicional
        $montoTotal += $horasAdicionales * $tarifaPorHoraAdicional;
        return $montoTotal;
    }
}

public static function obtenerTarifas()
{
    $stmt = Conexion::conectar()->prepare("SELECT tiempo, monto FROM tarifas");
    $stmt->execute();
    $tarifas = [];
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tarifas[$fila['tiempo']] = $fila['monto'];
    }
    return $tarifas;
}


public static function obtenerMontoPorTiempo($minutosTranscurridos)
{
    // Obtener todas las tarifas desde la base de datos
    $stmt = Conexion::conectar()->prepare("SELECT tiempo, monto FROM tarifas ORDER BY monto ASC");
    $stmt->execute();
    $tarifas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $montoTotal = 0;

    // Recorrer las tarifas para encontrar el monto correcto
    foreach ($tarifas as $tarifa) {
        $tiempo = $tarifa['tiempo'];
        $monto = $tarifa['monto'];
        
        // Verificar cada tramo de tiempo para asignar el monto adecuado
        switch ($tiempo) {
            case '15 minutos':
                if ($minutosTranscurridos <= 15) {
                    $montoTotal = $monto;
                    return $montoTotal;
                }
                break;
            case '30 minutos':
                if ($minutosTranscurridos <= 30) {
                    $montoTotal = $monto;
                    return $montoTotal;
                }
                break;
            case '1 hora':
                if ($minutosTranscurridos <= 60) {
                    $montoTotal = $monto;
                    return $montoTotal;
                }
                break;
            case '2 horas':
                if ($minutosTranscurridos <= 120) {
                    $montoTotal = $monto;
                    return $montoTotal;
                }
                break;
            case '4 horas':
                if ($minutosTranscurridos <= 240) {
                    $montoTotal = $monto;
                    return $montoTotal;
                }
                break;
            case '1 día (24 horas)':
                if ($minutosTranscurridos <= 1440) {
                    $montoTotal = $monto;
                    return $montoTotal;
                }
                break;
            case 'Día adicional':
                if ($minutosTranscurridos > 1440) {
                    $diasAdicionales = ceil(($minutosTranscurridos - 1440) / 1440);
                    $montoTotal = $monto * $diasAdicionales;
                    return $montoTotal;
                }
                break;
        }
    }

    return $montoTotal; // Retornar el monto calculado
}



// --------------------------------------------// 

   // Método para guardar el ticket
   public static function mdlGuardarTicket($tabla, $registroId, $archivoPdf) {
    try {
        // Conectar a la base de datos
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (registro_id, archivo_pdf) VALUES (:registro_id, :archivo_pdf)");

        // Asignar valores a los parámetros
        $stmt->bindParam(":registro_id", $registroId, PDO::PARAM_INT);
        $stmt->bindParam(":archivo_pdf", $archivoPdf, PDO::PARAM_LOB); // Usa PDO::PARAM_LOB para datos binarios

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Éxito
        } else {
            return false; // Fallo en la ejecución
        }
    } catch (PDOException $e) {
        // Manejo de errores
        return false;
    }
}

// Método para obtener un ticket por ID de registro
public static function mdlObtenerTicketPorRegistroId($tabla, $registroId) {
    $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE registro_id = :registro_id");
    $stmt->bindParam(":registro_id", $registroId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el ticket encontrado o false si no existe
}

public static function mdlObtenerDatosTicket($idTicket) {
    $stmt = Conexion::conectar()->prepare("SELECT tipo_vehiculo AS tipoVehiculo, nombre_vehiculo AS nombreVehiculo, numero_placa AS numeroPlaca, hora_entrada 
AS horaEntrada, hora_salida AS horaSalida, monto_total AS montoTotal FROM registro_vehiculos WHERE id = :id;");
    $stmt->bindParam(":id", $idTicket, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}





}
