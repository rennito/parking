<?php
require_once "../controladores/vehiculos.controlador.php";
require_once "../modelos/vehiculos.modelo.php";

// Registrar la entrada del vehículo
if (isset($_POST["tipoVehiculo"])) {
    $tipoVehiculo = $_POST["tipoVehiculo"];
    $nombreVehiculo = $_POST["nombreVehiculo"];
    $numeroPlaca = $_POST["numeroPlaca"];
    echo ControladorVehiculos::ctrRegistrarEntradaVehiculo($tipoVehiculo, $nombreVehiculo, $numeroPlaca);
}

// Marcar la salida del vehículo
if (isset($_POST["idVehiculo"])) {
    $idVehiculo = $_POST["idVehiculo"];
    echo ControladorVehiculos::ctrMarcarSalidaVehiculo($idVehiculo);
}
?>
