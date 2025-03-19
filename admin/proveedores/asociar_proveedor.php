<?php
require '../config/database.php';
require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Validar que los datos se envíen correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'], $_POST['proveedores'])) {
    $producto_id = intval($_POST['producto_id']);
    $proveedores = $_POST['proveedores'];

    // Asegurarse de que el producto ID sea válido
    if ($producto_id <= 0) {
        $_SESSION['error'] = "ID de producto inválido.";
        header('Location: productos.php');
        exit;
    }

    // Filtrar y validar los IDs de proveedores
    $proveedores_filtrados = array_filter($proveedores, function ($id) {
        return is_numeric($id) && intval($id) > 0;
    });

    if (empty($proveedores_filtrados)) {
        $_SESSION['error'] = "No se seleccionaron proveedores válidos.";
        header('Location: productos.php');
        exit;
    }

    // Convertir los IDs a una cadena separada por comas
    $proveedores_ids = implode(',', $proveedores_filtrados);

    // Conectar a la base de datos
    $db = new Database();
    $con = $db->conectar();

    // Actualizar el campo id_proveedor del producto
    $sql = "UPDATE productos SET id_proveedor = :id_proveedor WHERE id = :producto_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id_proveedor', $proveedores_ids, PDO::PARAM_STR);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Proveedores asociados correctamente al producto.";
    } else {
        $_SESSION['error'] = "Error al asociar proveedores al producto.";
    }
} else {
    $_SESSION['error'] = "Datos incompletos para realizar la operación.";
}

header('Location: productos.php');
exit;
