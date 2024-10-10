<?php
// Habilitar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la librería TCPDF
require_once '../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

// Incluir el modelo donde se define ModeloVehiculos
require_once '../modelos/vehiculo.modelo.php'; // Asegúrate de que esta ruta sea correcta

// Verifica si se ha recibido el ID del ticket
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID del ticket no proporcionado.";
    exit;
}

// Obtener el ID del ticket desde la URL
$idTicket = intval($_GET['id']);

// Obtener los datos del ticket desde la base de datos
$datosTicket = ModeloVehiculos::mdlObtenerDatosTicket($idTicket);

if (!$datosTicket) {
    echo "No se encontraron datos para este ticket.";
    exit;
}

// Crear un nuevo objeto TCPDF con tamaño personalizado para 80 mm de ancho
$pdf = new TCPDF('P', 'mm', array(80, 100), true, 'UTF-8', false); // 80mm de ancho

// Configuración inicial del PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Parking System');
$pdf->SetTitle('Ticket de Vehículo 80mm');
$pdf->SetHeaderData('', 0, 'Parking System', 'Ticket 80mm');

// Configuración de las fuentes
$pdf->SetFont('helvetica', '', 10);

// Añadir una página
$pdf->AddPage();

// Contenido del PDF (personalizado para ticket de 80mm)
$html = '
    <h1>Ticket de Vehículo</h1>
    <p><strong>Tipo de Vehículo:</strong> ' . htmlspecialchars($datosTicket['tipoVehiculo']) . '</p>
    <p><strong>Nombre del Vehículo:</strong> ' . htmlspecialchars($datosTicket['nombreVehiculo']) . '</p>
    <p><strong>Número de Placa:</strong> ' . htmlspecialchars($datosTicket['numeroPlaca']) . '</p>
    <p><strong>Hora de Entrada:</strong> ' . htmlspecialchars($datosTicket['horaEntrada']) . '</p>
    <p><strong>Hora de Salida:</strong> ' . htmlspecialchars($datosTicket['horaSalida']) . '</p>
    <p><strong>Monto Total:</strong> $' . htmlspecialchars($datosTicket['montoTotal']) . '</p>
';

// Escribir el contenido HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF al navegador
$pdf->Output('ticket_' . $idTicket . '_80mm.pdf', 'I'); // Muestra el PDF en el navegador