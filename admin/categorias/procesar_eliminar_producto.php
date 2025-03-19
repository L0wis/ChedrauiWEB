<?php
require '../config/database.php';
require '../config/config.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener la categoría y los productos seleccionados desde el formulario
    $categoria_id = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : null;
    $productos_seleccionados = isset($_POST['productos_seleccionados']) ? $_POST['productos_seleccionados'] : [];

    // Validar que la categoría y los productos seleccionados existan
    if ($categoria_id && !empty($productos_seleccionados)) {
        try {
            // Conectar a la base de datos
            $db = new Database();
            $con = $db->conectar();

            // Actualizar la columna id_categoria a 31 en los productos seleccionados
            $sql_actualizar = "UPDATE productos SET id_categoria = 31 WHERE id IN (" . implode(",", $productos_seleccionados) . ")";
            $stmt_actualizar = $con->prepare($sql_actualizar);
            $stmt_actualizar->execute();

            // Redirigir de nuevo a mostrar_productos.php con el ID de la categoría
            header('Location: mostrar_productos.php?categoria_id=' . $categoria_id);
            exit;
        } catch (Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir durante la actualización
            echo "Error al actualizar productos: " . $e->getMessage();
        }
    }
}

// Redirigir a la página principal si no se envió el formulario correctamente
header('Location: index.php');
exit;
?>
