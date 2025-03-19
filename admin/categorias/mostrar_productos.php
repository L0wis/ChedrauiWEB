<?php

ob_start();

require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Obtener el ID de la categoría desde la URL
if (isset($_GET['categoria_id'])) {
    $categoria_id = $_GET['categoria_id'];

    // Consulta para obtener el nombre de la categoría
    $db = new Database();
    $con = $db->conectar();

    $sql_categoria = "SELECT nombre FROM categorias WHERE id = :categoria_id AND activo = 1";
    $stmt_categoria = $con->prepare($sql_categoria);
    $stmt_categoria->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_categoria->execute();

    $nombre_categoria = $stmt_categoria->fetchColumn();

    // Consulta para obtener la cantidad total de productos en la categoría
    $sql_cantidad_productos = "SELECT COUNT(*) AS cantidad_productos FROM productos WHERE id_categoria = :categoria_id AND activo = 1";
    $stmt_cantidad_productos = $con->prepare($sql_cantidad_productos);
    $stmt_cantidad_productos->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_cantidad_productos->execute();

    $cantidad_productos = $stmt_cantidad_productos->fetchColumn();

    // Consulta para obtener los productos de la categoría seleccionada
    $sql_productos = "SELECT id, nombre, precio FROM productos WHERE id_categoria = :categoria_id AND activo = 1";
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
        <h2 class="mt-3">Productos de la Categoría: <?php echo $nombre_categoria; ?></h2>

        <!-- Frase con la cantidad total de productos -->
        <p>Esta categoría tiene <?php echo $cantidad_productos; ?> productos en total.</p>

        <!-- Botones para agregar y eliminar productos -->
        <div class="mb-3">
            <a href="agregar_producto.php?categoria_id=<?php echo $categoria_id; ?>" class="btn btn-success"><i class="bi bi-cart-plus-fill"></i> Agregar Producto</a>
            <a href="eliminar_producto.php?categoria_id=<?php echo $categoria_id; ?>" class="btn btn-danger"><i class="bi bi-cart-x-fill"></i> Eliminar Producto</a>
            <a href="descargar_reporte.php?categoria_id=<?php echo $categoria_id; ?>" class="btn btn-success"><i class="bi bi-filetype-pdf"></i> Descargar reporte productos PDF</a>
            <a href="descargar_reporte_excel.php?categoria_id=<?php echo $categoria_id; ?>" class="btn btn-primary"><i class="bi bi-file-spreadsheet-fill"></i> Descargar Reporte de Productos Excel</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre del Producto</th>
                        <th scope="col">Precio</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto) { ?>
                        <tr>
                            <td><?php echo $producto['id']; ?></td>
                            <td><?php echo $producto['nombre']; ?></td>
                            <td><?php echo $producto['precio']; ?></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require '../footer.php';

ob_end_flush();
?>
