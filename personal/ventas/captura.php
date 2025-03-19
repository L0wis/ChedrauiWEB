    <?php
    // Verificar si se recibió el ID de la venta a través de la URL
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['detalles']) && isset($_GET['id_venta'])) {
        // Obtener los detalles de la transacción desde PayPal
        $detalles = json_decode($_POST['detalles'], true);

        // Obtener el ID de venta de la URL
        $id_venta = $_GET['id_venta'];

        // Conectar a la base de datos
        require '../config/config.php';
        $db = new Database();
        $con = $db->conectar();

        // Actualizar el estado de la compra en la base de datos
        $sql_update_status = $con->prepare("UPDATE compra_personal SET status = 'APPROVED' WHERE id = ?");
        $sql_update_status->execute([$id_venta]);

        // Obtener los productos y cantidades de la transacción
        $productos_ids = $_POST['productos'];
        $cantidades = $_POST['cantidades'];

        // Actualizar el stock de productos
        foreach ($productos_ids as $key => $producto_id) {
            if (!empty($producto_id) && isset($cantidades[$key])) {
                // Restar la cantidad comprada del stock disponible
                restarStock($producto_id, $cantidades[$key], $con);
            }
        }

        // Redirigir a confirmacion.php con el ID de la compra como parámetro en la URL
        header("Location: confirmacion.php?id_venta=$id_venta");
        exit();
    } else {
        // Devolver una respuesta de error si no se recibieron los detalles de la transacción correctamente
        echo json_encode(array('success' => false, 'message' => 'Error: No se recibieron los detalles de la transacción correctamente.'));
    }

    // Función para restar el stock de un producto
    function restarStock($id, $cantidad, $con)
    {
        $sql = $con->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $sql->execute([$cantidad, $id]);
    }
