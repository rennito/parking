<?php
// Mostrar errores de PHP para depuración (eliminar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

// Incluimos los controladores y modelos necesarios
require_once "../controladores/vehiculo.controlador.php";
require_once "../modelos/vehiculo.modelo.php";
require_once "../modelos/ticket.modelo.php";


// Depuración: Verificar si los datos han sido recibidos correctamente
error_log("Datos recibidos por POST: " . print_r($_POST, true));

// Verificamos si se recibieron los datos necesarios desde la solicitud AJAX
if (isset($_POST['tipoVehiculo']) && isset($_POST['nombreVehiculo']) && isset($_POST['numeroPlaca']) && 
    !empty($_POST['tipoVehiculo']) && !empty($_POST['nombreVehiculo']) && !empty($_POST['numeroPlaca'])) {

    // Asignamos los datos recibidos a variables
    $tipoVehiculo = $_POST['tipoVehiculo'];
    $nombreVehiculo = $_POST['nombreVehiculo'];
    $numeroPlaca = $_POST['numeroPlaca'];

    // Depuración: Verificar los datos recibidos
    error_log("Datos procesados para registrar el vehículo: Tipo: $tipoVehiculo, Nombre: $nombreVehiculo, Placa: $numeroPlaca");

    // Registrar el vehículo
    $respuesta = ControladorVehiculos::ctrRegistrarVehiculo($tipoVehiculo, $nombreVehiculo, $numeroPlaca);

    // Depuración: Verificar la respuesta del controlador
    error_log("Respuesta del controlador: " . print_r($respuesta, true));

    // Verificamos si el registro fue exitoso
    if ($respuesta && isset($respuesta['success']) && $respuesta['success']) {
        $registroId = $respuesta['registro_id'] ?? null;

        // Depuración: Verificar si el registroId es válido
        error_log("Registro ID: " . $registroId);

        // Verificar si el registro_id es un número positivo
        if (!is_numeric($registroId) || $registroId <= 0) {
            echo json_encode([
                "success" => false,
                "message" => "El ID del registro debe ser un número positivo."
            ]);
            exit;
        }

        // Datos del ticket a enviar al servidor Java
        $data = [
            "registroId" => $registroId // Usamos el ID del registro recién creado
        ];

        $url = "http://localhost:8080/api/imprimirTicketRegistro"; // Ajusta la URL a tu servidor Java

        // Inicializar cURL para hacer la solicitud POST
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Verificar si hubo un error en la solicitud cURL
        if ($response === false) {
            $curlError = curl_error($ch);
            curl_close($ch);

            // Respuesta de error de cURL
            echo json_encode([
                "success" => false,
                "message" => "Error al comunicarse con el servidor Java: " . $curlError
            ]);
            exit;
        }

        curl_close($ch);

        // Depuración: Verificar la respuesta del servidor Java
        error_log("Respuesta del servidor Java: " . $response);
        error_log("Código HTTP del servidor Java: " . $httpCode);

        // Verificar la respuesta de la API de impresión
        if ($httpCode == 200 && !empty($response)) {
            // Guardar el ticket en la base de datos
            $ticketData = [
                "registro_id" => $registroId, // ID del registro recién creado
                "archivo_pdf" => $response // Guarda la respuesta del servidor Java o el archivo PDF
            ];

            $guardarTicket = ModeloTicket::mdlGuardarTicket($ticketData);

            if ($guardarTicket['success']) {
                // Respuesta exitosa
                echo json_encode([
                    "success" => true,
                    "message" => "Vehículo registrado y ticket impreso con éxito.",
                    "registroId" => $registroId,
                    "tipoVehiculo" => $tipoVehiculo,
                    "nombreVehiculo" => $nombreVehiculo,
                    "numeroPlaca" => $numeroPlaca,
                    "codigo_qr" => $respuesta['codigo_qr'] ?? null // Si tienes un QR, inclúyelo en la respuesta
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Vehículo registrado pero no se pudo guardar el ticket en la base de datos."
                ]);
            }
        } else {
            // Error al comunicarse con la API de Java
            echo json_encode([
                "success" => false,
                "message" => "Error al imprimir el ticket. Código HTTP: " . $httpCode
            ]);
        }
    } else {
        // Error al registrar el vehículo
        echo json_encode([
            "success" => false,
            "message" => isset($respuesta['message']) ? $respuesta['message'] : "Error al registrar el vehículo."
        ]);
    }
} else {
    // Si no se reciben los datos requeridos o están vacíos, respondemos con un error
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos para registrar el vehículo o algunos campos están vacíos."
    ]);
}

exit;
