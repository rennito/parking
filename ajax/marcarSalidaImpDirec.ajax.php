<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

// Incluye tus archivos de conexión y modelo
require_once "../controladores/vehiculo.controlador.php";
require_once "../modelos/vehiculo.modelo.php";
require_once "../modelos/ticket.modelo.php";

// Verifica que se haya recibido el ID del vehículo por POST
if (isset($_POST['id']) && !empty($_POST['id'])) {
    // Obtén el ID del vehículo de la solicitud POST
    $idVehiculo = intval($_POST['id']); // Asegura que sea un número entero

    // Intenta marcar la salida del vehículo
    $resultado = ControladorVehiculos::ctrMarcarSalidaVehiculo($idVehiculo);

    // Verifica si la operación fue exitosa
    if ($resultado && isset($resultado['success']) && $resultado['success']) {
        // Verifica si el registro_id está definido antes de usarlo
        if (isset($resultado['registro_id'])) {
            // Datos del ticket a enviar al servidor Java
            $data = [
                "registroId" => $resultado['registro_id'] // Envía solo el registroId como se espera
            ];

            // Realizar la solicitud al servidor Java
            $url = "http://localhost:8080/api/imprimirTicket"; // Ajusta la URL a tu servidor Java

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Solo envío de registroId

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Guardar el ticket en la base de datos
            $ticketData = [
                "registro_id" => $resultado['registro_id'], // Suponiendo que tienes el ID del registro
                "archivo_pdf" => $response // Guarda la respuesta del servidor Java o el archivo PDF
            ];
            
            $guardarTicket = ModeloTicket::mdlGuardarTicket($ticketData);

            if ($httpCode == 200 && $guardarTicket['success']) {
                echo json_encode([
                    "success" => true,
                    "message" => "Salida registrada con éxito y ticket impreso.",
                    "montoTotal" => $resultado['montoTotal'],
                    "horaEntrada" => $resultado['horaEntrada'],
                    "horaSalida" => $resultado['horaSalida'],
                    "tipoVehiculo" => $resultado['tipoVehiculo'],
                    "numeroPlaca" => $resultado['numeroPlaca'],
                    "respuestaImpresion" => $response
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Error al imprimir el ticket en el servidor Java. Código HTTP: " . $httpCode
                ]);
            }
        } else {
            // Maneja el caso en que registro_id no esté definido
            echo json_encode([
                "success" => false,
                "message" => "No se pudo obtener el registro ID."
            ]);
        }
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
