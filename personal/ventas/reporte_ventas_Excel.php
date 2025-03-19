<?php
require_once '../config/config.php';
require_once '../config/database.php'; // Ajusta la ruta según tu estructura de carpetas
require '../../excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Obtener el ID del personal que ha iniciado sesión
$personal_id = $_SESSION['user_id'];

// Creamos una instancia de la clase Spreadsheet
$spreadsheet = new Spreadsheet();

// Configuramos las propiedades del documento
$spreadsheet->getProperties()->setCreator("Luois Chavez")->setTitle("Reporte de ventas");

// Seleccionamos la primera hoja del documento
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

// Configuramos el estilo de la fuente por defecto
$spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);

// Agregamos encabezados a la hoja de cálculo
$hojaActiva->setCellValue('A1', 'Lista de Ventas Recientes');
$hojaActiva->mergeCells('A1:H1'); // Combina las celdas A1 a H1
$hojaActiva->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Centra el texto

$hojaActiva->setCellValue('A2', 'ID Personal');
$hojaActiva->setCellValue('B2', 'ID Venta');
$hojaActiva->setCellValue('C2', 'ID Transacción');
$hojaActiva->setCellValue('D2', 'Status');
$hojaActiva->setCellValue('E2', 'Email');
$hojaActiva->setCellValue('F2', 'ID Cliente');
$hojaActiva->setCellValue('G2', 'Fecha');
$hojaActiva->setCellValue('H2', 'Total');

// Obtén los datos de la base de datos
$db = new Database();
$con = $db->conectar();

if (!$con) {
    die("Error en la conexión a la base de datos");
}

$sql = "SELECT cp.id_personal, cp.id, cp.id_transaccion, cp.status, cp.email, cp.id_cliente, cp.fecha, cp.total 
        FROM compra_personal cp
        WHERE cp.id_personal = :personal_id
        ORDER BY cp.fecha DESC
        LIMIT 20";
$stmt = $con->prepare($sql);
$stmt->bindParam(':personal_id', $personal_id, PDO::PARAM_INT);
$stmt->execute();
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Llenar la hoja de cálculo con datos de la base de datos
$row = 3; // Comienza en la fila 3 para dejar espacio para la fila de encabezados
foreach ($ventas as $venta) {
    $hojaActiva->setCellValue('A' . $row, $venta['id_personal']);
    $hojaActiva->setCellValue('B' . $row, $venta['id']);
    $hojaActiva->setCellValue('C' . $row, $venta['id_transaccion']);
    $hojaActiva->setCellValue('D' . $row, $venta['status']);
    $hojaActiva->setCellValue('E' . $row, $venta['email']);
    $hojaActiva->setCellValue('F' . $row, $venta['id_cliente']);
    $hojaActiva->setCellValue('G' . $row, $venta['fecha']);
    $hojaActiva->setCellValue('H' . $row, $venta['total']);
    $row++;
}

// Cerramos la conexión a la base de datos
$con = null;

// Establecemos bordes en todas las celdas
$hojaActiva->getStyle('A1:H' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

$hojaActiva->getStyle('A1:H' . ($row - 1))->applyFromArray($styleArray);

// Redirigir la salida al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_ventas.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
