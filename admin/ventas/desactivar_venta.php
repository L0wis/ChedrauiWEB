<?php
// Requiere los archivos necesarios
require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Verifica el tipo de usuario y redirige si no es un administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Crea una instancia de la clase Database
$db = new Database();

// Obtiene la conexión a la base de datos
$con = $db->conectar();

// Verifica si la conexión se estableció correctamente
if (!$con) {
    echo "Error de conexión a la base de datos.";
    exit;
}

try {
    // Consulta SQL para obtener las ventas desactivadas de la tabla "compra" y "compra_personal"
    $sql = "SELECT id, id_transaccion, fecha, status, email, id_cliente, total, 'cliente' AS realizo FROM compra WHERE activo = 0
            UNION ALL
            SELECT id, id_transaccion, fecha, status, email, id_cliente, total, 'personal' AS realizo FROM compra_personal WHERE activo = 0";

    // Prepara y ejecuta la consulta SQL
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $ventas_desactivadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Muestra las ventas desactivadas en una tabla con un diseño mejorado
    ?>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-3">Ventas Desactivadas</h2>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID Transacción</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Status</th>
                            <th scope="col">Email</th>
                            <th scope="col">ID Cliente</th>
                            <th scope="col">Total</th>
                            <th scope="col">Realizó</th>
                            <th scope="col">Restaurar</th> <!-- Agregar esta columna -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventas_desactivadas)) { ?>
                            <tr>
                                <td colspan="8" class="text-center"><b>Sin ventas desactivadas</b></td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($ventas_desactivadas as $venta) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($venta['id_transaccion'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['fecha'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['status'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['email'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['id_cliente'], ENT_QUOTES); ?></td>
                                    <td><?php echo $venta['total']; ?></td>
                                    <td><?php echo htmlspecialchars($venta['realizo'], ENT_QUOTES); ?></td>
                                    <td>
                                        <!-- Botón para restaurar la venta -->
                                        <a href="restaurar_venta.php?id=<?php echo $venta['id']; ?>" class="btn btn-success btn-sm">Restaurar</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php
} catch (PDOException $e) {
    // Maneja el error
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
}

// Incluye el archivo de pie de página
require '../footer.php';
?>
