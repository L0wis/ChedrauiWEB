<?php
ob_start(); // Inicia el buffer de salida

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

// Conexión a la base de datos
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



// Consulta SQL para obtener los 10 productos más vendidos
$sqlProductosVendidos = "SELECT cpp.id_producto, SUM(cpp.cantidad) as total_vendido, p.nombre
                         FROM compra_personal_productos cpp
                         INNER JOIN productos p ON cpp.id_producto = p.id
                         INNER JOIN compra_personal cp ON cpp.id_venta = cp.id
                         WHERE cp.status IN ('APPROVED', 'COMPLETED')
                         GROUP BY cpp.id_producto
                         ORDER BY total_vendido DESC
                         LIMIT 10";

$resultadoProductosVendidos = $con->query($sqlProductosVendidos);
$productosVendidos = $resultadoProductosVendidos->fetchAll(PDO::FETCH_ASSOC);

// Crear objeto FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Añadir título
$pdf->SetFont('Arial', 'B', 16); // Negrita
$pdf->Cell(0, 10, utf8_decode('Reporte de Productos más Vendidos'), 0, 1, 'C'); // Cambiado para ocupar toda la página

// Agregar información adicional
$pdf->SetFont('Arial', 'B', 12); // Negrita
$pdf->Cell(0, 10, utf8_decode('Fecha de creación: ') . date('Y-m-d H:i:s'), 0, 1, 'C'); // Corregido para establecer la codificación correcta
$pdf->Cell(0, 10, 'Realizado por: ' . $nombreUsuario, 0, 1, 'C'); // Agregando el nombre del usuario

$pdf->Ln(5);  // Añadir espacio entre la información y la tabla

// Verificar si hay datos antes de intentar mostrarlos
if ($productosVendidos) {
    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 10, 'Producto', 1, 0, 'C');
    $pdf->Cell(90, 10, 'Total Vendido', 1, 1, 'C');

    // Mostrar datos en forma de tabla
    $pdf->SetFont('Arial', '', 12);
    foreach ($productosVendidos as $producto) {
        $pdf->Cell(90, 10, utf8_decode($producto['nombre']), 1, 0, 'C');
        $pdf->Cell(90, 10, $producto['total_vendido'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, 'No hay datos disponibles', 0, 1, 'C');
}

ob_clean();

// Configurar el encabezado para la descarga del archivo PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="reporte_productos_vendidos.pdf"');

$pdf->Output();
exit;
?>
