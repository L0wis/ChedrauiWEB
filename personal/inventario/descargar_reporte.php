<?php
ob_start(); // Inicia el buffer de salida

require '../config/config.php';
require '../header.php';
require '../../fpdf186/fpdf.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.descuento, p.stock, p.id_categoria, c.nombre as nombre_categoria 
        FROM productos p 
        INNER JOIN categorias c ON p.id_categoria = c.id
        WHERE p.activo = 1";
$resultado = $con->query($sql);
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
// Cambiar el tamaño de la página a un tamaño personalizado
$pdf->AddPage('P', array(230, 355)); // Ancho: 230mm, Alto: 355mm (tamaño A4)

// Resto del código...


// Resto del código...

// Resto del código...

// Mover al centro de la página
//$pdf->SetY($pdf->GetPageHeight() / 2 - 10);
//$pdf->SetX(0); // Alinea a la izquierda

// Resto del código...

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Productos', 0, 1, 'C');  // Cambiado para ocupar toda la página

// Agregar información adicional
$pdf->SetFont('Arial', 'B', 12); // Negrita
$pdf->Cell(0, 10, 'Fecha de corte: ' . date('Y-m-d H:i:s'), 0, 1, 'C'); // Agregando la hora
$pdf->Cell(0, 10, 'Cantidad de Productos: ' . count($productos), 0, 1, 'C');
$pdf->Cell(0, 10, 'Realizado por: Chedraui-Personal', 0, 1, 'C');


$pdf->Ln(2);  // Añadir espacio entre la información y la tabla

/// Resto del código...

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);

// Anchos de las columnas
$nombreWidth = 90;
$categoriaWidth = 45;
$precioWidth = 35;
$stockWidth = 20;

// Calcular el ancho total de la tabla
$tableWidth = $nombreWidth + $categoriaWidth + $precioWidth + $stockWidth;

// Calcular la posición para centrar la tabla
$pageWidth = $pdf->GetPageWidth();
$tableX = ($pageWidth - $tableWidth) / 3;

// Añadir espacio antes de la tabla
$pdf->Cell($tableX);

// Crear las celdas de encabezado centradas
$pdf->Cell($nombreWidth, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell($categoriaWidth, 10, utf8_decode('Categoría'), 1, 0, 'C'); // Modificado aquí
$pdf->Cell($precioWidth, 10, 'Precio', 1, 0, 'C');
$pdf->Cell($stockWidth, 10, 'Stock', 1, 1, 'C'); // Cambiado para indicar un salto de línea


foreach ($productos as $producto) {
    // Añadir espacio antes de cada fila de la tabla
    $pdf->Cell($tableX);

    // Crear las celdas de datos centradas
    $pdf->Cell($nombreWidth, 10, utf8_decode($producto['nombre']), 1, 0, 'C');
    $pdf->Cell($categoriaWidth, 10, $producto['nombre_categoria'], 1, 0, 'C');
    $pdf->Cell($precioWidth, 10, $producto['precio'], 1, 0, 'C');
    $pdf->Cell($stockWidth, 10, $producto['stock'], 1, 1, 'C'); // Cambiado para indicar un salto de línea
}

ob_clean();

$pdf->Output();
exit;
?>