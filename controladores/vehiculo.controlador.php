<?php

// Incluir la biblioteca TCPDF
require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php'; // Ajusta según tu estructura
// Asegúrate de que esta ruta sea correcta

class ControladorVehiculos
{
    // Método para registrar un vehículo y su entrada
    public static function ctrRegistrarVehiculo($tipoVehiculo, $nombreVehiculo, $numeroPlaca)
    {
        $tablaVehiculos = "vehicles"; // Nombre de la tabla de vehículos
        $tablaRegistro = "registro_vehiculos"; // Nombre de la tabla de registros

        // Primero, intenta registrar el vehículo (si no existe)
        $respuestaRegistro = ModeloVehiculos::mdlRegistrarEntrada($tablaVehiculos, $tablaRegistro, $tipoVehiculo, $nombreVehiculo, $numeroPlaca);

        // Verificar si el registro fue exitoso
        if ($respuestaRegistro) {
            return ["success" => true, "message" => "Registro exitoso."];
        } else {
            return ["success" => false, "message" => "Error al registrar el vehículo o la entrada."];
        }
    }

    // Método para mostrar vehículos
    public static function ctrMostrarVehiculos($item, $valor)
    {
        $tabla = "vehicles"; // Nombre de la tabla

        $respuesta = ModeloVehiculos::mdlMostrarVehiculos($tabla, $item, $valor);

        return $respuesta; // Retorna la respuesta del modelo
    }

    // Método para eliminar un vehículo
    public static function ctrEliminarVehiculo($idVehiculo)
    {
        $tabla = "vehicles"; // Nombre de la tabla

        // Llama al modelo para eliminar el vehículo
        $respuesta = ModeloVehiculos::mdlEliminarVehiculo($tabla, $idVehiculo);

        // Retorna true o false según la respuesta
        return $respuesta;
    }

    // Mostrar entradas de vehículos
    public static function ctrMostrarEntradasVehiculos($item, $valor)
    {
        $tabla = "registro_vehiculos";
        return ModeloVehiculos::mdlMostrarEntradasVehiculos($tabla, $item, $valor);
    }

    // -----------------METODO SIN API---------------------------------//
    /*
    // Método para marcar la salida del vehículo
    public static function ctrMarcarSalidaVehiculo($idVehiculo)
    {
        $tablaRegistro = "registro_vehiculos";
    
        // Obtener el registro de la entrada del vehículo
        $registro = ModeloVehiculos::mdlMostrarEntradasVehiculos($tablaRegistro, "id", $idVehiculo);
    
        if ($registro) {
            // Configurar la zona horaria
            date_default_timezone_set('America/Mazatlan');
    
            // Obtener la hora de entrada desde la base de datos
            $horaEntrada = new DateTime($registro['hora_entrada']);
            // Obtener la hora actual como hora de salida
            $horaSalida = new DateTime(); // Hora actual
    
            // Calcular la diferencia entre la hora de entrada y la hora de salida
            $intervalo = $horaSalida->diff($horaEntrada);
            $minutosTranscurridos = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;
    
            // Si los minutos transcurridos son negativos, esto indica un problema con la zona horaria
            if ($minutosTranscurridos < 0) {
                return ["success" => false, "message" => "Error: Diferencia de tiempo incorrecta"];
            }
    
            // Obtener el monto correspondiente según las tarifas
            $montoTotal = ModeloVehiculos::obtenerMontoPorTiempo($minutosTranscurridos);
    
            // Actualizar la base de datos con la hora de salida y el monto total
            $resultado = ModeloVehiculos::mdlMarcarSalidaVehiculo(
                $tablaRegistro,
                $idVehiculo,
                $horaSalida->format('Y-m-d H:i:s'),
                $montoTotal
            );
    
            // Verificar si la salida fue registrada correctamente
            if ($resultado) {
                // Generar el contenido del PDF
                $pdfContent = self::generarPdf($registro, $horaSalida->format('Y-m-d H:i:s'), $montoTotal);
    
                // Guardar el ticket en la base de datos
                $guardarTicket = ControladorVehiculos::ctrGuardarTicket($registro['id'], $pdfContent); // Suponiendo que el ID del registro es el del vehículo
    
                if (!$guardarTicket['success']) {
                    return ["success" => false, "message" => "Error al guardar el ticket."];
                }
    
                // Ahora agregar tipo de vehículo y número de placa a la respuesta
                return [
                    "success" => true,
                    "message" => "Salida registrada con éxito.",
                    "montoTotal" => $montoTotal,
                    "horaEntrada" => $horaEntrada->format('Y-m-d H:i:s'),
                    "horaSalida" => $horaSalida->format('Y-m-d H:i:s'),
                    "tipoVehiculo" => $registro['tipo_vehiculo'], // Asegúrate de que el campo exista
                    "numeroPlaca" => $registro['numero_placa']  // Asegúrate de que el campo exista
                ];
            } else {
                return ["success" => false, "message" => "Error al registrar la salida."];
            }
        } else {
            return ["success" => false, "message" => "Registro no encontrado."];
        }
    }
    */
    //------------METODO CON API----------//
    // Método para marcar la salida del vehículo
// Método para marcar la salida del vehículo
// Método para marcar la salida del vehículo
public static function ctrMarcarSalidaVehiculo($idVehiculo)
{
    $tablaRegistro = "registro_vehiculos";

    // Verifica que el idVehiculo sea válido
    if (empty($idVehiculo) || !is_numeric($idVehiculo)) {
        return ["success" => false, "message" => "ID del vehículo inválido."];
    }

    // Obtener el registro de la entrada del vehículo
    $registro = ModeloVehiculos::mdlMostrarEntradasVehiculos($tablaRegistro, "id", $idVehiculo);

    // Verificar que el registro se obtuvo correctamente
    if (!$registro) {
        return ["success" => false, "message" => "Registro no encontrado."];
    }

    // Configurar la zona horaria
    date_default_timezone_set('America/Mazatlan');

    // Obtener la hora de entrada desde la base de datos
    $horaEntrada = new DateTime($registro['hora_entrada']);
    // Obtener la hora actual como hora de salida
    $horaSalida = new DateTime(); // Hora actual

    // Calcular la diferencia entre la hora de entrada y la hora de salida
    $intervalo = $horaSalida->diff($horaEntrada);
    $minutosTranscurridos = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;

    // Si los minutos transcurridos son negativos, esto indica un problema con la zona horaria
    if ($minutosTranscurridos < 0) {
        return ["success" => false, "message" => "Error: Diferencia de tiempo incorrecta"];
    }

    // Obtener el monto correspondiente según las tarifas
    $montoTotal = ModeloVehiculos::obtenerMontoPorTiempo($minutosTranscurridos);

    // Actualizar la base de datos con la hora de salida y el monto total
    $resultado = ModeloVehiculos::mdlMarcarSalidaVehiculo(
        $tablaRegistro,
        $idVehiculo,
        $horaSalida->format('Y-m-d H:i:s'),
        $montoTotal
    );

    // Verificar si la salida fue registrada correctamente
    if ($resultado) {
        // Generar el contenido del PDF
        $pdfContent = self::generarPdf($registro, $horaSalida->format('Y-m-d H:i:s'), $montoTotal);

        // Guardar el ticket en la base de datos
        $guardarTicket = ControladorVehiculos::ctrGuardarTicket($registro['id'], $pdfContent); // Asegúrate de que el ID es correcto

        if (!$guardarTicket['success']) {
            return ["success" => false, "message" => "Error al guardar el ticket."];
        }

        // Ahora agregar tipo de vehículo y número de placa a la respuesta
        return [
            "success" => true,
            "message" => "Salida registrada con éxito.",
            "registro_id" => $registro['id'], // Añadir el ID del registro
            "montoTotal" => $montoTotal,
            "horaEntrada" => $horaEntrada->format('Y-m-d H:i:s'),
            "horaSalida" => $horaSalida->format('Y-m-d H:i:s'),
            "tipoVehiculo" => $registro['tipo_vehiculo'], // Asegúrate de que el campo exista
            "numeroPlaca" => $registro['numero_placa']  // Asegúrate de que el campo exista
        ];
    } else {
        return ["success" => false, "message" => "Error al registrar la salida."];
    }
}





