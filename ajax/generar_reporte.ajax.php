<?php

require '../vendor/autoload.php'; // Cargar PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Recibir las fechas desde el formulario
$fechaInicial = isset($_POST['fechaInicial']) ? $_POST['fechaInicial'] : null;
$fechaFinal = isset($_POST['fechaFinal']) ? $_POST['fechaFinal'] : null;

// Verificar que las fechas sean válidas
if (!$fechaInicial || !$fechaFinal) {
    echo "Error: Faltan fechas.";
    exit;
}

// Conectar a la base de datos y obtener los datos
require_once '../controladores/ventasTickets.controlador.php';
require_once '../modelos/ventasTickets.modelo.php';

try {
    // Obtener los datos de la base de datos según las fechas seleccionadas
    $tickets = ControladorVentasTickets::ctrRangoFechasTickets($fechaInicial, $fechaFinal);

    // Verificar si hay datos
    if (empty($tickets)) {
        echo "No se encontraron tickets para el rango de fechas especificado.";
        exit;
    }

    // Crear un nuevo archivo de Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos de las columnas
    $sheet->setCellValue('A1', 'ID Vehículo');
    $sheet->setCellValue('B1', 'Tipo de Vehículo');
    $sheet->setCellValue('C1', 'Número de Placa');
    $sheet->setCellValue('D1', 'Hora de Entrada');
    $sheet->setCellValue('E1', 'Hora de Salida');
    $sheet->setCellValue('F1', 'Monto Cobrado');
    $sheet->setCellValue('G1', 'Estado de Pago');

    // Llenar el archivo Excel con los datos
    $fila = 2;
    $totalCobrado = 0;

    foreach ($tickets as $ticket) {
        $sheet->setCellValue('A' . $fila, $ticket["id"]);
        $sheet->setCellValue('B' . $fila, $ticket["tipo_vehiculo"]);
        $sheet->setCellValue('C' . $fila, $ticket["numero_placa"]);
        $sheet->setCellValue('D' . $fila, $ticket["hora_entrada"]);
        $sheet->setCellValue('E' . $fila, $ticket["hora_salida"]);
        $sheet->setCellValue('F' . $fila, $ticket["monto_total"]);
        $sheet->setCellValue('G' . $fila, $ticket["estado_pago"] ? "Pendiente" : "Pagado");

        $totalCobrado += $ticket["monto_total"];
        $fila++;
    }

    // Escribir el total al final
    $sheet->setCellValue('E' . $fila, 'Total Cobrado:');
    $sheet->setCellValue('F' . $fila, $totalCobrado);

    // Configurar los encabezados para la descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_entradas_salidas_' . date("Ymd_His") . '.xlsx"');
    header('Cache-Control: max-age=0');

    // Limpiar el buffer de salida antes de generar el archivo
    ob_clean();
    flush();

    // Generar el archivo Excel y enviarlo al navegador
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    // Capturar y mostrar cualquier error
    echo 'Error al generar el archivo Excel: ',  $e->getMessage();
    exit;
}

?>
