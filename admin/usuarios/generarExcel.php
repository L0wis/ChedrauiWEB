<?php

require '../../excel/vendor/autoload.php';
require '../config/database.php'; // Asegúrate de ajustar la ruta según tu estructura de carpetas

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("Tu nombre aquí")->setTitle("Reporte de Personal");

$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);

$hojaActiva->getColumnDimension('A')->setWidth(30);
$hojaActiva->getColumnDimension('B')->setWidth(30); // Ajuste de ancho para la columna de nombre
$hojaActiva->getColumnDimension('C')->setWidth(30);
$hojaActiva->getColumnDimension('D')->setWidth(30); // Columna para el puesto
$hojaActiva->getColumnDimension('E')->setWidth(20); // Columna para la fecha de alta

// Combina las celdas de la primera fila y agrega un texto
$hojaActiva->setCellValue('A1', 'Lista de Personal');
$hojaActiva->mergeCells('A1:E1'); // Combina las celdas A1, B1, C1, D1, E1
$hojaActiva->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Centra el texto

$hojaActiva->setCellValue('A2', 'Usuario');
$hojaActiva->setCellValue('B2', 'Nombre');
$hojaActiva->setCellValue('C2', 'Email');
$hojaActiva->setCellValue('D2', 'Puesto');
$hojaActiva->setCellValue('E2', 'Fecha de Alta');

// Obtén los datos de la base de datos
$db = new Database();
$con = $db->conectar();

if (!$con) {
    die("Error en la conexión a la base de datos");
}

$sql = "SELECT usuario, nombre, email, puesto, fecha_alta
        FROM personal 
        WHERE activo = 1";
$resultado = $con->query($sql);
$personal = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Llenar la hoja de cálculo con datos de la base de datos
$row = 3; // Comienza en la fila 3 para dejar espacio para la fila de encabezados
foreach ($personal as $persona) {
    $hojaActiva->setCellValue('A' . $row, $persona['usuario']);
    $hojaActiva->setCellValue('B' . $row, $persona['nombre']);
    $hojaActiva->setCellValue('C' . $row, $persona['email']);
    $hojaActiva->setCellValue('D' . $row, $persona['puesto']);
    $hojaActiva->setCellValue('E' . $row, $persona['fecha_alta']);
    $row++;
}

// Establecer bordes en todas las celdas
$hojaActiva->getStyle('A1:E' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

$hojaActiva->getStyle('A1:E' . ($row - 1))->applyFromArray($styleArray);

// Redirigir la salida al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_de_personal.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
