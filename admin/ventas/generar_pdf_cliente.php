<?php
ob_start(); // Inicia el buffer de salida

require '../config/database.php';
require '../config/config.php';
require '../header.php';
require '../../fpdf186/fpdf.php';

// Verificar si el ID de la transacción está presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirigir si no se proporciona un ID válido
    header('Location: index.php');
    exit;
}

// Obtener el ID de la transacción de la URL
$id_transaccion = $_GET['id'];

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
    // Consulta SQL para obtener los detalles de la venta
    $sql = "SELECT * FROM compra WHERE id_transaccion = :id_transaccion";

    // Preparar la consulta SQL
    $stmt = $con->prepare($sql);

    // Vincular el parámetro ID de transacción
    $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_STR);

    // Ejecutar la consulta SQL
    $stmt->execute();

    // Obtener los detalles de la venta
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró la venta
    if ($venta) {
        // Crear el objeto PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Título
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Detalles de la Venta', 0, 1, 'C');
        $pdf->Ln(10);

        // Detalles de la venta
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('ID Transacción:'), 0, 0);
        $pdf->Cell(0, 10, $venta['id_transaccion'], 0, 1);
        $pdf->Cell(40, 10, 'Fecha:', 0, 0);
        $pdf->Cell(0, 10, $venta['fecha'], 0, 1);
        $pdf->Cell(40, 10, 'Status:', 0, 0);
        $pdf->Cell(0, 10, $venta['status'], 0, 1);
        $pdf->Cell(40, 10, 'Email:', 0, 0);
        $pdf->Cell(0, 10, $venta['email'], 0, 1);
        $pdf->Cell(40, 10, 'ID Cliente:', 0, 0);
        $pdf->Cell(0, 10, $venta['id_cliente'], 0, 1);

        // Obtener nombre del cliente
        $sql_cliente = "SELECT nombres, apellidos FROM clientes WHERE id = :id_cliente";
        $stmt_cliente = $con->prepare($sql_cliente);
        $stmt_cliente->bindParam(':id_cliente', $venta['id_cliente'], PDO::PARAM_INT);
        $stmt_cliente->execute();
        $cliente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);
        if ($cliente) {
            $pdf->Cell(40, 10, 'Nombre Cliente:', 0, 0);
            $pdf->Cell(0, 10, $cliente['nombres'] . ' ' . $cliente['apellidos'], 0, 1);
        }

        $pdf->Cell(40, 10, 'Total:', 0, 0);
        $pdf->Cell(0, 10, $venta['total'], 0, 1);

        // Nombre del archivo PDF
        $filename = 'comprobante_venta_' . $id_transaccion . '.pdf';

        // Salida del PDF
        ob_clean();
        $pdf->Output('I', $filename); // Cambiado para abrir el PDF en el navegador
        exit;
    } else {
        // Redirigir si no se encontró la venta
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    // Manejar el error
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
    exit;
}
?>
