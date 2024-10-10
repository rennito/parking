<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

// Incluye tus archivos de conexión y modelo
require_once "../controladores/vehiculo.controlador.php";
require_once "../modelos/vehiculo.modelo.php";

// Verifica que se haya recibido el ID del vehículo por POST
if (isset($_POST['id']) && !empty($_POST['id'])) {
    // Obtén el ID del vehículo de la solicitud POST
    $idVehiculo = intval($_POST['id']); // Asegura que sea un número entero

    // Intenta marcar la salida del vehículo
    $resultado = ControladorVehiculos::ctrMarcarSalidaVehiculo($idVehiculo);

    // Verifica si la operación fue exitosa
    if ($resultado && isset($resultado['success']) && $resultado['success']) {
        echo json_encode([
            "success" => true,
            "message" => "Salida registrada con éxito.",
            "montoTotal" => $resultado['montoTotal'], // Monto total
            "horaEntrada" => $resultado['horaEntrada'], // Hora de entrada
            "horaSalida" => $resultado['horaSalida'], // Hora de salida
            "tipoVehiculo" => $resultado['tipoVehiculo'], // Tipo de vehículo
            "numeroPlaca" => $resultado['numeroPlaca'] // Número de placa
        ]);
    } else {
        // En caso de error en el proceso de registrar salida
        echo json_encode([
            "success" => false,
            "message" => isset($resultado['message']) ? $resultado['message'] : "Error al registrar la salida."
        ]);
    }
} else {
    // En caso de que no se proporcione el ID del vehículo
    echo json_encode([
        "success" => false,
        "message" => "ID del vehículo no proporcionado o inválido."
    ]);
}

exit;
?>
