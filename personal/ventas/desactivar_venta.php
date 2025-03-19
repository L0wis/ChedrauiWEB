<?php
// Incluir archivo de configuración y encabezado
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Crear una instancia de la clase Database
$db = new Database();

// Obtener la conexión a la base de datos
$con = $db->conectar();

// Verificar si la conexión se estableció correctamente
if (!$con) {
    // Manejar el error de conexión
    echo "Error de conexión a la base de datos.";
    exit;
}

// Obtener el ID del personal
$personal_id = $_SESSION['user_id'];

// Realizar la consulta SQL para obtener las ventas desactivadas
$sql = "SELECT id, id_transaccion, status, email, id_cliente, fecha, total 
        FROM compra_personal 
        WHERE id_personal = :personal_id AND activo = 0
        ORDER BY fecha DESC 
        LIMIT 20";

try {
    // Preparar la consulta SQL
    $stmt = $con->prepare($sql);

    // Vincular parámetro personal_id
    $stmt->bindParam(':personal_id', $personal_id, PDO::PARAM_INT);

    // Ejecutar la consulta SQL
    $stmt->execute();

    // Verificar si hay resultados
    if ($stmt->rowCount() > 0) {
        // Mostrar las ventas desactivadas en una tabla
        ?>

        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-3">Ventas Desactivadas</h2>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">ID Transacción</th>
                                <th scope="col">Status</th>
                                <th scope="col">Email</th>
                                <th scope="col">ID Cliente</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Total</th>
                                <th scope="col">Restaurar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($venta = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($venta['id'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['id_transaccion'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['status'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['email'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['id_cliente'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['fecha'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['total'], ENT_QUOTES); ?></td>
                                    <td>
                                        <a href="restaurar_venta.php?id=<?php echo $venta['id']; ?>" class="btn btn-success btn-sm">Restaurar</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <?php
    } else {
        // Si no hay ventas desactivadas, mostrar un mensaje
        echo "<p>No se encontraron ventas desactivadas asociadas al ID de personal.</p>";
    }
} catch (PDOException $e) {
    // Manejar el error
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
}

// Incluir el archivo de pie de página
require '../footer.php';
?>
