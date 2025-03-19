<?php
require '../config/database.php';
require '../config/config.php';

// Verificar si se ha enviado el ID de la entrega a cancelar
if (isset($_GET['id'])) {
    $entrega_id = $_GET['id'];

    // Conectarse a la base de datos
    $db = new Database();
    $con = $db->conectar();

    // Consulta SQL para actualizar el estado de la entrega a Cancelada
    $sql = "UPDATE transaccion_prov SET status = 2 WHERE id = :entrega_id";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':entrega_id', $entrega_id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir de vuelta a la página principal de entregas con un mensaje de éxito
        header('Location: obtener_productos.php?id=' . $entrega_id . '&success=1');
        exit;
    } else {
        // Si la actualización falla, redirigir de vuelta con un mensaje de error
        header('Location: obtener_productos.php?id=' . $entrega_id . '&error=1');
        exit;
    }
} else {
    // Si no se proporciona el ID de la entrega, redirigir de vuelta a la página principal de entregas
    header('Location: obtener_productos.php');
    exit;
}
?>
