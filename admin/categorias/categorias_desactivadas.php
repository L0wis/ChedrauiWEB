<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = "SELECT id, nombre FROM categorias WHERE activo = 0";
$resultado = $con->query($sql);
$categorias_desactivadas = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Categorías Desactivadas</h2>

        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left-square-fill"></i> Volver a Categorías
        </a>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($categorias_desactivadas) > 0): ?>
                        <?php foreach ($categorias_desactivadas as $categoria){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($categoria['id'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($categoria['nombre'], ENT_QUOTES); ?></td>

                                <td>
                                    <!-- Botón "Productos" -->
                                    <a class="btn btn-success btn-sm" href="mostrar_productos.php?categoria_id=<?php echo $categoria['id']; ?>">
                                        <i class="bi bi-cart-check-fill"></i> Productos
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="edita.php?id=<?php echo 
                                    $categoria['id']; ?>">
                                        <i class="bi bi-pencil-fill"></i> Editar
                                    </a>
                                </td>
                                <td>
                                     <!-- Botón "Restaurar" -->
                                     <form action="restaurar.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-arrow-clockwise"></i> Restaurar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay categorías desactivadas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</main>

<?php require '../footer.php'; ?>
