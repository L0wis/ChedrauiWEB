<?php
ob_start();

require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Inicializa la variable de error
$error = "";

// Obtener el ID de la categoría desde la URL
if (isset($_GET['categoria_id'])) {
    $categoria_id = $_GET['categoria_id'];

    // Consulta para verificar si la categoría existe
    $db = new Database();
    $con = $db->conectar();

    $sql_categoria_existente = "SELECT COUNT(*) FROM categorias WHERE id = :categoria_id";
    $stmt_categoria_existente = $con->prepare($sql_categoria_existente);
    $stmt_categoria_existente->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_categoria_existente->execute();

    $categoria_existente = $stmt_categoria_existente->fetchColumn();

    if ($categoria_existente) {
        // La categoría existe, ahora puedes continuar obteniendo el nombre y otros datos si es necesario

        $sql_categoria = "SELECT nombre FROM categorias WHERE id = :categoria_id";
        $stmt_categoria = $con->prepare($sql_categoria);
        $stmt_categoria->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $stmt_categoria->execute();

        $nombre_categoria = $stmt_categoria->fetchColumn();

        // ... (Código para obtener otros datos si es necesario)

        // Obtener la lista de productos que no pertenecen a esta categoría
        $sql_productos_no_categoria = "SELECT id, nombre, precio FROM productos WHERE id_categoria IS NULL OR id_categoria <> :categoria_id";
        $stmt_productos_no_categoria = $con->prepare($sql_productos_no_categoria);
        $stmt_productos_no_categoria->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
        $stmt_productos_no_categoria->execute();

        $productos_no_categoria = $stmt_productos_no_categoria->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Redirigir si la categoría no existe
        header('Location: index.php');
        exit;
    }
} else {
    // Redirigir si no se proporciona el ID de la categoría
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la selección de productos
    if (isset($_POST['productos_seleccionados'])) {
        $productos_seleccionados = $_POST['productos_seleccionados'];

        // Realizar acciones con los productos seleccionados, por ejemplo, agregarlos a la categoría
        // Puedes implementar esta lógica según tus necesidades específicas

        // Redirigir a la página de mostrar productos después de procesar la selección
        header("Location: mostrar_productos.php?categoria_id=$categoria_id");
        exit;
    } else {
        $error = "No se han seleccionado productos.";
    }
}

?>

<!-- Tu código HTML y formulario aquí -->

<!-- ... (código previo) -->

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Agregar productos a la categoría: <?php echo $nombre_categoria; ?></h2>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <p class="fs-5 fw-bold mt-2">Productos que no están en esta categoría:</p>

        <form action="procesar_agregar_producto.php" method="post">
            <!-- Campo oculto para retener el ID de la categoría -->
            <input type="hidden" name="categoria_id" value="<?php echo $categoria_id; ?>">

            <!-- Otros campos del formulario -->

            <div class="table-responsive mt-1">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre del Producto</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_no_categoria as $producto) { ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><?php echo $producto['nombre']; ?></td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="productos_seleccionados[]" value="<?php echo $producto['id']; ?>" id="producto_<?php echo $producto['id']; ?>">
                                        <label class="form-check-label" for="producto_<?php echo $producto['id']; ?>"></label>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success">Agregar Productos Seleccionados</button>
        </form>
    </div>
</main>

<!-- ... (código posterior) -->



<?php require '../footer.php';

ob_end_flush();
?>
