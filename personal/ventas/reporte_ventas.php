<?php
// Incluir archivos de configuración y TCPDF
require '../config/config.php';
require '../config/database.php';
require '../../TCPDF-main/tcpdf.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Crear una instancia de la clase Database
$db = new Database();

// Obtener la conexión a la base de datos
$con = $db->conectar();

// Verificar si la conexión se estableció correctamente
if (!$con) {
    echo "Error de conexión a la base de datos.";
    exit;
}

// Obtener el ID del personal
$personal_id = $_SESSION['user_id'];

// Realizar la consulta SQL para obtener los datos de ventas
$sql = "SELECT id, id_transaccion, status, email, id_cliente, fecha, total 
        FROM compra_personal 
        WHERE id_personal = :personal_id 
        ORDER BY fecha DESC";

try {
    // Preparar la consulta SQL
    $stmt = $con->prepare($sql);

    // Vincular parámetro personal_id
    $stmt->bindParam(':personal_id', $personal_id, PDO::PARAM_INT);

    // Ejecutar la consulta SQL
    $stmt->execute();

    // Crear un nuevo objeto TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Establecer el título del documento
    $pdf->SetTitle('Informe de Ventas');

    // Agregar una página al documento
    $pdf->AddPage();

    // Configurar el contenido del PDF
    $content = '<h1 style="text-align:center;">Informe de Ventas</h1>';
    $content .= '<h2>Listado de Ventas Recientes</h2>';

    // Generar la tabla con los datos de ventas
    $content .= '<table style="width:100%; border-collapse: collapse;" border="1">
                    <thead>
                        <tr style="background-color:#f2f2f2;">
                            <th style="padding:10px;">ID</th>
                            <th style="padding:10px;">ID Transacción</th>
                            <th style="padding:10px;">Status</th>
                            <th style="padding:10px;">Email</th>
                            <th style="padding:10px;">ID Cliente</th>
                            <th style="padding:10px;">Fecha</th>
                            <th style="padding:10px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>';
    while ($venta = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $content .= '<tr>
                        <td style="padding:10px;">' . htmlspecialchars($venta['id'], ENT_QUOTES) . '</td>
                        <td style="padding:10px;">' . htmlspecialchars($venta['id_transaccion'], ENT_QUOTES) . '</td>
                        <td style="padding:10px;">' . htmlspecialchars($venta['status'], ENT_QUOTES) . '</td>
                        <td style="padding:10px;">' . htmlspecialchars($venta['email'], ENT_QUOTES) . '</td>
                        <td style="padding:10px;">' . htmlspecialchars($venta['id_cliente'], ENT_QUOTES) . '</td>
                        <td style="padding:10px;">' . htmlspecialchars($venta['fecha'], ENT_QUOTES) . '</td>
                        <td style="padding:10px;">' . htmlspecialchars($venta['total'], ENT_QUOTES) . '</td>
                    </tr>';
    }
    $content .= '</tbody></table>';

    // Escribir el contenido en el PDF
    $pdf->writeHTML($content, true, false, true, false, '');

    // Generar el PDF y mostrarlo al usuario
    $pdf->Output('informe_ventas.pdf', 'I');
    exit;
} catch (PDOException $e) {
    // Manejar el error
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
}
?>
