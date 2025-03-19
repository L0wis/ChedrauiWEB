<?php

require '../config/database.php';

// Verificar si se ha enviado el ID del personal a eliminar
if (isset($_POST['id'])) {
    // Obtener el ID del personal a eliminar
    $id = $_POST['id'];

    // Establecer una conexión a la base de datos
    $db = new Database();
    $con = $db->conectar();

    // Consulta SQL para actualizar el estado de activo a 0
    $sql = "UPDATE personal SET activo = 0 WHERE id = :id";

    // Preparar la consulta
    $stmt = $con->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir de nuevo a la página de personal
        header('Location: personal.php');
        exit;
    } else {
        // Si hay un error, mostrar un mensaje de error
        echo "Error al desactivar el personal.";
    }
} else {
    // Si no se proporciona un ID válido, redirigir a la página de personal
    header('Location: personal.php');
    exit;
}

?>
