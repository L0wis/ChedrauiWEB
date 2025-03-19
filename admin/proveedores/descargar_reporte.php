<?php
ob_start(); // Inicia el buffer de salida

require '../config/database.php';
require '../config/config.php';
require '../header.php';
require '../../fpdf186/fpdf.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = "SELECT id, nombre, direccion, ciudad, telefono 
        FROM proveedores 
        WHERE activo = 1";
$resultado = $con->query($sql);
$proveedores = $resultado->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
// Cambiar el tamaño de la página a un tamaño personalizado
$pdf->AddPage('P', array(230, 355)); // Ancho: 230mm, Alto: 355mm (tamaño A4)

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Proveedores', 0, 1, 'C');  // Cambiado para ocupar toda la página

// Agregar información adicional
$pdf->SetFont('Arial', 'B', 12); // Negrita
$pdf->Cell(0, 10, 'Fecha de corte: ' . date('Y-m-d H:i:s'), 0, 1, 'C'); // Agregando la hora
$pdf->Cell(0, 10, 'Cantidad de Proveedores: ' . count($proveedores), 0, 1, 'C');
$pdf->Cell(0, 10, 'Realizado por: Chedraui-Admin', 0, 1, 'C');


$pdf->Ln(6);  // Añadir espacio entre la información y la tabla

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);

// Anchos de las columnas
$nombreWidth = 50;
$direccionWidth = 80;
$ciudadWidth = 40;
$telefonoWidth = 40;

// Calcular el ancho total de la tabla
$tableWidth = $nombreWidth + $direccionWidth + $ciudadWidth + $telefonoWidth;

// Calcular la posición para centrar la tabla
$pageWidth = $pdf->GetPageWidth();
$tableX = ($pageWidth - $tableWidth) / 3;

// Añadir espacio antes de la tabla
$pdf->Cell($tableX);

// Crear las celdas de encabezado centradas
$pdf->Cell($nombreWidth, 10, utf8_decode('Nombre'), 1, 0, 'C'); // Modificado
$pdf->Cell($direccionWidth, 10, utf8_decode('Dirección'), 1, 0, 'C'); // Modificado
$pdf->Cell($ciudadWidth, 10, utf8_decode('Ciudad'), 1, 0, 'C');
$pdf->Cell($telefonoWidth, 10, utf8_decode('Teléfono'), 1, 1, 'C'); // Modificado para indicar un salto de línea

foreach ($proveedores as $proveedor) {
    // Calcula la altura necesaria para el texto del nombre
    $nombreHeight = 10; // Establece una altura inicial

    // Divide la dirección en palabras
    $direccionWords = explode(' ', $proveedor['direccion']);

    // Toma solo las primeras tres palabras y únelas
    $primerasTresPalabras = implode(' ', array_slice($direccionWords, 0, 4));

    // Determina la altura máxima entre las alturas de todas las celdas
    $cellHeight = max($nombreHeight, $ciudadLength, $telefonoLength);

    // Establece la altura de la celda para todas las columnas
    $pdf->Cell($tableX);
    $pdf->Cell($nombreWidth, $cellHeight, utf8_decode($proveedor['nombre']), 1, 0, 'C');
    $pdf->Cell($direccionWidth, 10, utf8_decode($primerasTresPalabras), 1, 0, 'C');
    $pdf->Cell($ciudadWidth, $cellHeight, utf8_decode($proveedor['ciudad']), 1, 0, 'C');
    $pdf->Cell($telefonoWidth, $cellHeight, utf8_decode($proveedor['telefono']), 1, 1, 'C'); // Modificado para indicar un salto de línea
}

ob_clean();

$pdf->Output();
exit;
?>
