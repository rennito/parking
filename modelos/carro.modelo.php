<?php

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

require_once __DIR__ . '/../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta
require_once "conexion.php"; // Asegúrate de que esta ruta sea correcta

class ModeloCarro
{

    /*=============================================
REGISTRAR ENTRADA DEL VEHÍCULO EN 'registro_vehiculos'
=============================================*/
    /*    public static function mdlRegistrarEntradaVehiculo($tipoVehiculo, $nombreVehiculo, $numeroPlaca)
    {
        try {
            $pdo = Conexion::conectar();

            // Verificar si el vehículo ya está en el estacionamiento con estado de pago = 0
            $stmtCheck = $pdo->prepare("SELECT * FROM registro_vehiculos WHERE numero_placa = :numeroPlaca AND estado_pago = 0");
            $stmtCheck->bindParam(":numeroPlaca", $numeroPlaca, PDO::PARAM_STR);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                return ["success" => false, "message" => "Este vehículo se encuentra en el estacionamiento."];
            }

            // Insertar en la tabla 'registro_vehiculos'
            $stmt = $pdo->prepare("INSERT INTO registro_vehiculos (tipo_vehiculo, nombre_vehiculo, numero_placa) 
                                VALUES (:tipoVehiculo, :nombreVehiculo, :numeroPlaca)");
            $stmt->bindParam(":tipoVehiculo", $tipoVehiculo, PDO::PARAM_STR);
            $stmt->bindParam(":nombreVehiculo", $nombreVehiculo, PDO::PARAM_STR);
            $stmt->bindParam(":numeroPlaca", $numeroPlaca, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $registroId = $pdo->lastInsertId(); // Obtener el ID del registro recién insertado

                // Generar y guardar el código QR
                $codigoQrPath = self::generarCodigoQR($registroId, $numeroPlaca);

                // Actualizar el registro con el código QR
                $stmtUpdate = $pdo->prepare("UPDATE registro_vehiculos SET codigo_qr = :codigoQr WHERE id = :registroId");
                $stmtUpdate->bindParam(":codigoQr", $codigoQrPath, PDO::PARAM_STR);
                $stmtUpdate->bindParam(":registroId", $registroId, PDO::PARAM_INT);
                $stmtUpdate->execute();

                return ["success" => true, "message" => "Vehículo registrado con éxito.", "registro_id" => $registroId];
            } else {
                return ["success" => false, "message" => "Error al registrar la entrada del vehículo."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }

*/

