<?php

// Mostrar errores de PHP para depuración (eliminar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../controladores/vehiculo.controlador.php"; // Asegúrate de incluir el controlador correspondiente
require_once "../modelos/vehiculo.modelo.php"; // Asegúrate de incluir el modelo correspondiente

class AjaxVehiculos {
    public $tipoVehiculo;
    public $nombreVehiculo;
    public $numeroPlaca; 

    // Método para registrar un vehículo
    public function ajaxRegistrarVehiculo() {
        $respuesta = ControladorVehiculos::ctrRegistrarVehiculo($this->tipoVehiculo, $this->nombreVehiculo, $this->numeroPlaca);
        
        // Devuelve la respuesta completa
        echo json_encode($respuesta); // Debería devolver ["success" => true/false, "message" => "mensaje"]
    }
    
}

// Verificamos si se recibieron los datos desde la solicitud AJAX
if (isset($_POST['tipoVehiculo']) && isset($_POST['nombreVehiculo']) && isset($_POST['numeroPlaca'])) { 
    $vehiculo = new AjaxVehiculos();
    $vehiculo->tipoVehiculo = $_POST['tipoVehiculo'];
    $vehiculo->nombreVehiculo = $_POST['nombreVehiculo'];
    $vehiculo->numeroPlaca = $_POST['numeroPlaca']; 
    
    // Llama al método para registrar el vehículo
    $vehiculo->ajaxRegistrarVehiculo(); 
} else {
    // Si no se reciben los datos requeridos, respondemos con un error
    echo json_encode(["success" => false, "message" => "Faltan datos para registrar el vehículo."]);
}


?>
