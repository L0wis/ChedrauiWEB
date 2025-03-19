<?php
// Incluir archivo de configuración y conexión a la base de datos
require '../config/database.php';
require '../config/config.php';

// Verificar si se recibió el ID de la transacción
if (isset($_POST['id_transaccion']) && !empty($_POST['id_transaccion'])) {
    // Obtener el ID de la transacción
    $id_transaccion = $_POST['id_transaccion'];

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
        // Consulta SQL para actualizar el estado de la venta a desactivado (por ejemplo, cambiar status de 1 a 0)
        $sql = "UPDATE compra_personal SET activo = 0 WHERE id_transaccion = :id_transaccion";

        // Preparar la consulta SQL
        $stmt = $con->prepare($sql);

        // Vincular el parámetro ID de transacción
        $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_STR);

        // Ejecutar la consulta SQL
        if ($stmt->execute()) {
            // Redireccionar a la página de detalles de la venta con un mensaje de éxito
            header('Location: index.php?id=' . $id_transaccion . '&success=true');
            exit;
        } else {
            // Manejar el caso en que la actualización no sea exitosa
            echo "Error al desactivar la venta. Por favor, intenta de nuevo más tarde.";
            exit;
        }
    } catch (PDOException $e) {
        // Manejar el error
        echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
        exit;
    }
} else {
    // Si no se recibió el ID de la transacción, redirigir a la página principal
    header('Location: index.php');
    exit;
}
?>