    public static function mdlRegistrarEntradaVehiculo($tipoVehiculo, $nombreVehiculo, $numeroPlaca)
    {
        try {
            $pdo = Conexion::conectar();

            // Verificar si el vehículo ya está en el estacionamiento con estado de pago = 0
            $stmtCheck = $pdo->prepare("SELECT * FROM registro_vehiculos WHERE numero_placa = :numeroPlaca AND estado_pago = 0");
            $stmtCheck->bindParam(":numeroPlaca", $numeroPlaca, PDO::PARAM_STR);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                return ["success" => false, "message" => "Este vehículo se encuentra en el estacionamiento."];
            }

            // Insertar en la tabla 'registro_vehiculos'
            $stmt = $pdo->prepare("INSERT INTO registro_vehiculos (tipo_vehiculo, nombre_vehiculo, numero_placa) 
                                VALUES (:tipoVehiculo, :nombreVehiculo, :numeroPlaca)");
            $stmt->bindParam(":tipoVehiculo", $tipoVehiculo, PDO::PARAM_STR);
            $stmt->bindParam(":nombreVehiculo", $nombreVehiculo, PDO::PARAM_STR);
            $stmt->bindParam(":numeroPlaca", $numeroPlaca, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $registroId = $pdo->lastInsertId(); // Obtener el ID del registro recién insertado

                // Generar y guardar el código QR
                $codigoQrPath = self::generarCodigoQR($registroId, $numeroPlaca);

                // Actualizar el registro con el código QR
                $stmtUpdate = $pdo->prepare("UPDATE registro_vehiculos SET codigo_qr = :codigoQr WHERE id = :registroId");
                $stmtUpdate->bindParam(":codigoQr", $codigoQrPath, PDO::PARAM_STR);
                $stmtUpdate->bindParam(":registroId", $registroId, PDO::PARAM_INT);
                $stmtUpdate->execute();

                // Generar ticket de entrada
                $ticketPdfPath = self::generarTicketEntrada($registroId, $tipoVehiculo, $nombreVehiculo, $numeroPlaca);

                // Intentar guardar el ticket de entrada
                if (!self::guardarTicketEntrada($registroId, $ticketPdfPath)) {
                    return ["success" => false, "message" => "Error al guardar el ticket de entrada."];
                }

                return ["success" => true, "message" => "Vehículo registrado con éxito.", "registro_id" => $registroId];
            } else {
                return ["success" => false, "message" => "Error al registrar la entrada del vehículo."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }



    // Método para generar el ticket de entrada
    private static function generarTicketEntrada($registroId, $tipoVehiculo, $nombreVehiculo, $numeroPlaca)
    {
        // Crear una nueva instancia de TCPDF
        $pdf = new TCPDF('P', 'mm', array(58, 100), true, 'UTF-8', false); // Configura el tamaño del ticket

        // Configuración inicial del PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Parking System');
        $pdf->SetTitle('Ticket de Entrada');
        $pdf->SetHeaderData('', 0, 'Parking System', 'Ticket de Entrada');

        // Configuración de las fuentes
        $pdf->SetFont('helvetica', '', 8);

        // Añadir una página
        $pdf->AddPage();

        // Contenido del PDF para el ticket de entrada
        $html = '
        <h1 style="text-align: center;">Ticket de Entrada</h1>
        <p><strong>Registro ID:</strong> ' . htmlspecialchars($registroId) . '</p>
        <p><strong>Tipo de Vehículo:</strong> ' . htmlspecialchars($tipoVehiculo) . '</p>
        <p><strong>Nombre del Vehículo:</strong> ' . htmlspecialchars($nombreVehiculo) . '</p>
        <p><strong>Número de Placa:</strong> ' . htmlspecialchars($numeroPlaca) . '</p>
        <p><strong>Hora de Entrada:</strong> ' . date('Y-m-d H:i:s') . '</p>
        <p style="text-align: center;">¡Gracias por su visita!</p>
    ';

        // Escribir el contenido HTML en el PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Generar el código QR
        $qrCode = new \Endroid\QrCode\QrCode("Registro ID: $registroId, Placa: $numeroPlaca");
        $writer = new \Endroid\QrCode\Writer\PngWriter(); // Cambia a PNG si lo prefieres

        // Definir la ruta para guardar el código QR
        $qrPath = __DIR__ . '/../qrs/qr_entrada_' . $registroId . '.png'; // Cambia la lógica de nombre según necesites

        // Guardar el QR como archivo PNG
        $result = $writer->write($qrCode);
        $result->saveToFile($qrPath);

        // Añadir el código QR al PDF
        $pdf->Image($qrPath, 15, 80, 25, 25, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false); // Ajustar posición y tamaño según sea necesario

        // Definir la ruta para guardar el ticket de entrada
        $ticketPath = __DIR__ . '/../tickets/ticket_entrada_' . $registroId . '.pdf'; // Cambia la lógica de nombre según necesites

        // Guardar el PDF en el servidor
        $pdf->Output($ticketPath, 'F'); // 'F' para guardar en un archivo

        return $ticketPath; // Retorna la ruta del archivo PDF guardado
    }



    // Método para guardar el ticket de entrada en la base de datos
    private static function guardarTicketEntrada($registroId, $ticketPdfPath)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO tickets_entrada (registro_id, archivo_pdf) VALUES (:registro_id, :archivo_pdf)");
        $stmt->bindParam(":registro_id", $registroId, PDO::PARAM_INT);

        // Almacenar el contenido del archivo en una variable
        $archivoPdfContenido = file_get_contents($ticketPdfPath);
        $stmt->bindParam(":archivo_pdf", $archivoPdfContenido, PDO::PARAM_LOB);

        return $stmt->execute();
    }



    /*=============================================
GENERAR CÓDIGO QR
=============================================*/
    public static function generarCodigoQR($registroId, $numeroPlaca)
    {
        // Crear el código QR
        $qrCode = new \Endroid\QrCode\QrCode("Vehiculo ID: $registroId, Placa: $numeroPlaca");
        $writer = new \Endroid\QrCode\Writer\SvgWriter();  // Cambiamos a SVG

        // Definir la ruta para guardar el código QR en formato SVG
        $qrPath = __DIR__ . '/../qrs/qr_' . $registroId . '.svg';

        // Guardar el QR como archivo SVG
        $result = $writer->write($qrCode);
        $result->saveToFile($qrPath); // Guarda el archivo SVG en la ruta especificada

        return $qrPath; // Retorna la ruta del archivo SVG guardado
    }




    /*=============================================
  MOSTRAR ENTRADAS DE VEHÍCULOS
=============================================*/
    public static function mdlMostrarEntradasVehiculos($item, $valor)
    {
        try {
            $pdo = Conexion::conectar();
            $query = "SELECT * FROM registro_vehiculos"; // Aquí puedes agregar filtros si es necesario

            $stmt = $pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los registros
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    /*=============================================
    ELIMINAR VEHÍCULO EN 'registro_vehiculos'
    =============================================*/
    public static function mdlEliminarVehiculo($registroId)
    {
        try {
            $pdo = Conexion::conectar();

            // Eliminar el registro del vehículo en la tabla 'registro_vehiculos'
            $stmt = $pdo->prepare("DELETE FROM registro_vehiculos WHERE id = :registroId");
            $stmt->bindParam(":registroId", $registroId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ["success" => true, "message" => "Vehículo eliminado con éxito."];
            } else {
                return ["success" => false, "message" => "Error al eliminar el vehículo."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error: " . $e->getMessage()];
        }
    }
}
