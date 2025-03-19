<?php

require '../config/database.php';
require '../../excel/vendor/autoload.php';
require '../config/config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Crear una instancia de Spreadsheet
$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("Tu Nombre")->setTitle("Reporte de Ventas");

// Seleccionar la primera hoja y configurar estilos
$hojaActiva = $spreadsheet->getActiveSheet();
$hojaActiva->getDefaultColumnDimension()->setWidth(20); // Establecer ancho predeterminado de columna
$hojaActiva->getDefaultRowDimension()->setRowHeight(20); // Establecer altura predeterminada de fila

// Definir los encabezados de las columnas
$hojaActiva->setCellValue('A1', 'ID');
$hojaActiva->setCellValue('B1', 'Fecha');
$hojaActiva->setCellValue('C1', 'Status');
$hojaActiva->setCellValue('D1', 'Total');
$hojaActiva->setCellValue('E1', 'Tipo de Pago');

// Obtener los datos de las ventas desde la base de datos
try {
    // Crear una instancia de Database
    $db = new Database();

    // Obtener la conexión a la base de datos
    $con = $db->conectar();

    // Preparar la consulta SQL
    $sql = "SELECT * FROM compra_personal WHERE id_personal = :user_id";

    // Preparar la consulta
    $stmt = $con->prepare($sql);

    // Ejecutar la consulta
    $stmt->execute(['user_id' => $_SESSION['user_id']]);

    // Obtener los resultados de la consulta
    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cerrar la conexión
    $con = null;
} catch (PDOException $e) {
    // Manejar errores de base de datos
    echo "Error al obtener las ventas: " . $e->getMessage();
    exit;
}

// Llenar la hoja de cálculo con los datos de las ventas
$row = 2; // Comienza en la fila 2 para dejar espacio para los encabezados
foreach ($ventas as $venta) {
    $hojaActiva->setCellValue('A' . $row, $venta['id']);
    $hojaActiva->setCellValue('B' . $row, $venta['fecha']);
    $hojaActiva->setCellValue('C' . $row, $venta['status']);
    $hojaActiva->setCellValue('D' . $row, $venta['total']);
    $hojaActiva->setCellValue('E' . $row, $venta['tipo_pago']);
    $row++;
}

// Configurar bordes y estilos
$hojaActiva->getStyle('A1:E1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
]);
$hojaActiva->getStyle('A2:E' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Redirigir la salida al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_Ventas_Totales.xlsx"');
header('Cache-Control: max-age=0');

// Guardar el archivo Excel en la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
