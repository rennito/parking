<?php
// Habilitar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la librería TCPDF
require_once '../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

// Incluir el modelo donde se define ModeloVehiculos
require_once '../modelos/carroGeneraTicket.modelo.php'; // Asegúrate de que esta ruta sea correcta

// Verifica si se ha recibido el ID a través de GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID del ticket no proporcionado.";
    exit;
}

// Obtener el ID del ticket desde la URL
$idTicket = intval($_GET['id']);

// Obtener los datos del ticket desde la base de datos
$datosTicket = ModeloGeneraTicket::mdlObtenerDatosTicket($idTicket);

if (!$datosTicket) {
    echo "No se encontraron datos para este ticket.";
    exit;
}

// Crear un nuevo objeto TCPDF con tamaño personalizado para 58 mm de ancho
$pdf = new TCPDF('P', 'mm', array(58, 165), true, 'UTF-8', false); // 58mm de ancho

// Configuración inicial del PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Parking System');
$pdf->SetTitle('Ticket de Vehículo 58mm');
$pdf->SetHeaderData('', 0, 'Parking System', '');

// Configuración de las fuentes
$pdf->SetFont('helvetica', '', 8);

// Añadir una página
$pdf->AddPage();

// Contenido del PDF (personalizado para ticket de 58mm)
$html = '
    <h1 style="text-align: center;">Ticket de Vehículo</h1>
    <p><strong>Tipo de Vehículo:</strong> ' . htmlspecialchars($datosTicket['tipoVehiculo']) . '</p>
    <p><strong>Nombre del Vehículo:</strong> ' . htmlspecialchars($datosTicket['nombreVehiculo']) . '</p>
    <p><strong>Número de Placa:</strong> ' . htmlspecialchars($datosTicket['numeroPlaca']) . '</p>
    <p><strong>Hora de Entrada:</strong> ' . htmlspecialchars($datosTicket['horaEntrada']) . '</p>
 
';

// Escribir el contenido HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Generar el código QR
$qrCode = new \Endroid\QrCode\QrCode("Vehículo ID: $idTicket, Placa: " . $datosTicket['numeroPlaca']);
$writer = new \Endroid\QrCode\Writer\PngWriter(); // Cambia a PNG si lo prefieres

// Definir la ruta para guardar el código QR
$qrPath = __DIR__ . '/../qrs/qr_' . $idTicket . '.png'; // Cambia la lógica de nombre según necesites

// Guardar el QR como archivo PNG
$result = $writer->write($qrCode);
$result->saveToFile($qrPath);

// Añadir el código QR al PDF
$pdf->Image($qrPath, 18, 65, 22, 22, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false); // Ajustar posición y tamaño

// Mensaje de agradecimiento
$pdf->SetFont('helvetica', 'I', 8); // Cambiar a fuente en cursiva
$pdf->Cell(0, 80, '¡Gracias por su visita!', 0, 1, 'C'); // Centrando el mensaje

// Salida del PDF al navegador
$pdf->Output('ticket_' . $idTicket . '_58mm.pdf', 'I'); // Muestra el PDF en el navegador
