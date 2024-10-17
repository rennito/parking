<?php

// Mostrar errores de PHP para depuración (eliminar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluye el controlador y el modelo
require_once "../controladores/carro.controlador.php"; 
require_once "../modelos/carro.modelo.php"; 

class AjaxVehiculos {
    public $tipoVehiculo;
    public $nombreVehiculo;
    public $numeroPlaca; 
    public $vehiculoId; // Propiedad para el ID del vehículo a eliminar

    // Método para registrar un vehículo
    public function ajaxRegistrarVehiculo() {
        // Validar que los campos no estén vacíos
        if (!empty($this->tipoVehiculo) && !empty($this->nombreVehiculo) && !empty($this->numeroPlaca)) {
            // Llamar al controlador para registrar el vehículo
            $respuesta = ControladorCarro::ctrRegistrarCarro($this->tipoVehiculo, $this->nombreVehiculo, $this->numeroPlaca);
            echo json_encode($respuesta); // Enviar la respuesta como JSON
        } else {
            echo json_encode(["success" => false, "message" => "Faltan datos para registrar el vehículo."]);
        }
    }

    // Método para eliminar un vehículo
    public function ajaxEliminarVehiculo() {
        // Validar que el ID no esté vacío
        if (!empty($this->vehiculoId)) {
            // Llamar al controlador para eliminar el vehículo
            $respuesta = ControladorCarro::ctrEliminarVehiculo($this->vehiculoId);
            echo json_encode($respuesta); // Enviar la respuesta como JSON
        } else {
            echo json_encode(["success" => false, "message" => "Falta el ID del vehículo para eliminar."]);
        }
    }

    
}

// Verificamos si se recibieron los datos desde la solicitud AJAX para registrar
if (isset($_POST['tipoVehiculo']) && isset($_POST['nombreVehiculo']) && isset($_POST['numeroPlaca'])) { 
    // Crear instancia de AjaxVehiculos y asignar valores
    $vehiculo = new AjaxVehiculos();
    $vehiculo->tipoVehiculo = $_POST['tipoVehiculo'];
    $vehiculo->nombreVehiculo = $_POST['nombreVehiculo'];
    $vehiculo->numeroPlaca = $_POST['numeroPlaca']; 
    $vehiculo->ajaxRegistrarVehiculo(); // Llamar al método de registro

// Verificamos si se recibió el ID del vehículo a eliminar
} else if (isset($_POST['vehiculoId'])) {
    // Crear instancia de AjaxVehiculos para eliminar
    $vehiculo = new AjaxVehiculos();
    $vehiculo->vehiculoId = $_POST['vehiculoId'];
    $vehiculo->ajaxEliminarVehiculo(); // Llamar al método de eliminación
} else {
    echo json_encode(["success" => false, "message" => "Faltan datos para registrar o eliminar el vehículo."]);
}

?>
