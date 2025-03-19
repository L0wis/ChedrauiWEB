<?php
ob_start();

require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $categoria_id = $_POST['categoria_id'];
    $productos_seleccionados = isset($_POST['productos_seleccionados']) ? $_POST['productos_seleccionados'] : [];

    // Validar que se haya seleccionado al menos un producto
    if (empty($productos_seleccionados)) {
        // Redirigir con mensaje de error
        header('Location: agregar_producto.php?categoria_id=' . $categoria_id . '&error=No se han seleccionado productos.');
        exit;
    }

    try {
        $db = new Database();
        $con = $db->conectar();

        // Iniciar transacción
        $con->beginTransaction();

        // Asignar la categoría a los productos seleccionados
        $sql_asignar_categoria = "UPDATE productos SET id_categoria = :categoria_id WHERE id IN (" . implode(',', $productos_seleccionados) . ")";
        $stmt_asignar_categoria = $con->prepare($sql_asignar_categoria);
        $stmt_asignar_categoria->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $stmt_asignar_categoria->execute();

        // Confirmar la transacción
        $con->commit();

        // Redirigir con mensaje de éxito
        header('Location: mostrar_productos.php?categoria_id=' . $categoria_id . '&success=Productos agregados exitosamente.');
        exit;
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $con->rollBack();

        // Redirigir con mensaje de error
        header('Location: agregar_producto.php?categoria_id=' . $categoria_id . '&error=Error al agregar productos. Por favor, inténtelo de nuevo.');
        exit;
    }
} else {
    // Redirigir si el formulario no se ha enviado
    header('Location: agregar_producto.php?categoria_id=' . $categoria_id);
    exit;
}
?>
