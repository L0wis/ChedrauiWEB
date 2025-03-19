<?php
require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Obtener el ID de la categoría desde la URL
if (isset($_GET['categoria_id'])) {
    $categoria_id = $_GET['categoria_id'];

    // Consulta para obtener el nombre de la categoría
    $db = new Database();
    $con = $db->conectar();

    $sql_categoria = "SELECT nombre FROM categorias WHERE id = :categoria_id";
    $stmt_categoria = $con->prepare($sql_categoria);
    $stmt_categoria->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_categoria->execute();

    $nombre_categoria = $stmt_categoria->fetchColumn();

    // Consulta para obtener los productos de la categoría seleccionada
    $sql_productos = "SELECT id, nombre, precio FROM productos WHERE id_categoria = :categoria_id";
    $stmt_productos = $con->prepare($sql_productos);
    $stmt_productos->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_productos->execute();

    $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Redirigir si no se proporciona el ID de la categoría
    header('Location: ../index.php');
    exit;
}
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Eliminar Producto de la Categoría: <?php echo $nombre_categoria; ?></h2>

        <form action="procesar_eliminar_producto.php" method="post">
            <!-- Tabla de productos -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre del Producto</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto) { ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><?php echo $producto['nombre']; ?></td>
                                <td><?php echo $producto['precio']; ?></td>
                                <td>
                                    <!-- Checkbox para seleccionar el producto -->
<!-- Checkbox para seleccionar el producto con margen a la derecha -->
<input type="checkbox" name="productos_seleccionados[]" value="<?php echo $producto['id']; ?>" style="transform: scale(1.5); margin-right: 15px;">
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Agregar un botón de submit para enviar el formulario -->
            <button type="submit" class="btn btn-danger">Eliminar Productos Seleccionados</button>
            <input type="hidden" name="categoria_id" value="<?php echo $categoria_id; ?>">
        </form>
    </div>
</main>

<?php require '../footer.php'; ?>
