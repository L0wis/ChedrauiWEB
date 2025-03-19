<?php
// Incluir los archivos necesarios y las clases requeridas
require '../../excel/vendor/autoload.php';
require '../config/database.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Crear una instancia de la clase Spreadsheet
$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("Luois Chavez")->setTitle("Reporte de Ventas");

// Obtener la hoja activa del documento
$hojaActiva = $spreadsheet->getActiveSheet();

// Establecer el ancho de las columnas
$hojaActiva->getColumnDimension('A')->setWidth(20);
$hojaActiva->getColumnDimension('B')->setWidth(20);
$hojaActiva->getColumnDimension('C')->setWidth(20);
$hojaActiva->getColumnDimension('D')->setWidth(20);
$hojaActiva->getColumnDimension('E')->setWidth(20);
$hojaActiva->getColumnDimension('F')->setWidth(20);
$hojaActiva->getColumnDimension('G')->setWidth(20);

// Combinar las celdas de la primera fila y establecer el título
$hojaActiva->setCellValue('A1', 'Reporte de Ventas');
$hojaActiva->mergeCells('A1:G1');
$hojaActiva->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Establecer los encabezados de las columnas
$hojaActiva->setCellValue('A2', 'ID Transacción');
$hojaActiva->setCellValue('B2', 'Fecha');
$hojaActiva->setCellValue('C2', 'Status');
$hojaActiva->setCellValue('D2', 'Email');
$hojaActiva->setCellValue('E2', 'ID Cliente');
$hojaActiva->setCellValue('F2', 'Total');
$hojaActiva->setCellValue('G2', 'Realizó');

// Obtener los datos de ventas desde la base de datos
$db = new Database();
$con = $db->conectar();

if (!$con) {
    die("Error en la conexión a la base de datos");
}

$sql = "SELECT id_transaccion, fecha, status, email, id_cliente, total, realizo FROM 
        (SELECT id_transaccion, fecha, status, email, id_cliente, total, 'cliente' as realizo FROM compra
        UNION ALL
        SELECT id_transaccion, fecha, status, email, id_cliente, total, 'personal' as realizo FROM compra_personal WHERE status IN ('COMPLETED', 'APPROVED', 'DISABLED')) AS ventas
        ORDER BY fecha DESC";

$resultado = $con->query($sql);
$ventas = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Llenar la hoja de cálculo con los datos de las ventas
$row = 3; // Comenzar en la fila 3 para dejar espacio para los encabezados
foreach ($ventas as $venta) {
    $hojaActiva->setCellValue('A' . $row, $venta['id_transaccion']);
    $hojaActiva->setCellValue('B' . $row, $venta['fecha']);
    $hojaActiva->setCellValue('C' . $row, $venta['status']);
    $hojaActiva->setCellValue('D' . $row, $venta['email']);
    $hojaActiva->setCellValue('E' . $row, $venta['id_cliente']);
    $hojaActiva->setCellValue('F' . $row, $venta['total']);
    $hojaActiva->setCellValue('G' . $row, $venta['realizo']);
    $row++;
}

// Establecer bordes en todas las celdas
$hojaActiva->getStyle('A2:G' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Aplicar formato de tabla
$styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
            'color' => ['argb' => 'FF000000'],
        ],
        'inside' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
    'font' => [
        'bold' => true,
        'color' => ['rgb' => '000000'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFFFF'],
    ],
];

$hojaActiva->getStyle('A2:G' . ($row - 1))->applyFromArray($styleArray);

// Redirigir la salida al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_de_ventas.xlsx"');
header('Cache-Control: max-age=0');

// Guardar el archivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
