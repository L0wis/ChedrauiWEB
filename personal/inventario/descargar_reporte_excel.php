<?php

require '../../excel/vendor/autoload.php';
require '../config/database.php'; // Asegúrate de ajustar la ruta según tu estructura de carpetas

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("Luois Chavez")->setTitle("Reporte de productos");

$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
$spreadsheet->getDefaultStyle()->getFont()->setSize(15);

$hojaActiva->getColumnDimension('A')->setWidth(40);
$hojaActiva->getColumnDimension('B')->setWidth(20);
$hojaActiva->getColumnDimension('C')->setWidth(20);
$hojaActiva->getColumnDimension('D')->setWidth(30); // Nueva columna para la categoría

// Combina las celdas de la primera columna y agrega un texto
$hojaActiva->setCellValue('A1', 'Lista de productos en inventario');
$hojaActiva->mergeCells('A1:D1'); // Combina las celdas A1, B1, C1 y D1
$hojaActiva->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Centra el texto

$hojaActiva->setCellValue('A2', 'Nombre del producto');
$hojaActiva->setCellValue('B2', 'Precio');
$hojaActiva->setCellValue('C2', 'Stock');
$hojaActiva->setCellValue('D2', 'Categoría'); // Nueva columna para la categoría

// ...

// Obtén los datos de la base de datos
$db = new Database();
$con = $db->conectar();

if (!$con) {
    die("Error en la conexión a la base de datos");
}

$sql = "SELECT p.nombre, p.precio, p.stock, c.nombre as nombre_categoria 
        FROM productos p
        INNER JOIN categorias c ON p.id_categoria = c.id
        WHERE p.activo = 1";
$resultado = $con->query($sql);
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Llenar la hoja de cálculo con datos de la base de datos
$row = 3; // Comienza en la fila 3 para dejar espacio para la fila de encabezados
foreach ($productos as $producto) {
    $hojaActiva->setCellValue('A' . $row, $producto['nombre']);
    $hojaActiva->setCellValue('B' . $row, $producto['precio']);
    $hojaActiva->setCellValue('C' . $row, $producto['stock']);
    $hojaActiva->setCellValue('D' . $row, $producto['nombre_categoria']); // Nueva columna para la categoría
    $row++;
}

// ...


// Establecer bordes en todas las celdas
$hojaActiva->getStyle('A1:D' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

$hojaActiva->getStyle('A1:D' . ($row - 1))->applyFromArray($styleArray);

// Redirigir la salida al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_de_productos.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