    // Método para generar el contenido del PDF
    private static function generarPdf($registro, $horaSalida, $montoTotal)
    {
        ob_start(); // Inicia el buffer de salida

        // Crear el PDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Write(0, "Ticket de Salida\n", '', 0, 'C', true, 0, false, false, 0);
        $pdf->Write(0, "Hora de entrada: " . $registro['hora_entrada'] . "\n", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Hora de salida: " . $horaSalida . "\n", '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, "Monto total: $" . $montoTotal . "\n", '', 0, 'L', true, 0, false, false, 0);

        // Guardar el PDF en el buffer
        $pdfContent = $pdf->Output('', 'S'); // Salida como string
        ob_end_clean(); // Limpiar el buffer de salida

        return $pdfContent; // Retornar el contenido del PDF
    }

    // Método para guardar un ticket
    public static function ctrGuardarTicket($registroId, $archivoPdf)
    {
        $tabla = "tickets"; // Nombre de la tabla

        // Llama al modelo para guardar el ticket
        $resultado = ModeloVehiculos::mdlGuardarTicket($tabla, $registroId, $archivoPdf);

        return $resultado ? ["success" => true] : ["success" => false, "message" => "Error al guardar el ticket."];
    }

    // Método para obtener un ticket por ID de registro
    public static function ctrObtenerTicketPorRegistroId($registroId)
    {
        $tabla = "tickets"; // Nombre de la tabla
        $resultado = ModeloVehiculos::mdlObtenerTicketPorRegistroId($tabla, $registroId); // Asegúrate de que esté usando el modelo correcto

        if ($resultado) {
            return $resultado; // Devuelve el ticket encontrado
        } else {
            return ["success" => false, "message" => "No se encontró el ticket."];
        }
    }

    // Método para obtener el archivo PDF
    public static function ctrObtenerTicket($registroId)
    {
        // Llama al modelo para obtener el ticket por ID de registro
        $ticket = ModeloVehiculos::mdlObtenerTicketPorRegistroId("tickets", $registroId);

        if ($ticket) {
            // Establece los encabezados para la respuesta del PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="ticket.pdf"');

            // Salida del PDF
            echo $ticket['archivo_pdf'];
            exit; // Termina el script después de enviar el PDF
        } else {
            // Maneja el caso en que no se encuentra el ticket
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Ticket no encontrado."]);
            exit;
        }
    }
}
