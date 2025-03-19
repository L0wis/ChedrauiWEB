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
    header('Location: index.php');
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
    header('Location: index.php');
    exit;
}

// Realizar la consulta SQL para actualizar el estado de la compra y restaurar la venta
$sql = "UPDATE compra_personal SET activo = 1, status = 'RESTORED' WHERE id = :id";

try {
    // Preparar la consulta SQL
    $stmt = $con->prepare($sql);

    // Vincular parámetro id
    $stmt->bindParam(':id', $id_venta, PDO::PARAM_INT);

    // Ejecutar la consulta SQL
    $stmt->execute();

    // Redirigir al usuario de vuelta a la página restaurar_venta.php
    header('Location: restaurar_venta.php');
    exit;
} catch (PDOException $e) {
    header('Location: index.php');
    exit;
}

// No es necesario incluir el archivo de pie de página ya que estamos redirigiendo al usuario
?>
