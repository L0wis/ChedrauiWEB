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

// Obtener el ID del usuario actual
$user_id = $_SESSION['user_id'];

// Consulta SQL para obtener el nombre del usuario actual
$sqlNombreUsuario = "SELECT nombre FROM personal WHERE id = :user_id";
$stmtNombreUsuario = $con->prepare($sqlNombreUsuario);
$stmtNombreUsuario->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtNombreUsuario->execute();
$nombreUsuario = $stmtNombreUsuario->fetchColumn();

// Consulta SQL para obtener los datos de las ventas
$sqlVentas = "SELECT * FROM compra_personal WHERE id_personal = :user_id";
$stmtVentas = $con->prepare($sqlVentas);
$stmtVentas->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtVentas->execute();
$ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);

// Iniciar la generación del PDF
$pdf = new FPDF();
$pdf->AddPage();

// Añadir título
$pdf->SetFont('Arial', 'B', 16); // Negrita
$pdf->Cell(0, 10, 'Reporte de Ventas', 0, 1, 'C'); // Cambiado para ocupar toda la página

// Agregar información adicional
$pdf->SetFont('Arial', 'B', 12); // Negrita
$pdf->Cell(0, 10, utf8_decode('Fecha de Generación: ') . date('Y-m-d H:i:s'), 0, 1, 'C'); // Agregando la fecha
$pdf->Cell(0, 10, 'Generado por: ' . $nombreUsuario, 0, 1, 'C'); // Agregando el nombre del usuario

$pdf->Ln(5);  // Añadir espacio entre la información y la tabla

// Verificar si hay ventas antes de intentar mostrarlas
if ($ventas) {
    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Fecha', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Status', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Total', 1, 1, 'C');

    // Mostrar datos en forma de tabla
    $pdf->SetFont('Arial', '', 12);
    foreach ($ventas as $venta) {
        $pdf->Cell(30, 10, $venta['id'], 1, 0, 'C');
        $pdf->Cell(50, 10, $venta['fecha'], 1, 0, 'C');
        $pdf->Cell(40, 10, $venta['status'], 1, 0, 'C');
        $pdf->Cell(40, 10, $venta['total'], 1, 1, 'C');
    }

    // Calcular el total de ventas
    $totalVentas = array_sum(array_column($ventas, 'total'));

    // Mostrar fila de total
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(120, 10, 'Total', 1, 0, 'C');
    $pdf->Cell(40, 10, $totalVentas, 1, 1, 'C');
} else {
    $pdf->Cell(0, 10, 'No hay ventas disponibles', 0, 1, 'C');
}

ob_clean();

$pdf->Output();
exit;
?>
