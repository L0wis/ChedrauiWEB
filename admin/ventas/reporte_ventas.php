<?php
// Iniciar el buffer de salida
ob_start();

// Incluir archivos necesarios
require '../config/database.php';
require '../config/config.php';
require '../header.php';
require '../../fpdf186/fpdf.php';

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirigir si no es un administrador
    header('Location: ../../index.php');
    exit;
}

// Crear una instancia de la clase Database
$db = new Database();

// Obtener la conexión a la base de datos
$con = $db->conectar();

// Verificar si la conexión se estableció correctamente
if (!$con) {
    // Manejar el error de conexión
    echo "Error de conexión a la base de datos.";
    exit;
}

try {
    // Consulta SQL para obtener todas las ventas realizadas
    $sql = "SELECT id_transaccion, fecha, status, email, id_cliente, total, 'cliente' as realizo FROM compra 
            UNION 
            SELECT id_transaccion, fecha, status, email, id_cliente, total, 'personal' as realizo FROM compra_personal 
            WHERE status IN ('COMPLETED', 'APPROVED', 'DISABLED') ORDER BY fecha DESC";

    // Preparar la consulta SQL
    $stmt = $con->prepare($sql);

    // Ejecutar la consulta SQL
    $stmt->execute();

    // Crear el objeto PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Detalles de Todas las Ventas', 0, 1, 'C');
    $pdf->Ln(10);

    // Cabecera de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 10, utf8_decode('ID Transacción'), 1, 0, 'C');
    $pdf->Cell(40, 10, 'Fecha', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Status', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Email', 1, 0, 'C');
    $pdf->Cell(20, 10, 'ID Cliente', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Total', 1, 0, 'C');
    $pdf->Cell(20, 10, utf8_decode('Realizó'), 1, 1, 'C');

    // Detalles de las ventas
    $pdf->SetFont('Arial', '', 8);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(30, 10, $row['id_transaccion'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['fecha'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['status'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['email'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['id_cliente'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['total'], 1, 0, 'C');
        $pdf->Cell(20, 10, $row['realizo'], 1, 1, 'C');
    }

    // Nombre del archivo PDF
    $filename = 'reporte_ventas.pdf';

    // Salida del PDF para abrirlo en el navegador
    ob_clean();
    $pdf->Output('I', $filename);
    exit;
} catch (PDOException $e) {
    // Manejar el error de la consulta SQL
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
    exit;
}
?>
