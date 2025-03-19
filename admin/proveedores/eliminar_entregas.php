<?php
require '../config/database.php';
require '../config/config.php';

// Verificar si el ID de la entrega se ha proporcionado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redireccionar si el ID no está presente
    header('Location: ../index.php');
    exit;
}

// Obtener el ID de la entrega desde la URL
$entrega_id = $_GET['id'];

// Crear una instancia de la clase Database para la conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Preparar la consulta para eliminar la entrega
$sql = "DELETE FROM transaccion_prov WHERE id = :entrega_id";

// Preparar y ejecutar la consulta usando PDO para evitar inyección de SQL
$stmt = $con->prepare($sql);
$stmt->bindParam(':entrega_id', $entrega_id);

if ($stmt->execute()) {
    // Redireccionar a la página principal con un mensaje de éxito si la eliminación fue exitosa
    header('Location: vermas.php?id=' . $_GET['proveedor_id'] . '&mensaje=La entrega ha sido eliminada exitosamente.');
    exit;
} else {
    // Redireccionar a la página principal con un mensaje de error si la eliminación falló
    header('Location: vermas.php?id=' . $_GET['proveedor_id'] . '&mensaje=Error al intentar eliminar la entrega.');
    exit;
}
