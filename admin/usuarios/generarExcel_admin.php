<?php

require '../../excel/vendor/autoload.php';
require '../config/database.php'; // Asegúrate de ajustar la ruta según tu estructura de carpetas

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

$db = new Database();
$con = $db->conectar();

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("Tu nombre aquí")->setTitle("Reporte de Administradores");

$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);

$hojaActiva->getColumnDimension('A')->setWidth(30);
$hojaActiva->getColumnDimension('B')->setWidth(30);
$hojaActiva->getColumnDimension('C')->setWidth(30);
$hojaActiva->getColumnDimension('D')->setWidth(20);

$hojaActiva->setCellValue('A1', 'Lista de Administradores');
$hojaActiva->mergeCells('A1:D1');
$hojaActiva->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$hojaActiva->setCellValue('A2', 'Usuario');
$hojaActiva->setCellValue('B2', 'Nombre');
$hojaActiva->setCellValue('C2', 'Email');
$hojaActiva->setCellValue('D2', 'Fecha de Alta');

if (!$con) {
    die("Error en la conexión a la base de datos");
}

$sql = "SELECT usuario, nombre, email, fecha_alta
        FROM admin";
$resultado = $con->query($sql);
$administradores = $resultado->fetchAll(PDO::FETCH_ASSOC);

$row = 3;
foreach ($administradores as $admin) {
    $hojaActiva->setCellValue('A' . $row, $admin['usuario']);
    $hojaActiva->setCellValue('B' . $row, $admin['nombre']);
    $hojaActiva->setCellValue('C' . $row, $admin['email']);
    // No hay columna 'puesto' en la tabla 'admin'
    // $hojaActiva->setCellValue('D' . $row, $admin['puesto']);
    $hojaActiva->setCellValue('D' . $row, $admin['fecha_alta']);
    $row++;
}

$hojaActiva->getStyle('A1:D' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

$hojaActiva->getStyle('A1:D' . ($row - 1))->applyFromArray($styleArray);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_de_administradores.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
