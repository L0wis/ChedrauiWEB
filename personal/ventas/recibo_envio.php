<?php
// Incluir la biblioteca TCPDF
require '../../TCPDF-main/tcpdf.php';

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nombre del Autor');
$pdf->SetTitle('Recibo de Compra');
$pdf->SetSubject('Recibo de Compra');
$pdf->SetKeywords('Recibo, Compra, Factura');

// Establecer márgenes
$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// Agregar una página
$pdf->AddPage();

// Establecer la zona horaria a la del Centro de México
date_default_timezone_set('America/Mexico_City');

// Obtener la fecha y hora actual
$fecha_actual = date('Y-m-d H:i:s');

// Conectar a la base de datos
require '../../config/config.php';
$db = new Database();
$con = $db->conectar();

// Obtener el ID de la venta si está disponible
$id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : null;

// Inicializar variables para la información de la compra
$informacion_compra = '';
$informacion_clientes = '';
$informacion_productos = '';
$referencias = '';

try {
    if ($id_venta) {
        // Consultar los datos de la venta desde la base de datos
        $sql = "SELECT * FROM compra_personal WHERE id = ?"; // Ajusta la consulta según tu esquema de base de datos
        $stmt = $con->prepare($sql);
        $stmt->execute([$id_venta]);
        $venta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($venta) {
            // Información de la compra: ID, ID Transacción, Fecha y Status
            $informacion_compra = '
                <h2>Información de la Compra</h2>
                <p><strong>ID:</strong> ' . $venta['id'] . '</p>
                <p><strong>ID Transacción:</strong> ' . $venta['id_transaccion'] . '</p>
                <p><strong>Fecha:</strong> ' . $venta['fecha'] . '</p>
                <p><strong>Status:</strong> ' . $venta['status'] . '</p>
                <hr> <!-- Línea adicional -->
            ';

            // Información del cliente: Email, ID Cliente y Dirección
            $informacion_clientes = '
                <h2>Información del Cliente</h2>
                <p><strong>Email:</strong> ' . $venta['email'] . '</p>
                <p><strong>ID Cliente:</strong> ' . $venta['id_cliente'] . '</p>
                <p><strong>Dirección:</strong> ' . $venta['direccion'] . '</p>
            ';

            // Obtener las referencias de la venta
            $referencias = '
                <h2>Referencias</h2>
                <p>' . $venta['referencias'] . '</p>
                <hr> <!-- Línea adicional -->
            ';

            // Obtener los IDs y cantidades de los productos asociados a la venta
            $sql_productos = "SELECT id_producto, cantidad FROM compra_personal_productos WHERE id_venta = ?";
            $stmt_productos = $con->prepare($sql_productos);
            $stmt_productos->execute([$id_venta]);
            $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

            if ($productos) {
                // Información de los productos comprados
                $informacion_productos = '<h2>Productos Comprados</h2><ul>';
                foreach ($productos as $producto) {
                    $producto_id = $producto['id_producto'];
                    $cantidad = $producto['cantidad'];
                    // Consultar el nombre del producto utilizando el ID
                    $sql_producto = "SELECT nombre FROM productos WHERE id = ?";
                    $stmt_producto = $con->prepare($sql_producto);
                    $stmt_producto->execute([$producto_id]);
                    $producto_nombre = $stmt_producto->fetchColumn();
                    // Agregar el nombre del producto y la cantidad a la lista
                    $informacion_productos .= '<li>' . $producto_nombre . ' - Cantidad: ' . $cantidad . '</li>';
                }
                $informacion_productos .= '</ul>';
            } else {
                $informacion_productos = '<p>No hay productos asociados a esta venta.</p>';
            }
        } else {
            $informacion_compra = '<p>No se encontraron datos de venta.</p>';
        }
    } else {
        $informacion_compra = '<p>No se proporcionó un ID de venta.</p>';
    }
} catch (PDOException $e) {
    $informacion_compra = '<p>Error al obtener los datos de venta: ' . $e->getMessage() . '</p>';
}

// Establecer el contenido del recibo
$html = '
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            color: #555;
        }
        p {
            margin: 0 0 10px;
            color: #555;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        li {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
            color: #777;
        }
        .linea-firma {
            border-top: 1px solid #555;
            margin-top: 20px;
            padding-top: 10px;
            text-align: center; /* Centrar contenido */
        }
        .firma-texto {
            margin-top: 10px; /* Espacio entre líneas */
        }
    </style>
    <div class="container">
        <h1>Recibo de Compra</h1>
        ' . $informacion_compra . '
        ' . $informacion_clientes . '
        ' . $referencias . '
        ' . $informacion_productos . '
        <p></p>
        <p></p>
            <p class="firma-texto">Nombre y Firma de Autorización:</p>
            <p class="firma-texto">_____________________________</p>
            <p></p>
            <p></p>
            <p></p>
            <p></p>
            <p></p>

            <p style="text-align: center;"><strong>**Favor de conservar el ticket, es su comprobante de compra.**</strong></p>
    </div>
';

// Escribir el contenido en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF (nombre del archivo, modo de salida)
$pdf->Output('recibo_compra.pdf', 'I'); // I para mostrar en el navegador
?>