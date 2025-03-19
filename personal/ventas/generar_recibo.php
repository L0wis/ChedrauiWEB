<?php
require '../config/config.php';
require '../../fpdf186/fpdf.php';
require '../header.php';

// Verificar si se recibió el ID de la venta a través de la URL
$id_venta = $_GET['id_venta'] ?? null;

if ($id_venta == null) {
    // Puedes agregar lógica adicional o devolver un error si el parámetro no está presente
    exit('Error: Falta el parámetro "id_venta".');
}

try {
    // Conectar a la base de datos
    $db = new Database();
    $con = $db->conectar();

    // Obtener información de la compra
    $sql_info_compra = $con->prepare("SELECT * FROM compra_personal WHERE id = ?");
    $sql_info_compra->execute([$id_venta]);
    $info_compra = $sql_info_compra->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró la compra con el ID proporcionado
    if ($info_compra) {
        // Obtener el nombre del cliente
        $sql_cliente = $con->prepare("SELECT nombres FROM clientes WHERE id = ?");
        $sql_cliente->execute([$info_compra['id_cliente']]);
        $cliente = $sql_cliente->fetch(PDO::FETCH_ASSOC);

        // Obtener el nombre del personal
        $sql_personal = $con->prepare("SELECT nombre FROM personal WHERE id = ?");
        $sql_personal->execute([$info_compra['id_personal']]);
        $personal = $sql_personal->fetch(PDO::FETCH_ASSOC);

        // Consultar los productos comprados en esta compra
        $sql_productos_comprados = $con->prepare("SELECT productos.nombre AS nombre_producto, compra_personal_productos.cantidad, productos.precio 
                                                  FROM compra_personal_productos 
                                                  INNER JOIN productos ON compra_personal_productos.id_producto = productos.id 
                                                  WHERE compra_personal_productos.id_venta = ?");
        $sql_productos_comprados->execute([$id_venta]);
        $productos_comprados = $sql_productos_comprados->fetchAll(PDO::FETCH_ASSOC);

        // Limpiar el buffer de salida
        ob_end_clean();

        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Configurar fuentes
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(0, 102, 204); // Azul
        $pdf->Cell(0, 10, 'Recibo de Compra', 0, 1, 'C');
        $pdf->Ln(10);

        // Agregar información de la compra
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0); // Negro
        $pdf->Cell(0, 10, 'ID de Venta: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12); // Establecer el estilo en negrita
        $pdf->Cell(0, 10, $info_compra['id'], 0, 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Fecha de la Compra: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, $info_compra['fecha'], 0, 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Folio de la Compra: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, $info_compra['id_transaccion'], 0, 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Total: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, MONEDA . ' ' . number_format($info_compra['total'], 2), 0, 1, 'R');
        // Agregar status de la compra
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Status de la Compra: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, $info_compra['status'], 0, 1, 'R');

        // Agregar nombre del cliente y del personal
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Nombre del Cliente: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, $cliente['nombres'], 0, 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Personal: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, $personal['nombre'], 0, 1, 'R');

        // Agregar productos comprados
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Productos Comprados', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        foreach ($productos_comprados as $producto) {
            $nombre_producto = $producto['nombre_producto'];
            $cantidad = $producto['cantidad'];
            $subtotal = $cantidad * $producto['precio'];

            $pdf->Cell(0, 10, $nombre_producto . ' x ' . $cantidad . ' : ' . MONEDA . number_format($subtotal, 2), 0, 1);
        }

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Ln(10);
        $pdf->MultiCell(0, 10, '!Gracias por su compra! !Vuelva Pronto!', 0, 'C');
        $pdf->Ln(10);
        
        // Agregar imagen al final del recibo
        $imageUrl = '..\images\recibo.jpg'; // Ruta de la imagen
        $pdf->Image($imageUrl, 10, $pdf->GetY() + 10, 80); // Ajusta la posición y el tamaño según sea necesario

        // Generar el PDF y enviarlo al navegador para su descarga
        $pdf->Output('recibo_compra_' . $id_venta . '.pdf', 'D');
    } else {
        // Mostrar un mensaje de error si no se encontró la compra con el ID proporcionado
        exit("No se encontró ninguna compra con el ID proporcionado.");
    }
} catch (PDOException $e) {
    // Manejar excepciones de la base de datos
    exit("Error al consultar la base de datos: " . $e->getMessage());
}
?>
