<?php
// Verificar si se recibió el ID de la venta
if (isset($_POST['id_venta'])) {
    // Obtener el ID de la venta
    $id_venta = $_POST['id_venta'];

    // Incluir el archivo de configuración de la base de datos
    require '../config/config.php';
    require '../config/database.php';

    // Crear una instancia de la clase Database para la conexión
    $db = new Database();
    $con = $db->conectar();

    try {
        // Iniciar una transacción
        $con->beginTransaction();

        // Actualizar el tipo de pago de la compra a 'EFECTIVO'
        $sql_update_tipo_pago = $con->prepare("UPDATE compra_personal SET tipo_pago = 'EFECTIVO' WHERE id = ?");
        if ($sql_update_tipo_pago->execute([$id_venta])) {
            // Consultar el estado actual de la compra
            $sql_check_status = $con->prepare("SELECT status FROM compra_personal WHERE id = ?");
            if ($sql_check_status->execute([$id_venta])) {
                $status = $sql_check_status->fetchColumn();

                // Verificar si la compra ya ha sido aprobada
                if ($status === 'APPROVED') {
                    // Si la compra ya ha sido aprobada, enviar respuesta de error al cliente
                    echo "error: Esta compra ya ha sido aprobada.";
                } else {
                    // Actualizar el estado de la compra a 'APPROVED'
                    $sql_update_status = $con->prepare("UPDATE compra_personal SET status = 'APPROVED' WHERE id = ?");
                    if ($sql_update_status->execute([$id_venta])) {
                        // Obtener los productos comprados en esta venta
                        $sql_productos_comprados = $con->prepare("SELECT id_producto, cantidad FROM compra_personal_productos WHERE id_venta = ?");
                        if ($sql_productos_comprados->execute([$id_venta])) {
                            $productos_comprados = $sql_productos_comprados->fetchAll(PDO::FETCH_ASSOC);

                            // Verificar si se encontraron productos comprados
                            if ($productos_comprados) {
                                // Iterar sobre los productos comprados para actualizar el stock
                                foreach ($productos_comprados as $producto) {
                                    $id_producto = $producto['id_producto'];
                                    $cantidad = $producto['cantidad'];

                                    // Actualizar el stock del producto
                                    $sql_actualizar_stock = $con->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
                                    $sql_actualizar_stock->execute([$cantidad, $id_producto]);
                                }

                                // Confirmar la transacción
                                $con->commit();

                                // Enviar respuesta de éxito al cliente
                                echo "success";
                            } else {
                                // Si no se encontraron productos comprados, hacer rollback y enviar respuesta de error al cliente
                                $con->rollback();
                                echo "error: No se encontraron productos comprados en esta venta.";
                            }
                        } else {
                            echo "error: Error al obtener productos comprados.";
                        }
                    } else {
                        echo "error: Error al actualizar el estado de la compra.";
                    }
                }
            } else {
                echo "error: Error al obtener el estado de la compra.";
            }
        } else {
            echo "error: Error al actualizar el tipo de pago.";
        }
    } catch (PDOException $e) {
        // Si ocurre un error, hacer rollback y enviar respuesta de error al cliente
        $con->rollback();
        echo "error: " . $e->getMessage();
    }
} else {
    // Si no se recibió el ID de la venta, enviar respuesta de error al cliente
    echo "error: No se recibió el ID de la venta.";
}
?>