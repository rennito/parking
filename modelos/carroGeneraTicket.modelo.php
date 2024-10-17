<?php

// Asegúrate de que la ruta a TCPDF sea correcta
require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once "conexion.php"; // Asegúrate de que esta ruta sea correcta

class ModeloGeneraTicket
{

    /*=============================================
    MARCAR SALIDA DEL VEHÍCULO
    =============================================*/
    /*public static function mdlMarcarSalidaVehiculo($idVehiculo)
    {
        try {
            $pdo = Conexion::conectar();
    
            // Obtener la hora de entrada del vehículo
            $stmt = $pdo->prepare("SELECT hora_entrada, tipo_vehiculo, nombre_vehiculo, numero_placa FROM registro_vehiculos WHERE id = :id");
            $stmt->bindParam(":id", $idVehiculo, PDO::PARAM_INT);
            $stmt->execute();
            $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$vehiculo) {
                return ["success" => false, "message" => "Vehículo no encontrado."];
            }
    
            $horaEntrada = new DateTime($vehiculo['hora_entrada']);
            $horaSalida = new DateTime(); // Hora actual
    
            // Calcular el monto total basado en el tiempo de estancia
            $montoTotal = self::calcularMontoTotal($horaEntrada, $horaSalida);
    
            // Actualiza la hora de salida y el estado de pago en la tabla 'registro_vehiculos'
            $stmtUpdate = $pdo->prepare("UPDATE registro_vehiculos SET hora_salida = NOW(), estado_pago = 1, monto_total = :montoTotal WHERE id = :id");
            $stmtUpdate->bindParam(":id", $idVehiculo, PDO::PARAM_INT);
            $stmtUpdate->bindParam(":montoTotal", $montoTotal, PDO::PARAM_STR);
            $stmtUpdate->execute();
    
            // Recupera los datos del vehículo nuevamente para asegurarte de que todos los valores estén disponibles
            $stmtSelect = $pdo->prepare("SELECT tipo_vehiculo, numero_placa FROM registro_vehiculos WHERE id = :id");
            $stmtSelect->bindParam(":id", $idVehiculo, PDO::PARAM_INT);
            $stmtSelect->execute();
            $vehiculoActualizado = $stmtSelect->fetch(PDO::FETCH_ASSOC);
    
            return [
                "success" => true,
                "message" => "Salida registrada con éxito.",
                "montoTotal" => number_format($montoTotal, 2), // Asegúrate de formatear el monto
                "horaEntrada" => $horaEntrada->format('Y-m-d H:i:s'),
                "horaSalida" => $horaSalida->format('Y-m-d H:i:s'),
                "tipoVehiculo" => $vehiculoActualizado['tipo_vehiculo'], // Ahora tomas de la consulta actualizada
                "numeroPlaca" => $vehiculoActualizado['numero_placa'], // Ahora tomas de la consulta actualizada
            ];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }*/
    /* public static function mdlMarcarSalidaVehiculo($idVehiculo)
    {
        try {
            $pdo = Conexion::conectar();

            // Actualiza la hora de salida y el estado de pago en la tabla 'registro_vehiculos'
            $stmt = $pdo->prepare("UPDATE registro_vehiculos SET hora_salida = NOW(), estado_pago = 1 WHERE id = :id");
            $stmt->bindParam(":id", $idVehiculo, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Obtener los datos del vehículo para el ticket
                $stmtSelect = $pdo->prepare("SELECT tipo_vehiculo, nombre_vehiculo, numero_placa, hora_entrada, monto_total FROM registro_vehiculos WHERE id = :id");
                $stmtSelect->bindParam(":id", $idVehiculo, PDO::PARAM_INT);
                $stmtSelect->execute();
                $vehiculo = $stmtSelect->fetch(PDO::FETCH_ASSOC);

                // Generar el PDF del ticket
                $pdfPath = self::generarTicketPdf($vehiculo); // Método para generar el PDF

                // Guardar el ticket en la base de datos
                $ticketSaved = self::guardarTicket($idVehiculo, $pdfPath);

                return [
                    "success" => true,
                    "message" => "Salida registrada con éxito.",
                    "montoTotal" => $vehiculo['monto_total'],
                    "horaEntrada" => $vehiculo['hora_entrada'],
                    "horaSalida" => date('Y-m-d H:i:s'), // Hora actual
                    "tipoVehiculo" => $vehiculo['tipo_vehiculo'],
                    "numeroPlaca" => $vehiculo['numero_placa'],
                    "ticketSaved" => $ticketSaved // Información sobre el guardado del ticket
                ];
            } else {
                return ["success" => false, "message" => "Error al actualizar la salida del vehículo."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }
*/
public static function mdlMarcarSalidaVehiculo($idVehiculo)
{
    try {
        $pdo = Conexion::conectar();

        // Obtener los datos del vehículo para el ticket, incluyendo el monto total
        $stmtSelect = $pdo->prepare("SELECT tipo_vehiculo, nombre_vehiculo, numero_placa, hora_entrada, monto_total FROM registro_vehiculos WHERE id = :id");
        $stmtSelect->bindParam(":id", $idVehiculo, PDO::PARAM_INT);
        $stmtSelect->execute();
        $vehiculo = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if (!$vehiculo) {
            return ["success" => false, "message" => "Vehículo no encontrado."];
        }

        $horaEntrada = new DateTime($vehiculo['hora_entrada']);
        $horaSalida = new DateTime(); // Hora actual

        // Calcular el monto total
        $montoTotal = self::calcularMontoTotal($horaEntrada, $horaSalida); // Método para calcular el monto

        // Actualiza la hora de salida y el estado de pago en la tabla 'registro_vehiculos'
        $stmtUpdate = $pdo->prepare("UPDATE registro_vehiculos SET hora_salida = NOW(), estado_pago = 1, monto_total = :montoTotal WHERE id = :id");
        $stmtUpdate->bindParam(":id", $idVehiculo, PDO::PARAM_INT);
        $stmtUpdate->bindParam(":montoTotal", $montoTotal, PDO::PARAM_STR);
        $stmtUpdate->execute();

        // Después de actualizar, vuelve a seleccionar para obtener el monto total actualizado
        $stmtSelect->execute();
        $vehiculo = $stmtSelect->fetch(PDO::FETCH_ASSOC); // Actualiza el array vehiculo

        // Generar el PDF del ticket
        $pdfPath = self::generarTicketPdf($vehiculo); // Método para generar el PDF

        // Guardar el ticket en la base de datos
        $ticketSaved = self::guardarTicket($idVehiculo, $pdfPath);

        return [
            "success" => true,
            "message" => "Salida registrada con éxito.",
            "montoTotal" => $vehiculo['monto_total'],
            "horaEntrada" => $horaEntrada->format('Y-m-d H:i:s'),
            "horaSalida" => $horaSalida->format('Y-m-d H:i:s'), // Hora actual
            "tipoVehiculo" => $vehiculo['tipo_vehiculo'],
            "numeroPlaca" => $vehiculo['numero_placa'],
        ];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error: " . $e->getMessage()];
    }
}


