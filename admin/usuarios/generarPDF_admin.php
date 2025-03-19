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

$sql = "SELECT id, usuario, nombre, email, fecha_alta
        FROM admin
        WHERE activo = 1";

$resultado = $con->query($sql);
$administradores = $resultado->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Administradores', 0, 1, 'C');

// Agregar información adicional
$pdf->SetFont('Arial', 'B', 12); 
$pdf->Cell(0, 10, utf8_decode('Fecha de Generación: ') . date('Y-m-d H:i:s'), 0, 1, 'C');
$pdf->Cell(0, 10, 'Cantidad de Administradores: ' . count($administradores), 0, 1, 'C');
$pdf->Cell(0, 10, 'Realizado por: Chedraui-Admin', 0, 1, 'C');

$pdf->Ln(6); 

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 11);

$pdf->Cell(10, 10, 'ID', 1, 0, 'C'); 
$pdf->Cell(30, 10, 'Usuario', 1, 0, 'C'); 
$pdf->Cell(40, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(70, 10, 'Email', 1, 0, 'C');
$pdf->Cell(45, 10, 'Fecha de Alta', 1, 1, 'C'); 

foreach ($administradores as $admin) {
    $pdf->Cell(10, 10, $admin['id'], 1, 0, 'C');
    $pdf->Cell(30, 10, $admin['usuario'], 1, 0, 'C');
    $pdf->Cell(40, 10, $admin['nombre'], 1, 0, 'C');
    $pdf->Cell(70, 10, $admin['email'], 1, 0, 'C');
    $pdf->Cell(45, 10, $admin['fecha_alta'], 1, 1, 'C');
}

ob_clean();

$pdf->Output();
exit;
?>
