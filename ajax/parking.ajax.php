<?php

require_once "../controladores/parking.controlador.php";
require_once "../modelos/parking.modelo.php";

class AjaxParking {

    /*=============================================
    EDITAR ENTRADA
    =============================================*/    

    public $idEntrada;

    public function ajaxEditarEntrada() {
        $item = "id";
        $valor = $this->idEntrada;

        $respuesta = ControladorParking::ctrMostrarRegistros($item, $valor);
        echo json_encode($respuesta);
    }

}

/*=============================================
EDITAR ENTRADA
=============================================*/    

if(isset($_POST["idEntrada"])) {
    $entrada = new AjaxParking();
    $entrada->idEntrada = $_POST["idEntrada"];
    $entrada->ajaxEditarEntrada();
}
