<?php

class ControladorParking {

    /*=============================================
    MOSTRAR VEHÍCULOS
    =============================================*/
    static public function ctrMostrarVehiculos($item, $valor) {
        $tabla = "vehiculos"; // Asegúrate de que este sea el nombre correcto de tu tabla en la base de datos
        $respuesta = ModeloParking::mdlMostrarVehiculos($tabla, $item, $valor);
        return $respuesta;
    }

    /*=============================================
    REGISTRAR ENTRADA DE VEHÍCULO
    =============================================*/
    public static function ctrRegistrarEntrada($vehicleNumber) {
        $tabla = "parking_entries";

        // Obtener ID del vehículo por su número (placa)
        $datos = array("vehicle_id" => $vehicleNumber);

        $respuesta = ModeloParking::mdlRegistrarEntrada($tabla, $datos);

        return $respuesta;
    }

    /*=============================================
    REGISTRAR SALIDA DE VEHÍCULO Y CALCULAR TARIFA
    =============================================*/
    public static function ctrRegistrarSalida($vehicleNumber, $vehicleType) {
        $tabla = "parking_entries";

        // Datos necesarios: ID del vehículo y tipo de vehículo para la tarifa
        $datos = array(
            "vehicle_id" => $vehicleNumber,
            "vehicle_type" => $vehicleType
        );

        $respuesta = ModeloParking::mdlRegistrarSalida($tabla, $datos);

        return $respuesta;
    }

    /*=============================================
    MOSTRAR REGISTROS DE ENTRADA Y SALIDA
    =============================================*/
    public static function ctrMostrarRegistros($item = null, $valor = null) {
        $tabla = "parking_entries";
        $respuesta = ModeloParking::mdlMostrarRegistros($tabla, $item, $valor);

        return $respuesta;
    }
}