    /*=============================================
CALCULAR MONTO TOTAL
=============================================*/
    private static function calcularMontoTotal($horaEntrada, $horaSalida)
    {
        $duracion = $horaEntrada->diff($horaSalida); // Calcula la diferencia
        $minutos = ($duracion->h * 60) + $duracion->i; // Convierte la duración a minutos

        // Obtiene las tarifas de la base de datos
        $tarifa = self::obtenerTarifa($minutos);

        return $tarifa ? $tarifa['monto'] : 0; // Retorna el monto o 0 si no se encuentra tarifa
    }

    /*=============================================
OBTENER TARIFA
=============================================*/
    private static function obtenerTarifa($minutos)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT * FROM tarifas ORDER BY tiempo ASC");
        $stmt->execute();
        $tarifas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verifica las tarifas
        foreach ($tarifas as $tarifa) {
            // Calcula la duración en minutos
            $duracionTarifa = intval(filter_var($tarifa['tiempo'], FILTER_SANITIZE_NUMBER_INT));

            if (strpos($tarifa['tiempo'], 'hora') !== false) {
                $duracionTarifa *= 60; // Convertir horas a minutos
            }

            if ($minutos <= $duracionTarifa) {
                return $tarifa; // Retorna la tarifa correspondiente
            }
        }

