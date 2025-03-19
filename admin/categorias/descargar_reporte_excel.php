<?php

require '../../excel/vendor/autoload.php';
require '../config/database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("Luois Chavez")->setTitle("Reporte de productos");

$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
$spreadsheet->getDefaultStyle()->getFont()->setSize(15);

$hojaActiva->getColumnDimension('A')->setWidth(40);
$hojaActiva->getColumnDimension('B')->setWidth(20);
$hojaActiva->getColumnDimension('C')->setWidth(20);

$hojaActiva->setCellValue('A2', 'Nombre del producto');
$hojaActiva->setCellValue('B2', 'Precio');
$hojaActiva->setCellValue('C2', 'Stock');

// Obtén los datos de la base de datos
$db = new Database();
$con = $db->conectar();

if (!$con) {
    die("Error en la conexión a la base de datos");
}

// Definir $nombre_categoria fuera del bloque condicional
$nombre_categoria = '';

// Verificar si se proporciona el parámetro 'categoria_id' en la URL
if (isset($_GET['categoria_id'])) {
    $idCategoria = $_GET['categoria_id'];

    // Realiza la consulta para obtener la información de la categoría
    $sqlCategoria = "SELECT id, nombre FROM categorias WHERE id = :id";
    $stmtCategoria = $con->prepare($sqlCategoria);
    $stmtCategoria->bindParam(':id', $idCategoria, PDO::PARAM_INT);
    $stmtCategoria->execute();

    $categoria = $stmtCategoria->fetch(PDO::FETCH_ASSOC);

    if ($categoria) {
        $nombre_categoria = $categoria['nombre']; // Asignar valor a $nombre_categoria

        // Combina las celdas de la primera columna y agrega un texto
        $hojaActiva->setCellValue('A1', 'Lista de productos en inventario - Categoría: ' . $nombre_categoria);
        $hojaActiva->mergeCells('A1:C1'); // Combina las celdas A1, B1 y C1
        $hojaActiva->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Centra el texto

        // Consulta para obtener los productos de la categoría seleccionada
        $sqlProductosCategoria = "SELECT nombre, precio, stock FROM productos WHERE id_categoria = :idCategoria AND activo = 1";
        $stmtProductosCategoria = $con->prepare($sqlProductosCategoria);
        $stmtProductosCategoria->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
        $stmtProductosCategoria->execute();
        $productos = $stmtProductosCategoria->fetchAll(PDO::FETCH_ASSOC);

        // Llenar la hoja de cálculo con datos de la base de datos
        $row = 3; // Comienza en la fila 3 para dejar espacio para la fila de encabezados
        foreach ($productos as $producto) {
            $hojaActiva->setCellValue('A' . $row, $producto['nombre']);
            $hojaActiva->setCellValue('B' . $row, $producto['precio']);
            $hojaActiva->setCellValue('C' . $row, $producto['stock']);
            $row++;
        }

        // Establecer bordes en todas las celdas
        $hojaActiva->getStyle('A1:C' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

        $hojaActiva->getStyle('A1:C' . ($row - 1))->applyFromArray($styleArray);

        // Redirigir la salida al navegador
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_de_productos.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    } else {
        echo "No se encontró la categoría con el ID proporcionado.";
        // Puedes realizar alguna otra acción aquí en lugar de redirigir al índice.
        // Por ejemplo, mostrar un mensaje de error o realizar alguna otra acción específica.
        // header('Location: ../index.php');
        // exit;
    }
} else {
    echo "No se proporcionó un ID de categoría en la URL.";
    // Puedes realizar alguna otra acción aquí en lugar de redirigir al índice.
    // Por ejemplo, mostrar un mensaje de error o realizar alguna otra acción específica.
    // header('Location: ../index.php');
    // exit;
}
?>
