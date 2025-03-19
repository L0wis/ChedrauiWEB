<?php
require '../config/config.php';
require '../config/database.php';
require '../../TCPDF-main/tcpdf.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

if(isset($_GET['id'])) {
    $id_venta = $_GET['id'];

    $db = new Database();
    $con = $db->conectar();

    if (!$con) {
        echo "Error de conexión a la base de datos.";
        exit;
    }

    try {
        $sql = "SELECT cp.*, c.nombres, c.apellidos, c.email, c.direccion, cpp.id_producto, cpp.cantidad, p.nombre AS nombre_producto
                FROM compra_personal cp
                INNER JOIN clientes c ON cp.id_cliente = c.id
                INNER JOIN compra_personal_productos cpp ON cp.id = cpp.id_venta
                INNER JOIN productos p ON cpp.id_producto = p.id
                WHERE cp.id = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([$id_venta]);
        $venta = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($venta) {
            // Creamos un nuevo objeto TCPDF
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Establecemos el título del documento
            $pdf->SetTitle('Comprobante de Compra');

            // Agregamos una página al documento
            $pdf->AddPage();

            // Configuramos el contenido del PDF
            $content = '<style>
                            .container {
                                padding: 20px;
                                background-color: #f0f0f0;
                                border-radius: 10px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            }
                            h1, h2 {
                                color: #333;
                                text-align: center;
                            }
                            ul {
                                list-style: none;
                                padding-left: 0;
                            }
                            li {
                                margin-bottom: 5px;
                            }
                            .info-group {
                                margin-bottom: 20px;
                            }
                            .info-label {
                                font-weight: bold;
                            }
                        </style>';
            $content .= '<div class="container">';
            $content .= '<h1>Comprobante de Compra</h1>';
            foreach ($venta as $item) {
                $content .= '<div class="info-group">';
                $content .= '<h2>Detalles de la Compra</h2>';
                $content .= '<ul>';
                $content .= '<li class="info-label">ID Transacción:</li>';
                $content .= '<li>' . $item['id_transaccion'] . '</li>';
                $content .= '<li class="info-label">Nombre del Cliente:</li>';
                $content .= '<li>' . $item['nombres'] . ' ' . $item['apellidos'] . '</li>';
                $content .= '<li class="info-label">Email del Cliente:</li>';
                $content .= '<li>' . $item['email'] . '</li>';
                $content .= '<li class="info-label">Dirección de Entrega:</li>';
                $content .= '<li>' . $item['direccion'] . '</li>';
                $content .= '<li class="info-label">Fecha:</li>';
                $content .= '<li>' . $item['fecha'] . '</li>';
                $content .= '<li class="info-label">Status:</li>';
                $content .= '<li>' . $item['status'] . '</li>';
                $content .= '<li class="info-label">Productos Comprados:</li>';
                $content .= '<li><ul>';
                $content .= '<li>' . $item['nombre_producto'] . ' - Cantidad: ' . $item['cantidad'] . '</li>';
                $content .= '</ul></li>';
                $content .= '</ul>';
                $content .= '</div>';
            }
            $content .= '</div>';

            // Escribimos el contenido en el PDF
            $pdf->writeHTML($content, true, false, true, false, '');

            // Generamos el PDF y lo mostramos al usuario
            $pdf->Output('comprobante_venta.pdf', 'I');
            exit;
        } else {
            echo "<p>No se encontraron detalles de la compra.</p>";
        }
    } catch (PDOException $e) {
        echo "Error al obtener los detalles de la compra: " . $e->getMessage();
    }
} else {
    header('Location: index.php');
    exit;
}
?>
