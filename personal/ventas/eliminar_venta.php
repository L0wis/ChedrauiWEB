<?php
// Incluir archivo de configuración y encabezado
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Verificar si se proporcionó un ID de venta válido en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de venta no válido.";
    exit;
}

// Obtener el ID de venta de la URL
$id_venta = $_GET['id'];

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

// Realizar la consulta SQL para actualizar el estado de la compra y desactivar la venta
$sql = "UPDATE compra_personal SET activo = 0, status = 'DISABLED' WHERE id = :id";

try {
    // Preparar la consulta SQL
    $stmt = $con->prepare($sql);

    // Vincular parámetro id
    $stmt->bindParam(':id', $id_venta, PDO::PARAM_INT);

    // Ejecutar la consulta SQL
    $stmt->execute();

    // Redirigir al usuario de vuelta a la página desactivar_venta.php
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    // Manejar el error
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
}

// Incluir el archivo de pie de página
require '../footer.php';
?>
