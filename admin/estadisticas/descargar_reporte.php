<?php
ob_start(); // Inicia el buffer de salida

require '../config/database.php';
require '../config/config.php';
require '../header.php';
require '../../fpdf186/fpdf.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Establecer la zona horaria a México
date_default_timezone_set('America/Mexico_City');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
  header('Location: ../index.php');
  exit;
}

$db = new Database();
$con = $db->conectar();

// ID del usuario actual obtenido de la sesión
$user_id = $_SESSION['user_id'];

// Consulta SQL para obtener el nombre del usuario actual
$sqlNombreUsuario = "SELECT nombre FROM personal WHERE id = :user_id";
$stmtNombreUsuario = $con->prepare($sqlNombreUsuario);
$stmtNombreUsuario->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtNombreUsuario->execute();
$nombreUsuario = $stmtNombreUsuario->fetchColumn();



$sql = "SELECT c.nombre as categoria, COUNT(p.id) as cantidad_productos
        FROM categorias c
        LEFT JOIN productos p ON c.id = p.id_categoria
        WHERE c.activo = 1
        GROUP BY c.id";

$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
$pdf->AddPage();

// Añadir título
$pdf->SetFont('Arial', 'B', 16); // Negrita
$pdf->Cell(0, 10, 'Reporte de Categorias', 0, 1, 'C'); // Cambiado para ocupar toda la página

// Agregar información adicional
$pdf->SetFont('Arial', 'B', 12); // Negrita
$pdf->Cell(0, 10, 'Fecha de corte: ' . date('Y-m-d H:i:s'), 0, 1, 'C'); // Agregando la hora
$pdf->Cell(0, 10, 'Realizado por: ' . $nombreUsuario, 0, 1, 'C'); // Agregando el nombre del usuario

$pdf->Ln(5);  // Añadir espacio entre la información y la tabla

// Calcular el ancho total de la tabla
$tableWidth = 100; // Suma de los anchos de las celdas

// Calcular la posición para centrar la tabla
$pageWidth = $pdf->GetPageWidth();
$tableX = ($pageWidth - $tableWidth) / 2.9;

// Verificar si hay categorías antes de intentar mostrarlas
if ($categorias) {
    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($tableX); // Añadir espacio antes de la tabla
    $pdf->Cell(50, 10, 'Categorias', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Cantidad de Productos', 1, 1, 'C');

    // Mostrar datos en forma de tabla
    $pdf->SetFont('Arial', '', 12);
    foreach ($categorias as $categoria) {
        $pdf->Cell($tableX); // Añadir espacio antes de cada fila de la tabla
        $pdf->Cell(50, 10, $categoria['categoria'], 1, 0, 'C');
        $pdf->Cell(50, 10, $categoria['cantidad_productos'], 1, 1, 'C');
    }
  // Calcular el total de productos
  $totalProductos = array_sum(array_column($categorias, 'cantidad_productos'));

  // Mostrar fila de total
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell($tableX); // Añadir espacio antes de la fila de total
  $pdf->Cell(50, 10, 'Total', 1, 0, 'C');
  $pdf->Cell(50, 10, $totalProductos, 1, 1, 'C');
} else {
  $pdf->Cell(0, 10, 'No hay categorías disponibles', 0, 1, 'C');
}

ob_clean();

$pdf->Output();
exit;
?>
