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

$sql = "SELECT id, nombres, apellidos, email, dni, telefono, fecha_alta, 
               CASE estatus WHEN 1 THEN 'ACTIVO' ELSE 'DESACTIVADO' END as estatus
        FROM clientes 
        WHERE estatus = 1"; // Solo clientes activos
$resultado = $con->query($sql);
$clientes = $resultado->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
// Cambiar el tamaño de la página a un tamaño personalizado
$pdf->AddPage('P', array(240, 355)); // Ancho: 230mm, Alto: 355mm (tamaño A4)

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Clientes', 0, 1, 'C');  // Cambiado para ocupar toda la página

// Agregar información adicional
$pdf->SetFont('Arial', 'B', 12); // Negrita
$pdf->Cell(0, 10, utf8_decode('Fecha de Generación: ') . date('Y-m-d H:i:s'), 0, 1, 'C'); // Agregando la hora
$pdf->Cell(0, 10, 'Cantidad de Clientes: ' . count($clientes), 0, 1, 'C');
$pdf->Cell(0, 10, 'Generado por: Chedraui-Admin', 0, 1, 'C');


$pdf->Ln(6);  // Añadir espacio entre la información y la tabla

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 11);

// Anchos de las columnas
$idWidth = 20;
$nombresWidth = 40;
$apellidosWidth = 40;
$emailWidth = 60;
$dniWidth = 25;
$telefonoWidth = 30;

// Calcular el ancho total de la tabla
$tableWidth = $idWidth + $nombresWidth + $apellidosWidth + $emailWidth + $dniWidth + $telefonoWidth + $fechaAltaWidth + $estatusWidth;

// Calcular la posición para centrar la tabla
$pageWidth = $pdf->GetPageWidth();
$tableX = ($pageWidth - $tableWidth) / 2; // Ahora se centra correctamente

// Crear las celdas de encabezado centradas
$pdf->Cell($idWidth, 10, utf8_decode('ID'), 1, 0, 'C');
$pdf->Cell($nombresWidth, 10, utf8_decode('Nombres'), 1, 0, 'C');
$pdf->Cell($apellidosWidth, 10, utf8_decode('Apellidos'), 1, 0, 'C');
$pdf->Cell($emailWidth, 10, utf8_decode('Email'), 1, 0, 'C');
$pdf->Cell($dniWidth, 10, utf8_decode('DNI'), 1, 0, 'C');
$pdf->Cell($telefonoWidth, 10, utf8_decode('Teléfono'), 1, 1, 'C');

foreach ($clientes as $cliente) {
    $pdf->Cell($idWidth, 10, utf8_decode($cliente['id']), 1, 0, 'C');
    $pdf->Cell($nombresWidth, 10, utf8_decode($cliente['nombres']), 1, 0, 'C');
    $pdf->Cell($apellidosWidth, 10, utf8_decode($cliente['apellidos']), 1, 0, 'C');
    $pdf->Cell($emailWidth, 10, utf8_decode($cliente['email']), 1, 0, 'C');
    $pdf->Cell($dniWidth, 10, utf8_decode($cliente['dni']), 1, 0, 'C');
    $pdf->Cell($telefonoWidth, 10, utf8_decode($cliente['telefono']), 1, 1, 'C');
}

ob_clean();

$pdf->Output();
exit;
?>
