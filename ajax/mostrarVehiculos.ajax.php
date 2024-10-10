<?php
// Asegúrate de mostrar los errores durante el desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Incluye los archivos necesarios
require_once "../controladores/vehiculo.controlador.php";
require_once "../modelos/vehiculo.modelo.php";

// Obtén los vehículos registrados
$vehiculos = ControladorVehiculos::ctrMostrarVehiculos(null, null);

// Verifica si se obtuvieron los datos correctamente
if ($vehiculos) {
    echo json_encode($vehiculos);
} else {
    echo json_encode(["error" => "No se encontraron vehículos."]);
}

exit;
?>
