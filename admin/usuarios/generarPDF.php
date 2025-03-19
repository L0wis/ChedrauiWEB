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

$sql = "SELECT id, usuario, nombre, email, puesto, fecha_alta
        FROM personal 
        WHERE activo = 1";
$resultado = $con->query($sql);
$personal = $resultado->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
// Cambiar el tamaño de la página a un tamaño personalizado
$pdf->AddPage('P', array(240, 355)); // Ancho: 230mm, Alto: 355mm (tamaño A4)

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Personal', 0, 1, 'C');  // Cambiado para ocupar toda la página

// Agregar información adicional
$pdf->SetFont('Arial', 'B', 12); // Negrita
$pdf->Cell(0, 10, 'Fecha de corte: ' . date('Y-m-d H:i:s'), 0, 1, 'C'); // Agregando la hora
$pdf->Cell(0, 10, 'Cantidad de Personal: ' . count($personal), 0, 1, 'C');
$pdf->Cell(0, 10, 'Realizado por: Chedraui-Admin', 0, 1, 'C');


$pdf->Ln(6);  // Añadir espacio entre la información y la tabla

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 11);

// Anchos de las columnas
$usuarioWidth = 30;
$nombreWidth = 40;
$emailWidth = 60;
$puestoWidth = 50;
$fechaAltaWidth = 40;

// Calcular el ancho total de la tabla
$tableWidth = $usuarioWidth + $nombreWidth + $emailWidth + $puestoWidth + $fechaAltaWidth;

// Calcular la posición para centrar la tabla
$pageWidth = $pdf->GetPageWidth();
$tableX = ($pageWidth - $tableWidth) / 2; // Ahora se centra correctamente

// Crear las celdas de encabezado centradas
$pdf->Cell($usuarioWidth, 10, utf8_decode('Usuario'), 1, 0, 'C'); // Modificado
$pdf->Cell($nombreWidth, 10, utf8_decode('Nombre'), 1, 0, 'C'); // Modificado
$pdf->Cell($emailWidth, 10, utf8_decode('Email'), 1, 0, 'C');
$pdf->Cell($puestoWidth, 10, utf8_decode('Puesto'), 1, 0, 'C');
$pdf->Cell($fechaAltaWidth, 10, utf8_decode('Fecha de Alta'), 1, 1, 'C'); // Modificado para indicar un salto de línea

foreach ($personal as $persona) {
    $pdf->Cell($usuarioWidth, 10, utf8_decode($persona['usuario']), 1, 0, 'C');
    $pdf->Cell($nombreWidth, 10, utf8_decode($persona['nombre']), 1, 0, 'C');
    $pdf->Cell($emailWidth, 10, utf8_decode($persona['email']), 1, 0, 'C');
    $pdf->Cell($puestoWidth, 10, utf8_decode($persona['puesto']), 1, 0, 'C');
    $pdf->Cell($fechaAltaWidth, 10, utf8_decode($persona['fecha_alta']), 1, 1, 'C'); // Modificado para indicar un salto de línea
}

ob_clean();

$pdf->Output();
exit;
?>
