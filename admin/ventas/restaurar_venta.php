<?php
// Requiere los archivos necesarios
require '../config/database.php';
require '../config/config.php';

// Verifica si se proporcionó el ID de la venta en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirige si no se proporcionó un ID válido
    header('Location: index.php');
    exit;
}

// Obtiene el ID de la venta de la URL
$id_venta = $_GET['id'];

// Crea una instancia de la clase Database
$db = new Database();

// Obtiene la conexión a la base de datos
$con = $db->conectar();

// Verifica si la conexión se estableció correctamente
if (!$con) {
    echo "Error de conexión a la base de datos.";
    exit;
}

// Consulta SQL para actualizar el estado de la venta a activo (1) en la tabla "compra"
$sql_compra = "UPDATE compra SET activo = 1 WHERE id = :id_venta";

// Consulta SQL para actualizar el estado de la venta a activo (1) en la tabla "compra_personal"
$sql_compra_personal = "UPDATE compra_personal SET activo = 1 WHERE id = :id_venta";

try {
    // Preparar la consulta SQL para actualizar en la tabla "compra"
    $stmt_compra = $con->prepare($sql_compra);

    // Vincular el parámetro ID de la venta
    $stmt_compra->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);

    // Ejecutar la consulta SQL para actualizar en la tabla "compra"
    $stmt_compra->execute();

    // Preparar la consulta SQL para actualizar en la tabla "compra_personal"
    $stmt_compra_personal = $con->prepare($sql_compra_personal);

    // Vincular el parámetro ID de la venta
    $stmt_compra_personal->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);

    // Ejecutar la consulta SQL para actualizar en la tabla "compra_personal"
    $stmt_compra_personal->execute();

    // Redirige de nuevo a la página de ventas desactivadas
    header('Location: desactivar_venta.php');
    exit;
} catch (PDOException $e) {
    // Maneja el error
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
}

?>
