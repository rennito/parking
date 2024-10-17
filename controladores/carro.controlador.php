<?php

class ControladorCarro
{
    /*=============================================
    REGISTRAR VEHÍCULO Y ENTRADA AL ESTACIONAMIENTO
    =============================================*/
    public static function ctrRegistrarCarro($tipoVehiculo, $nombreVehiculo, $numeroPlaca)
    {
        // Llama al modelo para registrar la entrada del vehículo
        $respuesta = ModeloCarro::mdlRegistrarEntradaVehiculo($tipoVehiculo, $nombreVehiculo, $numeroPlaca);

        return $respuesta; // Devuelve la respuesta del modelo
    }



    /*=============================================
  MOSTRAR ENTRADAS DE VEHÍCULOS
=============================================*/
    public static function ctrMostrarEntradasVehiculos($item, $valor)
    {
        return ModeloCarro::mdlMostrarEntradasVehiculos($item, $valor);
    }


    /*=============================================
    ELIMINAR VEHÍCULO
    =============================================*/
    public static function ctrEliminarVehiculo($vehiculoId) {
        // Llama al modelo para eliminar el vehículo
        $respuesta = ModeloCarro::mdlEliminarVehiculo($vehiculoId);
        
        return $respuesta; // Devuelve la respuesta del modelo
    }
    
}
