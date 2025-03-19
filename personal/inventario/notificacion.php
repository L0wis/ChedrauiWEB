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

// Obtener el ID del usuario actual
$user_id = $_SESSION['user_id'];

// Consultar las notificaciones del usuario actual
$sql = "SELECT n.*, GROUP_CONCAT(np.id_producto) AS productos
        FROM notificacion n
        LEFT JOIN notificacion_producto np ON n.id = np.id_notificacion
        WHERE n.id_personal = :user_id
        GROUP BY n.id
        ORDER BY n.fecha_creacion DESC";


$stmt = $con->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Notificaciones</h2>

        <!-- Agregar botón "Crear Notificación" -->
        <a href="crear_notificacion.php" class="btn btn-primary mb-3">Crear Notificación</a>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Mensaje</th>
                        <th scope="col">Prioridad</th>
                        <th scope="col">Status</th>
                        <th scope="col">Productos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notificaciones as $notificacion) { ?>
                        <tr>
                            <td><?php echo $notificacion['fecha_creacion']; ?></td>
                            <td><?php echo htmlspecialchars($notificacion['mensaje'], ENT_QUOTES); ?></td>
                            <td><?php echo $notificacion['prioridad']; ?></td>
                            <td><?php echo $notificacion['status']; ?></td>
                            <td>
                                <?php 
                                    if ($notificacion['productos']) {
                                        $producto_ids = explode(',', $notificacion['productos']);
                                        foreach ($producto_ids as $producto_id) {
                                            // Consultar el nombre del producto
                                            $sql_producto = "SELECT nombre FROM productos WHERE id = :producto_id";
                                            $stmt_producto = $con->prepare($sql_producto);
                                            $stmt_producto->execute(['producto_id' => $producto_id]);
                                            $producto = $stmt_producto->fetch(PDO::FETCH_ASSOC);
                                            echo htmlspecialchars($producto['nombre'], ENT_QUOTES) . '<br>';
                                        }
                                    } else {
                                        echo "Sin productos asociados";
                                    }
                                ?>
                            </td>
                        </tr>
                   <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require '../footer.php'; ?>
