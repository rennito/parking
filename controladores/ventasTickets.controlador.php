<?php

class ControladorVentasTickets {

    /*=============================================
    MOSTRAR TICKETS
    =============================================*/
    public static function ctrMostrarTickets() {
        $tabla = "tickets";
        $respuesta = ModeloVentasTickets::mdlMostrarTickets($tabla);
        return $respuesta;
    }


     /*=============================================
    ELIMINAR TICKET
    =============================================*/
    public static function ctrEliminarTicket($id) {
        $tabla = "tickets";
        $respuesta = ModeloVentasTickets::mdlEliminarTicket($tabla, $id);
        return $respuesta;
    }

    public static function ctrRangoFechasTickets($fechaInicial, $fechaFinal) {
        $tabla = "registro_vehiculos";
        $respuesta = ModeloVentasTickets::mdlRangoFechasTickets($tabla, $fechaInicial, $fechaFinal);
        return $respuesta;
    }
    

}
?>
