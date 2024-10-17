<?php

require_once "../controladores/graficaVentas.controlador.php";
require_once "../modelos/graficaVentas.modelo.php";

class AjaxGraficaVentas {

    public function ajaxObtenerVentasPorDia() {
        // Llamamos al controlador para obtener los datos
        $respuesta = ControladorGraficaVentas::ctrVentasPorDia();

        // Enviamos los datos como JSON al frontend
        echo json_encode($respuesta);
    }
}

// Crear la instancia de la clase y ejecutar el método si se hace una solicitud Ajax
$grafica = new AjaxGraficaVentas();
$grafica->ajaxObtenerVentasPorDia();