        return null; // Si no se encuentra tarifa
    }







    /*=============================================
    GENERAR TICKET PDF
    =============================================*/
    public static function generarTicketPdf($vehiculo)
    {
        // Crear una nueva instancia de TCPDF
        $pdf = new \TCPDF();

        // Configurar el documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Tu Nombre o Empresa');
        $pdf->SetTitle('Ticket de Vehículo');
        $pdf->SetSubject('Ticket de Vehículo');
        $pdf->SetKeywords('TCPDF, PDF, ticket, vehículo');

        // Establecer el tamaño de página y orientación
        $pdf->SetPageOrientation('P', true, 0);
        $pdf->AddPage(); // Añadir una página

        // Configura el contenido del PDF
        $html = '
        <h1 style="text-align: center;">Ticket de Vehículo</h1>
        <p><strong>Tipo de Vehículo:</strong> ' . htmlspecialchars($vehiculo['tipo_vehiculo']) . '</p>
        <p><strong>Nombre del Vehículo:</strong> ' . htmlspecialchars($vehiculo['nombre_vehiculo']) . '</p>
        <p><strong>Número de Placa:</strong> ' . htmlspecialchars($vehiculo['numero_placa']) . '</p>
        <p><strong>Hora de Entrada:</strong> ' . htmlspecialchars($vehiculo['hora_entrada']) . '</p>
        <p><strong>Hora de Salida:</strong> ' . date('Y-m-d H:i:s') . '</p>
        <p><strong>Monto Total:</strong> $' . number_format($vehiculo['monto_total'] ?? 0, 2) . '</p>

        ';

        // Escribir el contenido HTML en el PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Guardar el PDF en el servidor
        $pdfPath = __DIR__ . '/../tickets/ticket_' . $vehiculo['numero_placa'] . '.pdf'; // Cambia la lógica de nombre según necesites
        $pdf->Output($pdfPath, 'F'); // 'F' para guardar en un archivo

        return $pdfPath; // Retorna la ruta del archivo PDF guardado
    }


    public static function mdlObtenerDatosTicket($idTicket)
    {
        $stmt = Conexion::conectar()->prepare("SELECT tipo_vehiculo AS tipoVehiculo, nombre_vehiculo AS nombreVehiculo, numero_placa AS numeroPlaca, hora_entrada 
                                                AS horaEntrada, hora_salida AS horaSalida, monto_total AS montoTotal FROM registro_vehiculos WHERE id = :id;");
        $stmt->bindParam(":id", $idTicket, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*=============================================
    GUARDAR TICKET EN BASE DE DATOS
    =============================================*/
    public static function guardarTicket($registroId, $pdfPath)
    {
        try {
            $pdo = Conexion::conectar();

            // Cargar el contenido del archivo PDF
            $pdfData = file_get_contents($pdfPath);

            // Insertar en la tabla 'tickets'
            $stmt = $pdo->prepare("INSERT INTO tickets (registro_id, archivo_pdf) VALUES (:registroId, :archivoPdf)");
            $stmt->bindParam(":registroId", $registroId, PDO::PARAM_INT);
            $stmt->bindParam(":archivoPdf", $pdfData, PDO::PARAM_LOB); // LOB para el contenido del archivo PDF

            return $stmt->execute(); // Devuelve true si se guardó correctamente
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error al guardar el ticket: " . $e->getMessage()];
        }
    }
}
