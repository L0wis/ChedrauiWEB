<?php
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.descuento, p.stock, p.id_categoria, c.nombre as nombre_categoria 
        FROM productos p
        INNER JOIN categorias c ON p.id_categoria = c.id
        WHERE p.activo = 0"; // Cambia a 0 para obtener productos desactivados
$resultado = $con->query($sql);
$productosDesactivados = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Productos Desactivados</h2>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Categoría</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productosDesactivados)) { ?>
                        <tr>
                            <td colspan="5" class="text-center"><b>Sin productos desactivados</b></td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($productosDesactivados as $producto) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?></td>
                                <td><?php echo $producto['precio']; ?></td>
                                <td><?php echo $producto['stock']; ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre_categoria'], ENT_QUOTES); ?></td>
                                <td>
                                    <!-- Agrega aquí cualquier acción adicional para restaurar el producto -->
                                    <a href="restaurar.php?id=<?php echo $producto['id'] ?>" class="btn btn-success btn-sm">Restaurar</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- ... (código posterior) ... -->

<?php require '../footer.php'; ?>
