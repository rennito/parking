<?php



class ControladorGeneraTicket
{
    /*=============================================
    MARCAR SALIDA DEL VEHÍCULO
    =============================================*/
    public static function ctrMarcarSalidaVehiculo($idVehiculo)
    {
        // Llama al modelo para marcar la salida del vehículo
        return ModeloGeneraTicket::mdlMarcarSalidaVehiculo($idVehiculo);
    }
}
?>
