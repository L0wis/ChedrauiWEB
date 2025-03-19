<?php
// Aquí incluye las mismas configuraciones y clases que en tu archivo principal
require_once 'config/config.php';
require_once 'clases/clienteFunciones.php';
require_once('TCPDF-main/tcpdf.php');

// Recupera el orden de la solicitud GET
$orden = $_GET['orden'] ?? null;

if ($orden == null) {
    // Puedes agregar lógica adicional o devolver un error si el parámetro no está presente
    exit('Error: Falta el parámetro "orden".');
}

// Conectarse a la base de datos
$db = new Database();
$con = $db->conectar();

try {
    // Obtener información de la compra
    $sqlCompra = $con->prepare("SELECT id, id_transaccion, fecha, total FROM compra WHERE id_transaccion = ? LIMIT 1");
    $sqlCompra->execute([$orden]);
    $rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);
    $idcompra = $rowCompra['id'];

     // Crear instancia de TCPDF
     $pdf = new TCPDF();

     // Establecer nombre del archivo
     $filename = 'detalle_compra_' . $orden . '.pdf';
 
     // Configurar las cabeceras para indicar que se está enviando un archivo PDF
     header('Content-Type: application/pdf');
     header('Content-Disposition: attachment; filename="' . $filename . '"');

     // Establecer la fuente
$pdf->SetFont('dejavusans', '', 14);  // Puedes ajustar el tamaño de la fuente según tus preferencias
 
     // Agregar una página
     $pdf->AddPage();
 
     // Agregar contenido al PDF
     $pdf->writeHTML('<h1 style="color: #009688; text-align: center;">Detalle de la compra</h1>');
     $pdf->writeHTML('<p><strong style="color: #333;">Fecha: </strong> ' . $rowCompra['fecha'] . '</p>');
     $pdf->writeHTML('<p><strong style="color: #333;">Orden: </strong> ' . $rowCompra['id_transaccion'] . '</p>');
     $pdf->writeHTML('<p><strong style="color: #333;">Total: </strong> ' . MONEDA . ' ' . number_format($rowCompra['total'], 2, '.', ',') . '</p>');
 
     // Obtener detalles de la compra
     $sqlDetalle = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
     $sqlDetalle->execute([$idcompra]);
 
     // Agregar tabla con detalles de la compra
     $html = '<table style="width: 100%; border-collapse: collapse; margin-top: 20px;" border="1">';
     $html .= '<thead style="background-color: #009688; color: #fff;"><tr><th style="padding: 10px;">Producto</th><th style="padding: 10px;">Precio</th><th style="padding: 10px;">Cantidad</th><th style="padding: 10px;">Subtotal</th></tr></thead>';
     $html .= '<tbody>';
     while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
         $precio = $row['precio'];
         $cantidad = $row['cantidad'];
         $subtotal = $precio * $cantidad;
 
         $html .= '<tr>';
         $html .= '<td style="padding: 10px; text-align: left;">' . $row['nombre'] . '</td>';
         $html .= '<td style="padding: 10px; text-align: right;">' . MONEDA . ' ' . number_format($precio, 2, '.', ',') . '</td>';
         $html .= '<td style="padding: 10px; text-align: center;">' . $cantidad . '</td>';
         $html .= '<td style="padding: 10px; text-align: right;">' . MONEDA . ' ' . number_format($subtotal, 2, '.', ',') . '</td>';
         $html .= '</tr>';
     }
     $html .= '</tbody>';
     $html .= '</table>';
 
     $pdf->writeHTML($html);

     // Agregar mensaje de agradecimiento y línea de estrellas
$pdf->writeHTML('<p style="text-align: center; margin-top: 20px; color: #009688; font-size: 18px;">¡Gracias por su compra! Vuelva Pronto!</p>');
$pdf->writeHTML('<p style="text-align: center; margin-top: 10px; font-size: 24px;">★★★★★</p>');
 
     // Generar el PDF y enviarlo al navegador para su descarga
     $pdf->Output($filename, 'D');
 } catch (Exception $e) {
     exit('Error: ' . $e->getMessage());
 } finally {
     // Cerrar la conexión PDO
     $con = null;
 }
 ?>